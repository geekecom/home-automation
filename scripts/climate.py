from time import sleep
from math import pow
import sys
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BOARD)
data = 38
sck = 40
GPIO.setup(data, GPIO.OUT)
GPIO.setup(sck, GPIO.OUT)

	
def getHumidity():
	#inizialization
	GPIO.setup(data, GPIO.OUT)
	GPIO.setup(sck, GPIO.OUT)
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
	#Command='00101'
	for i in range(2):
		GPIO.output(sck,True)
		sleep(0.005)
		GPIO.output(sck,False)
		sleep(0.005)

	GPIO.output(data, True)
	GPIO.output(sck,True)
	sleep(0.005)
	GPIO.output(sck,False)
	sleep(0.005)

	GPIO.output(data, False)
	GPIO.output(sck,True)
	sleep(0.005)
	GPIO.output(sck,False)
	sleep(0.005)

	GPIO.output(data, True)
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
	humidityBinaryString = ''
	for i in range(8):
		GPIO.output(sck,True)
		sleep(0.005)
		humidityBinaryString = humidityBinaryString +str(GPIO.input(data))
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
		humidityBinaryString = humidityBinaryString +str(GPIO.input(data))
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


	#binary-string to decimal conversion (12 bits)
	#humiditySensorOUT
	humiditySO = 0
	for i in range(12):
		if (humidityBinaryString[15-i] == '1'):
			humiditySO = humiditySO + pow(2,i)

	#sensor specific conversion to real humidityerature (see Table 8 from datasheet for more info)
	c1 = -2.0468
	c2 = 0.0367
	#c3 = -1.5955
	humidityDecimal = c1 + c2*humiditySO
	
	#now ajust relative humidityBinaryString to a real value from the temperature
	#t1 and t2 are values given by the manufacturer	(see Table 7)
	t1 = 0.01
	t2 = 0.00008
	realHumidity = (temperature-25)*(t1+t2*humiditySO)+humidityDecimal
	return realHumidity

def getTemperature():
	data = 38
	sck = 40
	#GPIO.setup(data, GPIO.OUT)
	#GPIO.setup(sck, GPIO.OUT)

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

	#binary-string to decimal conversion (14 bits)
	temperatureDecimal = 0
	for i in range(14):
		if (temp[15-i] == '1'):
			temperatureDecimal = temperatureDecimal + pow(2,i)

	#sensor specific conversion to real temperature (see Table 8 from datasheet for more info)
	temperatureDecimal = (-39.65) + (0.01*temperatureDecimal)
	return temperatureDecimal

temperature = getTemperature()
humidity = getHumidity()

print temperature
print humidity

GPIO.cleanup()
