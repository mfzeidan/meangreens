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
from datetime import datetime
from pytz import timezone


topic = "esp_temp_test2"

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
	
	print data
	device_ID= int(data['devID'])
	temp= int(data['current_temp'])
	print device_ID
	print temp

	#TEMPERATURE TRIGGER LOOKS FOR THE RELATED SET TEMPERATURE FOR THE DEVICE


	temperature_trigger(device_ID,temp)



def email_sent_log(email):
	unx_timestamp = int(time.time())
	
	log_sql_statement = """insert into email_logging (email, ts, active) values ('%s',%s,1)""" % (email, unx_timestamp)
	print log_sql_statement

	con = MySQLdb.connect(host='159.203.165.37', port=3306,
		user='root',
		passwd='dataworks',
		db='login')
	cur = con.cursor()

	cur.execute(log_sql_statement)
	print "executed"
	con.commit()

#function that calls the function below, not sure why I made it originally
def send_email(email,current_temps,devID):
	print "im going to now send an email to " + str(email)
	print current_temps


	#here we will add the logic to check if an emailneeds to be added or not
	#TODO we need to add a script that will clear and old email logs in this table


	email_sql_check = """SELECT email from email_logging where email = '%s'""" % (email)
	cur.execute(email_sql_check)
	con.commit()
	rows_temp = cur.fetchall()

	email_total =int(len(rows_temp))
	
	if email_total > 0:
		print "an email has already been sent to this address"
		pass
		
	else:
		mail_to_send(email, current_temps,devID)
		print "an email is being sent to this address"


	



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
	
	email_sent_log(to)




def log_this(devID,temp):
	#print "the following info needs to be logged in a table"
	#print devID
	#print temp

	unx_timestamp = int(time.time())

	thedate = time.strftime('\'%Y-%m-%d %H:%M:%S\'')
	fmt = "\'%Y-%m-%d %H:%M:%S\'"
	now_utc = datetime.now(timezone('UTC'))
	now_est = now_utc.astimezone(timezone('US/Eastern'))
	datetime_val = now_est.strftime(fmt)
	#print datetime_val


	print "message has been logged"
	logging_sql = """INSERT INTO device_msg_loggerv3 (deviceID, temperature,ts,Datetime) values (%s,%s,%s,%s)""" % (devID,temp,unx_timestamp,datetime_val)
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

	#try:
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


#except:
	#con.rollback()
	#print "no device found"
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




