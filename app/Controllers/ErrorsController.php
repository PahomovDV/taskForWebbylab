<?php


namespace App\Controllers;

use App\Core\Controller;

class ErrorsController extends Controller
{
  public function initialize(){
  }

  public function NotFound(){
    $this->view->renderWithLayouts(VIEWS_PATH . "layout/errors/", VIEWS_PATH . "errors/404.php");
  }

  public function Internal (){
    $this->view->renderWithLayouts(VIEWS_PATH . "layout/errors/", VIEWS_PATH . "errors/500.php");
  }
}