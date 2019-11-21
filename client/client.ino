#include <ESP8266WiFi.h>
#include "DHT.h"

#define DHTTYPE DHT11
#define DHTPin 5

char celsiusTemp[7];
char fahrenheitTemp[7];
char humidityTemp[7];

DHT dht(DHTPin, DHTTYPE);

void setup()
{
    Serial.begin(9600);
    Serial.println("I'm here");
    dht.begin();
}

void loop()
{
    float h = dht.readHumidity();
    float t = dht.readTemperature();
    Serial.println("Humidity: " + String(h));
    Serial.println("Temperature: " + String(t));
    delay(1000);
}