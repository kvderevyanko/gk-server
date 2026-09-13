local conf = dofile("_config.lc")
local parseRequest = dofile("request.lc")
-- Keep handlers in memory. Loading bytecode for every request caused avoidable
-- heap pressure, especially while WS2812 effects were active.
local handlers = {
    ["gpio.lc"] = dofile("gpio.lc"),
    ["gpio-pwm.lc"] = dofile("gpio-pwm.lc"),
    ["dht.lc"] = dofile("dht.lc"),
    ["ws.lc"] = dofile("ws.lc"),
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
    client:send("HTTP/1.0 200 OK\r\nContent-Type: " .. contentType(filename) .. "\r\nConnection: close\r\n\r\n")
    if not file.open(filename) then return false end
    local remaining = stat.size
    while remaining > 0 do
        local chunk = file.read(math.min(1000, remaining))
        if not chunk then break end
        client:send(chunk)
        remaining = remaining - string.len(chunk)
    end
    file.close()
    return true
end

srv = net.createServer(net.TCP)
srv:listen(conf.general.port, function(conn)
    conn:on("receive", function(client, request)
        local method, target = string.match(request, "^(%u+)%s+([^%s]+)%s+HTTP/")
        if method ~= "GET" or not target then
            sendJson(client, "400 Bad Request", '{"status":"error","message":"GET request expected"}')
            return
        end

        local req = parseRequest(target)
        local filename = req.file
        if not filename then
            sendJson(client, "400 Bad Request", '{"status":"error","message":"invalid path"}')
        elseif handlers[filename] then
            local answer = handlers[filename](req.query)
            if not answer or answer == "" then
                answer = '{"status":"error","message":"empty module response"}'
            end
            sendJson(client, "200 OK", answer)
        elseif string.sub(filename, -3) == ".lc" then
            sendJson(client, "404 Not Found", '{"status":"error","message":"module not found"}')
        elseif file.exists(filename) and sendFile(client, filename) then
            -- The file was queued in chunks and is closed by the sent callback.
        else
            sendJson(client, "404 Not Found", '{"status":"error","message":"file not found"}')
        end
        collectgarbage()
    end)
    conn:on("sent", function(socket)
        socket:close()
    end)
end)
