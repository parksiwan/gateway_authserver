#!/usr/bin/env python
# The first fork accomplishes two things - allow the shell to return, and allow you to do a setsid().
#
# The setsid() removes yourself from your controlling terminal. You see, before, you were still listed
# as a job of your previous process, and therefore the user might accidentally send you a signal. 
# setsid() gives you a new session, and removes the existing controlling terminal.
#
# The problem is, you are now a session leader. As a session leader, if you open a file descriptor that is a terminal, 
# it will become your controlling terminal (oops!). Therefore, the second fork makes you NOT be a session leader. 
# Only session leaders can acquire a controlling terminal, so you can open up any file you wish 
# without worrying that it will make you a controlling terminal.
#
# So - first fork - allow shell to return, and permit you to call setsid()
#
# Second fork - prevent you from accidentally reacquiring a controlling terminal.

import MySQLdb
from socket import *
from daemonize import daemonize

def find_mac_address():
    HOST = 'l'
    PORT = 8082
    BUFSIZ = 1024
    ADDR = (HOST, PORT)

    server_sock = socket(AF_INET, SOCK_STREAM)
    server_sock.bind(ADDR)
    server_sock.listen(5)

    while True:
        (client_sock, addr) = server_sock.accept()
        mac = client_sock.recv(BUFSIZ)
        print(mac)
        db = MySQLdb.connect(host="localhost", user="auth_server", passwd="psw1101714", db="auth_sever") 
        cur = db.cursor() 

        
                
        response = 'nothing' + '&' + 'nothing'
        client_sock.send(response)
        client_sock.close()
        

        

    server_sock.close()


if __name__ == '__main__':
    daemonize(stdout='/tmp/stdout.log', stderr='/tmp/stderr.log')
    find_mac_address()










if __name__ == '__main__':