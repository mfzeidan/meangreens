//  This Arduino sketch reads DS18B20 "1-Wire" digital
//  temperature sensors.
//  Copyright (c) 2010 Mark McComb, hacktronics LLC
//  License: http://www.opensource.org/licenses/mit-license.php (Go crazy)
//  Tutorial:
//  http://www.hacktronics.com/Tutorials/arduino-1-wire-tutorial.html

#include <OneWire.h>
#include <DallasTemperature.h>
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include "DHT.h"



//COMMENT WHERE DHTGOES
#define DHTPIN 2     // what digital pin we're connected to

// Uncomment whatever type you're using!
#define DHTTYPE DHT11   // DHT 11

DHT dht(DHTPIN, DHTTYPE);

// Data wire is plugged into pin 5 on the Arduino
#define ONE_WIRE_BUS 5



//#define wifi_ssid "9a8fc8" //fill this
//#define wifi_password "278993750" //fill this

//#define wifi_ssid "Verizon-SCH-I545-4A6B" //fill this
//#define wifi_password "qama598#" //fill this

#define wifi_ssid "XPX7B"
#define wifi_password "HCMLTGNWPFBLH9MB"
#define mqtt_server "104.236.210.175" //fill this
#define mqtt_user "" // no fill necessary
#define mqtt_password "" // no fill necessary
//#define topic1 "temp/temp1"
#define topic1 "temperature/device1"
#define mqtt_client_name "d123" //this needs to be unique for each unit
WiFiClient espClient;
PubSubClient client(espClient);





// Setup a oneWire instance to communicate with any OneWire devices
OneWire oneWire(ONE_WIRE_BUS);

// Pass our oneWire reference to Dallas Temperature. 
DallasTemperature sensors(&oneWire);





int sensorID = 001;
int environment = 1;
String table_direction = "temp_info";


// Assign the unique addresses of your 1-Wire temp sensors.
// See the tutorial on how to obtain these addresses:
// http://www.hacktronics.com/Tutorials/arduino-1-wire-address-finder.html

DeviceAddress insideThermometer = { 0x28, 0x82, 0xA5, 0xDD, 0x06, 0x00, 0x00, 0x36 };
DeviceAddress outsideThermometer = { 0x28, 0x91, 0x37, 0xDD, 0x06, 0x00, 0x00, 0x00 };
//DeviceAddress dogHouseThermometer = { 0x28, 0x59, 0xBE, 0xDF, 0x02, 0x00, 0x00, 0x9F };




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










void setup(void)
{

  setup_wifi();
  client.setServer(mqtt_server, 1883);
  // start serial port
  
  Serial.begin(9600);
  pinMode(A0, INPUT);
  
  // Start up the library
  sensors.begin();
  // set the resolution to 10 bit (good enough?)
  sensors.setResolution(insideThermometer, 10);
  sensors.setResolution(outsideThermometer, 10);
  //sensors.setResolution(dogHouseThermometer, 10);
  dht.begin();
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

void loop(void)
{ 
    if (!client.connected()) {
    reconnect();
  }
  client.loop();
  
  delay(2000);
  Serial.print("Getting temperatures...\n\r");
  sensors.requestTemperatures();
  
  Serial.print("Inside  temperature is: ");
  printTemperature(insideThermometer);
  Serial.print("\n\r");
  Serial.print("Outside temperature is: ");
  float outside_tempC = sensors.getTempC(outsideThermometer);
  float outside_tempF = DallasTemperature::toFahrenheit(outside_tempC);
  float inside_tempC = sensors.getTempC(insideThermometer);
  float inside_tempF = DallasTemperature::toFahrenheit(inside_tempC);
  //number 2 is indoo
  //int indoor = tempC;
  Serial.print("\n\r");
  //Serial.print("Dog House temperature is: ");
  //printTemperature(dogHouseThermometer);
  Serial.print("\n\r\n\r");

  
  int humidityRaw = analogRead(A0);
  int moisture = map(humidityRaw,1023,0,0,100);

  Serial.println("Wetness");
  Serial.println(moisture);

////////////////////////////////////////////////////////////
  delay(2000);

  // Reading temperature or humidity takes about 250 milliseconds!
  // Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
  float h = dht.readHumidity();
  // Read temperature as Celsius (the default)
  float t = dht.readTemperature();
  // Read temperature as Fahrenheit (isFahrenheit = true)
  float f = dht.readTemperature(true);

  // Check if any reads failed and exit early (to try again).
 if (isnan(h) || isnan(t) || isnan(f)) {
   Serial.println("Failed to read from DHT sensor!");
    //return;
  }

    // Compute heat index in Fahrenheit (the default)
  float hif = dht.computeHeatIndex(f, h);
  // Compute heat index in Celsius (isFahreheit = false)
  float hic = dht.computeHeatIndex(t, h, false);

  Serial.print("Humidity: ");
  Serial.print(h);
  Serial.print(" %\t");
  Serial.print("Temperature: ");
  Serial.print(t);
  Serial.print(" *C ");
  Serial.print(f);
  Serial.print(" *F\t");
  Serial.print("Heat index: ");
  Serial.print(hic);
  Serial.print(" *C ");
  Serial.print(hif);
  Serial.println(" *F");

//////////////////////////////////////////////////


  String message_to_send1 = String("{\"Environment\":") +  
    String(environment) + 
    ", \"IndoorTemp\":" + inside_tempF +
    ", \"Soil_Moisture\":" + moisture +
    ", \"OutdoorTemp\":" + outside_tempF +
    ", \"Humidity\":" + String(0) +
    ",\"Table_Direction\":" + "\"" + table_direction +
    "\"" + "}";   

    
  String message_to_send2 = String("{\"Environment\":") +  
    String(environment) + 
    ", \"InTemp\":" + inside_tempF +
    ", \"Soil\":" + moisture +
    ", \"OutTemp\":" + outside_tempF +
    ", \"ID\":" + sensorID +
    ", \"Hum\":" + String(0) + ",\"TD\":" + "\"" + table_direction +
    "\"" + "}";   

                                                             

  Serial.println(message_to_send2);
  delay(10);
  //pubMQTT(topic1, String(message_to_send1));
  //pubMQTT(topic1, String("hi"));
  //pubMQTT(topic1, message_to_send2);
 
  delay(5000000);
  
}
