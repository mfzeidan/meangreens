#include <PubSubClient.h>
#include <ESP8266WiFi.h>
#include <ArduinoJson.h>

#define RELAY1  4                        


//////////////////////////Added toggling led with any topic publish "1"  /////

 //setting up environmental variables
const char* ssid = "Verizon-SCH-I545-4A6B";
const char* password = "qama598#";
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
  Serial.println("Payload: " + msgString);
  //reading in current state of device very cool :)
  int state = digitalRead(2);  // get the current state of GPIO1 pin

  Serial.println(message_to_print);

///here is where we will parse the json message that's being received to update all variables once a message is received
  
  JsonObject& root = jsonBuffer.parseObject(msgString);

  int desired_temp = root["desired_temp"];
  const char* humidity = root["newgrow_flag"];
  const char* sensor3 = root["Moisture"];
  const char* sensor4 = root["temp"];
  int current_temp = 70;

  
  Serial.println("this is the desired temp");
  Serial.println(desired_temp);
  


  //const char* sensor = root["temp"];

 // Serial.println(root);
  
  Serial.println("test"); 
  Serial.println(desired_temp);
  Serial.println(humidity);
  Serial.println(sensor3);
  Serial.println(sensor4);
        

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

//here is where will build logic rules for temperature control
// basically we need to read in the current temperature and compare it to the most recent desired temperature
//most recent desired temperature should be saved as a variable from the callback function above 

/*
 * lets say desired temperature is 76degrees
 * this will be variable desired_temp = 76
 * Reading in the current temp right now from a sensor, current temp = 75
 * this needs to trigger the relay to OFF to let the room heat up
 * if the current temp was 77, set relay to ON to cool the room
 * 
 * now if a message is received, this signifies that a new desired temperature is being requested
 * this needs to update the desired_temp variable
 * then we just let the other code stay static to control the environment
 *
 * 

   
  if (String(temperature) == "001"){    // if there is a "1" published to any topic (#) on the broker then:
   message_to_print = 78;
   digitalWrite(RELAY1,LOW  );          // Turns Relay Off
   Serial.println(message_to_print);  
    digitalWrite(2, !state);     // set pin to the opposite state 
    Serial.println("Switching LED"); 
  }
  if (String(temperature) == "002"){
       digitalWrite(RELAY1,HIGH);          // Turns Relay Off
          Serial.println(message_to_print);

  } 
  
}
 */

  if (current_temp < desired_temp){
    //we want to get room temp up
    digitalWrite(RELAY1,HIGH);
    delay(1000);
  }
  else {
     digitalWrite(RELAY1,LOW);
     delay(1000);
  }

  Serial.println("current temp");
  Serial.println(current_temp);
  Serial.println("desired temp");
  Serial.println(desired_temp);
}
