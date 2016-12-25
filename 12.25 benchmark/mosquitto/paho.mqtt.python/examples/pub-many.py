#!/usr/bin/python

# Copyright (c) 2014 Roger Light <roger@atchoo.org>
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

# This shows an example of using the publish.single helper function.

import sys
import time
import json
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

#pub_msg = {"DeviceID": "1"}
	



##pub_msg = (json.dumps({'DeviceID': "001","start_datetime":"","current_datetime":"","Temperature":77,"Moisture_1":45,"Moisture_2":44,"plant_height":0,"newgrow_flag":"0"}, sort_keys=True, indent=4))

	
pub_msg = (json.dumps({'DeviceID': "1","Weight":3.4}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")

time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":3.4}, sort_keys=True, indent=4))


publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":3.4}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":3.4}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":3.3}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":3.2}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":3.1}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":3.1}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":3.1}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":3.1}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":2.3}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":2.3}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":2.3}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":2.3}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":2.3}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)
	
pub_msg = (json.dumps({'DeviceID': "1","Weight":5.3}, sort_keys=True, indent=4))

publish.single("infs740_test", str(pub_msg), hostname="104.236.210.175")
time.sleep(5)

