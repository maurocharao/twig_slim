<?php

namespace app\controllers\admin;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use app\src\Image;
use app\src\Validate;
use app\models\admin\Admin;
use Slim\Views\Twig;

class Posts
{
  protected $post;

  public function __construct()
  {
    $this->post = new \app\models\Posts;
  }

  public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $rows = $this->post
     ->posts()
     ->search([ 'title', 'slug', 'description' ])
     ->orderBy('p.id', 'DESC')
     ->paginate(20)
     ->get();

    $view = Twig::fromRequest($request);

    return $view->render($response, 'admin/posts.html', [
     'rows' => $rows,
     'title' => 'Lista de posts',
     'search' => search(),
		 'links' => $this->post->links()
    ]);
  }

  public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
  {
    $post = $this->post->select()->where('id', $args['id'])->first();
    $fields = [ 'title' => 'Editar Post' ];
    if($post) {
      if($post->photo) {
        $post->photo = '/'.Image::IMG_PATH.$post->photo;
      }
      $fields['fields'] = $post;
    } else {
      $fields['fields'] = [];
      flash('message', error('Post não encontrado!'));
    }
    $view = Twig::fromRequest($request);

    return $view->render($response, 'admin/post.html', $fields);
  }

  public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $view = Twig::fromRequest($request);

    return $view->render($response, 'admin/post.html', [
      'title' => 'Cadastrar post'
    ]);
  }

  public function save(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $isEdit = isset($args['id']) && $args['id'] > 0;

    $validate = new Validate;
    $data = $validate->validate([
     'title' => 'required:max@150',
     'slug' => 'required:max@100',
     'description' => 'required'
    ]);
    if($validate->hasErrors()) {
   // return back($response);
      $view = Twig::fromRequest($request);

      return $view->render($response, 'admin/post.html', [
       'title' => ($isEdit ? 'Editar' : 'Cadastrar').' post',
       'fields' => $request->getParsedBody()
      ]);
    }
    if($isEdit) {
      $updated = $this->post->find('id', $args['id'])->update($data);

      if($updated) {
        flash('message', success('Post atualizado com sucesso!'));
      }
      return back($response);
    }
    $user = (new Admin)->user();
    $data['user'] = $user->id;
    $id = $this->post->create($data);
    if($id > 0) {
      flash('message', success('Post cadastrado com sucesso!'));
      return redirect($response, '/admin/post/edit/'.$id);
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
    $post = $this->post->select()->where('id', $args['id'])->first();
    if(!$post) {
      flash('message', error('Post não encontrado!'));
      return back($response);
    }
    if($post->photo) {
      unlink(path().'/public/'.Image::IMG_PATH.$post->photo);
    }
    $image = new Image();
    $image->upload('file', 400);

    $post = $this->post->find('id', $args['id'])->update([
      'photo' => $image->getName()
    ]);

    flash('message_upload', success('Upload feito com sucesso.'));
    return back($response);
  }

  public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
  {
    $post = $this->post->select()->where('id', $args['id'])->first();
    if(!$post) {
      flash('message', error('Post não encontrado!'));
      return redirect($response, '/admin/posts');
    }
    if($post->photo) {
      unlink(path().'/public/'.Image::IMG_PATH.$post->photo);
    }
    $deleted = $this->post->find('id', $args['id'])->delete();

    if($deleted) {
      flash('message', success('Post excluído!'));
      return redirect($response, '/admin/posts');
    }
    return $response;
  }
}
