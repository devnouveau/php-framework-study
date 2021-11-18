<?php

require_once './vendor/autoload.php';

// 데이터베이스 연결
// 세션 켜기
// 에러 핸들러 등록
// 환경설정


use Eclair\Support\ServiceProvider;
use Eclair\Application;


class SessionServiceProvide extends ServiceProvider
{
  public static function register()
  {
    // session_set_save_handler
  
    // Route::add...
  }

  public static function boot()
  {
    // session_start();
  }
}

$app = new Application([
  SessionServiceProvicder::class
]);



?>