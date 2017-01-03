
import MySQLdb
import time

con = MySQLdb.connect(host='159.203.165.37', port=3306,
		user='root',
		passwd='dataworks',
		db='login')
cur = con.cursor()


def send_email(email,current_temps):
	print "im going to now send an email to " + str(email)
	print current_temps
	
	
	
def temperature_trigger(devID,device_temp):

	temp_lookup = 'select desired_temp from device_mgmt where deviceID = %s' % (devID)

	try:
		cur.execute(temp_lookup)
		rows_temp = cur.fetchall()
		#print rows
		sql_list = []
		for r in rows_temp:
			print r[0]
			desired_temps =int(r[0])
			print device_temp
	except:
		con.rollback()
		print "nopeeeeeeeeeeee"

	if desired_temps < device_temp:
		print "desired temp"
		print desired_temps
		print "device_temp"
		print device_temp
		table_lookup(devID,device_temp)
	else:
		print "not high enough"

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

	try:
		cur.execute(sqldata)
		rows = cur.fetchall()
		#print rows
		sql_list = []
		for r in rows:
			print "username"
			username33 = r[0]
			print username33
			#username_lookup = 'mark123'
			sqldata2 = """select email from user where username = '%s'""" % (username33)
			#print sqldata2

	except:
		con.rollback()
		print "nope"

	try:
		cur.execute(sqldata2)
		email_rows = cur.fetchall()
		for email in email_rows:
			emailToSend = email[0]
	except:
		con.rollback()
		print "failed"
	con.close()

	send_email(emailToSend,current_temp)	
	

temperature_trigger(445,125)
