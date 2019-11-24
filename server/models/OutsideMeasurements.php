<?php

class OutsideMeasurements extends Model {
    protected static $table = 'outside_measurements';

    public static function selectNewest ($station_id) {
        return Database::query('SELECT * FROM `outside_measurements` WHERE `station_id` = ? ORDER BY `time` DESC LIMIT 1', $station_id);
    }

    public static function selectByDay ($station_id, $day) {
        return Database::query('SELECT * FROM `outside_measurements` WHERE `station_id` = ? AND `time` >= ? AND `time` < ? ORDER BY `time`', $station_id, date('Y-m-d H:i:s', $day), date('Y-m-d H:i:s', $day + 24 * 60 * 60));
    }
}
