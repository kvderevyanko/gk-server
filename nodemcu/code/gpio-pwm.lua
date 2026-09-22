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
    return saveStateFile("json/pwm-action.json", sjson.encode(state))
end

local appliedState
local storedState
local function applyRequest(request, restoring)
    if type(request) ~= "table" then return response("error", "PWM request rejected") end
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
            if not pinNumber or pinNumber < 1 or pinNumber > 9 then
                rejected[tostring(pin)] = "PWM pin must be an integer from 1 to 9"
            elseif not dutyNumber or dutyNumber < 0 or dutyNumber > 1023 then
                rejected[tostring(pin)] = "duty must be an integer from 0 to 1023"
            elseif pinOwners[tostring(pinNumber)] and pinOwners[tostring(pinNumber)] ~= "pwm" then
                rejected[tostring(pin)] = "pin is already used by " .. pinOwners[tostring(pinNumber)]
            else
                pins[tostring(pinNumber)] = dutyNumber
                state[tostring(pinNumber)] = dutyNumber
                count = count + 1
            end
        end
    end
    if count == 0 then rejected.request = "at least one PWM pin is required" end
    if count > 6 then rejected.request = "at most six PWM pins are supported" end
    if next(rejected) then return response("error", "PWM request rejected", nil, rejected), false end

    state.clock = clock
    for pin, duty in pairs(pins) do
        if not appliedState or appliedState.clock ~= clock or appliedState[pin] ~= duty then
            pwm.setup(tonumber(pin), clock, duty)
            pwm.start(tonumber(pin))
        end
        pinOwners[pin] = "pwm"
    end
    appliedState = state
    if restoring then
        storedState = state
    elseif not sameState(storedState, state) then
        if not saveState(state) then
            return response("error", "PWM state was applied but could not be persisted", pins, nil, clock), false
        end
        storedState = state
    end
    collectgarbage()
    return response("ok", "PWM command accepted", pins, nil, clock), true
end

-- Restores only a state that previously passed the same validation.
function openPwmJson()
    local encoded = readStateFile("json/pwm-action.json")
    if not encoded then return false end
    local ok, state = pcall(sjson.decode, encoded)
    if not ok or type(state) ~= "table" then return false end
    local _, applied = applyRequest(state, true)
    return applied
end

return function(args)
    return applyRequest(parseArgs(args))
end
