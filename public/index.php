<?php
define('ROOT', '../' . __DIR__ . '/');
include_once '../vendor/autoload.php';
require __DIR__ . '/../src/Middleware/App.php';
use \App\Middleware\App;

$app = new App();
$app->run();
