con = MySQLdb.connect(host='159.203.165.37', port=3306,
		user='root',
		passwd='.......',
		db='ddhydro')
cur = con.cursor()

devid = 333

sqldata = 'Select email from emails where devid = 333'
print sqldata
try:
	cur.execute(sqldata)
	rows = cur.fetchall()
	for r in rows:
		print r[0]

except:
	con.rollback()
	print "nope"
con.close()
#print "that works"
#except:
#	print "exception"
	

