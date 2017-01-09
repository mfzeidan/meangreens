
import MySQLdb
import time
import datetime
from pytz import timezone


def scan_old_emails(timeframe):

	print datetime.datetime.now().strftime("%s")

	x = datetime.datetime.now() - datetime.timedelta(minutes=timeframe)
	print "fifteen minutes ago"
	old_time = x.strftime("%s")
	print old_time
	#old_time = 1483900959

	sql_statement = """select * from email_logging where ts < %s """ % (old_time)
	#sql_statement = """select email from email_logging"""
	print sql_statement
	con = MySQLdb.connect(host='159.203.165.37', port=3306,
		user='root',
		passwd='dataworks',
		db='login')
	cur = con.cursor()

	log_sql_statement = """insert into email_logging (email, ts,active) values ('adam@gmail.com',1483903047,1)"""


	cur.execute(log_sql_statement)
	try:
		
		print "executed"
	except:
		con.rollback()
		print "failed"
	rows = cur.fetchall()
	#print len(rows)
	

	for r in rows:
		print r[0]
		email_to_delete = r[0]
		email_sql_statement = """delete from email_logging where email = '%s'""" % (email_to_delete)
		print email_sql_statement
		cur.execute(email_sql_statement)
		
		con.commit()


email = 'mfzeidan@gmail.com'
#scan_emails(email)

scan_old_emails(15)








