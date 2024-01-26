<?php

namespace app\models;

use app\models\Connection;
use app\traits\Read;

class Model
{
  use Read;

  protected $connect;
  protected $field;
  protected $value;
  protected $sql;

  public function __construct()
  {
    $this->connect = Connection::connect();
  }

  public function find($field, $value)
  {
    $this->field = $field;
    $this->value = $value;

    return $this;
  }

  public function create($attributes)
  {
    $keys = array_keys($attributes);

    $create = $this->connect->prepare(
      'INSERT INTO '.$this->table.'('.implode(',', $keys).')'
     .'VALUES(:'.implode(', :', $keys).')'
    );
    $create->execute($attributes);

    return $this->connect->lastInsertId();
  }

  public function update($attributes)
  {
    if(!isset($this->field) || !isset($this->value)) {
      throw new \Exception('Chamar o find antes do update');
    }
    $sql = 'UPDATE '.$this->table."\n"
     .'SET ';

    $keys = array_keys($attributes);
    $numKeys = count($keys);

    for($k = 0; $k < $numKeys; $k++) {
      $field = $keys[$k];
      $sql .= ($k > 0 ? ', ' : '').$field.' = :'.$field;
    }
    $sql .= "\n"
     .'WHERE '.$this->field.' = :'.$this->field;

    $attributes['id'] = $this->value;

    $update = $this->connect->prepare($sql);
    $update->execute($attributes);

    return $update->rowCount();
  }

  public function delete()
  {
    if(!isset($this->field) || !isset($this->value)) {
      throw new \Exception('Chamar o find antes do delete');
    }
    $delete = $this->connect->prepare(
      'DELETE FROM '.$this->table."\n"
     .'WHERE '.$this->field.' = :'.$this->field
    );
    $delete->bindValue($this->field, $this->value);
    $delete->execute();

    return $delete->rowCount();
  }
}
