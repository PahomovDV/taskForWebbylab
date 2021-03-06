<?php


namespace App\Models;

use App\Core\Model;

class FilmsModel extends Model
{
  public $table = 'films';

  public $fill = [
    'title', 'release_year', 'format', 'stars'
  ];

  public function searchValue($search, $ordering, $start, $length){
    $query = "SELECT * FROM " . $this->table . " WHERE 1 AND (title LIKE '%".$search."%' OR stars LIKE '%".$search."%') ";
    $order =  "ORDER BY ". $ordering[0] ." ". $ordering[1] . " ";
    $limit = "LIMIT ". $start .",". $length;
    $this->statement = $this->connection->prepare($query . $order . $limit);
    $this->execute();
    $result = $this->fetchAllAssociative();
    return $result;
  }

  public function getAllFilms($ordering, $start = 0, $length = 10){
    $query = "SELECT * FROM " . $this->table . " ORDER BY " . $ordering[0] . " " . $ordering[1] . " LIMIT " . $start . "," . $length;
    $this->statement = $this->connection->prepare($query);
    $this->execute();
    $result = $this->fetchAllAssociative();
    return $result;
  }

  public function tableMigration(){
    $query = "CREATE TABLE IF NOT EXISTS `films` (
    `id` int(11) unsigned NOT NULL auto_increment,
    `title` varchar(100) NULL,
    `release_year` int NULL,
    `format` varchar(15) NULL,  
    `stars` text NULL,
    PRIMARY KEY  (`ID`))";
    $this->statement = $this->connection->prepare($query);
    $result = $this->execute();
    return $result;
  }

  public function searchFilmByTitle($title){
    $query = "SELECT * FROM " . $this->table . " WHERE title = '" . $title . "'";
    $this->statement = $this->connection->prepare($query);
    $this->bindValue(':title', $title);
    $this->execute();
    $result = $this->fetchAssociative();
    return $result;
  }
}