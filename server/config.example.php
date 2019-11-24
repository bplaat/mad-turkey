<?php

define('DATABASE_DSN', 'mysql:host=127.0.0.1;dbname=mad-turkey');
define('DATABASE_USER', 'mad-turkey');
define('DATABASE_PASSWORD', ''); // Your database password

define('SESSION_COOKIE_NAME', 'mad-turkey-session');
define('SESSION_DURATION', 60 * 60 * 24 * 356);

define('OPEN_WEATHER_API_KEY', ''); // Your open weather api key

define('OUTSIDE_MEASUREMENT_INTERVAL', 10 * 60);
define('MEASUREMENT_INTERVAL', 60);

define('USER_ROLE_VIEWER', 0);
define('USER_ROLE_MODERATOR', 1);
define('USER_ROLE_ADMIN', 2);

define('EVENT_TYPE_LED', 0);
define('EVENT_TYPE_BEEPER', 1);
