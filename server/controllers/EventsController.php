<?php

class EventsController {
    public static function index () {
        $events = Events::select()->fetchAll();
        echo view('events.index', [ 'events' => $events ]);
    }

    public static function create () {
        $stations = Stations::select()->fetchAll();
        echo view('events.create', [ 'stations' => $stations ]);
    }

    public static function store () {
        Events::insert([
            'name' => $_POST['name'],
            'station_id' => $_POST['station_id'],
            'trigger' => $_POST['trigger'],
            'type' => $_POST['type'],
            'frequency' => $_POST['frequency'],
            'duration' => $_POST['duration'],
            'active' => false
        ]);
        Router::redirect('/events/' . Database::lastInsertId());
    }

    public static function show ($event) {
        echo view('events.show', [ 'event' => $event ]);
    }

    public static function edit ($event) {
        $stations = Stations::select()->fetchAll();
        echo view('events.edit', [ 'event' => $event, 'stations' => $stations ]);
    }

    public static function update ($event) {
        Events::update($event->id, [
            'name' => $_POST['name'],
            'station_id' => $_POST['station_id'],
            'trigger' => $_POST['trigger'],
            'type' => $_POST['type'],
            'frequency' => $_POST['frequency'],
            'duration' => $_POST['duration'],
            'active' => false
        ]);
        Router::redirect('/events/' . $event->id);
    }

    public static function delete ($event) {
        Events::delete($event->id);
        Router::redirect('/events');
    }
}
