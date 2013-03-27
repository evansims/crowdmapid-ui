#!/bin/bash

nohup nice sass --style compressed --compass --watch sass:css &
nohup nice jitter coffee scripts &

#coffee --compile --output js/ --watch coffeescript/
