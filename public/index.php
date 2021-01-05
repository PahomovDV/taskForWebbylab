<?php
require_once '../vendor/autoload.php';
$app = new App\Core\App();

if(!function_exists('apache_get_modules')){
  $request = $app->request;
  if(!empty($_SERVER["PATH_INFO"])){
    $request->query['url'] = $_SERVER["PATH_INFO"];
    $app->request = $request;
  }
}


define('PUBLIC_ROOT', $app->request->root());
define('PROJECT_ROOT', $app->request->getBaseUrl());

$app->run();