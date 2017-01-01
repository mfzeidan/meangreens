

try:
    import paho.mqtt.publish as publish
except ImportError:
    # This part is only required to run the example from within the examples
    # directory when the module itself is not installed.
    #
    # If you have the module installed, just use "import paho.mqtt.publish"
    import os
    import inspect
    cmd_subfolder = os.path.realpath(os.path.abspath(os.path.join(os.path.split(inspect.getfile( inspect.currentframe() ))[0],"../src")))
    if cmd_subfolder not in sys.path:
        sys.path.insert(0, cmd_subfolder)
    import paho.mqtt.publish as publish


# This shows a simple example of an MQTT subscriber.
from datetime import datetime
from pytz import timezone
#import datetime
import json
import sys
import MySQLdb
import time
import json


def device_publisher(deviceID, topic, messageToSend,host):

	topic_creator = str(topic) + "|" + str(deviceID)
	print topic_creator
	#time.sleep(500)

	publish.single(topic_creator, messageToSend, hostname=host)
	print "message published to " + topic_creator
	#time.sleep(500)


def grow_podv1(mainloop, datetimeloop,deviceID, deviceTopic, host):
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
			
			device_publisher(deviceID,deviceTopic,pub_msg,host)


			#TODO change the sleep the time the time difference once this testing begins
			#variable is "time to sleep"
			#print pub_msg
			time.sleep(2)
			i+=1


def get_data(tbl,ID,Topic,host):
	con = MySQLdb.connect(host='159.203.165.37', port=3306,
			user='root',
			passwd='dataworks',
			db='ddhydro')
	cur = con.cursor()


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

	#after the data is retrieved the next function is kicked off the parse all the data in an appropriate growing manner

	grow_podv1(main_rows,date_lookup,ID,Topic,host)
	

#growing cylce is just going to be the name of the table we're going to reference to grow our stuff

growing_cycle = 'hydro_info_8'
devID = "1d"
topic = "22"
	#host is our broker host
host = "104.236.210.175"

get_data(growing_cycle, devID, topic, host)

	
