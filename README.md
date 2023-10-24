## Smartcan v1 (2019)
The development of this project all started during my freshman-year biology class. As honors students, we were challenged to develop a prototype that would increase sustainability on our high school campus. This is where the idea for Smartcan v1 was born. The Smartcan v1 was originally designed to link the number of bottles recycled to a student’s ID. Campus administrators could then use this data to reward students based on their recycling efforts. 

You can view photos of the Smartcan v1 [here](https://github.com/connorgillespie/Smartcan/tree/main/v1/photos). 

### Functionality 
The Smartcan v1 utilized a Raspberry Pi Zero to run all of the software on the physical device. I created a simple Python script that would run as a systemd service on startup. The script would connect to a remote MySQL database to store the data collected. Using sleep intervals and a button, I created a simple loop to count the number of bottles inputted. Once finished, the data would be inserted or updated on the MySQL database. On the web dashboard, I wrote a rudimentary PHP script to query the top ten rows ordered by the number of bottles collected from the remote MySQL database. 

Looking back, I have realized there was a multitude of fundamental issues with the Smartcan v1. First, the method used to count the number of bottles could easily be abused. Second, due to privacy laws within public school districts, there would be no way to feasibly and legally translate a student’s ID to their name. This would require the storage of students’ names, IDs, and other related information, which violates FERPA. Third, the software security of the dashboard and Python script is ridiculously weak. For example, a student could tamper with the Python script on the device and obtain raw usernames and passwords to the remote MySQL database. 

## Smartcan v2 (2021)
Fast forward to my junior year of high school. The Smartcan v2 was designed for the IoT internship at the California State University of Fullerton. Since the initial design, I have gained a lot of experience with programming, cybersecurity, and system design. Instead of promoting recycling, the second version of the project focuses on securing the device and providing remote configuration to better suit the role of an IoT device. Unlike Smartcan v1, the Smartcan v2 featured a web dashboard and utilized an ESP8266-01 to communicate via WiFi. 

You can view photos of the Smartcan v2 [here](https://github.com/connorgillespie/Smartcan/tree/main/v2/photos). 

### Functionality 
Instead of using a Raspberry Pi Zero like the Smartcan v1, the Smartcan v2 utilized an Arduino Uno and an ESP8266-01 to provide a more cost-effective solution. I used serial communication over 9600 baud to allow communication between the Arduino Uno and ESP8266-01. As per the internship requirements, I was required to use the ESP8266-01 rather than utilizing a WiFi shield. This posed a variety of challenges. First, I was not in possession at the time of the necessary resources to directly program onto the ESP8266-01. Therefore, the ESP8266-01 was utilized as a slave device and I utilized AT commands to communicate via WiFi. I quickly learned there is little documentation about using serial communication and AT commands with an Arduino Uno and ESP8266-01. After many attempts, I was able to develop a function that was able to transmit data via GET header over HTTPS. This allowed secure communication between the Smartcan v2 and the remote MySQL database located in the cloud. I also implemented an infrared obstacle avoidance module to more accurately track the number of bottles inserted. I also implemented new tamper protection algorithms to detect if an object was just being held in front of the sensor. With these new methods, the accuracy of the device increased by upwards of 95%.

Unlike the Smartcan v1, I also significantly improved the physical security of the device. First, I drilled holes in the bottom of the can to provide a drainage port in the event the device gets wet. This would ultimately protect the recyclables and sensors inside the can. Second, I developed an alarm system for the Smartcan v2 that would notify administrators via email in the event the can was tampered with. I attached an LDR sensor on the inside of the lid of the can. In the event that a malicious actor was to remove the lid, the sensor would detect the changes in light levels when the lid is removed and notify the system administrators. I additionally attached a tilt-and-ball sensor to the lid of the can. In the event that the can was knocked over or moved, the sensor would detect this change in motion and notify the system administrators. These two methods of tamper protection drastically increase physical security. Paired with a simple lock on the lid, these methods of security seemed adequate for the device's cost and method of deployment. 

The Smartcan v2 also featured a new web dashboard that was rebuilt from the ground up. The dashboard featured a configuration file to allow for easy configuration changes. The dashboard also featured a REST API to allow for the Smartcan v2 to insert and update data to the remote MySQL database and to trigger email alerts. Likewise, the dashboard also featured new security features such as Google Recaptcha, authentication tokens, and sessions. Like the security, the functionality of the web dashboard also increased. The dashboard allowed system administrators to easily toggle on and off tilt and lid alarms, edit when to trigger the almost-full alert, the email used to receive alerts, view and reset the running count of bottles currently in the can, and view and reset the all-time count.  

## Conclusion
In conclusion, this project ultimately helped mold me into the person I am today. Every day, I automatically focus on ways to apply programming, circuitry, and IoT to everyday items. This project further sparked my passion for technology and is my prized possession. At the time of creation, I was relatively unfamiliar with these technologies. With the redesign, this project gave me a chance to illustrate just how much I’ve learned in such a short amount of time and to illustrate my ability to comprehend these complex topics.

As of fall 2023, I have started the development of a non-disclosed IoT project for a non-disclosed company with [Grand Canyon University](https://www.gcu.edu/). The Smartcan has exponentially influenced my design practices, program management, and approach to creating embedded systems and IoT devices. Once this project is finished and if my statements are been approved by the beneficiaries, I would like to further elaborate on my accomplishments with how Smartcan further influenced these decisions.

## License
[Smartcan](https://github.com/connorgillespie/Smartcan) © 2019 by [Connor Gillespie](https://github.com/connorgillespie) is licensed under [CC BY-NC-ND 4.0](http://creativecommons.org/licenses/by-nc-nd/4.0/?ref=chooser-v1)  
![Creative Commons SVG](http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png)