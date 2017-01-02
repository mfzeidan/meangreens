



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
//int light1_off_indicator =4;
////////////
int light2_wifi_connected_indicator =4;
int light2_wifi_not_connected_indicator =14;
////////////
int light3_growing_progress_on_light =12;
int light3_growing_progress_off_light =13;
/////////
int light4_mqtt_on_light = 12; // green
int light4_mqtt_off_light = 131; // red
///////////////


int temp_relay_switch = 13;
int current_temperature=77;
int desired_temperature=77;
// Data wire is plugged into pin 5 on the Arduino
/////////////////////////////////
int ONE_WIRE_BUS= 0;

//WIFI AND SERVER CREDs
const char* ssid = "XPX7B";
const char* password = "HCMLTGNWPFBLH9MB";
char* topic = "temp";     //  using wildcard to monitor all traffic from mqtt server
char* topic_outbound = "catcher";
char* server = "104.236.210.175";  // Address of my server on my network, substitute yours!
char message_buff[100];   // initialise storage buffer (i haven't tested to this capacity.)
int message_to_print = 176;
int current_temp = 74;
int desired_temp;

int DeviceID = 0001;  

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


//DeviceAddress insideThermometer = { 0x28, 0x82, 0xA5, 0xDD, 0x06, 0x00, 0x00, 0x36 };
//DeviceAddress outsideThermometer = { 0x28, 0x91, 0x37, 0xDD, 0x06, 0x00, 0x00, 0x00 };

StaticJsonBuffer<200> jsonBuffer;


void light_on_off(int pin){
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
      delay(300);
      digitalWrite(pin, LOW);
      delay(300);
      digitalWrite(pin, HIGH);

}

void printTemperature(DeviceAddress deviceAddress)
{
  float tempC = sensors.getTempC(deviceAddress);
  if (tempC == -127.00) {
    Serial.print("Error getting temperature");
  } else {
    Serial.print("C: ");
    Serial.print(tempC);
    Serial.print(" F: ");
    Serial.print(DallasTemperature::toFahrenheit(tempC));
  }
}

void callback(char* topic, byte* payload, unsigned int length) {
  // Here is what i have been using to handle subscriptions. I took it as a snippet from elsewhere but i cannot credit author as i dont have reference!
    int i = 0;
      //blink the green light signifying that a message has been received
    light_on_off(light4_mqtt_on_light);
    
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
  //Serial.println(message_to_print);

///here is where we will parse the json message that's being received to update all variables once a message is received
  JsonObject& root = jsonBuffer.parseObject(msgString);

  const char* desired_temperature = root["desired_temperature"];
  //const char* humidity = root["humidity"];
  //const char* light = root["light"];
  const char* progress_flag = root["progress_flag"];
 // Serial.println(root);
  //Serial.println("test");
  Serial.println(desired_temperature);
  //Serial.println(humidity);
  //Serial.println(light);
  Serial.println(progress_flag);

  //flag 1 signifies that there is currently a grow cycle in progress

  if(progress_flag == "1"){
    digitalWrite(light3_growing_progress_on_light,HIGH);
    digitalWrite(light3_growing_progress_off_light,LOW);
  }

  //flag 0 signifies that there is currently no grow cycle in progress
  if (progress_flag == "0"){
    digitalWrite(light3_growing_progress_on_light,LOW);
    digitalWrite(light3_growing_progress_off_light,HIGH);
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
    pinMode(light2_wifi_connected_indicator, OUTPUT);
    pinMode(light2_wifi_not_connected_indicator, OUTPUT);
    pinMode(light3_growing_progress_on_light, OUTPUT);
    pinMode(light3_growing_progress_off_light,OUTPUT);
    pinMode(temp_relay_switch, OUTPUT);
    pinMode(light4_mqtt_on_light, OUTPUT); // green
    pinMode(light4_mqtt_off_light, OUTPUT); // red
    pinMode(temp_relay_switch, OUTPUT);
//turning all the off indicators to high and turning all the on indicators to low

    digitalWrite(light1_on_indicator, HIGH);
    digitalWrite(light2_wifi_not_connected_indicator, HIGH);
    digitalWrite(light3_growing_progress_off_light,HIGH);
    digitalWrite(light4_mqtt_off_light,HIGH);
            /////////
    digitalWrite(light2_wifi_connected_indicator,LOW);
    digitalWrite(light3_growing_progress_on_light,LOW);
    digitalWrite(light4_mqtt_on_light,LOW);
    digitalWrite(temp_relay_switch, LOW);
            /////////

  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  StaticJsonBuffer<200> jsonBuffer;



  
  WiFi.begin(ssid, password);

  //while the wifi is not connected, make the light red, otherwise make the light green
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
     digitalWrite(light2_wifi_not_connected_indicator, LOW);
     light_on_off(light2_wifi_connected_indicator);
  Serial.println("");
  Serial.println("WiFi connected");  
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
 
  //  connection to broker script.
  if (client.connect("arduinoClient")) {
    light_on_off(light4_mqtt_on_light);
    //TODO change these
    //////////////////////
   Serial.print("Getting temperatures...\n\r");
  sensors.requestTemperatures();
    Serial.print("Temperature for Device 1 is: ");
  Serial.print(sensors.getTempFByIndex(0));




  //number 2 is indoo
  //int indoor = tempC;
  Serial.print("\n\r");
  //Serial.print("Dog House temperature is: ");
  //printTemperature(dogHouseThermometer);
  Serial.print("\n\r\n\r");

  int soil_level = analogRead(0);
  Serial.println(soil_level);

    String message_to_send2 = String("{\"DeviceID\":") +  
    String(DeviceID) + 
    //", \"Temp\":" + temp_avg +
    ", \"Soil_Moisture\":" + soil_level +
    "\"" + "}"; 

    //need to convert the string we build above into const char so that mqtt pub function will take in that value
    const char* pubmessage = message_to_send2.c_str();

//    client.publish(topic,message_to_send2);
///////////////
    //client.publish("topic_outbound","hello world");
        client.publish(topic_outbound,pubmessage);
    client.subscribe(topic);   //i should probably just point this to the varialbe above.
  }
}

  // put your main code here, to run repeatedly:
  void loop() {
  client.loop();
  //Serial.println(message_to_print);
  delay(1000);
     Serial.print("Getting temperatures...\n\r");
  sensors.requestTemperatures();
    Serial.print("Temperature for Device 1 is: ");
    int current_temp = sensors.getTempFByIndex(0);
  Serial.println(sensors.getTempFByIndex(0));
  
  ////////////
  Serial.println("current temps");
  Serial.println(current_temp);
  Serial.println("desired temp");
  Serial.println(desired_temperature);
  Serial.println("-----------------");


  if(current_temp > desired_temperature){
      Serial.println("relay high");
      digitalWrite(temp_relay_switch, HIGH);
  }
  else{
      Serial.println("relay low");
      digitalWrite(temp_relay_switch, LOW);
  }
  
}


//temperature readings CHECK
    //average the 2 temperature readings into one DONE
//relay control for light-
//    wait for v2
//relay control for temperature control pad -relay added
//code to read soil sensor
//code for the LEDS CHECK but need to edit input pins for the LEDS







