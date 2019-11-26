#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <DHT.h>
#include "config.h"

#define MEASUREMENT_INTERVAL 60 * 1000

#define DHT_TYPE DHT11
#define DHT_PIN D5
DHT dht(DHT_PIN, DHT_TYPE);

#define LDR_PIN A0

void send_measurement() {
    float temperature = dht.readTemperature();
    float humidity = dht.readHumidity();
    float light = map(analogRead(LDR_PIN), 0, 1023, 0, 100);

    HTTPClient http;
    http.begin(mad_turkey_api_url + "?key=" + mad_turkey_api_key + "&temperature=" + String(temperature) + "&humidity=" + String(humidity) + "&light=" + String(light), mad_turkey_api_https_fingerprint);
    int httpCode = http.GET();
    if (httpCode == HTTP_CODE_OK) {
        Serial.print("HTTP request response: ");
        Serial.println(http.getString());
    } else {
        Serial.print("HTTP request failed, error: ");
        Serial.println(http.errorToString(httpCode));
    }
    http.end();
}

void setup() {
    Serial.begin(9600);
    dht.begin();
    Serial.print("\nConnecting to ");
    Serial.print(wifi_ssid);
    Serial.println("...");
    WiFi.begin(wifi_ssid, wifi_password);
    while (WiFi.status() != WL_CONNECTED) {
        Serial.print(".");
        delay(500);
    }
    Serial.println("\nConnected");
    send_measurement();
}

uint32_t send_time = millis();
void loop() {
    if (millis() - send_time > MEASUREMENT_INTERVAL) {
        send_time = millis();
        send_measurement();
    }
}
