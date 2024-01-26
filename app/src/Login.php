<?php

namespace app\src;

use app\models\Model;
use app\helpers\Cfg;

class Login
{
  protected $type;
  protected $config;

  public function __construct(string $type)
  {
    $this->type = $type;
    $this->config = Cfg::LOGIN[$this->type];
  }

  public function login($data, Model $model)
  {
    $user = $model->select()->where('email', $data['email'])->first();

    if(!$user || !Password::verify($data['password'], $user->password))
    {
      return false;
    }
    $_SESSION[$this->config['loggedIn']] = true;
    $_SESSION[$this->config['idLoggedIn']] = $user->id;

    return true;
  }

	public function logout($response)
  {
		session_destroy();

		return redirect($response, $this->config['redirect']);
	}
}
