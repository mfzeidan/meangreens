
import MySQLdb
import time

con = MySQLdb.connect(host='159.203.165.37', port=3306,
		user='root',
		passwd='dataworks',
		db='login')
cur = con.cursor()


def send_email(email):
	print "im going to now send an email to " + str(email)

def table_lookup(devID):

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

	print "next step"

	try:
		cur.execute(sqldata2)
		email_rows = cur.fetchall()
		for email in email_rows:
			emailToSend = email[0]
	except:
		con.rollback()
		print "failed"


	con.close()
	send_email(emailToSend)	
	
table_lookup(445)
