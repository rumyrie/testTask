<?php
define('ROOT', '../' . __DIR__ . '/');
require __DIR__ . '/../src/Middleware/App.php';
use \App\Middleware\App;

$app = new App();
$app->run();
