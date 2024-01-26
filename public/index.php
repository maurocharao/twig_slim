<?php

use Slim\Routing\RouteCollectorProxy;
use app\src\AdminMiddleware;

require '../bootstrap.php';

$app->get('/', 'app\controllers\site\Posts:index');
$app->get('/post/{slug}', 'app\controllers\site\Posts:show');

$app->get('/login', 'app\controllers\admin\Login:index');
$app->post('/login', 'app\controllers\admin\Login:store');

$app->group('/admin', function(RouteCollectorProxy $group) {
  $group->get('/logout', 'app\controllers\admin\Login:destroy');

  $group->get('/posts', 'app\controllers\admin\Posts:index');
  $group->get('/post/create', 'app\controllers\admin\Posts:create');
  $group->get('/post/edit/{id}', 'app\controllers\admin\Posts:edit');
  $group->post('/post/save', 'app\controllers\admin\Posts:save');
  $group->post('/post/save/{id}', 'app\controllers\admin\Posts:save');
  $group->get('/post/delete/{id}', 'app\controllers\admin\Posts:delete');
  $group->post('/post/photo/upload/{id}', 'app\controllers\admin\Posts:upload');

  $group->get('/users', 'app\controllers\admin\Admin:index');
  $group->get('/user', 'app\controllers\admin\Admin:create');
  $group->get('/user/edit/{id}', 'app\controllers\admin\Admin:edit');
  $group->post('/user/save', 'app\controllers\admin\Admin:save');
  $group->post('/user/save/{id}', 'app\controllers\admin\Admin:save');
  $group->post('/user/photo/upload/{id}', 'app\controllers\admin\Admin:upload');
  $group->get('/user/delete/{id}', 'app\controllers\admin\Admin:delete');

})->add(new AdminMiddleware);

$app->get('/users', 'app\controllers\site\User:index');
$app->get('/user', 'app\controllers\site\User:create');
$app->get('/user/edit/{id}', 'app\controllers\site\User:edit');
$app->post('/user/save', 'app\controllers\site\User:save');
$app->post('/user/save/{id}', 'app\controllers\site\User:save');
$app->post('/user/photo/upload/{id}', 'app\controllers\site\User:upload');
$app->get('/user/delete/{id}', 'app\controllers\site\User:delete');

$app->get('/contato', 'app\controllers\site\Contato:index');
$app->post('/contato/store', 'app\controllers\site\Contato:store');

$app->run();
