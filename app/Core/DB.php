<?php
namespace App\Core;

use PDO;

class DB
{
  /**
   * @access public
   */
  public $connection = null;

  /**
   * @access public
   */
  public $statement = null;

  /**
   * This is the constructor for Database Object.
   *
   * @access private
   */

  public function __construct(){
    $settings = $this->settings();

    if ($this->connection === null) {
      $this->connection = new PDO($settings['dsn'], $settings['username'], $settings['password']);
      $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
  }

  private function settings()
  {
    include_once BASE_DIR . '/config/db.php';

    $result['dsn'] = DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $result['username'] = DB_USERNAME;
    $result['password'] = DB_PASSWORD;

    return $result;
  }

  public function prepare($query) {
    $this->statement = $this->connection->prepare($query);
  }

  public function execute($arr = null){
    if($arr === null)  {
      return $this->statement->execute();
    } else {
      return $this->statement->execute($arr);
    }
  }

  public function bindValue($param, $value) {
    $type = self::getPDOType($value);
    $this->statement->bindValue($param, $value, $type);
  }

  public static function getPDOType($value){
    switch ($value) {
      case is_int($value):
        return PDO::PARAM_INT;
      case is_bool($value):
        return PDO::PARAM_BOOL;
      case is_null($value):
        return PDO::PARAM_NULL;
      default:
        return PDO::PARAM_STR;
    }
  }

  public function fetchAllAssociative() {
    return $this->statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function fetchAssociative() {
    return $this->statement->fetch(PDO::FETCH_ASSOC);
  }

}