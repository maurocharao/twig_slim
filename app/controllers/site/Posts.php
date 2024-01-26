<?php

namespace app\controllers\site;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use app\src\Image;
use app\src\Validate;
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

    $numRows = count($rows);
    for($r = 0; $r < $numRows; $r++) {
      if($rows[$r]->photo) {
        $rows[$r]->photo = '/'.Image::IMG_PATH.$rows[$r]->photo;
      }
    }
    $view = Twig::fromRequest($request);

    return $view->render($response, 'site/posts.html', [
     'title' => 'Blog ASW',
     'rows' => $rows,
     'search' => search(),
     'links' => $this->post->links()
    ]);
  }

  public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $post = $this->post->posts()->where('slug', $args['slug'])->first();

    $fields = [ 'title' => $args['slug'] ];
    if($post) {
      if($post->photo) {
        $post->photo = '/'.Image::IMG_PATH.$post->photo;
      }
      $post->description = mb_strimwidth($post->description, 0, 200, '...');
      $fields['fields'] = $post;
    } else {
      $fields['fields'] = [];
      flash('message', error('Post não encontrado!'));
    }
    $view = Twig::fromRequest($request);

    return $view->render($response, 'site/post.html', $fields);
  }
}
