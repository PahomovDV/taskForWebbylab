<?php
namespace App\Core;

use App\Controllers\ErrorsController;

class Controller {

  /**
   * view
   *
   * @var View
   */
  protected $view;

  /**
   * request
   *
   * @var Request
   */
  public $request;

  /**
   * response
   *
   * @var Response
   */
  public $response;


  /**
   * Constructor
   *
   * @param Request  $request
   * @param Response $response
   */
  public function __construct(Request $request = null, Response $response = null){

    $this->request      =  $request  !== null ? $request  : new Request();
    $this->response     =  $response !== null ? $response : new Response();
    $this->view         =  new View($this);
  }

  /**
   * show error page
   *
   * @param $code
   * @return Response
   */
  public function error($code){
    $errors = [
      404 => "notfound",
      500 => "internal"
    ];
    $action = isset($errors[$code])? $errors[$code]: "Internal";
    $this->response->setStatusCode($code);

    $this->response->clearBuffer();
    (new ErrorsController($this->request, $this->response))->{$action}();

    return $this->response;
  }
}