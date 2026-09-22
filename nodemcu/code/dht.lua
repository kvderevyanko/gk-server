local function response(status, message, pin, temperature, humidity)
    local result = {status = status, message = message}
    if pin then result.pin = pin end
    if temperature then result.temperature = temperature end
    if humidity then result.humidity = humidity end
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

return function(args)
    local request = parseArgs(args)
    local pin = tonumber(request.pin)
    if not pin or pin % 1 ~= 0 or pin < 1 or pin > 9 then
        return response("error", "DHT pin must be an integer from 1 to 9")
    end
    if pinOwners[tostring(pin)] then
        return response("error", "pin is already used by " .. pinOwners[tostring(pin)], pin)
    end

    local status, temp, humi, tempDec, humiDec = dht.read(pin)
    if status == dht.OK then
        return response(
            "ok",
            "DHT reading received",
            pin,
            string.format("%d.%03d", math.floor(temp), tempDec),
            string.format("%d.%03d", math.floor(humi), humiDec)
        )
    end
    if status == dht.ERROR_CHECKSUM then
        return response("error", "DHT checksum error", pin)
    end
    if status == dht.ERROR_TIMEOUT then
        return response("error", "DHT timeout", pin)
    end
    return response("error", "DHT read failed", pin)
end
