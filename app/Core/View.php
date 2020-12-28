<?php


namespace App\Core;

class View {

  /**
   * controller object that instantiated view object
   *
   * @var object
   */
  public $controller;

  /**
   * Constructor
   *
   * @param Controller $controller
   */
  public function __construct(Controller $controller){

    $this->controller = $controller;

  }

  /**
   * Renders and returns output for the given file with its array of data.
   *
   * @param  string  $filePath
   * @param  array   $data
   * @return string  Rendered output
   *
   */
  public function render($filePath, $data = null){

    if(!empty($data)) {
      extract($data);
    }

    //using ob_start() & ob_get_clean() is a handy way, especially for ajax response.
    ob_start();
    include $filePath . "" ;
    $renderedFile = ob_get_clean();

    $this->controller->response->setContent($renderedFile);
    return $renderedFile;
  }

  /**
   * Renders and returns output with header and footer for the given file with its array of data.
   *
   * @param  string  $layoutDir
   * @param  string  $filePath
   * @param  array   $data
   * @return string  Rendered output
   */
  public function renderWithLayouts($layoutDir, $filePath, $data = null){

    if(!empty($data)) {
      extract($data);
    }

    //using ob_start() & ob_get_clean() is a handy way, especially for ajax response.
    ob_start();
    if(file_exists($layoutDir . "header.php")){
      require_once $layoutDir . "header.php";
    }

    require_once $filePath  . "" ;
    if(file_exists($layoutDir . "scripts.php")){
      require_once $layoutDir . "scripts.php";
    }
    if(file_exists($layoutDir . "footer.php")){
      require_once $layoutDir . "footer.php";
    }

    $renderedFile = ob_get_clean();

    $this->controller->response->setContent($renderedFile);
    return $renderedFile;
  }


  /**
   * Render a JSON view.
   *
   * @param  array   $data
   * @return string  Rendered output
   *
   */
  public function renderJson($data){

    $jsonData = $this->jsonEncode($data);
    $this->controller->response->type('application/json')->setContent($jsonData);
    return $jsonData;
  }

  /**
   * Serialize array to JSON and used for the response
   *
   * @param  array   $data
   * @return string  Rendered output
   *
   */
  public function jsonEncode($data){
    return json_encode($data);
  }

}