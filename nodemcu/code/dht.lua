function actionRequest(rd)
 local pin = tonumber(rd['pin'])

    local status, temp, humi, temp_dec, humi_dec = dht.read(pin)
    local message = "";
    if status == dht.OK then
        -- Integer firmware using this example
        message = (string.format('{"status":"ok","temperature":"%d.%03d","humidity":"%d.%03d"}',
                math.floor(temp),
                temp_dec,
                math.floor(humi),
                humi_dec
        ))
    elseif status == dht.ERROR_CHECKSUM then
        message = '{"status":"error","message":"DHT error"}'
    elseif status == dht.ERROR_TIMEOUT then
        message =  '{"status":"error","message":"DHT timeout"}'
    end
    rd = nil
    pin = nil
    status = nil
    temp = nil
    humi = nil
    temp_dec = nil
    humi_dec = nil
    collectgarbage()
    return message;
end

return function(args)
    local tableVar = {};
    if args then
        for kv in args.gmatch(args, "%s*&?([^=]+=[^&]+)") do
            local name, value = string.match(kv, "(.*)=(.*)");
            tableVar[name] = value;
        end
    end
    local answer = actionRequest(tableVar);
    tableVar = nil;
    args = nil;
    collectgarbage();
    return answer;
end