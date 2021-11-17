<?php

namespace Eclair\Routing;

use Eclair\Routing\RequestContext;
use Eclair\Http\Request;

class Route
{

    private static $contexts = [];

    // requestcontext생성
    public static function add($method, $path, $handler, $middlewares = [])
    {
        self::$contexts[] = new RequestContext($method, $path, $handler, $middlewares);
    }

    public static function run()
    {
        foreach (self::$contexts as $context) {
            if ($context->method === strtolower(Request::getMethod()) && //요청컨텍스트의 메소드가 실제 요청 메소드와 일치하고, 
                is_array($urlParams = $context->match(Request::getPath()))) { //파라미터배열이 정상적으로 반환되었을때(실제요청url이 url패턴과 일치할 떄)
                if ($context->runMiddlewares()) { // 미들웨어가 실행되어야 컨트롤러 로직에 도달할 수 있도록 함
                    return call_user_func($context->handler, ...$urlParams);
                }
                return false;
            }
        }
    }
}
