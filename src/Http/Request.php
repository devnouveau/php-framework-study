<?php

namespace Eclair\Http;

class Request
{
    public static function getMethod()
    {
        return filter_input(INPUT_POST, '_method') ?: $_SERVER['REQUEST_METHOD'];
        // filter_input : request변수값반환 
        // 없으면 false -> 
        // $_SERVER['REQUEST_METHOD'] : 페이지 액세스시 요청메소드
    }

    public static function getPath()
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }
}
