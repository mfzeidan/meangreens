



/*
 * written by mz
 * the goal of this script is to accomplish the following tasks
 * 3 lights signifiying if device is:
 *    connected to internet
 *    connected to the server
 *    currently growing/in progress
 *    
 *additionally, simply change a heat pad on and off based on the current required temperature.
 *    if it needs to be higher, make it higher
 * 
 * connect to wifi
 * TODO let users connect to wifi themselves
 * 
 * 
 * subscribe to the server
 * subscribe to temperature readings
 * publish their current conditions to a new server to store their data, 
 * 
 * compare the results of the guiding temp to what actually happened (server side)
 * 
 * 
 */

 #include <PubSubClient.h>
#include <ESP8266WiFi.h>
#include <ArduinoJson.h>
#include <OneWire.h>
#include <DallasTemperature.h>

//nodeMCU pins we can use - 5,4,2, 14,12,13,15,3,1,10,9

int light1_on_indicator = 5;
int light1_off_indicator =4;
int light2_wifi_connected_indicator =2;
int light2_wifi_not_connected_indicator =14;
int light3_growing_progress_on_light =12;
int light3_growing_progress_off_light =13;
int light4_mqtt_on_light = 12; // green
int light4_mqtt_off_light = 131; // red
int temp_relay_switch = 15;
int current_temperature=77;
int desired_temperature=77;
// Data wire is plugged into pin 5 on the Arduino
/////////////////////////////////
int ONE_WIRE_BUS= 9;

//WIFI AND SERVER CREDs
const char* ssid = "Verizon-SCH-I545-4A6B";
const char* password = "qama598#";
char* topic = "temp";     //  using wildcard to monitor all traffic from mqtt server
char* server = "104.236.210.175";  // Address of my server on my network, substitute yours!
char message_buff[100];   // initialise storage buffer (i haven't tested to this capacity.)
int message_to_print = 76;
int current_temp = 74;
int desired_temp;

//this is for the json parsing

const char* temperature = "";
const char* humidity = "";
const char* sensor3 = "";
const char* sensor4 = "";

String pin = "";
// Setup a oneWire instance to communicate with any OneWire devices 
// (not just Maxim/Dallas temperature ICs)
OneWire oneWire(ONE_WIRE_BUS);
// Pass our oneWire reference to Dallas Temperature.
DallasTemperature sensors(&oneWire);
//this allows the esp to parse the json

WiFiClient wifiClient;


DeviceAddress insideThermometer = { 0x28, 0x82, 0xA5, 0xDD, 0x06, 0x00, 0x00, 0x36 };
DeviceAddress outsideThermometer = { 0x28, 0x91, 0x37, 0xDD, 0x06, 0x00, 0x00, 0x00 };

StaticJsonBuffer<200> jsonBuffer;




void light_on_off(int pin){
      digitalWrite(pin, LOW);
      delay(500);
       digitalWrite(pin, HIGH);
      delay(300);
      digitalWrite(pin, LOW);
      delay(300);
      digitalWrite(pin, HIGH);
      delay(300);
      digitalWrite(pin, LOW);
      delay(300);
      digitalWrite(pin, HIGH);
      delay(300);
      digitalWrite(pin, LOW);
      delay(300);
      digitalWrite(pin, HIGH);

}


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
  Serial.begin(9600);
  delay(10);
  

    pinMode(light1_on_indicator, OUTPUT);
    pinMode(light1_off_indicator, OUTPUT);
    pinMode(light2_wifi_connected_indicator, OUTPUT);
    pinMode(light2_wifi_not_connected_indicator, OUTPUT);
    pinMode(light3_growing_progress_on_light, OUTPUT);
    pinMode(light3_growing_progress_off_light,OUTPUT);
    pinMode(temp_relay_switch, OUTPUT);
    pinMode(light4_mqtt_on_light, OUTPUT); // green
    pinMode(light4_mqtt_off_light, OUTPUT); // red
    pinMode(temp_relay_switch, OUTPUT);
  
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  StaticJsonBuffer<200> jsonBuffer;

  
  WiFi.begin(ssid, password);

  //while the wifi is not connected, make the light red, otherwise make the light green
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
     digitalWrite(light2_wifi_not_connected_indicator, HIGH);
    Serial.print(".");
  }
     digitalWrite(light2_wifi_not_connected_indicator, LOW);
     digitalWrite(light2_wifi_connected_indicator, HIGH);
  Serial.println("");
  Serial.println("WiFi connected");  
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
 
  //  connection to broker script.
  if (client.connect("arduinoClient")) {
    //TODO change these
    //client.publish("outTopic","hello world");
    client.subscribe(topic);   // i should probably just point this to the varialbe above.
  }
}

  // put your main code here, to run repeatedly:
  void loop() {

  client.loop();
  Serial.println(message_to_print);
  delay(1000);
  
}


