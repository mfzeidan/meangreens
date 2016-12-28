

from datetime import datetime
from pytz import timezone
#import datetime
import json
import sys
import MySQLdb
import time

thedate = time.strftime('\'%Y-%m-%d %H:%M:%S\'')
fmt = "\'%Y-%m-%d %H:%M:%S\'"
# Current time in UTC
now_utc = datetime.now(timezone('UTC'))
# Convert to US/Pacific time zone
now_est = now_utc.astimezone(timezone('US/Eastern'))
datetime_val = now_est.strftime(fmt)
#print datetime.now()

#current datetime
print "current time value"
print datetime_val


print "current time unix"

ts = int(time.time())
print ts

target_table = 'hydro_1228'

con = MySQLdb.connect(host='159.203.165.37', port=3306,
		user='root',
		passwd='dataworks',
		db='ddhydro')
cur = con.cursor()

#table structure

# soil moisture, growing environment, outside temperature of container, inside temp of container,
	# humidity of environment, target table 

#print thedate
sqldata = 'INSERT INTO %s VALUES(%s,%s)' % (target_table, ts, datetime_val)
print sqldata
try:
	cur.execute(sqldata)
	con.commit()
	print "committed"
except:
	con.rollback()
	print "nope"
con.close()
#print "that works"
#except:
#	print "exception"
