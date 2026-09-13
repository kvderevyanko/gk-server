#!/usr/bin/env sh

# Backs up the complete 4 MiB ESP8266 flash before a firmware replacement.
set -eu

script_dir=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
project_root=$(CDPATH= cd -- "$script_dir/.." && pwd)
esptool="$project_root/.tools/nodemcu-python/bin/esptool.py"
port=${1:-/dev/ttyUSB0}
baud=${ESPTOOL_BAUD:-115200}
backup_dir="$project_root/.local/esp-backups"
timestamp=$(date -u +%Y%m%dT%H%M%SZ)
output="$backup_dir/esp8266-flash-$timestamp.bin"
log="$output.log"

if [ ! -x "$esptool" ]; then
    printf '%s\n' 'Project-local esptool is missing. Run tools/bootstrap-nodemcu-tools.sh first.' >&2
    exit 1
fi

mkdir -p "$backup_dir"
if ! "$esptool" --baud "$baud" --port "$port" read_flash 0x0 0x400000 "$output" >"$log" 2>&1; then
    printf '%s\n' "Backup failed; inspect $log" >&2
    exit 1
fi

sha256sum "$output" >"$output.sha256"
printf '%s\n' "Backup written to $output"
