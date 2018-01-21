#!/usr/bin/env python

import sys
import re
import logging
import subprocess
from datetime import datetime
from socket import *
import MySQLdb


def authenticate_mac(mac):
    HOST = ''
    PORT = 8081
    BUFSIZ = 1024
    ADDR = (HOST, PORT)

    client_sock = socket(AF_INET, SOCK_STREAM)
    client_sock.connect(ADDR)
    server_response = 'qwe'
    client_sock.send(mac)
    print (mac)
    server_response = client_sock.recv(BUFSIZ)
    print (mac+'aaa')
    client_sock.close()
    if server_response != '':
        client_info = server_response.split('&')
        found_mac = client_info[0]
        account_id = client_info[1]
        if found_mac != 'nothing':
            return True
        else:
            return False


if __name__ == '__main__':
    found_mac = authenticate_mac('12:23:12:qw:12:12')
    print (found_mac)