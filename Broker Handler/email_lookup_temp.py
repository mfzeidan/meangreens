import sys
import json
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
import MySQLdb
import time

topic = "thetopic"

con = MySQLdb.connect(host='159.203.165.37', port=3306,
		user='root',
		passwd='dataworks',
		db='login')
cur = con.cursor()

def on_connect(mqttc, obj, flags, rc):
    print("rc: "+str(rc))


def on_message(mqttc, obj, msg):
	con = MySQLdb.connect(host='159.203.165.37', port=3306,
			user='root',
			passwd='dataworks',
			db='login')
	cur = con.cursor()
	#try:
	x = (str(msg.payload))
	data = json.loads(x)
	
	print data
	device_ID= int(data['devID'])
	temp= int(data['current_temp'])
	print device_ID
	print temp


	temperature_trigger(device_ID,temp)


	

def on_publish(mqttc, obj, mid):
    print("mid: "+str(mid))

def on_subscribe(mqttc, obj, mid, granted_qos):
    print("Subscribed: "+str(mid)+" "+str(granted_qos))

def on_log(mqttc, obj, level, string):
    print(string)


	



def send_email(email,current_temps):
	print "im going to now send an email to " + str(email)
	print current_temps

def log_this(devID,temp):
	print "the following info needs to be logged in a table"
	print devID
	print temp
	
	
	
def temperature_trigger(devID,device_temp):
	desired_temps = 0

	temp_lookup = 'select desired_temp from device_mgmt where deviceID = %s' % (devID)

	try:
		cur.execute(temp_lookup)
		rows_temp = cur.fetchall()
		#print rows
		sql_list = []
		print "rows_temp"

		for r in rows_temp:
			print r[0]
			desired_temps =int(r[0])
			print "desired temperature found in query"
			print desired_temps
			print desired_temps
			print desired_temps
			print desired_temps
	
	except:
		con.rollback()


	if desired_temps < device_temp:
	#if device_temp:
		print "desired temp"
		print desired_temps
		print "device_temp"
		print device_temp
		table_lookup(devID,device_temp)
		
	else:
		print "not high enough"
		log_this(devID,device_temp)
	

def table_lookup(devID,current_temp):


	sqldata = 'select username from device_mgmt where deviceID = %s' % (devID)

	try:
		cur.execute(sqldata)
		rows = cur.fetchall()
		#print rows
		sql_list = []
		for r in rows:
			username_lookup = r[0]
			sqldata2 = """select email from user where username = '%s'""" % (username_lookup)
			#print sqldata2


	except:
		con.rollback()
		print "nopeeeeee"


	#find the username to get the email

	sqldata = 'select username from device_mgmt where deviceID = %s' % (devID)
	print sqldata

	try:
		cur.execute(sqldata)
		print "triggering initial ssql statement"
		rows = cur.fetchall()
		print rows
		sql_list = []
		for r in rows:
			print "username"
			username33 = r[0]
			print username33
			#username_lookup = 'mark123'
			sqldata2 = """select email from user where username = '%s'""" % (username33)
			print sqldata2

	except:
		con.rollback()
		print "nope"
	emailToSend = "mfzeidan@gmail.com"

	try:
		cur.execute(sqldata2)
		email_rows = cur.fetchall()
		for email in email_rows:
			print email
			emailToSend = email[0]
	except:
		con.rollback()
		print "failed"
	#con.close()
	cur.reconnect()
	print "connection has been closed"

	send_email(emailToSend,current_temp)	
	


mqttc = mqtt.Client()
mqttc.on_message = on_message
mqttc.on_connect = on_connect
mqttc.on_publish = on_publish
mqttc.on_subscribe = on_subscribe
# Uncomment to enable debug messages
#mqttc.on_log = on_log 
mqttc.connect("104.236.210.175", 1883, 60)
mqttc.subscribe(topic, 0)
mqttc.loop_forever()




