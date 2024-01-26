<?php

namespace app\traits;

trait Validations
{
  private $errors = [];

  protected function required($field)
  {
    if(!isset($_POST[$field]) || empty($_POST[$field])) {
      $this->errors[$field][] = flash($field, error('Esse campo é obrigatório.'));
    }
  }

  protected function max($field, $size)
  {
    if(isset($_POST[$field]) && strlen($_POST[$field]) > $size) {
      $this->errors[$field][] = flash($field, error('O tamanho máximo desse campo é '.$size.' letras.'));
    }
  }

  protected function image($field)
  {
    $file = $_FILES[$field]['name'];
    if(empty($file)) {
      $this->errors[$field][] = flash($field, error('Imagem não enviada.'));
    }
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    if(!in_array($extension, [ 'png', 'jpg', 'jpeg', 'gif' ])) {
      $this->errors[$field][] = flash($field, error('Somente são aceitas imagens dos tipos JPEG, PNG e GIF.'));
    }
  }

  protected function email($field)
  {
    if(!filter_var($_POST[$field], FILTER_VALIDATE_EMAIL)) {
      $this->errors[$field][] = flash($field, error('Esse campo tem que ter um email válido.'));
    }
  }

  protected function phone($field)
  {
    if(!preg_match('/[0-9]{5}\-[0-9]{4}/', $_POST[$field])) {
      $this->errors[$field][] = flash($field, error('Por favor tente utilizar o formato xxxxx-xxxx.'));
    }
  }

  protected function unique($field, $model)
  {
    if(!isset($_POST[$field]) || empty($_POST[$field]))
    {
      return;
    }
    $model = 'app\\models\\'.str_replace('.', '\\', $model);
    $model = new $model();

    if($model->select()->where($field, $_POST[$field])->first()) {
      $this->errors[$field][] = flash($field, error('Este valor já está cadastrado no banco de dados.'));
    }
  }

  public function hasErrors()
  {
    return !empty($this->errors);
  }
}
