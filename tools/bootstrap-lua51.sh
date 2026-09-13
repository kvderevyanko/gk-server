#!/usr/bin/env sh

# Builds a project-local Lua 5.1 toolchain for NodeMCU source checks.
# It does not install files system-wide and does not access ESP hardware.
set -eu

script_dir=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
project_root=$(CDPATH= cd -- "$script_dir/.." && pwd)
tools_dir="$project_root/.tools"
version=5.1.5
archive="$tools_dir/lua-$version.tar.gz"
source_dir="$tools_dir/lua-$version"
bin_dir="$tools_dir/lua-$version/bin"
expected_sha256=2640fc56a795f29d28ef15e13c34a47e223960b0240e8cb0a82d9b0738695333

if [ -x "$bin_dir/lua" ] && [ -x "$bin_dir/luac" ]; then
    printf '%s\n' "Project-local Lua $version is already available at $bin_dir"
    exit 0
fi

if ! command -v curl >/dev/null 2>&1; then
    printf '%s\n' 'curl is required to bootstrap Lua 5.1.' >&2
    exit 1
fi
if ! command -v make >/dev/null 2>&1 || ! command -v cc >/dev/null 2>&1; then
    printf '%s\n' 'make and a C compiler are required to bootstrap Lua 5.1.' >&2
    exit 1
fi

mkdir -p "$tools_dir"
if [ ! -f "$archive" ]; then
    curl --fail --location --retry 3 \
        "https://www.lua.org/ftp/lua-$version.tar.gz" \
        --output "$archive"
fi

actual_sha256=$(sha256sum "$archive" | awk '{print $1}')
if [ "$actual_sha256" != "$expected_sha256" ]; then
    printf '%s\n' "Unexpected SHA-256 for $archive" >&2
    exit 1
fi

if [ ! -d "$source_dir" ]; then
    tar -xzf "$archive" -C "$tools_dir"
fi

# The generic target avoids an optional readline dependency and is enough for
# the Lua interpreter and bytecode compiler used by this project.
make -C "$source_dir/src" generic
mkdir -p "$bin_dir"
cp "$source_dir/src/lua" "$source_dir/src/luac" "$bin_dir/"

printf '%s\n' "Built project-local Lua $version in $bin_dir"
