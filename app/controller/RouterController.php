<?php

class Router {
    private array $getRoutes = [];
    private array $postRoutes = [];

    public function addGetRoute($route, $callback): void {
        $this->getRoutes[$route] = $callback;
    }

    public function addPostRoute($route, $callback): void {
        $this->postRoutes[$route] = $callback;
    }

    public function dispatch($route): void {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method == 'GET' && isset($this->getRoutes[$route])) {
            call_user_func($this->getRoutes[$route]);
        } elseif ($method == 'POST') {
            $postAction = $_POST['action'] ?? '';
            if (isset($this->postRoutes[$postAction])) {
                call_user_func($this->postRoutes[$postAction]);
            } else {
                // POST路由未找到
                header("HTTP/1.1 404 Not Found");
                echo '404 Not Found - POST Route';
            }
        } else {
            // 无匹配路由
            header("HTTP/1.1 404 Not Found");
            echo '404 Not Found or Method Not Supported';
        }
    }
}


