#!/usr/bin/python

import time 
import MySQLdb

#Create connection
con = MySQLdb.connect(host='159.203.165.37', port=3306,
		user='root',
		passwd='dataworks',
		db='ddhydro')
cur = con.cursor()

#Insert record


con.close()


#GRANT ALL PRIVILEGES ON *.* TO 'root'@'104.236.210.175' IDENTIFIED BY dataworks WITH GRANT OPTION;

#GRANT ALL PRIVILEGES ON *.* TO 'root'@'104.236.210.175'
#    IDENTIFIED BY 'some_characters'  
#    WITH GRANT OPTION;