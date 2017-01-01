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
import json
#now = datetime.datetime.now()



def grow_podv1(mainloop, datetimeloop):
	i = 1
	#print mainloop[13004]
	lengthOfCycle = len(mainloop)
	print lengthOfCycle
	for r in mainloop:	
	#0 is environment, 1 is humidity,  2 is a temp, 3 is a temp, 4 is moisture levels, 5 is datetime
		if i == lengthOfCycle:
			sys.exit()
		else:
			print i
			print "date from our list should be one ahead of the date below"
			nexttime = datetimeloop[i][5]
			print "this is the date from the loop we're using"
			currenttime = r[5]
			print nexttime
			print currenttime
			diff = nexttime - currenttime
			print diff
			timeToSleep = int(diff.total_seconds())
			print timeToSleep
			pub_msg = (json.dumps({'environment': r[0],'humidity':r[1], 'temp1':r[2], 'temp2':r[3],'moisture':r[4]}, sort_keys=True, indent=4))
			
			#print pub_msg
			time.sleep(2)
			i+=1


def get_data(tbl):
	con = MySQLdb.connect(host='159.203.165.37', port=3306,
			user='root',
			passwd='dataworks',
			db='ddhydro')
	cur = con.cursor()

	devid = 333

	sqldata = 'select * from %s order by datetime asc;' % (tbl)
	print sqldata
	try:
		cur.execute(sqldata)
		main_rows = cur.fetchall()
		date_lookup = main_rows
		print "done"
		#for r in rows:
		#	print r

	except:
		con.rollback()
		print "nope"
	con.close()

	grow_podv1(main_rows,date_lookup)
	


table_scan = 'hydro_info_8 '

get_data(table_scan)

	
