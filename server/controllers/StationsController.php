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
        Stations::insert([
            'name' => $_POST['name'],
            'key' => md5(microtime(true) . $_SERVER['REMOTE_ADDR']),
            'lat' => $_POST['lat'],
            'lng' => $_POST['lng']
        ]);
        Router::redirect('/stations/' . Database::lastInsertId());
    }

    public static function show ($station) {
        static::showByDay($station, date('Y-m-d'));
    }

    public static function showByDay ($station, $day) {
        $day = strtotime($day);

        $labels = [];
        $temperature_data = [];
        $humidity_data = [];
        $light_data = [];
        $meassurements = Database::query('SELECT * FROM `measurements` WHERE `station_id` = ? AND `time` >= ? AND `time` < ? ORDER BY `time`', $station->id, date('Y-m-d H:i:s', $day),  date('Y-m-d H:i:s', $day + 24 * 60 * 60))->fetchAll();
        foreach ($meassurements as $meassurement) {
            $labels[] = date('H:i', strtotime($meassurement->time));
            $temperature_data[] = $meassurement->temperature;
            $humidity_data[] = $meassurement->humidity;
            $light_data[] = $meassurement->light;
        }

        $outside_labels = [];
        $outside_temperature_data = [];
        $outside_humidity_data = [];
        $outside_meassurements = Database::query('SELECT * FROM `outside_measurements` WHERE `station_id` = ? AND `time` >= ? AND `time` < ? ORDER BY `time`', $station->id, date('Y-m-d H:i:s', $day),  date('Y-m-d H:i:s', $day + 24 * 60 * 60))->fetchAll();
        foreach ($outside_meassurements as $outside_meassurement) {
            $outside_labels[] = date('H:i', strtotime($outside_meassurement->time));
            $outside_temperature_data[] = $outside_meassurement->temperature;
            $outside_humidity_data[] = $outside_meassurement->humidity;
        }

        echo view('stations.show', [
            'station' => $station,
            'day' => $day,

            'labels' => $labels,
            'temperature_data' => $temperature_data,
            'humidity_data' => $humidity_data,
            'light_data' => $light_data,

            'outside_labels' => $outside_labels,
            'outside_temperature_data' => $outside_temperature_data,
            'outside_humidity_data' => $outside_humidity_data
        ]);
    }

    public static function edit ($station) {
        echo view('stations.edit', [ 'station' => $station ]);
    }

    public static function update ($station) {
        Stations::update($station->id, [
            'name' => $_POST['name'],
            'lat' => $_POST['lat'],
            'lng' => $_POST['lng']
        ]);
        Router::redirect('/stations/' . $station->id);
    }

    public static function delete ($station) {
        Events::delete([ 'station_id' => $station->id ]);
        Measurements::delete([ 'station_id' => $station->id ]);
        Stations::delete($station->id);
        Router::redirect('/stations');
    }
}
