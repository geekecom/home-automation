#!/bin/bash
mjpg_streamer -i "/usr/local/lib/input_uvc.so -f 10 -r 320x240" -o "/usr/local/lib/output_http.so -w /home/pi/domotica/video -c domo:tica"
