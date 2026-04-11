<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Add this to help identify permission issues
if (!is_writable(storage_path())) {
    die('Storage directory is not writable. Path: ' . storage_path());
}

define('LARAVEL_START', microtime(true));

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
