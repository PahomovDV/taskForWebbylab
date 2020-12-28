<?php
require_once '../vendor/autoload.php';

$app = new App\Core\App();

define('PUBLIC_ROOT', $app->request->root());
define('PROJECT_ROOT', $app->request->getBaseUrl());

$app->run();