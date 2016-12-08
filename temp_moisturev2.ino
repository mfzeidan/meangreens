// Include the libraries we need
#include <OneWire.h>
#include <DallasTemperature.h>
#include <ESP8266WiFi.h>
#include <PubSubClient.h>

#define wifi_ssid "9a8fc8" //fill this
#define wifi_password "278993750" //fill this
#define mqtt_server "104.236.210.175" //fill this
#define mqtt_user "" // no fill necessary
#define mqtt_password "" // no fill necessary
#define topic1 "testing1"
#define mqtt_client_name "EAS1" //this needs to be unique for each unit
WiFiClient espClient;
PubSubClient client(espClient);

// Data wire is plugged into port 2 on the Arduino
#define ONE_WIRE_BUS 5
int pinOut = 5;
String device_id = "001";
String start_datetime = "";
String current_datetime = "";
String newgrow_flag = "";
String plant_height = "0";
const int buttonPin = 2;     // the number of the pushbutton pin
int buttonState = 0;         // variable for reading the pushbutton status

String reset_message = """asda""";


// Setup a oneWire instance to communicate with any OneWire devices (not just Maxim/Dallas temperature ICs)
OneWire oneWire(ONE_WIRE_BUS);

// Pass our oneWire reference to Dallas Temperature. 
DallasTemperature sensors(&oneWire);

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
  // put your setup code here, to run once:
  Serial.begin(9600);
    pinMode(A0, INPUT);
    pinMode(buttonPin, INPUT);
    sensors.begin();

  //  Serial.begin(9600);
  //pinMode(10, OUTPUT);
  //pinMode(8,OUTPUT);
  
}

void loop() {
    // check if the pushbutton is pressed.
  // if it is, the buttonState is HIGH:
   buttonState = digitalRead(buttonPin);
  if (buttonState == HIGH) {
    // turn LED on:
    String newgrow_flag = "1";

    //CONSIDER JUST SENDING A COMPLETE RESET JSON MESSAGE THE RESET THE WHOLE PROCESS THAT INCLUDES THE DEVICEID
    //NEWFLAG = 1
    //SET TEMP, PLANT HEIGHT, SOILMOISTURE TO 0

    
  } else  {

    String newgrow_flag = "0";
  }
  if (!client.connected()) {
    reconnect();
  }
  client.loop();

 
  // put your main code here, to run repeatedly:
  int humidityRaw = analogRead(A0);
  int humidityReal = map(humidityRaw,1023,0,0,100);
  int waterpump = 11;
  
  Serial.println("Wetness");
  Serial.println(humidityReal);
  delay(500);
  // call sensors.requestTemperatures() to issue a global temperature 
  // request to all devices on the bus
  //Serial.print("Requesting temperatures...");
  sensors.requestTemperatures(); // Send the command to get temperatures
  //Serial.println("DONE");
  // After we got the temperatures, we can print them here.
  // We use the function ByIndex, and as an example get the temperature from the first sensor only.
  Serial.println("Temperature");
  int faren = sensors.getTempFByIndex(0);
  Serial.println(faren);
  delay(1000);  

  String message_to_send =  String("{\"DeviceID\":\"") +  String(device_id) + "\", \"Moisture\":\"" + String(humidityReal) + "\", \"Temperature\":\"" + String(faren) + "\"}";
  String test_message_to_send =  String("{\"DeviceID\":\"") +  String(device_id) + "\", \"soil_moisture\":\"" + String(humidityReal) +"\", \"start_datetime\":\""+ String(start_datetime)+"\", \"current_datetime\":\""+ String(current_datetime)+"\", \"newgrow_flag\":\""+ String(newgrow_flag)+"\", \"plant_height\":\""+ String(plant_height)+ "\", \"temperature\":\"" + String(faren) + "\"}";
  Serial.println(message_to_send);
  Serial.println(test_message_to_send);
  pubMQTT(topic1, String(message_to_send));
  delay(3000 );
}
