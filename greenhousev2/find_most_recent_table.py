
import MySQLdb
import time


con = MySQLdb.connect(host='159.203.165.37', port=3306,
		user='root',
		passwd='dataworks',
		db='ddhydro')
cur = con.cursor()

sqldata = 'show tables'

try:
	cur.execute(sqldata)
	rows = cur.fetchall()
	#print rows
	sql_list = []
	for r in rows:
		#print r[0]
		if "hydro_info" in r[0]:
			sql_list.append(r[0])

except:
	con.rollback()
	print "nope"
con.close()

sql_list_sorted = sorted(sql_list, reverse = True)

#note that this is the table thats most recent that needs to be used for the hot data

first_table = sql_list_sorted[0]
print first_table
