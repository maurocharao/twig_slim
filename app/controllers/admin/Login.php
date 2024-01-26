<?php

namespace app\controllers\admin;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use app\src\Validate;
use app\models\admin\Admin;
use Slim\Views\Twig;

class Login
{
  private $login;

  public function __construct()
  {
    $this->login = new \app\src\Login('admin');
  }

  public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $view = Twig::fromRequest($request);

    return $view->render($response, 'admin/login.html', [
      'title' => 'Login do administrador'
    ]);
  }

  public function store(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $validate = new Validate;
    $data = $validate->validate([
     'email' => 'required:email',
     'password' => 'required'
    ]);
    if($validate->hasErrors()) {
      return back($response);
    }
    if($this->login->login($data, new Admin))
    {
			return redirect($response, '/admin/posts');
		}
    flash('message', error('E-mail ou senha inválidos.'));
    return back($response);
  }

	public function destroy(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
		return $this->login->logout($response);
	}
}
