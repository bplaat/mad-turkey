# Mad Turkey Weather Network
Mad Turkey is a simple PHP based weather service with NodeMCU weather station clients

## Users
You can register multiple users.

## Stations
You can connect multiple stations to the server.

## Events
You can script events in PHP with these variables:

```
$trigger = The event trigger PHP code
$absolute_time = Seconds that has passed since 1 January 1970 a.k.a unix epoch
$time = Seconds that has passed since the start of the day at 00:00:00
$temperature = Current measured temperature in degrees Celcius
$humidity = Current measured humidity in procent
$light = Current measured light in procent
$outside_temperature = Current outside measured temperature in procent
$outside_humidity = Current outside measured humidity in procent
```

## Clients
When you want to upload your code to the client you need to rename `config.example.h`
to `config.h` and fill in your client detials to make a connection with the server.
