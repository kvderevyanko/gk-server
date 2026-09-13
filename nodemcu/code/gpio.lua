-- Restores GPIO settings after a restart.
function openGpioJson()
    if file.open("json/gpio-action.json") then
        local stored = file.read()
        file.close()
        if stored then
            local state = sjson.decode(stored)
            actionRequest(state)
        end
        return true
    end
    return false
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
    local encoded = sjson.encode(pins)
    if not file.open("json/gpio-action.json-tmp", "w") then
        return false
    end

    file.write(encoded)
    file.close()
    file.remove("json/gpio-action.json")
    file.rename("json/gpio-action.json-tmp", "json/gpio-action.json")
    return true
end

-- Applies a complete, validated GPIO request and persists the commanded state.
function actionRequest(request)
    local pins, rejected = validateRequest(request)
    if next(rejected) ~= nil then
        return encodeResponse("error", "GPIO request rejected", nil, rejected)
    end

    for pin, value in pairs(pins) do
        local numericPin = tonumber(pin)
        gpio.mode(numericPin, gpio.OUTPUT)
        gpio.write(numericPin, value == 1 and gpio.HIGH or gpio.LOW)
    end

    if not saveState(pins) then
        return encodeResponse("error", "GPIO state was applied but could not be persisted", pins)
    end

    return encodeResponse("ok", "GPIO command accepted", pins)
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
