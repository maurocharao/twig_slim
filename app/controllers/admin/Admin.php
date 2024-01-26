<?php

namespace app\controllers\admin;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use app\src\Image;
use app\src\Validate;
use Slim\Views\Twig;

class Admin
{
  private $admin;

  public function __construct()
  {
    $this->admin = new \app\models\admin\Admin;
  }

  public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $rows = $this->admin->select()->search([ 'name', 'email' ])->paginate(5)->get();

    $view = Twig::fromRequest($request);

    return $view->render($response, 'admin/users.html', [
     'title' => 'Gerenciar Usuários Administradores',
     'rows' => $rows,
     'search' => search(),
		'links' => $this->admin->links()
    ]);
  }

  public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
  {
    $admin = $this->admin->select()->where('id', $args['id'])->first();
    $fields = [ 'title' => 'Editar Usuário Administrador' ];
    if($admin) {
      if($admin->photo) {
        $admin->photo = '/'.Image::IMG_PATH.$admin->photo;
      }
      $fields['fields'] = $admin;
    } else {
      $fields['fields'] = [];
      flash('message', error('Usuário não encontrado!'));
    }
    $view = Twig::fromRequest($request);

    return $view->render($response, 'admin/user.html', $fields);
  }

  public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $view = Twig::fromRequest($request);

    return $view->render($response, 'admin/user.html', [
      'title' => 'Adicionar Usuário Administrador'
    ]);
  }

  public function save(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $isEdit = isset($args['id']) && $args['id'] > 0;

    $validate = new Validate;
    $data = $validate->validate([
     'name' => 'required:max@40',
     'email' => 'required:email'.($isEdit ? '' : ':unique@admin.admin'),
     'phone' => 'required:phone'
    ]);
    if($validate->hasErrors()) {
   // return back($response);
      $view = Twig::fromRequest($request);

      return $view->render($response, 'admin/user.html', [
       'title' => ($isEdit ? 'Editar' : 'Adicionar').' Usuário',
       'fields' => $request->getParsedBody()
      ]);
    }
    else if($isEdit) {
      $updated = $this->admin->find('id', $args['id'])->update($data);

      if($updated) {
        flash('message', success('Usuário atualizado com sucesso!'));
      }
      return back($response);
    }
    else {
      $id = $this->admin->create($data);
      if($id > 0) {
        flash('message', success('Usuário cadastrado com sucesso!'));
        return redirect($response, '/admin/user/edit/'.$id);
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
    $admin = $this->admin->select()->where('id', $args['id'])->first();
    if(!$admin) {
      flash('message', error('Usuário admnistrador não encontrado!'));
      return back($response);
    }
    if($admin->photo) {
      unlink(path().'/public/'.Image::IMG_PATH.$admin->photo);
    }
    $image = new Image();
    $image->upload('file', 640);

    $admin = $this->admin->find('id', $args['id'])->update([
      'photo' => $image->getName()
    ]);

    flash('message_upload', success('Upload feito com sucesso.'));
    return back($response);
  }

  public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
  {
    $admin = $this->admin->select()->where('id', $args['id'])->first();
    if(!$admin) {
      flash('message', error('Usuário administrador não encontrado!'));
      return redirect($response, '/admin/users');
    }
    if($admin->photo) {
      unlink(path().'/public/'.Image::IMG_PATH.$admin->photo);
    }
    $deleted = $this->admin->find('id', $args['id'])->delete();

    if($deleted) {
      flash('message', success('Usuário excluído!'));
      return redirect($response, '/admin/users');
    }
    return $response;
  }
}
