local allowedModes = {
    off = true,
    static = true,
    ["static-soft-blink"] = true,
    ["static-soft-random-blink"] = true,
    ["round-random"] = true,
    ["round-static"] = true,
    rainbow = true,
    ["rainbow-circle"] = true,
}

local function response(status, message, values, rejected)
    local result = {status = status, message = message}
    if values then result.values = values end
    if rejected then result.rejected = rejected end
    return sjson.encode(result)
end

local function parseArgs(args)
    local request = {}
    if not args then return request end
    for pair in string.gmatch(args, "([^&]+)") do
        local name, value = string.match(pair, "([^=]+)=(.*)")
        if name then request[name] = value end
    end
    return request
end

local function hexToChar(value)
    return string.char(tonumber(value, 16))
end

local function uriDecode(value)
    return value:gsub("%+", " "):gsub("%%(%x%x)", hexToChar)
end

local function integer(value, default, minimum, maximum, name, rejected)
    if value == nil then return default end
    local number = tonumber(value)
    if not number or number % 1 ~= 0 or number < minimum or number > maximum then
        rejected[name] = name .. " must be an integer from " .. minimum .. " to " .. maximum
        return default
    end
    return number
end

local function decodeColor(value, rejected)
    if value == nil then return {0, 0, 0}, "[0,0,0]" end
    if type(value) == "table" then
        local color = value
        for i = 1, 3 do
            if type(color[i]) ~= "number" or color[i] % 1 ~= 0 or color[i] < 0 or color[i] > 255 then
                rejected.single_color = "single_color must contain three integers from 0 to 255"
                return {0, 0, 0}, "[0,0,0]"
            end
        end
        return color, sjson.encode(color)
    end

    -- string.gsub returns both the resulting string and replacement count.
    -- Keep only the string: passing both values to sjson.decode rejects an
    -- otherwise valid URL-encoded RGB array on NodeMCU.
    local decodedValue = uriDecode(value)
    local ok, color = pcall(sjson.decode, decodedValue)
    if not ok or type(color) ~= "table" then
        rejected.single_color = "single_color must be a JSON RGB array"
        return {0, 0, 0}, "[0,0,0]"
    end
    for i = 1, 3 do
        if type(color[i]) ~= "number" or color[i] % 1 ~= 0 or color[i] < 0 or color[i] > 255 then
            rejected.single_color = "single_color must contain three integers from 0 to 255"
            return {0, 0, 0}, "[0,0,0]"
        end
    end
    return color, sjson.encode(color)
end

local function saveState(state)
    return saveStateFile("json/ws-action.json", sjson.encode(state))
end

local appliedState
local storedState
local function applyRequest(request, restoring)
    if type(request) ~= "table" then return response("error", "WS2812 request rejected") end
    local rejected = {}
    local buffer = integer(request.buffer, nil, 1, 330, "buffer", rejected)
    local mode = request.mode and tostring(request.mode) or nil
    if not buffer then rejected.buffer = "buffer is required" end
    if not mode or not allowedModes[mode] then rejected.mode = "unsupported WS2812 mode" end

    local delay = integer(request.delay, 100, 20, 10000, "delay", rejected)
    local bright = integer(request.bright, 100, 1, 255, "bright", rejected)
    local modeOptions = integer(request.mode_options, 1, 1, 255, "mode_options", rejected)
    local color, colorJson = decodeColor(request.single_color, rejected)
    if pinOwners["4"] and pinOwners["4"] ~= "ws" then
        rejected.pin = "D4 is already used by " .. pinOwners["4"]
    end

    if next(rejected) then return response("error", "WS2812 request rejected", nil, rejected), false end
    local state = {
        buffer = buffer, mode = mode, delay = delay, bright = bright,
        mode_options = modeOptions, single_color = colorJson,
    }
    if sameState(appliedState, state) then
        if not restoring and not sameState(storedState, state) then
            if not saveState(state) then
                return response("error", "WS2812 state was applied but could not be persisted", state), false
            end
            storedState = state
        end
        return response("ok", "WS2812 command accepted", state), true
    end
    if not wsTimer then _G.wsTimer = tmr.create() end
    wsTimer:stop()
    if mode == "static" then
        dofile("ws-effect-basic.lc")
    else
        dofile("ws-effect.lc")
    end

    if mode == "off" then
        wsEffOff(buffer)
    elseif mode == "static" then
        wsEffStatic(buffer, color, bright)
    elseif mode == "static-soft-blink" then
        wsEffStaticSoftBlink(buffer, color, bright, delay, modeOptions)
    elseif mode == "static-soft-random-blink" then
        wsEffStaticSoftRandomBlink(buffer, color, bright, delay, modeOptions)
    elseif mode == "round-random" then
        wsEffRoundRandom(buffer, color, bright, delay, modeOptions)
    elseif mode == "round-static" then
        wsEffRoundStatic(buffer, color, bright, delay, modeOptions)
    elseif mode == "rainbow" then
        wsEffRainbow(buffer, color, bright, delay, modeOptions)
    elseif mode == "rainbow-circle" then
        wsEffRainbowCircle(buffer, color, bright, delay, modeOptions)
    end
    pinOwners["4"] = "ws"
    appliedState = state
    if restoring then
        storedState = state
    elseif not sameState(storedState, state) then
        if not saveState(state) then
            return response("error", "WS2812 state was applied but could not be persisted", state), false
        end
        storedState = state
    end
    collectgarbage()
    return response("ok", "WS2812 command accepted", state), true
end

-- Restores the last accepted state and prepares the shared effect timer.
function openWsJson()
    _G.wsTimer = tmr.create()
    local encoded = readStateFile("json/ws-action.json")
    if not encoded then return false end
    local ok, state = pcall(sjson.decode, encoded)
    if not ok or type(state) ~= "table" then return false end
    local _, applied = applyRequest(state, true)
    return applied
end

return function(args)
    return applyRequest(parseArgs(args))
end
