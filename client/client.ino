#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <DHT.h>
#include "config.h"
#include <ArduinoJson.h>

#define MEASUREMENT_INTERVAL 60 * 1000

#define DHT_TYPE DHT11
#define DHT_PIN D0 //  D1 on NodeMCU
#define LED_PIN D2  // D7 on NodeMCU
#define BUZZER_PIN D3 // D3 on NodeMCU
#define LDR_PIN A0

DHT dht(DHT_PIN, DHT_TYPE);

const size_t capacity = 256;
DynamicJsonDocument doc(capacity);
String json;
int duration_led;
int duration_buzzer;
int freq_buzzer;

void send_measurement() {
    float temperature = dht.readTemperature();
    float humidity = dht.readHumidity();
    float light = map(analogRead(LDR_PIN), 0, 1023, 0, 100);

    HTTPClient http;
    http.begin(mad_turkey_api_url + "?key=" + mad_turkey_api_key + "&temperature=" + String(temperature) + "&humidity=" + String(humidity) + "&light=" + String(light), mad_turkey_api_https_fingerprint);
    int httpCode = http.GET();
    if (httpCode == HTTP_CODE_OK) {
        json = http.getString();    //json object
        Serial.print("HTTP request response: ");
        Serial.println(http.getString());
    } else {
        Serial.print("HTTP request failed, error: ");
        Serial.println(http.errorToString(httpCode));
    }
    http.end();
}

void retrieve_event() {
  DeserializationError error = deserializeJson(doc, json);

  // Test if parsing succeeds.
  if (error) {
    Serial.print(F("deserializeJson() failed: "));
    Serial.println(error.c_str());
    return;
  }

  const char* message = doc["message"];
  Serial.println(message);
  
  JsonArray events = doc["events"];

  for (uint8_t i = 0; i < events.size(); i++) {
    if (events[i]["type"] == 0) {
      duration_led = events[i]["duration"];
    }

    if (events[i]["type"] == 1) {
      duration_buzzer = events[i]["duration"];
      freq_buzzer = events[i]["frequency"];
    }
  }

  if (duration_led > 0) {
    digitalWrite(LED_PIN, HIGH);
    uint32_t start_time = millis();

    if (millis() - start_time > duration_led) {
      start_time = millis();
      digitalWrite(LED_PIN, LOW);
    }

    if (duration_buzzer > 0 && freq_buzzer > 0) {
      tone(BUZZER_PIN, freq_buzzer, duration_buzzer);
    }

  }
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
    retrieve_event();
}

uint32_t send_time = millis();
void loop() {
    if (millis() - send_time > MEASUREMENT_INTERVAL) {
        send_time = millis();
        send_measurement();
        retrieve_event();
    }
}
