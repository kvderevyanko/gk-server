-- Effects that fit comfortably into the ESP8266 heap.
-- The larger animation catalogue remains in ws-effect.lua and is loaded only
-- for modes that actually need it.
function wsEffStatic(bufferWs, color, bright)
    ws2812.init()
    local buffer = pixbuf.newBuffer(bufferWs, 3)
    buffer:fill(color[2], color[1], color[3])
    buffer:mix(bright, buffer)
    ws2812.write(buffer)
    buffer = nil
    collectgarbage()
end
