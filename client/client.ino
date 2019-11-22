#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <DHT.h>

#define DHTTYPE DHT11
#define DHTPin 5
#define SEND_INTERVAL 1 * 1000

String apiKey = "0ccd5cbd10ae4dc8d9102dd5db322235";
String ssid = "famMC";                    //"Tesla IoT";
String password = "72923136904048253746"; // "fsL6HgjN";

DHT dht(DHTPin, DHTTYPE);

void setup()
{
    Serial.begin(9600);
    Serial.println("I'm here");
    dht.begin();
    Serial.println();
    Serial.print("Connecting to ");
    Serial.println(ssid);

    //Start the wifi
    WiFi.begin(ssid, password);

    while (WiFi.status() != WL_CONNECTED)
    {
        delay(500);
        Serial.print(".");
    }
    Serial.println("");
    Serial.println("WiFi connected");
}

long previousTime = millis();
void loop()
{
    // Long delays are not allowed
    if (millis() > previousTime + SEND_INTERVAL)
    {
        sendData();
        previousTime = millis();
    }
}

void sendData()
{
    int lightStrength = map(analogRead(A0), 0, 1023, 0, 100);
    float humidity = dht.readHumidity();
    float temperature = dht.readTemperature();

    HTTPClient http;
    String urlToAPI = "https://bad-turkeys/aaaaa?apiKey=" + apiKey + "&temperature=" + String(temperature) + "&humidity=" + String(humidity) + "&light=" + String(lightStrength);
    //String urlToAPI = "http://arduino.esp8266.com/stable/package_esp8266com_index.json";
    Serial.println(urlToAPI);

    http.begin(urlToAPI);
    int httpCode = http.GET();         //Send the request
    String payload = http.getString(); //Get the response payload

    Serial.println(httpCode); //Print HTTP return code
    Serial.println(payload);  //Print request response payload

    http.end(); //Close connection
}