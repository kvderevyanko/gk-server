#!/usr/bin/env sh

# Reads ESP8266 identity and flash geometry. It never writes or erases flash.
set -eu

script_dir=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
project_root=$(CDPATH= cd -- "$script_dir/.." && pwd)
esptool="$project_root/.tools/nodemcu-python/bin/esptool.py"
port=${1:-/dev/ttyUSB0}

if [ ! -x "$esptool" ]; then
    printf '%s\n' 'Project-local esptool is missing. Run tools/bootstrap-nodemcu-tools.sh first.' >&2
    exit 1
fi

"$esptool" --port "$port" chip_id
"$esptool" --port "$port" flash_id
