<?php

class StationsController {
    public static function index () {
        $stations = Stations::select()->fetchAll();
        $stations_info = [];
        foreach ($stations as $station) {
            $station_info = [
                'id' => $station->id,
                'name' => $station->name,
                'point' => [ $station->lat, $station->lng ]
            ];

            $last_meassurement_query = Database::query('SELECT * FROM `measurements` WHERE `station_id` = ? ORDER BY `time` DESC LIMIT 1', $station->id);
            if ($last_meassurement_query->rowCount() == 1) {
                $last_meassurement = $last_meassurement_query->fetch();
                $station_info['temperature'] = $last_meassurement->temperature;
                $station_info['humidity'] = $last_meassurement->humidity;
                $station_info['light'] = $last_meassurement->light;
            }
            $stations_info[] = $station_info;
        }
        echo view('stations.index', [ 'stations' => $stations, 'stations_info' => $stations_info ]);
    }

    public static function create () {
        echo view('stations.create');
    }

    public static function store () {
        Stations::insert([ 'name' => $_POST['name'], 'key' => md5(microtime(true) . $_SERVER['REMOTE_ADDR']), 'lat' => $_POST['lat'], 'lng' => $_POST['lng'] ]);
        Router::redirect('/stations/' . Database::lastInsertId());
    }

    public static function show ($station) {
        $day = strtotime(date('Y-m-d'));
        echo view('stations.show', [ 'station' => $station, 'day' => $day ]);
    }

    public static function showByDay ($station, $day) {
        $day = strtotime($day);
        echo view('stations.show', [ 'station' => $station, 'day' => $day ]);
    }

    public static function edit ($station) {
        echo view('stations.edit', [ 'station' => $station ]);
    }

    public static function update ($station) {
        Stations::update($station->id, [ 'name' => $_POST['name'], 'lat' => $_POST['lat'], 'lng' => $_POST['lng'] ]);
        Router::redirect('/stations/' . $station->id);
    }

    public static function delete ($station) {
        Stations::delete($station->id);
        Router::redirect('/stations');
    }
}
