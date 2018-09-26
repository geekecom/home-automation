#!/usr/bin/python

# import the MySQLdb and sys modules
import MySQLdb
import sys
from time import sleep
import RPi.GPIO as GPIO

GPIO.setmode(GPIO.BOARD)
GPIO.setup(18, GPIO.IN)
GPIO.setup(16, GPIO.OUT)

pinPulsador = 18
#pinBocina

# open a database connection
# be sure to change the host IP address, username, password and database name to match your own
connection = MySQLdb.connect (host = "localhost", user = "root", passwd = "tfg2015", db = "domo_tfg")
# prepare a cursor object using cursor() method
cursor = connection.cursor ()
cursor.execute ("select pin_id from pins where control_id = 'door_ring'")
# fetch all of the rows from the query
data = cursor.fetchall ()
for row in data :
	pinBocina = row[0]
	print pinBocina;
		     
while 1:
	# timbre pulsado
    if (GPIO.input(pinPulsador) == 1):
    	connection = MySQLdb.connect (host = "localhost", user = "root", passwd = "tfg2015", db = "domo_tfg")
    	cursor = connection.cursor ()
        # execute the SQL query using execute() method.
        cursor.execute ("select state from controls where control_id = 'door_ring'")
        # fetch all of the rows from the query
        data = cursor.fetchall ()
        # print the rows
        for row in data :
            value = row[0];
            print value
		
        # si esta activado el timbre suena
        if(value == 1):
            if(GPIO.input(pinBocina) == 0):
            	print "Se activa timbre"
            	GPIO.output(pinBocina, True)
        # close the cursor object
        cursor.close ()
        # close the connection
        connection.close ()
    # si no esta pulsado el timbre no suena
    else:
        GPIO.output(pinBocina, False)
    sleep(0.2)
    
GPIO.cleanup()

