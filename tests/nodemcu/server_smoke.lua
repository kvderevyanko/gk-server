-- Mock HTTP server test; no socket or device is opened.
local root = arg[1] or "nodemcu/code"
local originalDofile = dofile
local asset = string.rep("a", 2500)
local cursor = 1
local accept
local gpioLoads = 0

file = {
    stat = function(name) if name == "index.html" then return {size = #asset} end end,
    exists = function(name) return name == "index.html" end,
    open = function(name) if name ~= "index.html" then return nil end; cursor = 1; return true end,
    seek = function(_, offset) cursor = offset + 1; return offset end,
    read = function(size) return string.sub(asset, cursor, cursor + (size or #asset) - 1) end,
    close = function() end,
}
net = {
    TCP = 1,
    createServer = function()
        return {listen = function(_, _, callback) accept = callback end}
    end,
}
node = {heap = function() return 10000 end}
dofile = function(name)
    if name == "_config.lc" then return {general = {port = 80, token = "test-token"}} end
    if name == "request.lc" then return originalDofile(root .. "/request.lua") end
    if name == "gpio.lc" then
        gpioLoads = gpioLoads + 1
        return function() return '{"status":"ok"}' end
    end
    if name == "dht.lc" then return function() error("mock module failure") end end
    error("unexpected module: " .. name)
end

assert(loadfile(root .. "/server.lua"))()
local function connection()
    local socket = {handlers = {}, output = "", closed = false}
    function socket:on(event, callback) self.handlers[event] = callback end
    function socket:send(chunk) self.output = self.output .. chunk end
    function socket:close() self.closed = true end
    accept(socket)
    return socket
end
local function drain(socket)
    for _ = 1, 20 do
        if socket.closed then return end
        socket.handlers.sent(socket)
    end
    error("response never closed")
end

local socket = connection()
socket.handlers.receive(socket, "GET /gpio.lc?0=1 HTTP/1.1\r\nX-ESP-")
assert(socket.output == "", "partial request was processed")
socket.handlers.receive(socket, "Token: test-token\r\n\r\n")
drain(socket)
assert(string.find(socket.output, "200 OK", 1, true))

socket = connection()
socket.handlers.receive(socket, "GET /gpio.lc?0=1 HTTP/1.1\r\nX-ESP-Token: test-token\r\n\r\n")
drain(socket)
assert(gpioLoads == 1, "GPIO handler was reloaded for every request")

socket = connection()
socket.handlers.receive(socket, "GET /gpio.lc HTTP/1.1\r\n\r\n")
drain(socket)
assert(string.find(socket.output, "403 Forbidden", 1, true))

socket = connection()
socket.handlers.receive(socket, "GET /dht.lc HTTP/1.1\r\nX-ESP-Token: test-token\r\n\r\n")
drain(socket)
assert(string.find(socket.output, "500 Internal Server Error", 1, true))

socket = connection()
socket.handlers.receive(socket, "GET / HTTP/1.1\r\nX-ESP-Token: test-token\r\n\r\n")
drain(socket)
assert(string.sub(socket.output, -#asset) == asset, "static file was truncated")

socket = connection()
socket.handlers.receive(socket, "GET /gpio.lc?" .. string.rep("a", 2050))
drain(socket)
assert(string.find(socket.output, "413 Payload Too Large", 1, true))

node.heap = function() return 3000 end
socket = connection()
assert(socket.closed and socket.output == "", "low-heap connection was accepted")

print("NodeMCU HTTP mock tests passed")
