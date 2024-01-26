<?php

namespace app\helpers;

define('DIR_ROOT', dirname(__FILE__, 3));

final class Cfg {
  const EMAIL = [
   'encryption' => 'tls',
   'port' => 587,
   'host' => '',
   'username' => '',
   'password' => ''
  ];
  const LOGIN = [
   'admin' => [
    'loggedIn' => 'admin_login',
    'redirect' => '/login',
    'idLoggedIn' => 'id_admin'
   ],
   'user' => [
    'loggedIn' => 'user_login',
    'redirect' => '/',
    'idLoggedIn' => 'id_user'
   ]
  ];
}
