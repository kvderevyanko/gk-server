local conf = dofile("_config.lc")
local parseRequest = dofile("request.lc")
local handlers = {
    ["gpio.lc"] = "gpioHandler",
    ["gpio-pwm.lc"] = "pwmHandler",
    ["dht.lc"] = "dhtHandler",
    ["ws.lc"] = "wsHandler",
}

local function sendJson(client, code, answer)
    client:send("HTTP/1.0 " .. code .. "\r\nContent-Type: application/json\r\nConnection: close\r\n\r\n" .. answer)
end

local function contentType(filename)
    if string.sub(filename, -5) == ".html" then return "text/html" end
    if string.sub(filename, -4) == ".css" then return "text/css" end
    if string.sub(filename, -3) == ".js" then return "application/javascript" end
    if string.sub(filename, -4) == ".ico" then return "image/x-icon" end
    if string.sub(filename, -5) == ".json" then return "application/json" end
    return "text/plain"
end

local function sendFile(client, filename)
    local stat = file.stat(filename)
    if not stat then return false end
    if not file.open(filename) then return false end
    file.close()
    local offset = 0
    client:on("sent", function(socket)
        if offset >= stat.size then
            socket:close()
            collectgarbage()
            return
        end
        collectgarbage()
        if not file.open(filename) then socket:close(); return end
        if not file.seek("set", offset) then file.close(); socket:close(); return end
        local chunk = file.read(math.min(256, stat.size - offset))
        file.close()
        if not chunk or chunk == "" then socket:close(); return end
        offset = offset + string.len(chunk)
        socket:send(chunk)
    end)
    client:send("HTTP/1.0 200 OK\r\nContent-Type: " .. contentType(filename) .. "\r\nConnection: close\r\n\r\n")
    return true
end

local function authorized(headers)
    if not conf.general.token or conf.general.token == "" then return true end
    for line in string.gmatch(headers, "([^\r\n]+)") do
        local name, value = string.match(line, "^([^:]+):%s*(.-)%s*$")
        if name and string.lower(name) == "x-esp-token" then
            return value == conf.general.token
        end
    end
    return false
end

srv = net.createServer(net.TCP)
srv:listen(conf.general.port, function(conn)
    -- TCP buffers linger briefly after close. Avoid an OOM reboot during bursts.
    if node.heap() < 4500 then
        conn:close()
        collectgarbage()
        return
    end
    local input = ""
    local handled = false
    conn:on("receive", function(client, request)
        if handled then return end
        input = input .. request
        if string.len(input) > 2048 then
            handled = true
            sendJson(client, "413 Payload Too Large", '{"status":"error","message":"request too large"}')
            return
        end
        local headerEnd = string.find(input, "\r\n\r\n", 1, true)
        if not headerEnd then return end
        handled = true
        local method, target = string.match(input, "^(%u+)%s+([^%s]+)%s+HTTP/")
        if method ~= "GET" or not target then
            sendJson(client, "400 Bad Request", '{"status":"error","message":"GET request expected"}')
            return
        end
        if not authorized(string.sub(input, 1, headerEnd - 1)) then
            sendJson(client, "403 Forbidden", '{"status":"error","message":"unauthorized"}')
            return
        end
        input = ""

        local req = parseRequest(target)
        local filename = req.file
        if not filename then
            sendJson(client, "400 Bad Request", '{"status":"error","message":"invalid path"}')
        elseif handlers[filename] then
            local ok, answer = pcall(function()
                local handler = _G[handlers[filename]]
                if not handler then
                    handler = dofile(filename)
                    _G[handlers[filename]] = handler
                end
                return handler(req.query)
            end)
            if not ok then
                print("HTTP module error: " .. tostring(answer))
                sendJson(client, "500 Internal Server Error", '{"status":"error","message":"module failed"}')
            else
                if not answer or answer == "" then
                    answer = '{"status":"error","message":"empty module response"}'
                end
                sendJson(client, "200 OK", answer)
            end
        elseif string.sub(filename, -3) == ".lc" then
            sendJson(client, "404 Not Found", '{"status":"error","message":"module not found"}')
        elseif file.exists(filename) and sendFile(client, filename) then
            -- The sent callback queues one small chunk at a time.
        else
            sendJson(client, "404 Not Found", '{"status":"error","message":"file not found"}')
        end
        collectgarbage()
    end)
    conn:on("sent", function(socket)
        socket:close()
        collectgarbage()
    end)
end)
