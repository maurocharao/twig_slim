<?php

namespace app\controllers\site;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use app\src\Email;
use app\src\Validate;
use Slim\Views\Twig;

class Contato
{
  public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $view = Twig::fromRequest($request);

    return $view->render($response, 'site/contato.html', [
      'title' => 'Para dúvidas ou sugestões, utilize o formulário abaixo:',
      'nome' => 'Alexandre'
    ]);
  }

  public function store(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $validate = new Validate;

    $data = $validate->validate([
     'name' => 'required',
     'email' => 'required:email',
     'assunto' => 'required',
     'mensagem' => 'required'
    ]);

    if($validate->hasErrors()) {
      return back($response);
    }
    $email = new Email;

    $email->data([
     'toName' => 'Mauro Charão',
     'toEmail' => 'maurocharao@gmail.com',
     'fromName' => $data['name'],
     'fromEmail' => $data['email'],
     'assunto' => $data['assunto'],
     'mensagem' => $data['mensagem'],
    ])
    ->template(new \app\templates\Contato);

    return $email->send($response);
  }
}
