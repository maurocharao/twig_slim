<?php

namespace app\src;
use app\traits\Validations;

class Validate
{
  use Validations;

  public function validate($rules)
  {
    foreach($rules AS $field => $validation)
    {
      $validations = explode(':', $validation);
      $numValidations = count($validations);

      for($v = 0; $v < $numValidations; $v++)
      {
        $validation = $validations[$v];

        if(($pos = strpos($validation, '@')) !== false)
        {
          $method = substr($validation, 0, $pos);
          $parameter = substr($validation, $pos + 1);
          $this->$method($field, $parameter);
        }
        else {
          $this->$validation($field);
        }
      }
    }
    return $this->sanitize();
  }

  protected function sanitize()
  {
    $sanitized = [];

    foreach($_POST AS $field => $value)
    {
      $sanitized[$field] = filter_var($value, FILTER_UNSAFE_RAW, FILTER_FLAG_NO_ENCODE_QUOTES);
    }
    return $sanitized;
  }
}
