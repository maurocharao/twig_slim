<?php

namespace app\src;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use app\helpers\Cfg;

class AdminMiddleware
{
  public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    if(!isset($_SESSION[Cfg::LOGIN['admin']['loggedIn']])) {
      return redirect(null, Cfg::LOGIN['admin']['redirect']);
    }
    $response = $handler->handle($request);

    return $response;
  }
}
