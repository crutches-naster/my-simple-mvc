<?php

namespace Core;

class Router
{
    static protected array $routes = [], $params = [];

    static protected array $convertTypes = [
        'd' => 'int',
        'D' => 'string'
    ];

    // notes/{id: \d+}/edit
    static public function add(string $route, array $params = []) : void
    {
        $route = preg_replace('/\//', '\\/', $route );
        $route = preg_replace('/\{([a-z_]+):([^}]+)}/', '(?P<$1>$2)', $route );
        $route = "/^{$route}$/i";

        static::$routes[ $route ] = $params;
    }

    // notes/{id: \d+}/edit
    // notes/5/edit
    /**
     * [
     *  'controller' => '',
     *  'action' => '',
     *  'method' => 'GET',
     *  'id' => 5
     * ]
     * @param string $url
     * @return void
     * @throws \Exception
     */
    static public function dispatch(string $url) : void
    {
        $url = static::removeQueryVariables($url);
        $url = trim($url, '/');

        if ( static::match($url) && static::checkRequestMethod() ) {

            $controller = static::getController();
            $action = static::getAction($controller);

            if ($controller->before($action)) {
                call_user_func_array([$controller, $action], static::$params);
                $controller->after($action);
            }
        }
    }

    /**
     * @throws \Exception
     */
    static protected function getAction(Controller $controller): string
    {
        $action = static::$params['action'] ?? null;

        if (method_exists($controller, $action)) {
            unset(static::$params['action']);
            return $action;
        }

        throw new \Exception($controller::class . " doesn't have '{$action}'!");
    }

    /**
     * @throws \Exception
     */
    static protected function getController(): Controller
    {
        $controller = static::$params['controller'] ?? null;

        if (class_exists($controller)) {
            unset(static::$params['controller']);

            return new $controller;
        }

        throw new \Exception("Controller '{$controller}' doesn't exists!");
    }

    /**
     * @throws \Exception
     */
    static protected function checkRequestMethod()
    {
        if ( array_key_exists('method', static::$params)) {
            $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);

            if ( $requestMethod == strtolower( static::$params['method'] ) ) {
                unset( static::$params['method'] );
                return true;
            }

            throw new \Exception("Method '{$_SERVER['REQUEST_METHOD']}' not allowed for this route");
        }
    }

    /**
     * @throws \Exception
     */
    static protected function match(string $url): bool
    {
        foreach (static::$routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                static::$params = static::setParams($route, $matches, $params);
                return true;
            }
        }

        throw new \Exception("Route [{$url}] not found", 404 );
    }

    static protected function setParams(string $route, array $matches, array $params): array
    {
        preg_match_all('/\(\?P<[\w]+>\\\\(\w[\+]*)\)/', $route, $types);
        $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        if (!empty($types)) {
            $lastKey = array_key_last($types);
            $step = 0;
            $types[$lastKey] = str_replace('+', '', $types[$lastKey]);

            foreach ($matches as $name => $match) {
                settype($match, static::$convertTypes[$types[$lastKey][$step]]);
                $params[$name] = $match;
                $step++;
            }
        }

        return $params;
    }

    static protected function removeQueryVariables(string $url): string
    {
        // notes/5/edit(?var=value&) => notes/5/edit
        return preg_replace('/([\w\/]+)\?([\w\/=\d]+)/i', '$1', $url);
    }
}
