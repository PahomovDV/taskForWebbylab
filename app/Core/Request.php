<?php
namespace App\Core;

class Request
{
  /**
   * Set a list of trusted hosts patterns.
   *
   * @var array
   */
  private static $trustedHostPatterns = [];

  /**
   * Array of parameters parsed from the URL.
   *
   * @var array
   */
  public $params = [
    "controller" => null, "action"  => null, "args"  => null
  ];

  /**
   * Array of POST data as well as uploaded files.
   *
   * @var array
   */
  public $data = [];

  /**
   * Array of querystring arguments
   *
   * @var array
   */
  public $query = [];

  /**
   * The URL used to make the request.
   *
   * @var string
   */
  public $url = null;

  /**
   * Constructor
   * Create a new request from PHP superglobals.
   *
   * @param array $config user provided config
   */
  public function __construct($config = []){

    $this->data    = $this->mergeData($_POST, $_FILES);
    $this->query   = $_GET;
    $this->params += isset($config["params"])? $config["params"]: [];
    $this->url     = $this->fullUrl();
  }

  /**
   * merge post and files data
   * You shouldn't have two fields with the same 'name' attribute in $_POST & $_FILES
   *
   * @param  array $post
   * @param  array $files
   * @return array the merged array
   */
  private function mergeData(array $post, array $files){
    foreach($post as $key => $value) {
      if(is_string($value)) { $post[$key] = trim($value); }
    }
    return array_merge($files, $post);
  }

  /**
   * safer and better access to $this->data
   *
   * @param  string   $key
   * @return mixed
   */
  public function data($key){
    return array_key_exists($key, $this->data)? $this->data[$key]: null;
  }

  /**
   * safer and better access to $this->query
   *
   * @param  string   $key
   * @return mixed
   */
  public function query($key){
    return array_key_exists($key, $this->query)? $this->query[$key]: null;
  }

  /**
   * safer and better access to $this->params
   *
   * @param  string   $key
   * @return mixed
   */
  public function param($key){
    return array_key_exists($key, $this->params)? $this->params[$key]: null;
  }

  /**
   * detect if request is Ajax
   *
   * @return boolean
   */
  public function isAjax(){
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])){
      return strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    return false;
  }

  /**
   * detect if request is POST request
   *
   * @return boolean
   */
  public function isPost(){
    return $_SERVER["REQUEST_METHOD"] === "POST";
  }

  /**
   * detect if request is GET request
   *
   * @return boolean
   */
  public function isGet(){
    return $_SERVER["REQUEST_METHOD"] === "GET";
  }

  /**
   * detect if request over secured connection(SSL)
   *
   * @return boolean
   *
   */
  public function isSSL(){
    return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off";
  }

  /**
   * Add parameters to $this->params.
   *
   * @param  array $params
   * @return Request
   */
  public function addParams(array $params){
    $this->params = array_merge($this->params, $params);
    return $this;
  }

  /**
   * get content length
   *
   * @return integer
   */
  public function contentLength(){
    return (int)$_SERVER['CONTENT_LENGTH'];
  }

  /**
   * get the current uri of the request
   *
   * @return string|null
   */
  public function uri(){
    return isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI']: null;
  }

  /**
   * Get the name of the server host
   *
   * @return string|null
   */
  public function name(){
    return isset($_SERVER['SERVER_NAME'])? $_SERVER['SERVER_NAME']: null;
  }

  /**
   * Get the referer of this request.
   *
   * @return string|null
   */
  public function referer(){
    return isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']: null;
  }

  /**
   * get the client IP addresses.
   *
   * 'REMOTE_ADDR' is the most trusted one,
   * otherwise you can use HTTP_CLIENT_IP, or HTTP_X_FORWARDED_FOR.
   *
   * @return string|null
   */
  public function clientIp(){
    return isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR']: null;
  }

  /**
   * get the contents of the User Agent
   *
   * @return string|null
   */
  public function userAgent(){
    return isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT']: null;
  }

  /**
   * Gets the request's protocol.
   *
   * @return string
   */
  public function protocol(){
    return $this->isSSL() ? 'https' : 'http';
  }


  /**
   * Get the current host of the request
   *
   * @return string
   */
  public function host(){

    if (!$host = $_SERVER['HTTP_HOST']) {
      if (!$host = $this->name()) {
        $host = $_SERVER['SERVER_ADDR'];
      }
    }

    // trim and remove port number from host
    return strtolower(preg_replace('/:\d+$/', '', trim($host)));
  }

  /**
   * Gets the protocol and HTTP host.
   *
   * @return string The protocol and the host
   */
  public function getProtocolAndHost(){
    return $this->protocol() . '://' . $this->host();
  }

  /**
   * Get the full URL for the request with the added query string parameters.
   *
   * @return string
   */
  public function fullUrl(){

    // get uri
    $uri = $this->uri();
    if (strpos($uri, '?') !== false) {
      list($uri) = explode('?', $uri, 2);
    }

    // add querystring arguments(neglect 'url' & 'redirect')
    $query    = "";
    $queryArr = $this->query;
    unset($queryArr['url']);
    unset($queryArr['redirect']);

    if(!empty($queryArr)){
      $query .= '?' . http_build_query($queryArr, null, '&');
    }

    return  $this->getProtocolAndHost() . $uri . $query;
  }

  /**
   * Returns the base URL.
   *
   * @return string
   */
  public function getBaseUrl(){
    $baseUrl = str_replace(['public', '\\'], ['', '/'], dirname($_SERVER['SCRIPT_NAME']));
    return $baseUrl;
  }

  /**
   * Get the root URL for the application.
   *
   * @return string
   */
  public function root(){
    return $this->getProtocolAndHost() . $this->getBaseUrl();
  }
}