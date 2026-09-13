#!/usr/bin/env sh

# Parses all NodeMCU sources with Lua 5.1. No code is uploaded to devices.
set -eu

script_dir=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
project_root=$(CDPATH= cd -- "$script_dir/.." && pwd)
default_luac="$project_root/.tools/lua-5.1.5/bin/luac"
luac_bin=${LUA51_LUAC:-$default_luac}

if [ ! -x "$luac_bin" ]; then
    printf '%s\n' 'Lua 5.1 compiler is missing. Run tools/bootstrap-lua51.sh first,' >&2
    printf '%s\n' 'or set LUA51_LUAC to a Lua 5.1-compatible luac executable.' >&2
    exit 1
fi

find "$project_root/nodemcu" -type f -name '*.lua' -exec "$luac_bin" -p {} +
printf '%s\n' 'NodeMCU Lua syntax check passed (Lua 5.1).'
