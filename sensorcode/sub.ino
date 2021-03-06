#include <PubSubClient.h>
#include <ESP8266WiFi.h>
#include <ArduinoJson.h>

//////////////////////////Added toggling led with any topic publish "1"  /////
 
const char* ssid = "Verizon-SCH-I545-4A6B";
const char* password = "qama598#";
 
char* topic = "pubsub";     //  using wildcard to monitor all traffic from mqtt server
char* server = "104.236.210.175";  // Address of my server on my network, substitute yours!
 
char message_buff[100];   // initialise storage buffer (i haven't tested to this capacity.)

int message_to_print = 76;

//char json[] = "";
  StaticJsonBuffer<200> jsonBuffer;

WiFiClient wifiClient;

void callback(char* topic, byte* payload, unsigned int length) {
  // Here is what i have been using to handle subscriptions. I took it as a snippet from elsewhere but i cannot credit author as i dont have reference!
    int i = 0;

  Serial.println("Message arrived:  topic: " + String(topic));
  Serial.println("Length: " + String(length,DEC));
  
  // create character buffer with ending null terminator (string)
  for(i=0; i<length; i++) {
    message_buff[i] = payload[i];
  }
  message_buff[i] = '\0';
  
  String msgString = String(message_buff);
  
  Serial.println("Payload: " + msgString);
  int state = digitalRead(2);  // get the current state of GPIO1 pin

  Serial.println(message_to_print);

///here is where we will parse the json message that's being received to update all variables once a message is received
  
  JsonObject& root = jsonBuffer.parseObject(msgString);

  const char* sensor1 = root["DeviceID"];
  const char* sensor2 = root["newgrow_flag"];
  const char* sensor3 = root["Moisture"];
  const char* sensor4 = root["temp"];


  const char* sensor = root["temp"];

 // Serial.println(root);
  
  Serial.println("test"); 
  Serial.println(sensor1);
  Serial.println(sensor2);
  Serial.println(sensor3);
  Serial.println(sensor4);
        
  
  if (msgString == "1"){    // if there is a "1" published to any topic (#) on the broker then:
   message_to_print = 78;
   Serial.println(message_to_print);  

    
    digitalWrite(2, !state);     // set pin to the opposite state 
    Serial.println("Switching LED"); 
  }
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
  Serial.begin(115200);
  delay(10);
  
    // prepare GPIO2 *********************************************************************
  pinMode(2, OUTPUT);   // i am using gpio2 as output to toggle an LED
  digitalWrite(2, 0);  //*****************************************************************
  
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
  if (client.connect("arduinoClient")) {
    client.publish("outTopic","hello world");
    client.subscribe(topic);   // i should probably just point this to the varialbe above.
  }
}
 
void loop() {

  client.loop();
  Serial.println(message_to_print);
  delay(10000);
  
}


