#!/usr/bin/env python

# Test whether a client produces a correct connect with a will, username and password.

# The client should connect to port 1888 with keepalive=60, clean session set,
# client id 01-will-unpwd-set , will topic set to "will-topic", will payload
# set to "will message", will qos=2, will retain not set, username set to
# "oibvvwqw" and password set to "#'^2hg9a&nm38*us".

import inspect
import os
import subprocess
import socket
import sys

# From http://stackoverflow.com/questions/279237/python-import-a-module-from-a-folder
cmd_subfolder = os.path.realpath(os.path.abspath(os.path.join(os.path.split(inspect.getfile( inspect.currentframe() ))[0],"..")))
if cmd_subfolder not in sys.path:
    sys.path.insert(0, cmd_subfolder)

import paho_test

rc = 1
keepalive = 60
connect_packet = paho_test.gen_connect("01-will-unpwd-set",
        keepalive=keepalive, username="oibvvwqw", password="#'^2hg9a&nm38*us",
        will_topic="will-topic", will_qos=2, will_payload="will message")

sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
sock.settimeout(10)
sock.bind(('', 1888))
sock.listen(5)

client_args = sys.argv[1:]
env = dict(os.environ)
try:
    pp = env['PYTHONPATH']
except KeyError:
    pp = ''
env['PYTHONPATH'] = '../../src:'+pp
client = subprocess.Popen(client_args, env=env)

try:
    (conn, address) = sock.accept()
    conn.settimeout(10)

    if paho_test.expect_packet(conn, "connect", connect_packet):
        rc = 0

    conn.close()
finally:
    client.terminate()
    client.wait()
    sock.close()

exit(rc)
