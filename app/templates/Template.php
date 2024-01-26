<?php

namespace app\templates;

class Template
{
  public function run($data)
  {
    $find = array();
    $replace = array();

    foreach($data AS $key => $value)
    {
      $find[] = '#'.$key;
      $replace[] = $value;
    }
    $find[] = "\n";
    $replace[] = "<br>\n";

    $contents = file_get_contents('emails/'.$this->template.'.html');
    $contents = str_replace($find, $replace, $contents);

    $msgBody = "<!DOCTYPE html>\n"
    ."<html lang=\"pt-br\">\n"
    ."<head>\n"
    ." <meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\">\n"
    ." <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n"
    ." <title>Email</title>\n"
    ."</head>\n"
    ."<body>\n"
    .$contents
    ."</body>\n"
    ."</html>\n";
    return $msgBody;
  }
}
