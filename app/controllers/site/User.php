<?php

namespace app\controllers\site;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use app\src\Image;
use app\src\Validate;
use app\models\Users;
use Slim\Views\Twig;

class User
{
  private $user;

  public function __construct()
  {
    $this->user = new Users;
  }

  public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $rows = $this->user->select()->search([ 'name', 'email' ])->paginate(5)->get();

    $view = Twig::fromRequest($request);

    return $view->render($response, 'site/users.html', [
     'title' => 'Gerenciar Usuários',
     'rows' => $rows,
     'search' => search(),
		'links' => $this->user->links()
    ]);
  }

  public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
  {
    $user = $this->user->select()->where('id', $args['id'])->first();
    $fields = [ 'title' => 'Editar Usuário' ];
    if($user) {
      if($user->photo) {
        $user->photo = '/'.Image::IMG_PATH.$user->photo;
      }
      $fields['fields'] = $user;
    } else {
      $fields['fields'] = [];
      flash('message', error('Usuário não encontrado!'));
    }
    $view = Twig::fromRequest($request);

    return $view->render($response, 'site/user.html', $fields);
  }

  public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $view = Twig::fromRequest($request);

    return $view->render($response, 'site/user.html', [
      'title' => 'Adicionar Usuário'
    ]);
  }

  public function save(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $isEdit = isset($args['id']) && $args['id'] > 0;

    $validate = new Validate;
    $data = $validate->validate([
     'name' => 'required:max@40',
     'email' => 'required:email'.($isEdit ? '' : ':unique@users'),
     'phone' => 'required:phone'
    ]);
    if($validate->hasErrors()) {
   // return back($response);
      $view = Twig::fromRequest($request);

      return $view->render($response, 'site/user.html', [
       'title' => ($isEdit ? 'Editar' : 'Adicionar').' Usuário',
       'fields' => $request->getParsedBody()
      ]);
    }
    else if($isEdit) {
      $updated = $this->user->find('id', $args['id'])->update($data);

      if($updated) {
        flash('message', success('Usuário atualizado com sucesso!'));
      }
      return back($response);
    }
    else {
      $id = $this->user->create($data);
      if($id > 0) {
        flash('message', success('Usuário cadastrado com sucesso!'));
        return redirect($response, '/user/edit/'.$id);
      }
    }
    return $response;
  }

  public function upload(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $validate = new Validate;
    $data = $validate->validate([
     'file' => 'image'
    ]);
    if($validate->hasErrors()) {
      return back($response);
    }
    $user = $this->user->select()->where('id', $args['id'])->first();
    if(!$user) {
      flash('message', error('Usuário não encontrado!'));
      return back($response);
    }
    if($user->photo) {
      unlink(path().'/public/'.Image::IMG_PATH.$user->photo);
    }
    $image = new Image();
    $image->upload('file', 640);

    $user = $this->user->find('id', $args['id'])->update([
      'photo' => $image->getName()
    ]);

    flash('message_upload', success('Upload feito com sucesso.'));
    return back($response);
  }

  public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
  {
    $user = $this->user->select()->where('id', $args['id'])->first();
    if(!$user) {
      flash('message', error('Usuário não encontrado!'));
      return redirect($response, '/users');
    }
    if($user->photo) {
      unlink(path().'/public/'.Image::IMG_PATH.$user->photo);
    }
    $deleted = $this->user->find('id', $args['id'])->delete();

    if($deleted) {
      flash('message', success('Usuário excluído!'));
      return redirect($response, '/users');
    }
    return $response;
  }
}
