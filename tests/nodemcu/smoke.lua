-- Run with Lua 5.1; these mocks never contact an ESP.
local root = arg[1] or "nodemcu/code"
local files = {}
local opened
local modeCalls, writeCalls, pwmCalls, wsCalls = 0, 0, 0, 0
local fileWrites = 0
local failReplace = false

file = {
    exists = function(name) return files[name] ~= nil end,
    open = function(name, mode)
        if mode == "w" then files[name] = ""
        elseif files[name] == nil then return nil end
        opened = name
        return true
    end,
    read = function() return files[opened] end,
    write = function(value) files[opened] = value; fileWrites = fileWrites + 1; return true end,
    close = function() opened = nil end,
    remove = function(name) files[name] = nil; return true end,
    rename = function(old, new)
        if failReplace and old == "test-tmp" then return nil end
        if files[old] == nil then return nil end
        files[new], files[old] = files[old], nil
        return true
    end,
}
sjson = {
    encode = function(value) return value.status or "state" end,
    decode = function(value)
        if value == "bad" then error("corrupt state") end
        return { ["0"] = 0 }
    end,
}
gpio = {
    OUTPUT = 1, HIGH = 1, LOW = 0,
    mode = function() modeCalls = modeCalls + 1 end,
    write = function() writeCalls = writeCalls + 1 end,
}
pwm = {
    setup = function() pwmCalls = pwmCalls + 1 end,
    start = function() end,
}
pinOwners = {}
assert(loadfile(root .. "/state-file.lua"))()
files.test = "old"
failReplace = true
assert(saveStateFile("test", "new") == false)
assert(files.test == "old", "failed replacement lost previous state")
failReplace = false
files.test, files["test-bak"] = nil, "backup"
assert(readStateFile("test") == "backup", "backup was not restored")
local initialFileWrites = fileWrites

local gpioHandler = assert(loadfile(root .. "/gpio.lua"))()
assert(gpioHandler("0=0") == "ok")
assert(gpioHandler("0=0") == "ok")
assert(modeCalls == 1 and writeCalls == 1, "repeated GPIO command reconfigured output")
assert(fileWrites == initialFileWrites + 1, "repeated GPIO command rewrote flash")
assert(gpioHandler("0=1") == "ok")
assert(modeCalls == 1 and writeCalls == 2, "changed GPIO value was not written")
files["json/gpio-action.json"] = "bad"
assert(openGpioJson() == false, "corrupt GPIO state was accepted")

local pwmHandler = assert(loadfile(root .. "/gpio-pwm.lua"))()
assert(pwmHandler("0=100&clock=500") == "error", "D0 was accepted for PWM")
assert(pwmHandler("1=100&clock=500") == "ok")
assert(pwmHandler("1=100&clock=500") == "ok")
assert(pwmCalls == 1)
assert(gpioHandler("1=1") == "error", "GPIO stole a PWM pin")

tmr = {create = function() return {stop = function() end} end}
local originalDofile = dofile
dofile = function(name)
    if name == "ws-effect.lc" then
        wsEffOff = function() wsCalls = wsCalls + 1 end
        return
    end
    return originalDofile(name)
end
local wsHandler = assert(loadfile(root .. "/ws.lua"))()
assert(wsHandler("buffer=1&mode=off&blueBright=100&blink=1") == "ok")
assert(wsHandler("buffer=1&mode=off&blueBright=100&blink=1") == "ok")
assert(wsCalls == 1 and pwmCalls == 1, "WS off mode used PWM/D4 indicator")
assert(gpioHandler("4=1") == "error", "GPIO stole the WS2812 pin")

local dhtCalls = 0
dht = {OK = 1, read = function() dhtCalls = dhtCalls + 1; return 1, 20, 40, 0, 0 end}
local dhtHandler = assert(loadfile(root .. "/dht.lua"))()
assert(dhtHandler("pin=0") == "error", "D0 was accepted for DHT")
assert(dhtHandler("pin=1") == "error", "DHT stole a PWM pin")
assert(dhtCalls == 0)

print("NodeMCU mock smoke tests passed")
