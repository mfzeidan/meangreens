#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <Ticker.h>
#include <DHT.h>
#include <SPI.h>
#include <Wire.h>
#include <PubSubClient.h>
#include <OneWire.h>
#include <DallasTemperature.h>
int deviceID = 4411;
 
#define WIFI_SSID           "Verizon-SCH-I545-4A6B"
#define WIFI_PASSWORD       "qama598#"

#define MAX_LOOP_TIME_MS     10000
#define mqtt_server "104.236.210.175" //fill this
#define mqtt_user "" // no fill necessary
#define mqtt_password "" // no fill necessary
//#define topic1 "temp/temp1"
#define topic1 "esp_temp_test_esp"
#define mqtt_client_name "d123" //dont change

Ticker sleepTicker;
char current_temp[32];

//sleeptimes is a variable that converts the deep sleep to seconds. 
//setting this variable to 10 sets the deepsleep cycle to 10 seconds

int sleepTimeS = 100;

//set the temp wire input here
#define ONE_WIRE_BUS 4
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
WiFiClient espClient;
PubSubClient client(espClient);

unsigned long startTime;


//this sets the device to sleep regardless if a connection is made or not
void sleepyTime() {
  const int elapsed = millis() - startTime;
  Serial.println(millis());
  Serial.println(startTime);
  Serial.printf("Sleeping. Loop took %d ms\n", elapsed);
  // If this sleep happened because of timeout, clear the
  // Wifi state.
  if (elapsed >= MAX_LOOP_TIME_MS) {
    WiFi.disconnect();
  }
  ESP.deepSleep(sleepTimeS * 1000000); 
  
  // It can take a while for the ESP to actually go to sleep.
  // When it wakes up we start again in setup().
  delay(5000);
}

void waitForWifi() {
  Serial.print("Connecting to WiFi.");
  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(100);
  }
  Serial.println(" Done");
  Serial.println("ESP8266 Weather Sensor");
  Serial.printf("Connected to %s\n", WIFI_SSID);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
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
            // Wait 5 seconds before retrying
            delay(500);
        }
    }
}



void setup(void)
{
  startTime = millis();
  sleepTicker.once_ms(12000, &sleepyTime);
  Serial.begin(115200);
  Serial.println();

  Serial.println("Using saved SSID: " + WiFi.SSID());
  if (WiFi.SSID() != WIFI_SSID) {
    Serial.println("Configuring persistent wifi...");
    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
    WiFi.persistent(true);
    WiFi.setAutoConnect(true);
    WiFi.setAutoReconnect(true);
  } else {
    Serial.println("Using saved wifi info..."); 
  }
  client.setServer(mqtt_server, 1883);
}

bool isValidHumidity(const float humidity) {
  return (!isnan(humidity) && humidity >= 0 && humidity <= 100);
}

bool isValidTemp(const float temp) {
  return (!isnan(temp) && temp >= -100 && temp <= 212);
}

float tempF(const float temp) {
  return 1.8F * temp + 32;
}


void pubMQTT(String topic,String topic_val){
    Serial.print("Newest topic " + topic + " value:");
    Serial.println(String(topic_val).c_str());
    client.publish(topic.c_str(), String(topic_val).c_str(), true);
    //blink_lights(green_light,3);
}



void loop(void)
{
    if (!client.connected()) {
    reconnect();
  }
   client.loop();
  Serial.println("beginning");                         
  const int bmeTime = millis() - startTime;
  Serial.printf("BME read took %d ms\n", bmeTime);
  waitForWifi();
  const int wifiTime = millis() - (startTime + bmeTime);
  Serial.printf("WiFi init took an additional %d ms\n", wifiTime);
   Serial.println("end");


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
  delay(100);
  Serial.println("this shouldnt print");
  sleepyTime();
} 
