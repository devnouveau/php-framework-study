<?php

namespace Eclair\Routing;

use Eclair\Routing\Middleware;

class RequestContext
{
    public $method;
    public $path;
    public $handler;
    public $middlewares;

    public function __construct($method, $path, $handler, $middlewares = [])
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->middlewares = $middlewares;
    }


    public function match($url)
    {
        //$this->path => /posts (urlpattern), $url=> /posts (url)
        //$this->path => /posts/{id} (urlpattern), $url => /posts/1 (url)

        $urlParts = explode('/', $url);
        $urlPatternParts = explode('/', $this->path); 

        // 파라미터 찾아서 저장하기
        if (count($urlParts) === count($urlPatternParts)) {
            $urlParams = [];
            foreach ($urlPatternParts as $key => $part) { 
                if (preg_match('/^\{.*\}$/', $part)) {  // {parameter} 형태인 부분이 있다면
                    $urlParams[$key] = $part; // 파라미터명 저장
                } else { // 플레인텍스트인 부분
                    if ($urlParts[$key] !== $part) { // url이 지정된 url패턴과 일치하지 않는 부분이 있는 경우
                        return null;
                    }
                }
            }
      
            return count($urlParams) < 1 ? 
                [] : array_map(fn ($k) => $urlParts[$k], array_keys($urlParams));
                // url파라미터가 있는 경우
                // 파라미터명이 저장된 $urlParams배열의 모든 키를 가져와서 
                // 파라미터값이 저장된 $urlParts배열에서 그 키(0,1,2,3...)로 저장된 
                // 파라미터값들을 array로 반환 
        }
    }

    public function runMiddlewares()
    {
        foreach ($this->middlewares as $middleware) {
            if (! $middleware::process()) {
                return false;
            }
        }
        return true;
    }
}
