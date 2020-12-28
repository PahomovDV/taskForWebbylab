<?php
namespace App\Core;

class Response
{
  /**
   * @var array
   */
  public $headers;

  /**
   * @var string
   */
  private $content;

  /**
   * @var string
   */
  private $version;

  /**
   * @var int
   */
  private $statusCode;

  /**
   * @var string
   */
  private $statusText;

  /**
   * @var string
   */
  private $charset;

  /**
   * @var string
   */
  private $file = null;

  /**
   * @var array
   */
  private $csv = null;

  /**
   * Holds HTTP response statuses
   *
   * @var array
   */
  private $statusTexts = [
    200 => 'OK',
    302 => 'Found',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not Found',
    500 => 'Internal Server Error'
  ];

  /**
   * Constructor.
   *
   * @param string $content The response content
   * @param int    $status  The response status code
   * @param array  $headers An array of response headers
   *
   */
  public function __construct($content = '', $status = 200, $headers = array()){

    $this->content = $content;
    $this->statusCode = $status;
    $this->headers = $headers;
    $this->statusText = $this->statusTexts[$status];
    $this->version = '1.0';
    $this->charset = 'UTF-8';
  }

  /**
   * Sends HTTP headers and content.
   *
   */
  public function send(){

    $this->sendHeaders();
    $this->sendContent();
    return $this;
  }

  /**
   * Sends HTTP headers.
   *
   * @return Response
   */
  private function sendHeaders(){

    // check headers have already been sent by the developer
    if (headers_sent()) {
      return $this;
    }

    // status
    header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText), true, $this->statusCode);

    //if Content-Type is already exists in headers, then don't send it
    if(!array_key_exists('Content-Type', $this->headers)){
      header('Content-Type: ' . 'text/html; charset=' . $this->charset);
    }

    // headers
    foreach ($this->headers as $name => $value) {
      header($name .': '. $value, true, $this->statusCode);
    }

    return $this;
  }

  /**
   * Sends content for the current web response.
   *
   * @return Response
   */
  private function sendContent(){
    echo $this->content;
    return $this;
  }

  /**
   * Sets content for the current web response.
   *
   * @param string $content The response content
   * @return Response
   */
  public function setContent($content = ""){
    $this->content = $content;
    return $this;
  }


  /**
   * Sets the response status code & it's relevant text.
   *
   * @param int $code HTTP status code
   * @return Response
   */
  public function setStatusCode($code){

    $this->statusCode = (int) $code;
    $this->statusText = isset($this->statusTexts[$code]) ? $this->statusTexts[$code] : '';

    return $this;
  }


  /**
   * Sets content for the current web response.
   *
   * @param string|null $contentType The response content
   * @return Response
   */
  public function type($contentType = null){

    if($contentType === null){
      unset($this->headers['Content-Type']);
    }else{
      $this->headers['Content-Type'] = $contentType;
    }

    return $this;
  }

  /**
   * Clean (erase) the output buffer
   *
   * @return void
   */
  public function clearBuffer(){
    if(ob_get_level() > 0){
      return ob_clean();
    }
  }
}