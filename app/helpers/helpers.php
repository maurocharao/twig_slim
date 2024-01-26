<?php

use app\src\Flash;
use Slim\Psr7\Response;

function dd($data)
{
  header('Content-Type: text/plain');
  print_r($data);
  exit;
}

function path()
{
  $vendorDir = dirname(dirname(__FILE__));
  return dirname($vendorDir);
}

function flash($index, $message)
{
  Flash::add($index, $message);
}

function error($message)
{
  return '<span class="alert alert-danger p-1">* '.$message.'</span>';
}

function success($message)
{
  return '<span class="alert alert-success p-1">'.$message.'</span>';
}

function back($response)
{
  if(isset($_SERVER['HTTP_REFERER'])) {
    $previous = $_SERVER['HTTP_REFERER'];
  }
  else {
    $previous = 'javascript:history.go(-1)';
  }
  return redirect($response, $previous);
}

function redirect($response, $target)
{
  global $app;

  if(!$response) {
    $response = $app->getResponseFactory()->createResponse();
  }
  return $response->withHeader('Location', $target)
   ->withStatus(302);
}

function search()
{
  $search = filter_input(INPUT_GET, 'search', FILTER_UNSAFE_RAW, FILTER_FLAG_NO_ENCODE_QUOTES);
  if(!$search) {
    return '';
  }
  return trim($search);
}
