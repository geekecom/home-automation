from time import sleep
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BOARD)
GPIO.setup(15, GPIO.OUT)

period = 0.1 
for i in range (0,2):
	GPIO.output(15, True)
	sleep(period*(i+0.1))
	GPIO.output(15, False)
	sleep(period)	 
GPIO.cleanup()
