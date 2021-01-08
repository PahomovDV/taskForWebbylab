<?php


namespace App\Controllers;

use App\Core\Controller;
use App\Core\Document;
use App\Models\FilmsModel;

class FilmsController extends Controller
{
  public function index(){
    $this->view->renderWithLayouts(VIEWS_PATH . "layout/pages/", VIEWS_PATH . "pages/films.php");
  }

  public function addFilm(){
    $title = $this->request->query('title');
    $release_year = $this->request->query('release_year');
    $format = $this->request->query('format');
    $stars = $this->request->query('stars');
    if(!empty($title) && !empty($release_year) && !empty($format) && !empty($stars)){
      (new FilmsModel())->create(compact(['title', 'release_year', 'format', 'stars']));
      $this->view->renderJson(['Success!']);
    } else {
      $this->view->renderJson(['Error!']);
    }
  }

  public function getFilms(){
    $search = $this->request->query('search');
    $start = $this->request->query('start') ?? 0;
    $length = $this->request->query('length') ?? 10;
    $order = $this->request->query('order');
    $columns = $this->request->query('columns');
    if(!empty($order)){
      $ordering = [$columns[$order[0]['column']]['name'], $order[0]['dir']];
    } else {
      $ordering = ['title', 'desc'];
    }
    $iTotalRecords = (new FilmsModel())->countAll();
    if($iTotalRecords === false){
      (new FilmsModel())->tableMigration();
      $iTotalRecords = 0;
    }
    $draw = $this->request->query('draw') + 1;
    if(!empty($search['value'])){
      $data = (new FilmsModel())->searchValue($search['value'], $ordering, $start, $length);
      $iTotalDisplayRecords = count($data);
    }
    else {
      $data = (new FilmsModel())->getAllFilms($ordering, $start, $length);
      $iTotalDisplayRecords = $iTotalRecords;
    }
    $this->view->renderJson(compact(['data', 'draw', 'iTotalRecords', 'iTotalDisplayRecords']));
  }

  public function findFilm(){
    $title = $this->request->data('title');
    $data = (new FilmsModel())->searchFilmByTitle($title);
    if(!empty($data)){
      $this->view->renderJson(["true"]);
    } else {
      $this->view->renderJson(["false"]);
    }

  }

  public function deleteFilmById(){
    $id = $this->request->query('id');
    $result = (new FilmsModel())->deleteById($id);
    if($result === true){
      $this->view->renderJson(['Success!']);
    } else {
      $this->view->renderJson(['Error!']);
    }
  }

  public function deleteFilms(){
    (new FilmsModel())->deleteAll();
  }

  public function uploadFilms(){
    $upload_file  = $this->request->data("file");
    if(!empty($upload_file['name']) && !empty($upload_file['tmp_name'])){
      if (!file_exists(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0777, true);
      }
      $path = UPLOAD_PATH . $upload_file['name'];
      $data = file_get_contents($upload_file['tmp_name']);
      $check = file_put_contents($path, $data);
    }
    if(!empty($check) && !empty($path)){
      $document = new Document($path);
      $text = $document->convertToText();
      $pre_array = explode("\n", $text);
      $i = 0;
      foreach ($pre_array as $pre_value){
        if(!empty($pre_value)){
          $key = str_replace(' ', '_', stristr($pre_value, ':', true));
          $value = trim(substr(stristr($pre_value, ': '), 1));
          $array[strtolower($key)] = $value;
        }
        if(!empty($array['title']) && !empty($array['release_year']) && !empty($array['format']) && !empty($array['stars'])){
          (new FilmsModel())->create($array);
          $i++;
          unset($array);
        }
      }
      return $i;
    } else {
      return false;
    }
  }
}