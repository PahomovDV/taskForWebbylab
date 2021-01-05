<?php
namespace App\Core;

use App\Controllers\ErrorsController;
use App\Core\DB;

class App
{
  /**
   * controller
   * @var mixed
   */
  private $controller = null;

  /**
   * action method
   * @var string
   */
  private $method = "index";

  /**
   * passed arguments
   * @var array
   */
  private $args = array();

  /**
   * request
   * @var Request
   */
  public $request = null;

  /**
   * response
   * @var Response
   */
  public $response = null;

  /**
   * application constructor
   *
   * @access public
   */
  public function __construct(){

    // initialize request and respond objects
    $this->request  = new Request();
    $this->response = new Response();

  }

  public function run(){
    // split the requested URL
    $this->splitUrl();
    if(!self::isControllerValid($this->controller)){
      return $this->notFound();
    }
    if(!empty($this->controller)){

      $controllerName = APP_NAMESPACE . $this->controller;
      if(!self::isMethodValid($controllerName, $this->method)){
        return $this->notFound();
      }

      if(!empty($this->method)){
        return $this->invoke($controllerName, $this->method, $this->args);

      } else {

        if(!method_exists($controllerName, $this->method)){
          return $this->notFound();
        }
        return $this->invoke($controllerName, $this->method, $this->args);
      }
    } else {
      return $this->invoke(APP_NAMESPACE . 'FilmsController', 'index', $this->args);
    }
  }

  /**
   * instantiate controller object and trigger it's action method
   *
   * @param  string $controller
   * @param  string $method
   * @param  array  $args
   * @return Response
   */

  private function invoke($controller, $method = "index", $args = []){
    $this->request->addParams(['controller' => $controller, 'action' => $method, 'args' => $args]);
    $this->controller = new $controller($this->request, $this->response);

    if(!empty($args)) {
      $response = call_user_func_array([$this->controller, $method], $args);
    } else {
      $response = $this->controller->{$method}();
    }
    if ($response instanceof Response) {
      return $response->send();
    }

    return $this->response->send();
  }

  /**
   * detect if controller is valid
   *
   * any request to error controller will be considered as invalid,
   * because error pages will be rendered(even with ajax) from inside the application
   *
   * @param  string $controller
   * @return boolean
   */

  private static function isControllerValid($controller){
    if(!empty($controller)){
      if (!preg_match('/\A[a-z]+\z/i', $controller) ||
        !file_exists(APP_ROOT . 'Controllers/' . $controller . '.php'))
      {
        return false;
      } else {
        return true; }
    } else { return true; }
  }

  /**
   * detect if action method is valid
   *
   * make a request to 'index' method will be considered as invalid,
   * the constructor will take care of index methods.
   *
   * @param string $controller
   * @param string $method
   * @return boolean
   */
  private static function isMethodValid($controller, $method){

    if(!empty($method)){
      if (!preg_match('/\A[a-z]+\z/i', $method) ||
        !method_exists($controller, $method)){
        return false;
      }else {
        return true; }
    } else { return true; }

  }

  /**
   * Split the URL for the current request.
   *
   */

  public function splitUrl(){
    $url = $this->request->query("url");
    if (!empty($url)) {
      $url = explode('/', filter_var(trim($url, '/'), FILTER_SANITIZE_URL));
      $this->controller = !empty($url[0]) ? ucwords($url[0]) . 'Controller' : null;
      $this->method = !empty($url[1]) ? $url[1] : null;
      unset($url[0], $url[1]);
      $this->args = !empty($url)? array_values($url): [];
    }
  }

  /**
   * Shows not found error page
   *
   */

  private function notFound(){
    return (new ErrorsController())->error(404)->send();
  }

  private function internalServerError(){
    return (new ErrorsController())->error(500)->send();
  }

}