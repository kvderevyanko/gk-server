-- A pin must not be driven by two independent modules at the same time.
_G.pinOwners = {}
dofile("state-file.lc")
_G.wsHandler = dofile("ws.lc");
openWsJson();

_G.pwmHandler = dofile("gpio-pwm.lc");
openPwmJson();

_G.gpioHandler = dofile("gpio.lc");
openGpioJson();

collectgarbage();
