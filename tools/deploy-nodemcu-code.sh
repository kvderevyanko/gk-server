#!/usr/bin/env sh

# Formats the NodeMCU file system and uploads the common nodemcu/code payload.
# The second argument is a local _config.lua that must not be committed.
set -eu

script_dir=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
project_root=$(CDPATH= cd -- "$script_dir/.." && pwd)
uploader="$project_root/.tools/nodemcu-python/bin/nodemcu-uploader"
port=${1:-/dev/ttyUSB0}
config=${2:?Usage: tools/deploy-nodemcu-code.sh [port] /path/to/local/_config.lua}
source_dir="$project_root/nodemcu/code"
mkdir -p "$project_root/.local"
staging_dir=$(mktemp -d "$project_root/.local/nodemcu-deploy.XXXXXX")

cleanup() {
    rm -rf "$staging_dir"
}
trap cleanup EXIT HUP INT TERM

if [ ! -x "$uploader" ]; then
    printf '%s\n' 'Project-local nodemcu-uploader is missing. Run tools/bootstrap-nodemcu-tools.sh first.' >&2
    exit 1
fi
if [ ! -f "$config" ]; then
    printf '%s\n' "Local NodeMCU configuration is missing: $config" >&2
    exit 1
fi

for source in "$source_dir"/*; do
    case "$(basename "$source")" in
        _config.example.lua) continue ;;
    esac
    cp "$source" "$staging_dir/"
done
cp "$config" "$staging_dir/_config.lua"

"$uploader" --port "$port" --baud 115200 --start_baud 115200 --timeout 15 file remove_all

set --
for file in "$staging_dir"/*; do
    case "$(basename "$file")" in
        compile.lua|init.lua) continue ;;
    esac
    set -- "$@" "$file:$(basename "$file")"
done

"$uploader" --port "$port" --baud 115200 --start_baud 115200 --timeout 15 \
    upload --verify raw "$@"

"$uploader" --port "$port" --baud 115200 --start_baud 115200 --timeout 15 \
    upload --verify raw "$staging_dir/compile.lua:compile.lua"
"$uploader" --port "$port" --baud 115200 --start_baud 115200 --timeout 15 \
    upload --verify raw --restart "$staging_dir/init.lua:init.lua"
