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
#now = datetime.datetime.now()
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



def on_connect(mqttc, obj, flags, rc):
    print("rc: "+str(rc))

def on_message1(mqttc, obj, msg):
    print(msg.topic+" "+str(msg.qos)+" "+str(msg.payload))
	
	

con = MySQLdb.connect(host='159.203.165.37', port=3306,
		user='root',
		passwd='dataworks',
		db='ddhydro')
cur = con.cursor()


sqldata = 'show tables'
print sqldata
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
first_table = sql_list_sorted[0]
print first_table
