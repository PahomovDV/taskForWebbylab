<?php
namespace App\Core;

class Model extends DB
{
  public $table;
  
  public $fill;

  public function bindValue($param, $value) {
    $type = self::getPDOType($value);
    $this->statement->bindValue($param, $value, $type);
  }

  public function getAll()
  {
    $query = 'SELECT * FROM '. $this->table;
    $this->statement = $this->connection->prepare($query);
    $this->execute();
    $result = $this->fetchAllAssociative();
    return $result;
  }

  public function getById($id)
  {
    $query = 'SELECT * FROM '. $this->table . ' WHERE id=' . $id . '  LIMIT 1';
    $this->statement = $this->connection->prepare($query);
    $this->bindValue(':id', $id);
    $this->execute();
    $result = $this->fetchAssociative();
    return $result;
  }

  public function deleteAll()
  {
    $query ='DELETE FROM '. $this->table;
    $this->statement = $this->connection->prepare($query);
    return $this->execute();
  }

  public function deleteById($id)
  {
    $query ='DELETE FROM '. $this->table . ' WHERE id=' . $id;
    $this->statement = $this->connection->prepare($query);
    $this->bindValue(':id', $id);
    return $this->execute();
  }

  public function countAll(){
    $query = 'SELECT COUNT(*) AS count FROM '. $this->table;
    try{
      $this->statement = $this->connection->prepare($query);
      $this->execute();
      $result = $this->fetchAssociative();
      if(!empty($result['count'])){
        return $result['count'];
      } else {
        return 0;
      }
    } catch (\Exception $exception){
      return false;
    }
  }

  public function create(array $arr){
    $filter_arr = array_filter($arr, function ($value, $key){
      return in_array($key, $this->fill);
    }, ARRAY_FILTER_USE_BOTH );
    if(!empty($filter_arr)){
      $columns = implode(', ' , array_keys($filter_arr));
      $values = "'" .  implode("', '" , array_values($filter_arr)) . "'";
      $query    = "INSERT INTO " . $this->table . " ({$columns}) VALUES ({$values})";
      $this->statement = $this->connection->prepare($query);
      return $this->execute();
    } else {
      return false;
    }
  }
}