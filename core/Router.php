<?php

namespace liveticket\core;

use Exception;
use liveticket\app\helpers\POSTSupervisor;

class Router
{
    private $routes = array(
        'GET' => array(),
        'POST' => array(),
        'DELETE' => array()
    );

    public static function load($file)
    {
        $router = new static;
        require $file;
        App:: bind ('router', $router);
    }

    public function get($uri, $controller, $role='ROLE_ANONIMO')
    {
        $this->routes['GET'][$uri] = array(
            'controller'=>$controller,
            'role'=>$role
        );
    }

    public function post($uri, $controller, $role='ROLE_ANONIMO')
    {
        $this->routes['POST'][$uri] = array(
            'controller'=>$controller,
            'role'=>$role
        );
    }

    public function delete($uri, $controller, $role='ROLE_ANONIMO')
    {
        $this->routes['DELETE'][$uri] = array(
            'controller'=>$controller,
            'role'=>$role
        );
    }

    private function callAction($controller, $action, $parameters=[])
    {
        $controller = "liveticket\\app\\controllers\\" . $controller;
        $objController = new $controller;

        if(! method_exists ($objController, $action))
        {
            throw new Exception(
                "El controlador $controller no responde al action $action");
        }

        return call_user_func_array(array($objController, $action), $parameters);
    }

    private function prepareRoute(string $route)
    {
        $urlRule = preg_replace (
            '/:([^\/]+)/',
            '(?<\1>[^/]+)',
            $route
        );

        return str_replace ('/', '\/', $urlRule);
    }

    private function getParametersRoute(string $route, array $matches)
    {
        preg_match_all ('/:([^\/]+)/', $route, $parameterNames);

        return array_intersect_key ($matches, array_flip ($parameterNames[1]));
    }

    public function direct($uri, $method)
    {
        POSTSupervisor::trim();
        POSTSupervisor::detectspecialchars();

        foreach ($this->routes[$method] as $route => $routeData) {
            $urlRule = $this->prepareRoute($route);
            if (preg_match('/^' . $urlRule . '\/*$/s', $uri, $matches)) {
                if (Security::isUserGranted($routeData['role']) === false)
                    if (!is_null(App::get('user')))
                        return $this->callAction('AuthController', 'unauthorized');
                    else
                        $this->redirect('login');
                else {
                    if(strpos($uri, 'idiomas') !== 0) {
                        $_SESSION['uri'] = $uri;
                        $_SESSION['method'] = $method;
                    }
                    $parameters = $this->getParametersRoute($route, $matches);
                    list($controller, $action) = explode('@', $routeData['controller']);

                    return $this->callAction($controller, $action, $parameters);
                }
            }
        }

        $this->callAction('PagesController', 'notFound');
    }

    public function redirect($uri)
    {
        header('location: /' . $uri);
        exit();
    }
}