#include "DHT.h"

#include <ESP8266WiFi.h>
#include <PubSubClient.h>

 
#define DHTPIN 12     // what pin we're connected to
#define DHTTYPE DHT22   // DHT 22  (AM2302)
#define fan 2 

#define wifi_ssid ""
#define wifi_password ""
#define mqtt_server "104.236.210.175" //fill this
#define mqtt_user "" // no fill necessary
#define mqtt_password "" // no fill necessary
//#define topic1 "temp/temp1"
#define topic1 "humidity"
#define mqtt_client_name "d123" //this needs to be unique for each unit
WiFiClient espClient;
PubSubClient client(espClient);







 
int maxHum = 60;
int maxTemp = 40;
 
DHT dht(DHTPIN, DHTTYPE);


void setup_wifi() {
    delay(10);
    // We start by connecting to a WiFi network
    Serial.println();
    Serial.print("Connecting to ");
    Serial.println(wifi_ssid);
    WiFi.begin(wifi_ssid, wifi_password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("");
    Serial.println("WiFi connected");
    Serial.println("IP address: ");
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
            delay(5000);
        }
    }
}

void pubMQTT(String topic,String topic_val){
    Serial.print("Newest topic " + topic + " value:");
    Serial.println(String(topic_val).c_str());
    client.publish(topic.c_str(), String(topic_val).c_str(), true);
}
 
void setup() {

   setup_wifi();
  client.setServer(mqtt_server, 1883);
  pinMode(fan, OUTPUT);
  Serial.begin(9600); 
  dht.begin();
}
 
void loop() {

      if (!client.connected()) {
    reconnect();
  }

   client.loop();
   
  // Wait a few seconds between measurements.
  delay(2000);
 
  // Reading temperature or humidity takes about 250 milliseconds!  
  // Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
  float h = dht.readHumidity();
  // Read temperature as Celsius
  float t = dht.readTemperature();
  
  // Check if any reads failed and exit early (to try again).
  if (isnan(h) || isnan(t)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }

  
  Serial.print("Humidity: "); 
  Serial.print(h);
  Serial.print(" %\t");
  Serial.print("Temperature: "); 
  Serial.print(t);
  Serial.println(" *C ");

 String message_to_send2 = String("{\"humidity\":") +  
    String(h) +     + "}";  


  Serial.println(message_to_send2);

  //pubMQTT(topic1, String(message_to_send1));
  //pubMQTT(topic1, String("hi"));
  pubMQTT(topic1, message_to_send2);
    delay(100000);
 
}
