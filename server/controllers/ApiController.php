<?php

class ApiController {
    public static function sendMeasurement () {
        $station = Stations::select([ 'key' => $_POST['key'] ]);
        Measurement::insert([
            'station_id' => $station,
            'time' => date('Y-m-d H:i:s'),
            'temperature' => $_POST['temperature'],
            'humidity' => $_POST['humidity'],
            'light' => $_POST['light']
        ]);
        echo json_encode([ 'message' => 'Succesful' ]);
    }
}
