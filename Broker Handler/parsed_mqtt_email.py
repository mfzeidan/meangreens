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


def on_publish(mqttc, obj, mid):
    print("mid: "+str(mid))

def on_subscribe(mqttc, obj, mid, granted_qos):
    print("Subscribed: "+str(mid)+" "+str(granted_qos))

def on_log(mqttc, obj, level, string):
    print(string)



#this is where we log any sql statement that we want 


def sql_logger(sql_statement):
	print "logging beginning"

	con = MySQLdb.connect(host='159.203.165.37', port=3306,
			user='root',
			passwd='dataworks',
			db='login')
	cur = con.cursor()
	print sql_statement
	cur.execute(sql_statement)
	try:
		
		print "inserted"
	except:
		con.rollback()
		print "failed"
	con.commit()

	


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

	#TEMPERATURE TRIGGER LOOKS FOR THE RELATED SET TEMPERATURE FOR THE DEVICE


	temperature_trigger(device_ID,temp)


#function that calls the function below, not sure why I made it originally
def send_email(email,current_temps,devID):
	print "im going to now send an email to " + str(email)
	print current_temps
	mail_to_send(email, current_temps,devID)


#function to send an email to whomever we want

def mail_to_send(to, temp,deviceID):	
	import smtplib
	from email.MIMEMultipart import MIMEMultipart
	from email.MIMEText import MIMEText
	 
	 
	fromaddr = "smokertempmail@gmail.com"
	toaddr = "mfzeidan@gmail.com"
	msg = MIMEMultipart()
	msg['From'] = fromaddr
	msg['To'] = to
	msg['Subject'] = "SUBJECT OF THE MAIL"
	 
	body = "Your device " + str(deviceID) +  " has reached the desire temperature of " + str(temp) + " degrees F. Please remove it now! \n Visit us at www.meagreensfarm.com"
	msg.attach(MIMEText(body, 'plain'))
	 
	server = smtplib.SMTP('smtp.gmail.com:587')
	server.ehlo()
	server.starttls()
	server.login(fromaddr, "44sdsdsdSD")
	text = msg.as_string()
	server.sendmail(fromaddr, toaddr, text)
	server.quit()





def log_this(devID,temp):
	#print "the following info needs to be logged in a table"
	#print devID
	#print temp
	unx_timestamp = int(time.time())
	print "message has been logged"
	logging_sql = """INSERT INTO device_msg_loggerv2 (deviceID, temperature,ts) values (%s,%s,%s)""" % (devID,temp,unx_timestamp)
	sql_logger(logging_sql)
	
	


	
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
			print "desired temperature found in query"
			print r[0]
			desired_temps =int(r[0])
			print "desired temperature found in query"
	
	except:
		con.rollback()


	if desired_temps < device_temp:
		print "desired temp"
		print desired_temps
		print "device_temp"
		print device_temp
		table_lookup(devID,device_temp)
		
	else:
		print "not high enough"
		log_this(devID,device_temp)



def find_username(sql):


	cur.execute(sql)
	print "triggering initial sql statement"
	rows = cur.fetchall()
	print rows
	sql_list = []
	#print rows.rowcount()
	if cur.execute(sqldata) == 0:
		print "no records found"


	



def email_lookup_find(rows):

	for r in rows:
		print "username"
		username33 = r[0]
		print username33
		#username_lookup = 'mark123'
		sqldata2 = """select email from user where username = '%s'""" % (username33)
		print sqldata2
		
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
		#cur.reconnect()
		#print "connection has been closed"
		send_email(emailToSend,current_temp,devID)

	

def table_lookup(devID,current_temp):


	#find the username to get the email

	sqldata = 'select username from device_mgmt where deviceID = %s' % (devID)
	print sqldata

	try:
		cur.execute(sqldata)
		print "triggering initial sql statement"
		rows = cur.fetchall()
		print rows
		sql_list = []
		#print rows.rowcount()
		if cur.execute(sqldata) == 0:
			print "no records found"
			ts = int(time.time())
			no_device_found = """INSERT INTO no_device_found (devID,ts) VALUES (%s,%s)""" % (devID,ts)
			sql_logger(no_device_found)
		else:
			
			for r in rows:
				print "username"
				username33 = r[0]
				print username33
				#username_lookup = 'mark123'
				sqldata2 = """select email from user where username = '%s' """ % (username33)
				print sqldata2
				
				try:
					cur.execute(sqldata2)
					email_rows = cur.fetchall()
					for email in email_rows:
						print email
						emailToSend = email[0]
				except:
					con.rollback()
					print "failed"

				send_email(emailToSend,current_temp,devID)


	except:
		#con.rollback()
		print "no device found"


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


