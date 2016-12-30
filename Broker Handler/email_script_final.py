#!/usr/bin/python

# Copyright (c) 2010-2013 Roger Light <roger@atchoo.org>
#
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the Eclipse Distribution License v1.0
# which accompanies this distribution. 
#
# The Eclipse Distribution License is available at 
#   http://www.eclipse.org/org/documents/edl-v10.php.
#
# Contributors:
#    Roger Light - initial implementation
# Copyright (c) 2010,2011 Roger Light <roger@atchoo.org>
# All rights reserved.

# This shows a simple example of an MQTT subscriber.
from datetime import datetime
from pytz import timezone
#import datetime
import json
import sys
import MySQLdb
import time
import smtplib
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText
	
	
	
#now = datetime.datetime.now()
try:
    import paho.mqtt.client as mqtt
except ImportError:
    # This part is only required to run the example from within the examples
    # directory when the module itself is not installed.
    #
    # If you have the module installed, just use "import paho.mqtt.client"
    import os
    import inspect
    cmd_subfolder = os.path.realpath(os.path.abspath(os.path.join(os.path.split(inspect.getfile( inspect.currentframe() ))[0],"../src")))
    if cmd_subfolder not in sys.path:
        sys.path.insert(0, cmd_subfolder)
    import paho.mqtt.client as mqtt



def on_connect(mqttc, obj, flags, rc):
    print("rc: "+str(rc))

def on_message1(mqttc, obj, msg):
    print(msg.topic+" "+str(msg.qos)+" "+str(msg.payload))
	
	
	
def mail_to_send(to_address):	
	fromaddr = "smokertempmail@gmail.com"
	toaddr = to_address
	msg = MIMEMultipart()
	msg['From'] = fromaddr
	msg['To'] = toaddr
	msg['Subject'] = "SUBJECT OF THE MAIL"
	 
	body = "lookup successful"
	msg.attach(MIMEText(body, 'plain'))
	 
	server = smtplib.SMTP('smtp.gmail.com:587')
	server.ehlo()
	server.starttls()
	server.login(fromaddr, "44sdsdsdSD")
	text = msg.as_string()
	server.sendmail(fromaddr, toaddr, text)
	server.quit()
	
def get_the_email(deviceID):
	con = MySQLdb.connect(host='159.203.165.37', port=3306,
			user='root',
			passwd='dataworks',
			db='ddhydro')
	cur = con.cursor()
	
	sqldata = 'Select email from emails where devid = %s' % (deviceID)
	
	try:
		cur.execute(sqldata)
		rows = cur.fetchall()
		for r in rows:
			print r[0]
			email_to_send = r[0]
			
	except:
		con.rollback()
		print "nope"

	con.close()
	
	print email_to_send
	
	#send the email using the function written above
	
	mail_to_send(email_to_send)
	
	
		
	
def on_message(mqttc, obj, msg):
	#try:
	x = (str(msg.payload))
	data = json.loads(x)
	
	print data
	device_ID= int(data['devid'])
	print device_ID

	if data['status'] == 'wet':
		get_the_email(device_ID)
		


def on_publish(mqttc, obj, mid):
    print("mid: "+str(mid))

def on_subscribe(mqttc, obj, mid, granted_qos):
    print("Subscribed: "+str(mid)+" "+str(granted_qos))

def on_log(mqttc, obj, level, string):
    print(string)

# If you want to use a specific client id, use
# mqttc = mqtt.Client("client-id")
# but note that the client id must be unique on the broker. Leaving the client
# id parameter empty will generate a random id for you. 	
mqttc = mqtt.Client()
mqttc.on_message = on_message
mqttc.on_connect = on_connect
mqttc.on_publish = on_publish
mqttc.on_subscribe = on_subscribe
# Uncomment to enable debug messages
#mqttc.on_log = on_log 
mqttc.connect("104.236.210.175", 1883, 60)
mqttc.subscribe("email_test", 0)


mqttc.loop_forever()
