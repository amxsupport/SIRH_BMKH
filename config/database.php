<?php
defined('SIRH_BMKH') or die('Direct script access denied.');

error_log("Loading database configuration");

$db_config = [
    'host' => 'localhost',
    'dbname' => 'sirh_bmkh',
    'username' => 'root',
    'password' => ''
];

error_log("Database configuration loaded: " . json_encode($db_config));

return $db_config;
