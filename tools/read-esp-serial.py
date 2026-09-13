#!/usr/bin/env python3
"""Print UART data from an ESP without sending commands."""

import argparse
import sys
import time

import serial


parser = argparse.ArgumentParser(description=__doc__)
parser.add_argument("port", nargs="?", default="/dev/ttyUSB0")
parser.add_argument("--baud", type=int, default=115200)
parser.add_argument("--seconds", type=float, default=5)
parser.add_argument("--reset", action="store_true", help="hard-reset before listening")
args = parser.parse_args()

with serial.Serial(args.port, args.baud, timeout=0.2) as connection:
    connection.dtr = False
    connection.rts = False
    if args.reset:
        connection.rts = True
        time.sleep(0.1)
        connection.rts = False
    deadline = time.monotonic() + args.seconds
    while time.monotonic() < deadline:
        data = connection.read(connection.in_waiting or 1)
        if data:
            sys.stdout.buffer.write(data)
            sys.stdout.buffer.flush()
