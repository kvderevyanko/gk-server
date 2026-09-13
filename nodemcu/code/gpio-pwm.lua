local function response(status, message, pins, rejected, clock)
    local result = {status = status, message = message}
    if pins then result.pins = pins end
    if rejected then result.rejected = rejected end
    if clock then result.clock = clock end
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

local function validInteger(value)
    local number = tonumber(value)
    if not number or number % 1 ~= 0 then return nil end
    return number
end

local function saveState(state)
    local encoded = sjson.encode(state)
    file.open("json/pwm-action.json-tmp", "w")
    file.write(encoded)
    file.close()
    file.remove("json/pwm-action.json")
    file.rename("json/pwm-action.json-tmp", "json/pwm-action.json")
end

local function applyRequest(request)
    local rejected, pins, state = {}, {}, {}
    local count = 0
    local clock = validInteger(request.clock or 500)

    if not clock or clock < 1 or clock > 1000 then
        rejected.clock = "clock must be an integer from 1 to 1000"
    end
    for pin, duty in pairs(request) do
        -- The Yii2 client still sends a legacy aggregate `duty` setting.
        -- Per-pin values are the actual PWM duties, so it is intentionally ignored.
        if pin ~= "clock" and pin ~= "duty" then
            local pinNumber = validInteger(pin)
            local dutyNumber = validInteger(duty)
            if not pinNumber or pinNumber < 0 or pinNumber > 9 then
                rejected[tostring(pin)] = "pin must be an integer from 0 to 9"
            elseif not dutyNumber or dutyNumber < 0 or dutyNumber > 1023 then
                rejected[tostring(pin)] = "duty must be an integer from 0 to 1023"
            else
                pins[tostring(pinNumber)] = dutyNumber
                state[tostring(pinNumber)] = dutyNumber
                count = count + 1
            end
        end
    end
    if count == 0 then rejected.request = "at least one PWM pin is required" end
    if next(rejected) then return response("error", "PWM request rejected", nil, rejected) end

    state.clock = clock
    for pin, duty in pairs(pins) do
        pwm.setup(tonumber(pin), clock, duty)
        pwm.start(tonumber(pin))
    end
    saveState(state)
    collectgarbage()
    return response("ok", "PWM command accepted", pins, nil, clock)
end

-- Restores only a state that previously passed the same validation.
function openPwmJson()
    if not file.open("json/pwm-action.json") then return false end
    local encoded = file.read()
    file.close()
    if not encoded then return false end
    local ok, state = pcall(sjson.decode, encoded)
    if not ok or type(state) ~= "table" then return false end
    applyRequest(state)
    return true
end

return function(args)
    return applyRequest(parseArgs(args))
end
