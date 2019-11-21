<?php

class ApiController {
    public static function sendMeasurementGet () {
        static::sendMeasurement($_GET['key'], $_GET['temperature'], $_GET['humidity'], $_GET['light']);
    }

    public static function sendMeasurementPost () {
        static::sendMeasurement($_POST['key'], $_POST['temperature'], $_POST['humidity'], $_POST['light']);
    }

    public static function sendMeasurement ($key, $temperature, $humidity, $light) {
        $station_query = Stations::select([ 'key' => $key ]);
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
            echo json_encode([ 'message' => 'successful' ]);
            return;
        }
        http_response_code(401);
        echo json_encode([ 'message' => 'bad key' ]);
    }
}
