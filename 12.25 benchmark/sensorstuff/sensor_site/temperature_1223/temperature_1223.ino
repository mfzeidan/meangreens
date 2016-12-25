
#include <PubSubClient.h>
#include <ESP8266WiFi.h>
#include <ArduinoJson.h>
#include <OneWire.h>
#include <DallasTemperature.h>
 
// Data wire is plugged into pin 2 on the Arduino
#define ONE_WIRE_BUS 2
#define RELAY1  4   
#define FAN 6        
 
// Setup a oneWire instance to communicate with any OneWire devices 
// (not just Maxim/Dallas temperature ICs)
OneWire oneWire(ONE_WIRE_BUS);
 
// Pass our oneWire reference to Dallas Temperature.
DallasTemperature sensors(&oneWire);

const char* ssid = "XPX7B";
const char* password = "HCMLTGNWPFBLH9MB";
char* topic = "test";     //  using wildcard to monitor all traffic from mqtt server
char* server = "159.203.102.9";  // Address of my server on my network, substitute yours!
char message_buff[100];   // initialise storage buffer (i haven't tested to this capacity.)
int message_to_print = 76;
float current_temp = 74.0;
float desired_temp = 80;
const char* message;

char str[32];

  StaticJsonBuffer<200> jsonBuffer;

WiFiClient wifiClient;



void callback(char* topic, byte* payload, unsigned int length) {

  
    StaticJsonBuffer<200> jsonBuffer;
  // Here is what i have been using to handle subscriptions. I took it as a snippet from elsewhere but i cannot credit author as i dont have reference!
    int i = 0;

  Serial.println("Message arrived:  topic: " + String(topic));
  Serial.println("Length: " + String(length,DEC));
  
  // create character buffer with ending null terminator (string)
  for(i=0; i<length; i++) {
    message_buff[i] = payload[i];
  }
  message_buff[i] = '\0';

  //this takes all the characters received and puts them into a string
  
  String msgString = String(message_buff);
  Serial.println("Payload: " + msgString);
  //reading in current state of device very cool :)
  int state = digitalRead(2);  // get the current state of GPIO1 pin

  Serial.println(message_to_print);

///here is where we will parse the json message that's being received to update all variables once a message is received
  
  JsonObject& root = jsonBuffer.parseObject(msgString);


  desired_temp = root["desired_temp"];
  const char* humidity = root["newgrow_flag"];
  const char* sensor3 = root["Moisture"];
  const char* sensor4 = root["temp"];


  
  current_temp = 70;

  
  Serial.println("this is the desired temp");
  Serial.println(desired_temp);
  


  //const char* sensor = root["temp"];

 // Serial.println(root);
  
        }

PubSubClient client(server, 1883, callback, wifiClient);

String macToStr(const uint8_t* mac)
{
  String result;
  for (int i = 0; i < 6; ++i) {
    result += String(mac[i], 16);
    if (i < 5)
      result += ':';
  }
  return result;
}

 
void setup(void)
{
  // start serial port
  Serial.begin(9600);
  Serial.println("Dallas Temperature IC Control Library Demo");

      // prepare GPIO2 *********************************************************************
  pinMode(2, OUTPUT);   // i am using gpio2 as output to toggle an LED
  digitalWrite(2, 0);  //*****************************************************************

    //set the relay as output
    pinMode(RELAY1, OUTPUT);   
    pinMode(FAN, OUTPUT);  

  // Start up the library
  sensors.begin();

   
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  StaticJsonBuffer<200> jsonBuffer;

  
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");  
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
    if (client.connect("arduinoClient")) {
    //client.publish("outTopic","hello world");
    client.subscribe(topic);   // i should probably just point this to the varialbe above.
  }
}
 
 
void loop(void)
{
  // call sensors.requestTemperatures() to issue a global temperature
  // request to all devices on the bus
  Serial.print(" Requesting temperatures...");
  sensors.requestTemperatures(); // Send the command to get temperatures
  Serial.println("DONE");

  Serial.print("Temperature for Device 1 is: ");
  Serial.print(sensors.getTempFByIndex(0)); // Why "byIndex"? 
  current_temp = sensors.getTempFByIndex(0);
    // You can have more than one IC on the same bus. 
    // 0 refers to the first IC on the wire

   client.loop();
   Serial.println("current temp :");
   Serial.println(current_temp);

   Serial.println("desired temp :");
   Serial.println(desired_temp);
  //Serial.println(message_to_print);
  delay(1000);


  if (current_temp < desired_temp){ 
    //we want to get room temp up
    //digitalWrite(RELAY1,HIGH);
    delay(1000);
    Serial.println("temp still too low :)");
    digitalWrite(FAN,LOW);

         

  }
  else {
     digitalWrite(FAN,HIGH);
     Serial.println("temp now high enough");
     delay(1000);
     dtostrf(current_temp, 8, 2, str);
     client.publish("sendmail2",str);
     //delay(100000000000000);
  }
  
 
}
