<?php
require_once "RouterController.php";
require_once "DataController.php";
require_once "UserController.php";

$router = new Router();
$dataController = new DataController();
$userController = new UserController();

// 添加GET路由
$router->addGetRoute('get_blogs', [$dataController, 'getBlogs']);
$router->addGetRoute('get_total_pages', [$dataController, 'getTotalPages']);
$router->addGetRoute('delete_comment', [$dataController, 'deleteComment']);
$router->addGetRoute('get_posts', [$dataController, 'getPosts']);
$router->addGetRoute('user_logout', [$userController, 'userLogout']);

// 添加POST路由
$router->addPostRoute('create_blog', [$dataController, 'createBlog']);
$router->addPostRoute('user_login', [$userController, 'userLogin']);
$router->addPostRoute('user_register', [$userController, 'userRegister']);

// 分发请求
$requestedRoute = $_GET['action'] ?? ($_POST['action'] ?? '');
$router->dispatch($requestedRoute);
