from time import sleep
import RPi.GPIO as GPIO
import sys


pin = int(sys.argv[1])

GPIO.setmode(GPIO.BOARD)
GPIO.setup(pin, GPIO.OUT)
GPIO.output(pin, True)
sleep(1)

