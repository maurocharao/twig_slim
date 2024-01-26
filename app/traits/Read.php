<?php

namespace app\traits;

use app\models\Paginate;

trait Read
{
  private $binds;
	private $paginate;

  public function select($fields = '*')
  {
    $this->sql = 'SELECT '.$fields.' FROM '.$this->table."\n";

    return $this;
  }

  public function all()
  {
    $all = $this->connect->query(
     'SELECT * FROM '.$this->table
    );
    $all->execute();

    return $all->fetchAll();
  }

  public function where()
  {
    $num_args = func_num_args();
    $args = func_get_args();

    if($num_args == 2) {
      $field = $args[0];
      $op = '=';
      $value = $args[1];
    }
    else if($num_args == 3) {
      $field = $args[0];
      $op = $args[1];
      $value = $args[2];
    }
    else {
      throw new \Exception('O where precisa de 2 ou 3 argumentos.');
    }
    $this->sql .= 'WHERE '.$field.' '.$op.' :'.$field."\n";

    $this->binds = [
      $field => $value
    ];

    return $this;
  }

	public function paginate($perPage)
  {
		$this->paginate = new Paginate;

		$this->paginate->records($this->count());

		$this->paginate->paginate($perPage);

		$this->sql .= $this->paginate->sqlPaginate();

		return $this;
	}

	public function links()
  {
		return $this->paginate->links();
	}

	public function orderBy($field, $order = '')
  {
    $this->sql .= 'ORDER BY '.$field.($order ? ' '.$order : '')."\n";

    return $this;
	}

	public function search($fields)
  {
    if(($search = search()) == '') {
      return $this;
    }
    if(!is_array($fields)) {
      $fields = explode(',', $fields);
    }
    if(($numFields = count($fields)) == 0) {
      return $this;
    }
    $this->binds['search'] = '%'.str_replace(' ', '%', $search).'%';

    $this->sql .= (strpos($this->sql, 'WHERE') !== false ? 'AND(' : 'WHERE(');

    for($f = 0; $f < $numFields; $f++) {
      $this->sql .= ($f > 0 ? ' OR ' : '').$fields[$f].' LIKE :search';
    }
    $this->sql .= ")\n";

    return $this;
	}

  public function get()
  {
    $select = $this->bindAndExecute();

    return $select->fetchAll();
  }

  public function first()
  {
    $select = $this->bindAndExecute();

    return $select->fetch();
  }

	public function count()
  {
		$select = $this->bindAndExecute();

		return $select->rowCount();
	}

  private function bindAndExecute()
  {
    $select = $this->connect->prepare($this->sql);
    $select->execute($this->binds);

    return $select;
  }
}
