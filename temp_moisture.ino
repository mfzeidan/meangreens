// Include the libraries we need
#include <OneWire.h>
#include <DallasTemperature.h>

// Data wire is plugged into port 2 on the Arduino
#define ONE_WIRE_BUS 5
int pinOut = 5;
String device_id = "1";
// Setup a oneWire instance to communicate with any OneWire devices (not just Maxim/Dallas temperature ICs)
OneWire oneWire(ONE_WIRE_BUS);

// Pass our oneWire reference to Dallas Temperature. 
DallasTemperature sensors(&oneWire);

void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);
    pinMode(A0, INPUT);
    sensors.begin();

  //  Serial.begin(9600);
  //pinMode(10, OUTPUT);
  //pinMode(8,OUTPUT);
  
}

void loop() {
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

  String message_to_send = String("{") + String("\"DeviceInfo\"") + String(":[{\"DeviceID\":\"") +  String(device_id) + "\", \"Moisture\":\"" + String(humidityReal) + "\", \"temp\":\"" + String(faren) + "\"}]}";
  Serial.println(message_to_send);
  
}
