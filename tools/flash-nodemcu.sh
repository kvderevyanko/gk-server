#!/usr/bin/env sh

# Flashes the NodeMCU image required by nodemcu/code on a 4 MiB ESP8266.
set -eu

script_dir=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
project_root=$(CDPATH= cd -- "$script_dir/.." && pwd)
esptool="$project_root/.tools/nodemcu-python/bin/esptool.py"
port=${1:-/dev/ttyUSB0}
firmware="$project_root/nodemcu/firmware/adc, bit, color_utils, dht, encoder, file, gpio, http, mdns, net, node, pixbuf, pwm, sjson, tmr, uart, wifi, ws2812.bin"
baud=${ESPTOOL_BAUD:-115200}
flash_mode=${ESP_FLASH_MODE:-dio}

if [ ! -x "$esptool" ]; then
    printf '%s\n' 'Project-local esptool is missing. Run tools/bootstrap-nodemcu-tools.sh first.' >&2
    exit 1
fi
if [ ! -f "$firmware" ]; then
    printf '%s\n' "Firmware image is missing: $firmware" >&2
    exit 1
fi

"$esptool" --baud "$baud" --port "$port" write_flash \
    --flash_mode "$flash_mode" \
    --flash_size 4MB \
    --verify \
    --no-progress \
    0x0 "$firmware"
