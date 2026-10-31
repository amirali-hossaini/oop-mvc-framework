<?php

namespace App\Core;

class Router
{
    public function dispatch()
    {
        $controller = 'index';
        $method = 'index';
        $params = [];
        $url = getUrl();

        if (!empty($url[0]) && fileExistsFromRoot('app/Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $controller = $url[0];
            unset($url[0]);
        }

        $controllerClass = 'App\\Controllers\\' . ucfirst($controller) . 'Controller';
        $controller = new $controllerClass;

        if (!empty($url[1]) && method_exists($controller, $url[1])) {
            $method = $url[1];
            unset($url[1]);
        }

        if (!empty($url)) {
            $params = array_values($url);
        }

        return call_user_func_array([$controller, $method], $params);
    }
}
