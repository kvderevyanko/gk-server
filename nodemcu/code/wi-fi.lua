local conf = dofile("_config.lc");
local serverStarted = false

local function startServer()
    if serverStarted then return end
    serverStarted = true
    dofile("server.lc")
end

wifi.setmode(conf.wifi.mode)

if (conf.wifi.mode == wifi.SOFTAP) or (conf.wifi.mode == wifi.STATIONAP) then
    print('AP MAC: ', wifi.ap.getmac())
    wifi.ap.config(conf.wifi.accessPoint.config)
    wifi.ap.setip(conf.wifi.accessPoint.net)
end

if (conf.wifi.mode == wifi.STATION) or (conf.wifi.mode == wifi.STATIONAP) then
    print('Client MAC: ', wifi.sta.getmac())
    wifi.sta.config(conf.wifi.station)
    if conf.wifi.station.ip then
        wifi.sta.setip({
            ip = conf.wifi.station.ip,
            netmask = conf.wifi.station.netmask,
            gateway = conf.wifi.station.gateway,
        })
    end
end



if (wifi.getmode() == wifi.STATION) or (wifi.getmode() == wifi.STATIONAP) then

    wifi.eventmon.register(wifi.eventmon.STA_GOT_IP, function(args)
        print("Connected to WiFi Access Point. Got IP: " .. args["IP"])
        startServer()
        wifi.eventmon.register(wifi.eventmon.STA_DISCONNECTED, function(args)
            print("Lost WiFi connectivity; waiting for reconnect")
        end)
    end)

    -- Keep the outputs stable while the Wi-Fi stack reconnects on its own.
    local watchdogTimer = tmr.create()
    watchdogTimer:register(30000, tmr.ALARM_AUTO, function (watchdogTimer)
        local ip = wifi.sta.getip()
        if (not ip) then ip = wifi.ap.getip() end
        if ip == nil then
            print("No IP yet; waiting for WiFi")
        else
            startServer()
            watchdogTimer:unregister()
        end
    end)
    watchdogTimer:start()
else
    print("Server created")
    print("ssid: "..conf.wifi.accessPoint.config.ssid)
    print("IP: "..conf.wifi.accessPoint.net.ip)
    startServer()
end

conf = nil
collectgarbage()
