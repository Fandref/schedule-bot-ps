<?php

namespace school\core;

use school\core\View;

class Router {
    protected $routes = [];
    protected $params = [];
    protected $lang = 'ua';
    public function __construct() {
        $arr = require 'school/config/routes.php';
        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    public function add($route, $params) {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    public function match() {

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;
                return true;
            }
        }

        return false;

    }

    public function run(){
        if ($this->match()) {
            $path = 'app\base\\'.ucfirst($this->params['action']).'Action';
            if (class_exists($path)) {
                $action = $this->params['action'].'Action';
                if (method_exists($path, $action)) {
                    $controller = new $path($this->params);
                    $controller->$action($this->lang);
                    
                } else {
                    View::errorCode(404);
                   
                }
            } else {
                View::errorCode(404);
                
            }
        } else {
            View::errorCode(404);
            
        }
    }

}