//  This Arduino sketch reads DS18B20 "1-Wire" digital
//  temperature sensors.
//  Copyright (c) 2010 Mark McComb, hacktronics LLC
//  License: http://www.opensource.org/licenses/mit-license.php (Go crazy)
//  Tutorial:
//  http://www.hacktronics.com/Tutorials/arduino-1-wire-tutorial.html

#include <OneWire.h>
#include <DallasTemperature.h>

// Data wire is plugged into pin 3 on the Arduino
#define ONE_WIRE_BUS 2

// Setup a oneWire instance to communicate with any OneWire devices
OneWire oneWire(ONE_WIRE_BUS);

// Pass our oneWire reference to Dallas Temperature. 
DallasTemperature sensors(&oneWire);

// Assign the unique addresses of your 1-Wire temp sensors.
// See the tutorial on how to obtain these addresses:
// http://www.hacktronics.com/Tutorials/arduino-1-wire-address-finder.html

DeviceAddress insideThermometer = { 0x28, 0x82, 0xA5, 0xDD, 0x06, 0x00, 0x00, 0x36 };
DeviceAddress outsideThermometer = { 0x28, 0x91, 0x37, 0xDD, 0x06, 0x00, 0x00, 0x00 };
//DeviceAddress dogHouseThermometer = { 0x28, 0x59, 0xBE, 0xDF, 0x02, 0x00, 0x00, 0x9F };

void setup(void)
{
  // start serial port
  Serial.begin(9600);
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
  delay(2000);
  Serial.print("Getting temperatures...\n\r");
  sensors.requestTemperatures();
  
  Serial.print("Inside temperature is: ");
  printTemperature(insideThermometer);
  Serial.print("\n\r");
  Serial.print("Outside temperature is: ");
  printTemperature(outsideThermometer);
  Serial.print("\n\r");
  //Serial.print("Dog House temperature is: ");
  //printTemperature(dogHouseThermometer);
  Serial.print("\n\r\n\r");
}
