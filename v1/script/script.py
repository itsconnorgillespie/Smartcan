import time
import cv2
import mysql.connector
import RPi.GPIO as GPIO
from gpiozero import Button


mydb = mysql.connector.connect(
    host="host",
    user="username",
    passwd="password",
    database="database"
)

cursor = mydb.cursor()

GPIO.setmode(GPIO.BCM)
GPIO.setup(18, GPIO.IN,pull_up_down=GPIO.PUD_UP)

while True:
    studentid = input('Enter your Student Id: ')
    
    #Counts Bottles
    try:
        bottles = 1
        while True:
            bottles = bottles + 1
            print(bottles)
            time.sleep(.5)
            if button = GPIO.input(23):
                break
            
    #Updates or Inserts New Data       
    finally:

        #Selects the User's Information
        sql = "SELECT * FROM bioproject WHERE studentid = %s"
        val = (studentid,)

        cursor.execute(sql, val)
        result = cursor.fetchall()

        #If The User Is Not In The Database, It Will Insert a New Row
        if not result:
            sql = "INSERT INTO bioproject (studentid, collected) VALUES (%s, %s)"
            val = (studentid, bottles)
            cursor.execute(sql, val)
            print("Inserted")

        #If The User Is In The Database, It Will Update The User's Information
        else:
            for row in result:
                if (row[0] == studentid):
                    bottles = bottles + row[1]
                    sql = "UPDATE bioproject SET collected = %s WHERE studentid = %s"
                    val = (bottles, studentid)
                    print("Updated")
                    cursor.execute(sql, val)

        mydb.commit() #Updates or Inserts MySQL Information
        print('Done')
