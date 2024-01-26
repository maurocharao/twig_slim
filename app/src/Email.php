<?php

namespace app\src;

use app\helpers\Cfg;
use app\templates\Template;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
  private $data;
  private $template;

  public function data(array $data)
  {
    $this->data = (object)$data;

    return $this;
  }

  public function template(Template $template)
  {
    if(!isset($this->data))
    {
      throw new \Exception("Antes de chamar o template, passe os dados atraves do metodo data");
    }
    $this->template = $template->run($this->data);

    return $this;
  }

  public function send($response)
  {
    if(!isset($this->template)) {
      throw new \Exception("Por favor, antes de enviar o email, escolha um template com o método template");
    }
    $mail = new PHPMailer;

 // $mail->SMTPDebug = 2;                         // Enable verbose debug output
    $mail->isSMTP();                              // Set mailer to use SMTP
    $mail->Host = Cfg::EMAIL['host'];             // SMTP host
    $mail->SMTPAuth = true;                       // Enable SMTP authentication
    $mail->Username = Cfg::EMAIL['username'];     // SMTP username
    $mail->Password = Cfg::EMAIL['password'];     // SMTP password
    $mail->SMTPSecure = Cfg::EMAIL['encryption']; // Enable TLS encryption, `ssl` also accepted
    $mail->Port = Cfg::EMAIL['port'];             // TCP port to connect to
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($this->data->fromEmail, $this->data->fromName);
    // Add a recipient
    $mail->addAddress($this->data->toEmail, $this->data->toName);
    // Content
    $mail->isHTML(true);
    $mail->Subject = $this->data->assunto;
    $mail->Body    = $this->template;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    try {
      if($mail->send()) {
        flash('message', success('E-mail enviado.'));
      } else {
        flash('message', error('Erro ao enviar e-mail: '.$mail->ErrorInfo.'.'));
      }
    }
    catch(Exception $e) {
      flash('message', error('Erro ao enviar e-mail: '.$mail->ErrorInfo.'.'));
    }
    return back($response);
  }
}
