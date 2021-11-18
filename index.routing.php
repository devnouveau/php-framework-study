<?php 

/**
 * DB, Http, Routing(Route, Middleware, RequestContext) 구현내용
 */

require_once './vendor/autoload.php';

use Eclair\Routing\Route;
use Eclair\Routing\Middleware;
use Eclair\Database\Adaptor;

Adaptor::setup('mysql:dbname=OOP', 'homestead', 'secret');

class HelloMiddleWare extends Middleware 
{
  public static function process()
  {
    return true; 
  }
}

Route::add('get', '/', function() {
  echo 'Hello, World';
}, [ HelloMiddleWare::class ]);

Route::add('get', '/posts/{id}', function ($id) { // {id}를 파라미터로 받음
  if($post = Adaptor::getAll('SELECT * FROM posts WHERE `id` = ?', [$id])) {
    return var_dump($post);
  }
  http_response_code(404);
});

Route::run();


?>