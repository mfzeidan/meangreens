#include <PubSubClient.h>
#include <ESP8266WiFi.h>
#include <ArduinoJson.h>
#include <OneWire.h>
#include <DallasTemperature.h>

#define RELAY1  4                        
// Data wire is plugged into pin 5 on the Arduino
#define ONE_WIRE_BUS 5
// Setup a oneWire instance to communicate with any OneWire devices 
// (not just Maxim/Dallas temperature ICs)
OneWire oneWire(ONE_WIRE_BUS);
// Pass our oneWire reference to Dallas Temperature.
DallasTemperature sensors(&oneWire);
 

//////////////////////////Added toggling led with any topic publish "1"  /////

 //setting up environmental variables
//const char* ssid = ".....";
//const char* password = ".....#";
const char* ssid = ".....";
const char* password = ".....";
char* topic = "test";     //  using wildcard to monitor all traffic from mqtt server
char* server = "159.203.102.9";  // Address of my server on my network, substitute yours!
char message_buff[100];   // initialise storage buffer (i haven't tested to this capacity.)
int message_to_print = 76;

int current_temp = 74;
int desired_temp;

const char* temperature = "";
const char* humidity = "";
const char* sensor3 = "";
const char* sensor4 = "";




//this allows the esp to parse the json
  StaticJsonBuffer<200> jsonBuffer;

WiFiClient wifiClient;

//TODO: make this function much better, feels too hacky

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
    Serial.println(msgString);
  Serial.println("Payload: " + msgString);
  //reading in current state of device very cool :)
  int state = digitalRead(2);  // get the current state of GPIO1 pin

  Serial.println(message_to_print);

///here is where we will parse the json message that's being received to update all variables once a message is received
  
  JsonObject& root = jsonBuffer.parseObject(msgString);

  desired_temp = root["desired_temp"];
  //const char* humidity = root["newgrow_flag"];
  //const char* sensor3 = root["Moisture"];
  //const char* sensor4 = root["temp"];
  
  current_temp = 70;
  Serial.println("this is the desired temp");
  Serial.println(desired_temp);
  




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



 
void setup() {

   
  sensors.begin();

  int current_temp = 70;
  Serial.begin(115200);
  delay(10);
  
    // prepare GPIO2 *********************************************************************
  pinMode(2, OUTPUT);   // i am using gpio2 as output to toggle an LED
  digitalWrite(2, 0);  //*****************************************************************

  //set the relay as output
    pinMode(RELAY1, OUTPUT);       

  
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
 
  //  connection to broker script.
  //we can publish and subscribe to something here but not necssary
  if (client.connect("arduinoClient")) {
    //client.publish("outTopic","hello world");
    client.subscribe(topic);   // i should probably just point this to the varialbe above.
  }
}
 
void loop() {

  client.loop();
  Serial.println(message_to_print);
  delay(1000);


  Serial.println("current temp");
  Serial.println(current_temp);
  Serial.println("desired temp");
  Serial.println(desired_temp);
  
}



