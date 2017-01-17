#include <FS.h>                   //this needs to be first, or it all crashes and burns...

#include <ESP8266WiFi.h>          //https://github.com/esp8266/Arduino

//needed for library
#include <DNSServer.h>
#include <ESP8266WebServer.h>
#include <WiFiManager.h>          //https://github.com/tzapu/WiFiManager
#include <PubSubClient.h>
#include <OneWire.h>
#include <DallasTemperature.h>

#define mqtt_server "104.236.210.175" //fill this
#define mqtt_user "" // no fill necessary
#define mqtt_password "" // no fill necessary
//#define topic1 "temp/temp1"
//#define topic1 "esp_temp_test3"
#define topic1 "esp_temp_test_2test"
#define mqtt_client_name "d12345" //dont change
//set the temp wire input here
#define ONE_WIRE_BUS 12
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
WiFiClient espClient;
PubSubClient client(espClient);
int deviceID = 1002;
int red_pin = 4;
int green_pin = 5;
char current_temp[32];

void blink_lights(int light, int counter, int time_delay){
 int i;
 for(i = 0; i < counter; i++){
  digitalWrite(light, HIGH);   // turn the LED on (HIGH is the voltage level)
  delay(time_delay);                       // wait for a second
  digitalWrite(light, LOW);    // turn the LED off by making the voltage LOW
  delay(time_delay);
 }  
}


void pubMQTT(String topic,String topic_val){
    Serial.print("Newest topic " + topic + " value:");
    Serial.println(String(topic_val).c_str());
    client.publish(topic.c_str(), String(topic_val).c_str(), true);
    //green pin blink 3 times at a 150 ms delay
    blink_lights(green_pin, 3,150);
}

void reconnect() {
    // Loop until we're reconnected
    while (!client.connected()) {
        Serial.print("Attempting MQTT connection...");
        // Attempt to connect
        if (client.connect(mqtt_client_name)) { //* See //NOTE below
            Serial.println("connected");
        } else {
            Serial.print("failed, rc=");
            Serial.print(client.state());
            Serial.println(" try again in 5 seconds");
            blink_lights(red_pin, 4,200);
            // Wait 5 seconds before retrying
            delay(5000);
        }
    }
}



void setup() {
    Serial.begin(115200);
  // put your setup code here, to run once:
  pinMode(red_pin,OUTPUT);
  pinMode(green_pin,OUTPUT);
  digitalWrite(red_pin, HIGH);
  WiFiManager wifiManager;
  //exit after config instead of connecting
  
  //wifiManager.setBreakAfterConfig(true);
  
  //reset settings - for testing
  ////
  //  wifiManager.resetSettings();
  //tries to connect to last known settings
  //if it does not connect it starts an access point with the specified name
  //here  "AutoConnectAP" with password "password"
  //and goes into a blocking loop awaiting configuration
  if (!wifiManager.autoConnect("AutoConnectAP1234")) {
    Serial.println("failed to connect, we should reset as see if it connects");
    blink_lights(red_pin,5,200);
    digitalWrite(red_pin, HIGH);
    ESP.reset();
    delay(500);
  }

    wifiManager.autoConnect("AutoConnectAP1234");
  //if you get here you have connected to the WiFi
  Serial.println("connected...yeey :)");
  //blink_lights(green_pin,6,200);
  digitalWrite(red_pin, LOW);
  Serial.println("local ip");
  Serial.println(WiFi.localIP());
  client.setServer(mqtt_server, 1883);
}

void loop() {

      sensors.requestTemperatures(); // Send the command to get temperatures
      Serial.println("temp below");
      float temp_float = sensors.getTempFByIndex(0);

      
  if(temp_float < 20){
        //here we enter a delay so the device wont connect to the internet, it reads the temperature first
        Serial.println("temperature too low");
         delay(50000);
          }
 
  
  else{
      if (!client.connected()) {
        reconnect();
        }
    String temperature = dtostrf(temp_float, 4, 0, current_temp);
    temperature.trim();
  
    String message_to_send1 = String("{\"devID\":") +  
    String(deviceID) + 
    ", \"current_temp\":" + temperature +
     + "}";  

     Serial.println(message_to_send1);
    pubMQTT(topic1, message_to_send1);
   
    delay(100000);
  } 
}
