#include <OneWire.h>
#include <DallasTemperature.h>



// temp Data wire is plugged into pin 2 on the Arduino
#define ONE_WIRE_BUS 2

// Setup a oneWire instance to communicate with any OneWire devices 
// (not just Maxim/Dallas temperature ICs)
OneWire oneWire(ONE_WIRE_BUS);

// Pass our oneWire reference to Dallas Temperature.
DallasTemperature sensors(&oneWire);

//define distance variables

#define TRIGGER 5
#define ECHO 4
 

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
  
 

    
 
}
