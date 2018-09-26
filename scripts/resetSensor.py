from time import sleep
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BOARD)
data = 38
sck = 40
GPIO.setup(data, GPIO.OUT)
GPIO.setup(sck, GPIO.OUT)

GPIO.output(data,True)
sleep(0.1)
GPIO.output(sck, True)
sleep(0.1)
GPIO.output(data,False)
#transmission start
sleep(0.1)
GPIO.output(sck, False)
sleep(0.1)
GPIO.output(sck, True)
sleep(0.1)
GPIO.output(data,True)
#end transmission start
sleep(0.1)
GPIO.output(sck, False)
sleep(0.1)
GPIO.output(data,False)
sleep(0.1)
#address='000'
for i in range(3):
	GPIO.output(sck,True)
	sleep(0.1)
	GPIO.output(sck,False)
	sleep(0.1)
#Command='11110'
GPIO.output(data,True)
sleep(0.1)
for i in range(4):
	GPIO.output(sck,True)
	sleep(0.1)
	GPIO.output(sck,False)
	sleep(0.1)
GPIO.output(data,False)
for i in range(1):
	GPIO.output(sck,True)
	sleep(0.1)
	GPIO.output(sck,False)
	sleep(0.1)
GPIO.cleanup()
