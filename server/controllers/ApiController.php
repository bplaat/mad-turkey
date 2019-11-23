<?php

class ApiController {
    public static function runTrigger ($trigger, $absolute_time, $time, $temperature, $humidity, $light, $outside_temperature, $outside_humidity) {
        return eval('return ' . $trigger . ';');
    }

    public static function sendMeasurementGet () {
        static::sendMeasurement($_GET['key'], $_GET['temperature'], $_GET['humidity'], $_GET['light']);
    }

    public static function sendMeasurementPost () {
        static::sendMeasurement($_POST['key'], $_POST['temperature'], $_POST['humidity'], $_POST['light']);
    }

    public static function sendMeasurement ($key, $temperature, $humidity, $light) {
        $station_query = Stations::select([ 'key' => $key ]);
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        if ($station_query->rowCount() == 1) {
            $station = $station_query->fetch();
            Measurements::insert([
                'station_id' => $station->id,
                'time' => date('Y-m-d H:i:s'),
                'temperature' => $temperature,
                'humidity' => $humidity,
                'light' => $light
            ]);

            $send_events = [];

            $events = Events::select([ 'station_id' => $station->id ])->fetchAll();
            foreach ($events as $event) {
                $newest_outside_measurements_query = OutsideMeasurements::selectNewest($station->id);
                if ($newest_outside_measurements_query->rowCount() == 1) {
                    $newest_outside_measurements = $newest_outside_measurements_query->fetch();
                    if (static::runTrigger($event->trigger, time(), time() - strtotime('today'), $temperature, $humidity, $light, $newest_outside_measurements->temperature, $newest_outside_measurements->humidity)) {
                        Events::update($event->id, [ 'active' => true ]);

                        if ($event->type == EVENT_TYPE_LED) {
                            $send_events[] = [ 'type' => EVENT_TYPE_LED, 'duration' => $event->duration ];
                        }

                        if ($event->type == EVENT_TYPE_BEEPER) {
                            $send_events[] = [ 'type' => EVENT_TYPE_BEEPER, 'frequency' => $event->frequency, 'duration' => $event->duration ];
                        }
                    } else {
                        Events::update($event->id, [ 'active' => false ]);
                    }
                }
            }

            echo json_encode([ 'message' => 'successful', 'events' => $send_events ]);
            return;
        }
        http_response_code(401);
        echo json_encode([ 'message' => 'bad key' ]);
    }
}
