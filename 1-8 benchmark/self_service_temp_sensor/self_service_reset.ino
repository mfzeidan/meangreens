#include <FS.h>                   //this needs to be first, or it all crashes and burns...

#include <ESP8266WiFi.h>          //https://github.com/esp8266/Arduino

//needed for library
#include <DNSServer.h>
#include <ESP8266WebServer.h>
#include <WiFiManager.h>          //https://github.com/tzapu/WiFiManager
#include <PubSubClient.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <Ticker.h>

#define mqtt_server "104.236.210.175" //fill this
#define mqtt_user "" // no fill necessary
#define mqtt_password "" // no fill necessary
//#define topic1 "temp/temp1"
#define topic1 "esp_temp_test3"
#define mqtt_client_name "d1234" //dont change
#define MAX_LOOP_TIME_MS     10000
Ticker sleepTicker;
int sleepTimeS = 10;

//set the temp wire input here
#define ONE_WIRE_BUS 4
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
WiFiClient espClient;
PubSubClient client(espClient);

int deviceID = 443;
int switch_pin = 3;
unsigned long startTime;
char current_temp[32];


void pubMQTT(String topic,String topic_val){
    Serial.print("Newest topic " + topic + " value:");
    Serial.println(String(topic_val).c_str());
    client.publish(topic.c_str(), String(topic_val).c_str(), true);
}

void sleepyTime() {
  const int elapsed = millis() - startTime;
  Serial.println(millis());
  Serial.println(startTime);
  Serial.printf("Sleeping. Loop took %d ms\n", elapsed);
  if (elapsed >= MAX_LOOP_TIME_MS) {
    ESP.deepSleep(sleepTimeS * 1000000);  
  }
  // It can take a while for the ESP to actually go to sleep.
  // When it wakes up we start again in setup().
  delay(5000);
}



void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200);
  Serial.println();
  //WiFiManager
  //Local intialization. Once its business is done, there is no need to keep it around
  WiFiManager wifiManager;
  //exit after config instead of connecting

  //reset settings - for testing
  ////
  pinMode(switch_pin, INPUT);
  Serial.println("SERIAL READ");
  Serial.println(digitalRead(switch_pin));
  digitalRead(switch_pin);
  //switch_pin = 0;
  
  if(switch_pin = 0){
    wifiManager.resetSettings();
      wifiManager.setBreakAfterConfig(true);
  
  //tries to connect to last known settings
  //if it does not connect it starts an access point with the specified name
  //here  "AutoConnectAP" with password "password"
  //and goes into a blocking loop awaiting configuration
  if (!wifiManager.autoConnect("AutoConnectAP", "password")) {
    Serial.println("failed to connect, we should reset as see if it connects");
    delay(3000);
    //ESP.reset();
    //  delay(5000);
  }
  //note that if this completes, the devie needs to reset. put in a longer deepsleep to give the user time to flip the switch back

    ESP.deepSleep(1000000,WAKE_RF_DEFAULT);

  }
  //if you get here you have connected to the WiFi
  Serial.println("connected...yeey :)");
  Serial.println("local ip");
  Serial.println(WiFi.localIP());
  client.setServer(mqtt_server, 1883);
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
            delay(5000);
        }
    }
}




void loop() {
      //reconnect if wifi drops
      startTime = millis();
      sleepTicker.once_ms(12000, &sleepyTime);
    if (!client.connected()) {
    reconnect();
  }

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
  delay(100);
  Serial.println("this shouldnt print");
}
