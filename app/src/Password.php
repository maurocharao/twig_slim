<?php

namespace app\src;

class Password {
  public static function make(string $password) : string {
    $options = [
      'cost' => 12
    ];
    return password_hash($password, PASSWORD_BCRYPT, $options);
  }

  public static function verify(string $password, string $hash) : bool {
    return password_verify($password, $hash);
  }
}
