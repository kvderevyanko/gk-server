#!/usr/bin/env sh

# Installs project-local ESP8266/NodeMCU tooling. No system packages are changed.
set -eu

script_dir=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
project_root=$(CDPATH= cd -- "$script_dir/.." && pwd)
venv_dir="$project_root/.tools/nodemcu-python"
requirements="$script_dir/nodemcu-tools-requirements.txt"

if ! command -v python3 >/dev/null 2>&1; then
    printf '%s\n' 'python3 is required to bootstrap NodeMCU tools.' >&2
    exit 1
fi

if [ ! -x "$venv_dir/bin/python" ]; then
    python3 -m venv "$venv_dir"
fi

"$venv_dir/bin/python" -m pip install \
    --disable-pip-version-check \
    --requirement "$requirements"

printf '%s\n' "Installed project-local NodeMCU tools in $venv_dir"
