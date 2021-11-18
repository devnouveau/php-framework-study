<?php
/**
 * 커스텀 세션 핸들러 사용
 */

require_once './vendor/autoload.php';

use Eclair\Session\DatabaseSessionHandler;
use Eclair\Database\Adaptor;

Adaptor::setup('mysql:dbname=OOP', 'homestead', 'secret');

session_set_save_handler(new DatabaseSessionHandler()); 
// session_set_save_handler()는 유저레벨 세션 스토리지 함수를 설정하게 해준다.


session_Start();

$_SESSION['message'] = 'hello world';
$_SESSION['foo'] = new stdClass();

?>