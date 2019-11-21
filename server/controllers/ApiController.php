<?php

class ApiController {
    public static function sendMeasurement () {
        $station_query = Stations::select([ 'key' => $_POST['key'] ]);
        if ($station_query->rowCount() == 1) {
            $station = $station_query->fetch();
            Measurement::insert([
                'station_id' => $station->id,
                'time' => date('Y-m-d H:i:s'),
                'temperature' => $_POST['temperature'],
                'humidity' => $_POST['humidity'],
                'light' => $_POST['light']
            ]);
            echo json_encode([ 'message' => 'Succesful' ]);
        }
    }
}
