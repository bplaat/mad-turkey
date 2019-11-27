#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <DHT.h>
#include <ArduinoJson.h>
#include "config.h"

#define EVENT_TYPE_LED 0
#define EVENT_TYPE_BEEPER 1

#define DHT_TYPE DHT11
#define DHT_PIN D1
DHT dht(DHT_PIN, DHT_TYPE);

#define LED_PIN D2
uint32_t led_time = millis();
uint32_t led_duration = 0;
#define BEEPER_PIN D3
#define LDR_PIN A0

#define MEASUREMENT_INTERVAL 60 * 1000
uint32_t send_time = millis();

DynamicJsonDocument json_document(256);

String send_measurement() {
    String json;
    float temperature = dht.readTemperature();
    float humidity = dht.readHumidity();
    float light = map(analogRead(LDR_PIN), 0, 1023, 0, 100);

    HTTPClient http;
    http.begin(mad_turkey_api_url + "?key=" + mad_turkey_api_key + "&temperature=" + String(temperature) + "&humidity=" + String(humidity) + "&light=" + String(light), mad_turkey_api_https_fingerprint);
    int httpCode = http.GET();
    if (httpCode == HTTP_CODE_OK) {
        json = http.getString();
        Serial.print("HTTP request response: ");
        Serial.println(http.getString());
    } else {
        Serial.print("HTTP request failed, error: ");
        Serial.println(http.errorToString(httpCode));
    }
    http.end();

    return json;
}

void retrieve_events(String json) {
    led_duration = 0;
    uint32_t beeper_duration = 0;
    uint32_t beeper_frequency = 0;

    DeserializationError error = deserializeJson(json_document, json);
    if (error) {
        Serial.print("deserializeJson() failed: ");
        Serial.println(error.c_str());
        return;
    }

    JsonArray events = json_document["events"];
    for (uint8_t i = 0; i < events.size(); i++) {
        JsonObject event = events[i];
        if (event["type"] == EVENT_TYPE_LED) {
            led_duration = event["duration"];
        }

        if (event["type"] == EVENT_TYPE_BEEPER) {
            beeper_duration = event["duration"];
            beeper_frequency = event["frequency"];
        }
    }

    if (led_duration > 0) {
        digitalWrite(LED_PIN, HIGH);
        led_time = millis();
    }

    if (beeper_duration > 0 && beeper_frequency > 0) {
        tone(BEEPER_PIN, beeper_frequency, beeper_duration);
    }
}

void setup() {
    Serial.begin(9600);
    dht.begin();
    pinMode(LED_PIN, OUTPUT);
    pinMode(BEEPER_PIN, OUTPUT);
    Serial.print("\nConnecting to ");
    Serial.print(wifi_ssid);
    Serial.println("...");
    WiFi.begin(wifi_ssid, wifi_password);
    while (WiFi.status() != WL_CONNECTED) {
        Serial.print(".");
        delay(500);
    }
    Serial.println("\nConnected");
    retrieve_events(send_measurement());
}

void loop() {
    if (millis() - send_time > MEASUREMENT_INTERVAL) {
        send_time = millis();
        retrieve_events(send_measurement());
    }

    if (millis() - led_time > led_duration) {
        led_time = millis();
        digitalWrite(LED_PIN, LOW);
    }
}
