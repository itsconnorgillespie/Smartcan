#include <NewPing.h>
#include <Adafruit_Sensor.h>
#include<SoftwareSerial.h>
#include <NewTone.h>
#include <LiquidCrystal.h>

// Smartcan settings
boolean debug = true;
String server = "";
int port = 80;
String token = "";
String ssid = "";
String password = "";
long period = 300000L;

// Circuit
const int rx = 6;
const int tx = 7;
const int trigger = 8;
const int echo = 9;
const int piezo = 10;
const int tilt = 13;
const byte ldr = A0;
const int rs = 12;
const int en = 11;
const int d4 = 5;
const int d5 = 4;
const int d6 = 3;
const int d7 = 2;

// Variables
int _time; 
boolean _status = false;
double _average;
long _lid = period * -1;
long _tip = period * -1;
int _state;
long _clock;

SoftwareSerial esp(rx,tx);
NewPing hc(trigger, echo, 20);
LiquidCrystal lcd(rs, en, d4, d5, d6, d7);

/*
  Pre-loop sensor and WiFi checks.
*/
void setup(){
  // Serial
  Serial.begin(9600);
  esp.begin(115200);

  // ESP8266-01
  transmit("AT+RST", 5, "OK");
  transmit("AT+CIPMUX=1", 5, "OK");
  transmit("AT+CWMODE=3", 5, "OK");
  transmit("AT+CWJAP=\"" + ssid + "\",\"" + password + "\"", 15, "OK");

  // LDR
  _average = level();
  Serial.println("LDR => " + String(_average));

  // Tilt
  pinMode(tilt, INPUT);
  digitalWrite(tilt, HIGH);
  _state = digitalRead(tilt);

  // Clock
  _clock = millis();

  // LCD
  lcd.begin(16,2);
}

/*
  A loop of checks that repeat every 1/4 of a second. 
*/
void loop(){
  delay(10);
  
  // HC Counter
  int _distance = hc.ping_cm();
  if(_distance <= 3){
    Serial.println("Trigger");
    //request("count=1");
  }

  // Every 2 Seconds
  if(millis() >= _clock + 2000){
    _clock = millis();

    // Tilt Alarm
    int _tilt = digitalRead(tilt); 
    if(_tilt != _state){
      _state = _tilt;
      
      if(millis() >= _tip + period){
        _tip = millis();
        
        // Error code
        alarm(true);
        delay(1000);
        alarm(false);
  
        // Notification Request
        request("tip=1");

        // LCD
        lcd.print("Tilt Alarm!");
      }
    }
      
    // LDR Alarm
    int _level = level();   
    if(_level > _average + 5){
      if(millis() >= _lid + period){
        _lid = millis();
        
        // Error code
        alarm(true);
        delay(1000);
        alarm(false);
  
        // Notification Request
        request("lid=1");

        // LCD
        lcd.print("Lid Alarm!");
      }
    }

    // Debug Messages
    if(debug){
      Serial.println("Tilt => " + String(_tilt));
      Serial.println("LDR => " + String(_level));
    }
  }
}

/* 
  Piezo functions used to toggle the buzzer on or off at the user's request.
*/
void alarm(boolean _status){
  // Start
  if(_status){
    NewTone(piezo, 1000);
  }
  // Stop
  else {
    noNewTone(piezo);
  }
}

/* 
  ESP8266-01 functions used to preform GET requests and AT commands. Preformed as slave device, not directly on the module.
*/
void transmit(String _command, int _timeout, char _result[]){
  while(_time < _timeout){
    esp.println(_command);
    
    if(esp.find(_result)){
      _status = true;
      break;
    }
  
    _time++;
  }

  // Debug messages
  if(debug){
    Serial.print(_command);
    Serial.print(" => ");

    if(_status){
      Serial.println("Success\r");
    }
  
    else {
      Serial.println("Fail\r");

      // Error code
      alarm(true);
      delay(200);
      alarm(false);
      delay(200);
      alarm(true);
      delay(200);
      alarm(false);
    }
  }

  // Reset global variables
  _time = 0;
  _status = false;
}

void request(String _request){
  String _get = "GET /api.php?token=" + token + "&" + _request;
  transmit("AT+CIPMUX=1", 5, "OK");
  transmit("AT+CIPSTART=0,\"TCP\",\"" + server + "\"," + port, 15, "OK");
  transmit("AT+CIPSEND=0," + String(_get.length()+6), 5, ">");
  transmit(_get, 5, "SEND OK");
  transmit("AT+CIPCLOSE=0", 5, "OK");
}

/* 
  LDR functions used to detect light level changes from the bottom of the lid.
*/
double level(){
  double _sum = 0;
  
  for(int i = 0; i < 5; i++){
    _sum += analogRead(ldr);
  }

  return _sum / 5; 
}
