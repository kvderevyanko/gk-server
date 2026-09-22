-- Restores GPIO settings after a restart.
function openGpioJson()
    local stored = readStateFile("json/gpio-action.json")
    if not stored then return false end
    local ok, state = pcall(sjson.decode, stored)
    if not ok or type(state) ~= "table" then return false end
    local _, applied = actionRequest(state, true)
    return applied
end

local function encodeResponse(status, message, pins, rejected)
    local response = {
        status = status,
        message = message,
    }

    if pins then
        response.pins = pins
    end
    if rejected then
        response.rejected = rejected
    end

    return sjson.encode(response)
end

local function validateRequest(request)
    local pins = {}
    local rejected = {}
    local count = 0

    for pin, value in pairs(request) do
        local numericPin = tonumber(pin)
        local numericValue = tonumber(value)

        if numericPin == nil or numericPin % 1 ~= 0 or numericPin < 0 or numericPin > 9 then
            rejected[tostring(pin)] = "pin must be an integer from 0 to 9"
        elseif numericValue ~= 0 and numericValue ~= 1 then
            rejected[tostring(pin)] = "value must be 0 or 1"
        else
            pins[tostring(numericPin)] = numericValue
            count = count + 1
        end
    end

    if count == 0 then
        rejected.request = "at least one GPIO value is required"
    end

    return pins, rejected
end

local function saveState(pins)
    return saveStateFile("json/gpio-action.json", sjson.encode(pins))
end

-- Applies a complete, validated GPIO request and persists the commanded state.
local configuredPins = {}
local lastValues = {}
local storedPins
function actionRequest(request, restoring)
    if type(request) ~= "table" then
        return encodeResponse("error", "GPIO request rejected", nil, {request = "invalid state"}), false
    end
    local pins, rejected = validateRequest(request)
    for pin in pairs(pins) do
        local owner = pinOwners[pin]
        if owner and owner ~= "gpio" then
            rejected[pin] = "pin is already used by " .. owner
        end
    end
    if next(rejected) ~= nil then
        return encodeResponse("error", "GPIO request rejected", nil, rejected), false
    end

    for pin, value in pairs(pins) do
        local numericPin = tonumber(pin)
        if not configuredPins[pin] then
            gpio.mode(numericPin, gpio.OUTPUT)
            configuredPins[pin] = true
        end
        if lastValues[pin] ~= value then
            gpio.write(numericPin, value == 1 and gpio.HIGH or gpio.LOW)
            lastValues[pin] = value
        end
        pinOwners[pin] = "gpio"
    end

    if restoring then
        storedPins = pins
    elseif not sameState(storedPins, pins) then
        if not saveState(pins) then
            return encodeResponse("error", "GPIO state was applied but could not be persisted", pins), false
        end
        storedPins = pins
    end

    return encodeResponse("ok", "GPIO command accepted", pins), true
end

return function(args)
    local request = {}
    if args then
        for pair in string.gmatch(args, "([^&]+)") do
            local pin, value = string.match(pair, "^([^=]+)=([^=]+)$")
            if pin then
                request[pin] = value
            else
                request[pair] = ""
            end
        end
    end

    local response = actionRequest(request)
    collectgarbage()
    return response
end
