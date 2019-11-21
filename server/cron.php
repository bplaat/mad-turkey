<?php

define('ROOT', dirname(__FILE__));

spl_autoload_register(function ($class) {
    $file = ROOT . '/' . $class . '.php';
    if (file_exists($file)) require_once $file;
});
spl_autoload_register(function ($class) {
    $file = ROOT . '/models/' . $class . '.php';
    if (file_exists($file)) require_once $file;
});

require_once ROOT . '/config.php';

Database::connect(DATABASE_DSN, DATABASE_USER, DATABASE_PASSWORD);

$stations = Stations::select()->fetchAll();
foreach ($stations as $station) {
    $data = json_decode(file_get_contents('https://api.openweathermap.org/data/2.5/weather?appid=' . OPEN_WEATHER_API_KEY . '&lat=' . $station->lat . '&lon=' . $station->lng . '&units=metric'));
    OutsideMeasurements::insert([
        'station_id' => $station->id,
        'time' => date('Y-m-d H:i:s'),
        'temperature' => $data->main->temp,
        'humidity' => $data->main->humidity
    ]);
}
