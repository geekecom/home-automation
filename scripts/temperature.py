from time import sleep
from math import pow
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BOARD)
data = 38
sck = 40
GPIO.setup(data, GPIO.OUT)
GPIO.setup(sck, GPIO.OUT)

#inizialization
GPIO.output(data,True)
GPIO.output(sck,False)
sleep(0.005)

#transmission start
GPIO.output(sck, True)
GPIO.output(data,False)
sleep(0.005)
GPIO.output(sck, False)
sleep(0.005)
GPIO.output(sck, True)
sleep(0.005)
GPIO.output(data,True)
sleep(0.005)
GPIO.output(sck,False)
sleep(0.005)
GPIO.output(data,False)
#end transmission start

sleep(0.005)

#address='000'
for i in range(3):
	GPIO.output(sck,True)
	sleep(0.005)
	GPIO.output(sck,False)
	sleep(0.005)
#Command='00011'

for i in range(3):
	GPIO.output(sck,True)
	sleep(0.005)
	GPIO.output(sck,False)
	sleep(0.005)

GPIO.output(data, True)

for i in range(2):
        GPIO.output(sck,True)
        sleep(0.005)
        GPIO.output(sck,False)
        sleep(0.005)


#sensor takes the control of DATA line
GPIO.setup(data, GPIO.IN)

#ack
GPIO.output(sck,True)
sleep(0.005)
GPIO.output(sck,False)

#waiting until DATA = 0
wait = 0
while GPIO.input(data):
	wait = wait + 1	
#reading
temp = ''
for i in range(8):
	GPIO.output(sck,True)
	sleep(0.005)
        temp = temp +str(GPIO.input(data))
	sleep(0.005)
	GPIO.output(sck,False)
	sleep(0.005)

#take down the DATA line
GPIO.setup(data, GPIO.OUT)
GPIO.output(data, False)
#ack
GPIO.output(sck,True)
sleep(0.005)
GPIO.output(sck,False)
sleep(0.005)
GPIO.setup(data, GPIO.IN)

for i in range(8):
	GPIO.output(sck,True)
	sleep(0.005)
        temp = temp +str(GPIO.input(data))
	sleep(0.005)
	GPIO.output(sck,False)
	sleep(0.005)
#ack

GPIO.setup(data, GPIO.OUT)
GPIO.output(data, False)
GPIO.output(sck,True)
sleep(0.005)
GPIO.output(sck,False)
GPIO.output(data, False)
sleep(0.005)
GPIO.setup(data, GPIO.IN)



#checksum
sum = 'checksum: '
for i in range(8):
        GPIO.output(sck,True)
        sleep(0.005)
        GPIO.output(sck,False)
        sleep(0.005)
	sum = sum + str(GPIO.input(data))

#ACK
GPIO.setup(data, GPIO.IN)
GPIO.output(sck,True)
sleep(0.005)
GPIO.output(sck,False)


#binary-string to decimal conversion
tempDecimal = 0
for i in range(16):
	if (temp[15-i] == '1'):
		tempDecimal = tempDecimal + pow(2,i)

#sensor specific conversion to real temperature (see Table 8 from datasheet for more info)
tempDecimal = (-39.65) + (0.01*tempDecimal)
print tempDecimal
GPIO.cleanup()
