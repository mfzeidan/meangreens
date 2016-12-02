// Connect to the WiFi


#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <OneWire.h>
#include <DallasTemperature.h>

#define ONE_WIRE_BUS 2
#define TRIGGER 5
#define ECHO 4

#define wifi_ssid "Verizon-MiFi6620L-E739" //fill this
#define wifi_password "015207b8" //fill this
#define mqtt_server "162.243.161.166" //fill this
#define mqtt_user "" // no fill necessary
#define mqtt_password "" // no fill necessary
#define topic1 "test"
#define mqtt_client_name "EAS1" //this needs to be unique for each unit
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
    


// temp Data wire is plugged into pin 2 on the Arduino


// Setup a oneWire instance to communicate with any OneWire devices 
// (not just Maxim/Dallas temperature ICs)
OneWire oneWire(ONE_WIRE_BUS);

// Pass our oneWire reference to Dallas Temperature.
DallasTemperature sensors(&oneWire);

//This is a unused function that just handles receiving mqtt messages

void callback(char* topic, byte* payload, unsigned int length) {
 Serial.print("Message arrived [");
 Serial.print(topic);
 Serial.print("] ");
 for (int i=0;i<length;i++) {
  char receivedChar = (char)payload[i];
  Serial.print(receivedChar);
  //if (receivedChar == '0')
  // ESP8266 Huzzah outputs are "reversed"
 // digitalWrite(ledPin, HIGH);
  //if (receivedChar == '1')
   //digitalWrite(ledPin, LOW);
  }
  Serial.println();
}


 

void setup() {
  // initialize digital pin LED_BUILTIN as an output.
  Serial.begin(9600);
  //this is to setup the distance sensor code
  pinMode(TRIGGER, OUTPUT);
  pinMode(ECHO, INPUT);
  

  // Start up the temp library
  sensors.begin();

}

// the loop function runs over and over again forever
void loop() {
    if (!client.connected()) {
    reconnect();
  }
//set up temp readings

  Serial.print(" Requesting temperatures...");
  sensors.requestTemperatures(); // Send the command to get temperatures
  Serial.println("DONE");

  Serial.print("Temperature for Device 1 is: ");
  Serial.print(sensors.getTempCByIndex(0)); // Why "byIndex"? 
  
  //setting up distance variables
  
  long duration, distance;
  digitalWrite(TRIGGER, LOW);  
  delayMicroseconds(2); 
  
  digitalWrite(TRIGGER, HIGH);
  delayMicroseconds(10); 
  
  digitalWrite(TRIGGER, LOW);
  duration = pulseIn(ECHO, HIGH);
  distance = (duration/2) / 29.1;
  
  Serial.print(distance);
  Serial.println("Centimeter:");
  delay(1000);
  
  pubMQTT(topic1, String(distance));

}
