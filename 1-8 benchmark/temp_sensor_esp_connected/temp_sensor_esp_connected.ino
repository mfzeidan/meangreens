

//You shouldn't have to change anything in this script.
//this does everything - wifi, backend and temperature readings as well as publishes a message

#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <OneWire.h>
#include <DallasTemperature.h>
int deviceID = 443;
 
#define wifi_ssid "XPX7B"
#define wifi_password "HCMLTGNWPFBLH9MB"

#define mqtt_server "104.236.210.175" //fill this
#define mqtt_user "" // no fill necessary
#define mqtt_password "" // no fill necessary
//#define topic1 "temp/temp1"
#define topic1 "esp_temp_test2"
#define mqtt_client_name "d123" //dont change

char current_temp[32];

//set the temp wire input here
#define ONE_WIRE_BUS 4
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
WiFiClient espClient;
PubSubClient client(espClient);


void setup_wifi() {
    delay(10);
    // We start by connecting to a WiFi network
    Serial.println();
    Serial.print("Connecting to ");
    Serial.println(wifi_ssid);
    WiFi.begin(wifi_ssid, wifi_password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(100);
        Serial.print(".");
    }
    Serial.println("");
    Serial.println("WiFi connected");
    Serial.println("IP address: ");
    Serial.println(WiFi.localIP());
    //start up the temperature sensor
      sensors.begin();
}

//incase wifi drops this will kick in to reconnect, otherwise it will wait 5 seconds
//most likely best to drop the delay down to reconnect but we can figure that out once we get there

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
            // Wait 5 seconds before retrying
            delay(5000);
        }
    }
}
//publishes messages out of the device to the backend
void pubMQTT(String topic,String topic_val){
    Serial.print("Newest topic " + topic + " value:");
    Serial.println(String(topic_val).c_str());
    client.publish(topic.c_str(), String(topic_val).c_str(), true);
}



 
void setup() {
   setup_wifi();
  client.setServer(mqtt_server, 1883);
  Serial.begin(9600); 
}

 
void loop() {
    //reconnect if wifi drops
    if (!client.connected()) {
    reconnect();
  }
   client.loop();
      ///temperature lookup here
   Serial.print(" Requesting temperatures...");
  sensors.requestTemperatures(); // Send the command to get temperatures
  Serial.println("DONE");

  Serial.print("Temperature for Device 1 is: ");
  Serial.println(sensors.getTempFByIndex(0)); // 
  float temp_float = sensors.getTempFByIndex(0);

  //need to convert temperature character type so that it works in pubMQTT

  String temperature = dtostrf(temp_float, 4, 0, current_temp);
  String stringOne = "Hello!       ";
  Serial.println(stringOne);

   stringOne.trim();
  Serial.print(stringOne);
  //Serial.println(temperature.trim());
  
  temperature.trim();
 String message_to_send1 = String("{\"devID\":") +  
    String(deviceID) + 
    ", \"current_temp\":" + temperature +
     + "}";  

    Serial.println(message_to_send1);
  
  Serial.println("simulate deepsleep here");
  Serial.println(deviceID);
  Serial.println(temperature);
  
  pubMQTT(topic1, message_to_send1);
  Serial.println("off to sleep i go");
  ESP.deepSleep(1000000,WAKE_RF_DEFAULT);
  delay(100);
  Serial.println("this shouldnt print");
}
