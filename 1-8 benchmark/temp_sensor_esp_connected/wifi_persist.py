#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <Ticker.h>
#include <DHT.h>
#include <SPI.h>
#include <Wire.h>

 
#define WIFI_SSID           "Verizon-SCH-I545-4A6B"
#define WIFI_PASSWORD       "qama598#"

#define SERVER_IP           "10.1.20.200"
#define SERVER_PORT         9091

#define MAX_LOOP_TIME_MS     10000

Ticker sleepTicker;


unsigned long startTime;

void sleepyTime() {
  const int elapsed = millis() - startTime;
  Serial.printf("Sleeping. Loop took %d ms\n", elapsed);
  // If this sleep happened because of timeout, clear the
  // Wifi state.
  if (elapsed >= MAX_LOOP_TIME_MS) {
    WiFi.disconnect();
  }
  ESP.deepSleep(1000000, WAKE_RF_DEFAULT);
  // It can take a while for the ESP to actually go to sleep.
  // When it wakes up we start again in setup().
  delay(5000);
}

void waitForWifi() {
  Serial.print("Connecting to WiFi.");
  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(100);
  }
  Serial.println(" Done");
  Serial.println("ESP8266 Weather Sensor");
  Serial.printf("Connected to %s\n", WIFI_SSID);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}

void setup(void)
{
  startTime = millis();
  sleepTicker.once_ms(12000, &sleepyTime);
  Serial.begin(115200);
  Serial.println();

  Serial.println("Using saved SSID: " + WiFi.SSID());
  if (WiFi.SSID() != WIFI_SSID) {
    Serial.println("Configuring persistent wifi...");
    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
    WiFi.persistent(true);
    WiFi.setAutoConnect(true);
    WiFi.setAutoReconnect(true);
  } else {
    Serial.println("Using saved wifi info..."); 
  }
}

bool isValidHumidity(const float humidity) {
  return (!isnan(humidity) && humidity >= 0 && humidity <= 100);
}

bool isValidTemp(const float temp) {
  return (!isnan(temp) && temp >= -100 && temp <= 212);
}

float tempF(const float temp) {
  return 1.8F * temp + 32;
}



void loop(void)
{
  Serial.println("beginning");                         
  const int bmeTime = millis() - startTime;
  Serial.printf("BME read took %d ms\n", bmeTime);
  waitForWifi();
  const int wifiTime = millis() - (startTime + bmeTime);
  Serial.printf("WiFi init took an additional %d ms\n", wifiTime);
   Serial.println("end");   

  sleepyTime();
} 
