<?php

class StationsController {
    public static function index () {
        $stations = Stations::select()->fetchAll();
        $stations_info = [];
        foreach ($stations as $station) {
            $stations_info[] = [ 'id' => $station->id, 'name' => $station->name, 'point' => [ $station->lat, $station->lng ] ];
        }
        echo view('stations.index', [ 'stations' => $stations, 'stations_info' => $stations_info ]);
    }

    public static function create () {
        echo view('stations.create');
    }

    public static function store () {
        Stations::insert([ 'name' => $_POST['name'], 'key' => md5(microtime() . $_SERVER['REMOTE_ADDR']), 'lat' => $_POST['lat'], 'lng' => $_POST['lng'] ]);
        Router::redirect('/stations/' . Database::lastInsertId());
    }

    public static function show ($station) {
        $point = [ $station->lat, $station->lng ];
        echo view('stations.show', [ 'station' => $station, 'point' => $point ]);
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
