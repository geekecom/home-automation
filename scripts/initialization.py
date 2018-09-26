#!/usr/bin/python

# import the MySQLdb and sys modules
import MySQLdb
import sys
#from time import sleep
# import RPi.GPIO as GPIO
import os

# GPIO.setmode(GPIO.BOARD)
# GPIO.setup(18, GPIO.IN)
# GPIO.setup(16, GPIO.OUT)

# pinBocina

# controlsArray = [alarm_ring,camera,door_ring,fan0,fan1,light0,light1,presence_ring]
# open a database connection
# be sure to change the host IP address, username, password and database name to match your own
connection = MySQLdb.connect (host="localhost", user="root", passwd="tfg2015", db="domo_tfg")

# prepare a cursor object using cursor() method
cursor = connection.cursor ()
cursor.execute ("select control_id,state from controls where control_id like 'light%' order by control_id;");
# fetch all of the rows from the query
stateArray = cursor.fetchall ()

cursor = connection.cursor ()
cursor.execute ("select pin_id from pins where control_id like 'light%' order by control_id;");
# fetch all of the rows from the query
pinArray = cursor.fetchall ()

print len(stateArray)

controlId = []
state = []
pinId = []

for i in range(len(stateArray)) :
    controlId.append(1)
    state.append(1)
    pinId.append(1)
    controlId[i] = stateArray[i][0]
    state[i] = stateArray[i][1]
    pinId[i] = pinArray[i][0]
    print controlId[i];
    print state[i]
    print pinId[i];
    if(state[i] == 0):
        os.system("sudo python /home/pi/domotica/scripts/pinOff.py " + str(pinId[i]))
    else:
        os.system("sudo python /home/pi/domotica/scripts/pinOn.py " + str(pinId[i]))
