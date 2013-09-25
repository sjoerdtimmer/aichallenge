#!/usr/bin/python

import time

import MySQLdb
from server_info import server_info

DEFAULT_BUFFER = 50
MAX_FILL = 60

def log(msg):
    timestamp = time.asctime()
    print "%s: %s" % (timestamp, msg)

def add_matchup():
    try:
        with MySQLdb.connect(host = server_info["db_host"],
                             user = server_info["db_username"],
                             passwd = server_info["db_password"],
                             db = server_info["db_name"]) as con:
            with con.cursor() as cur:
                cur.callproc("generate_matchup",())
                cur.close()
                con.commit()
                con.close()
    except MySQLdb.Error, e:
        print("MySQL Error %d: %s"%(e.args[0],e.args[1]))
       


def main():
    connection = MySQLdb.connect(host = server_info["db_host"],
                                 user = server_info["db_username"],
                                 passwd = server_info["db_password"],
                                 db = server_info["db_name"])
    

    buf_size = DEFAULT_BUFFER
    log("Buffer size set to %d" % (buf_size,))

    fill_size = buf_size
    full = False
    while True:
        cursor = connection.cursor()
        cursor.execute("select count(*) from matchup where worker_id is NULL")
        cur_buffer = cursor.fetchone()[0]
        cursor.close()

        if cur_buffer >= buf_size:
            log("Buffer full with %d matches in buffer" % (cur_buffer,))
            time.sleep(10)
            if full:
                fill_size = max(buf_size, fill_size * 0.9)
            full = True
        else:
            if not full:
                fill_size = min(MAX_FILL, fill_size * 1.5)
            full = False
            add = int(fill_size) - cur_buffer
            if cur_buffer == 0:
                log("WARNING: Found empty buffer")
            log("Adding %d matches to buffer already having %d" % (
                add, cur_buffer))
            for i in range(add):
                add_matchup()
                #print("adding matchup:")
                #cursor = connection.cursor()
                #cursor.callproc("generate_matchup")
                #cursor.close()
                #connection.commit()
                #cursor.execute("call generate_matchup")
                #print("result:")
                #print(cursor.fetchall())
                #r = 1
                #while r is not None:
                #    r = cursor.nextset()
                #    print("next set: %s"%r)

if __name__ == "__main__":
    main()

