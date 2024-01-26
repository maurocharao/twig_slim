<?php

namespace app\models\admin;

use app\models\Model;

class Admin extends Model
{
  protected $table = 'admin';

	public function user()
  {
		if(!isset($_SESSION['id_admin'])) {
			return [];
		}
		$id = $_SESSION['id_admin'];

		return $this->select()->where('id', $id)->first();
	}
}
