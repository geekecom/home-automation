#!/bin/sh

NOW=$(date +"%F"_"%H"-"%M"-"%S")

FILE="/home/pi/domotica/web/media/snapshots/$NOW.jpg"

wget --user domo --password tica http://localhost:8080/?action=snapshot -O $FILE
