#define RELAY1  2    
 


void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);
   
}

void loop() {
  // put your main code here, to run repeatedly:
  Serial.println("relay LOW");
  digitalWrite(RELAY1,LOW);
  delay(10000);
  Serial.println("relay HIGH");
  digitalWrite(RELAY1,HIGH);
  delay(10000);
}
