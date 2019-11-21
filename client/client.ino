#include <ESP8266WiFi.h>
#include "DHT.h"

#define DHTTYPE DHT11
#define DHTPin 5

const char *ssid = "Tesla IoT";
const char *password = "SERVER PASSWORD";

DHT dht(DHTPin, DHTTYPE);

void setup()
{
    Serial.begin(9600);
    Serial.println("I'm here");
    dht.begin();
    // Serial.println();
    // Serial.print("Connecting to ");
    // Serial.println(ssid);

    //     //Start the wifi
    //     WiFi.begin(ssid, password);

    //     while (WiFi.status() != WL_CONNECTED)
    //     {
    //         delay(500);
    //         Serial.print(".");
    //     }
    //     Serial.println("");
    //     Serial.println("WiFi connected");
    // }

    void loop()
    {
        float h = dht.readHumidity();
        float t = dht.readTemperature();
        Serial.println("Humidity: " + String(h));
        Serial.println("Temperature: " + String(t));
        Serial.println(analogRead(A0));
        delay(1000);
    }