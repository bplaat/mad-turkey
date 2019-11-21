<?php

class StationsController {
    public static function index () {
        $stations = Stations::select()->fetchAll();
        echo view('stations.index', [ 'stations' => $stations ]);
    }

    public static function create () {
        echo view('stations.create');
    }

    public static function store () {
        Stations::insert([ 'name' => $_POST['name'], 'key' => md5(microtime() . $_SERVER['REMOTE_ADDR']), 'lat' => $_POST['lat'], 'lng' => $_POST['lng'] ]);
        Router::redirect('/stations/' . $station_id);
    }

    public static function show ($station) {
        echo view('stations.show', [ 'station' => $station ]);
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
