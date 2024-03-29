
local conf = {}

-- General server configuration.
conf.general = {}
-- TCP port in which to listen for incoming HTTP requests.
conf.general.port = 80

-- WiFi configuration
conf.wifi = {}
-- Can be wifi.STATION, wifi.SOFTAP, or wifi.STATIONAP
conf.wifi.mode = wifi.SOFTAP
-- Theses apply only when configured as Access Point (wifi.SOFTAP or wifi.STATIONAP)
if (conf.wifi.mode == wifi.SOFTAP) or (conf.wifi.mode == wifi.STATIONAP) then
    conf.wifi.accessPoint = {}
    conf.wifi.accessPoint.config = {}
    conf.wifi.accessPoint.config.ssid = "ESP-"..node.chipid() -- Name of the WiFi network to create.
    conf.wifi.accessPoint.config.pwd = "ESP-"..node.chipid() -- WiFi password for joining - at least 8 characters
    conf.wifi.accessPoint.net = {}
    conf.wifi.accessPoint.net.ip = "192.168.111.1"
    conf.wifi.accessPoint.net.netmask="255.255.255.0"
    conf.wifi.accessPoint.net.gateway="192.168.111.1"
end
-- These apply only when connecting to a router as a client
if (conf.wifi.mode == wifi.STATION) or (conf.wifi.mode == wifi.STATIONAP) then
    conf.wifi.station = {}
    conf.wifi.station.ssid = ""        -- Name of the WiFi network you want to join
    conf.wifi.station.pwd =  ""                -- Password for the WiFi network
end

--[[

-- mDNS, applies if you compiled the mdns module in your firmware.
conf.mdns = {}
conf.mdns.hostname = 'nodemcu' -- You will be able to access your server at "http://nodemcu.local."
conf.mdns.location = 'Earth'
conf.mdns.description = 'A tiny HTTP server'

]]

return conf
