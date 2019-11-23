<?php

class EventsController {
    public static function testTrigger ($trigger) {
        ob_start();
        try {
            $absolute_time = 0;
            $time = 0;
            $temperature = 0;
            $humidity = 0;
            $light = 0;
            $outside_temperature = 0;
            $outside_humidity = 0;
            eval('return ' . $trigger . ';');
        } catch (ParseError $error) {
            return false;
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output == '';
    }

    public static function index () {
        $events = Events::select()->fetchAll();
        foreach ($events as $event) {
            $event->station = Stations::select($event->station_id)->fetch();
        }
        echo view('events.index', [ 'events' => $events ]);
    }

    public static function create () {
        $stations = Stations::select()->fetchAll();
        echo view('events.create', [ 'stations' => $stations ]);
    }

    public static function store () {
        if (static::testTrigger($_POST['trigger'])) {
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
        Router::back();
    }

    public static function show ($event) {
        $event->station = Stations::select($event->station_id)->fetch();
        echo view('events.show', [ 'event' => $event ]);
    }

    public static function edit ($event) {
        $stations = Stations::select()->fetchAll();
        echo view('events.edit', [ 'event' => $event, 'stations' => $stations ]);
    }

    public static function update ($event) {
        if (static::testTrigger($_POST['trigger'])) {
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
        Router::back();
    }

    public static function delete ($event) {
        Events::delete($event->id);
        Router::redirect('/events');
    }
}
