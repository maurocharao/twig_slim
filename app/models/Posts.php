<?php

namespace app\models;

class Posts extends Model
{
  protected $table = 'posts';

  public function posts() {
    $this->sql = "SELECT p.*, a.name\n"
     .'FROM '.$this->table." p\n"
     ."INNER JOIN admin a ON a.id = p.user\n";

    return $this;
  }
}
