<?php
date_default_timezone_set("Asia/Manila");

define('DB_HOST', 'localhost');
define('DB_NAME', 'school_ms');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('APP_NAME', 'School Management System');
define('APP_URL', 'http://localhost/simpleproject');
define('BASE_PATH', dirname(__DIR__));

define('ROLES', [
    'admin'   => 'admin',
    'teacher' => 'teacher',
    'student' => 'student',
]);

session_start();