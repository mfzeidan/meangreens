

#include <OneWire.h>
#include <DallasTemperature.h>
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <ArduinoJson.h>



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
int ONE_WIRE_BUS= 9;

String pin = "";


//nodeMCU pins we can use - 5,4,2, 14,12,13,15,3,1,10,9

// Data wire is plugged into pin 5 on the Arduino
#define ONE_WIRE_BUS 5



//#define wifi_ssid "9a8fc8" //fill this
//#define wifi_password "278993750" //fill this

#define wifi_ssid "Verizon-SCH-I545-4A6B" //fill this
#define wifi_password "qama598#" //fill this
#define mqtt_server "104.236.210.175" //fill this
#define mqtt_user "" // no fill necessary
#define mqtt_password "" // no fill necessary
//#define topic1 "temp/temp1"
#define topic1 "temp"
#define mqtt_client_name "d123" //this needs to be unique for each unit
WiFiClient espClient;
PubSubClient client(espClient);





// Setup a oneWire instance to communicate with any OneWire devices
OneWire oneWire(ONE_WIRE_BUS);

// Pass our oneWire reference to Dallas Temperature. 
DallasTemperature sensors(&oneWire);

const char* temperature = "";
const char* humidity = "";
const char* sensor3 = "";
const char* sensor4 = "";


int sensorID = 001;
int environment = 1;
String table_direction = "temp_info";


// Assign the unique addresses of your 1-Wire temp sensors.
// See the tutorial on how to obtain these addresses:
// http://www.hacktronics.com/Tutorials/arduino-1-wire-address-finder.html

DeviceAddress insideThermometer = { 0x28, 0x82, 0xA5, 0xDD, 0x06, 0x00, 0x00, 0x36 };
DeviceAddress outsideThermometer = { 0x28, 0x91, 0x37, 0xDD, 0x06, 0x00, 0x00, 0x00 };
//DeviceAddress dogHouseThermometer = { 0x28, 0x59, 0xBE, 0xDF, 0x02, 0x00, 0x00, 0x9F };

//this allows the esp to parse the json
StaticJsonBuffer<200> jsonBuffer;


void setup_wifi() {
    delay(10);
    // We start by connecting to a WiFi network
    Serial.println();
//making sure the connected light has been turned off
    digitalWrite(light2_wifi_connected_indicator,LOW);
    Serial.print("Connecting to ");
    Serial.println(wifi_ssid);
    WiFi.begin(wifi_ssid, wifi_password);
    while (WiFi.status() != WL_CONNECTED) {
      //turn on the RED wifi light showing that wifi is not connected
     digitalWrite(light2_wifi_not_connected_indicator, HIGH);
      delay(500);
      Serial.print(".");

    }
    Serial.println("");
    Serial.println("WiFi connected");
    
  //turn off the RED wifi 
  //turn on the GREEN wifi light

    digitalWrite(light2_wifi_not_connected_indicator, LOW);

    //after we turn off the red light lets blink the green light and then leave it high
    light_on_off(light2_wifi_connected_indicator);
    delay(10);
    digitalWrite(light2_wifi_connected_indicator, HIGH);    
    Serial.println("IP address: ");
    Serial.println(WiFi.localIP());
}

void reconnect() {
    // Loop until we're reconnected
    while (!client.connected()) {

      digitalWrite(light4_mqtt_on_light,LOW); // green
      digitalWrite(light4_mqtt_off_light,HIGH); // red


        Serial.print("Attempting MQTT connection...");
        // Attempt to connect
        if (client.connect(mqtt_client_name)) { //* See //NOTE below
            Serial.println("connected");
            //once we're connected back to the server lets turn the greenlight on and the redlight off
              digitalWrite(light4_mqtt_off_light,LOW);
              light_on_off(light4_mqtt_on_light);
              digitalWrite(light4_mqtt_off_light,HIGH);

        } else {
          //else we leave the red light on and make sure the green light is off
            digitalWrite(light4_mqtt_off_light,HIGH);
            digitalWrite(light4_mqtt_on_light,LOW);
            Serial.print("failed, rc=");
            Serial.print(client.state());
            Serial.println(" try again in 5 seconds");
            // Wait 5 seconds before retrying
            delay(5000);
        }
    }
}



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


void pubMQTT(String topic,String topic_val){
    Serial.print("Newest topic " + topic + " value:");
    Serial.println(String(topic_val).c_str());
    client.publish(topic.c_str(), String(topic_val).c_str(), true);
    //turn on a green light to signify that the message has been sent
    light_on_off(light4_mqtt_on_light);
}




void setup(void)
{

  setup_wifi();
  client.setServer(mqtt_server, 1883);
  // start serial port
  
  Serial.begin(9600);
  pinMode(A0, INPUT);

    pinMode(light1_on_indicator, OUTPUT);
    pinMode(light1_off_indicator, OUTPUT);
    pinMode(light2_wifi_connected_indicator, OUTPUT);
    pinMode(light2_wifi_not_connected_indicator, OUTPUT);
    pinMode(light3_growing_progress_on_light, OUTPUT);
    pinMode(light3_growing_progress_off_light,OUTPUT);
    pinMode(temp_relay_switch, OUTPUT);
    pinMode(light4_mqtt_on_light, OUTPUT);
  
  // Start up the library
  sensors.begin();
  // set the resolution to 10 bit (good enough?)
  sensors.setResolution(insideThermometer, 10);
  sensors.setResolution(outsideThermometer, 10);
  //sensors.setResolution(dogHouseThermometer, 10);

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

//////////////////////////////////////////////////
    
  String message_to_send2 = String("{\"Environment\":") +  
    String(environment) + 
    ", \"InTemp\":" + inside_tempF +
    ", \"Soil\":" + moisture +
    ", \"OutTemp\":" + outside_tempF +
    ", \"ID\":" + sensorID +
    ", \"Hum\":" + String(0) + ",\"TD\":" + "\"" + table_direction +
    "\"" + "}";   
//////////////////////////////////////
                                                             

  Serial.println(message_to_send2);
  delay(10);
  //pubMQTT(topic1, String(message_to_send1));
  //pubMQTT(topic1, String("hi"));
  pubMQTT(topic1, message_to_send2);
 
  delay(50000);
  
}


