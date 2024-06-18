

# 博客系统产品文档

### 产品名称

N1etZsch3的博客系统

### 作者

N1etZsch3

### 产品简介

博客系统V2是一个现代化的博客平台，允许用户创建、发布、查看和评论博客。它的设计注重用户体验和安全性，提供了一个简洁且直观的界面来进行博客的交互。

## 设计思路

### 前端架构

- **技术栈**: 前端采用HTML、CSS和JavaScript，结合jQuery库来增强页面的交互性和动态加载内容。
- **页面布局**: 使用响应式设计来确保在不同设备上均能提供良好的用户体验。
- **交互设计**: AJAX技术用于前后端的数据交互，减少页面重载，提高用户体验。
- **用户界面**: 界面设计简洁明了，易于导航，使用户可以轻松地浏览和管理博客。

### 后端架构

- **技术栈**: 后端使用PHP进行开发，结合MySQL数据库进行数据存储。
- **MVC模式**: 后端架构遵循MVC（模型-视图-控制器）模式，提高了代码的可维护性和可扩展性。
- **RESTful API**: 接口设计遵循RESTful原则，确保API的清晰和一致性，便于前端进行调用。
- **数据库设计**: 数据库结构合理，通过关系型设计来存储用户信息、博客内容和评论等数据。
- **路由设计**: 根据前端请求数据解析路由，减少内部文件暴露

### 安全性考虑

- **用户认证**: 系统包含用户注册和登录机制，密码在存储前进行哈希处理，提高账户安全性。
- **输入验证**: 所有用户输入都经过严格的验证和清理，防止跨站脚本（XSS）和SQL注入攻击。
- **会话管理**: 使用会话控制用户登录状态，防止会话劫持。
- **错误处理**: 系统妥善处理错误和异常，避免敏感信息泄露。

### 项目结构

```cmd
+---config	#配置文件
|       db_config.php  # 数据库配置文件
|
└---controller	# 控制器
|       DataController.php  # 数据接口控制器
|       router.php	# 路由分发器
|       RouterController.php  # 路由控制器
|       UserController.php  # 用户管理接口控制器
|
+---model	# 模板
|       common.php	# 数据库交互模板，用户状态管理
|
+---resource	# 未编译源码
|       config.sql  # 未编译的数据库SQL语句
|
└---view	# 视图
    |   blog_view.php	# 博客预览视图
    |   index.php	# 游客系统首页视图
    |   login.php	# 登录界面视图
    |   register.php	# 注册界面视图
    |   sys_index.php	# 用户系统首页视图
    |   user_index.php	# 用户主页视图
    |   user_submit.php	# 博客发表视图
    |
    └---static	#静态资源
        +---css
        |       blog.css
        |       comment.css
        |       create_blog.css
        |       index.css
        |       login.css
        |
        └---js
                create_blog.js
                jquery-3.7.1.min.js
                log_reg.js
                sys_index.js
                user_index.js
```

### 面向对象设计

+ **数据库模板设计**：

  ​	1、首先，定义一个父类，用来对数据库进行IO操作。使用构造函数和析构函数，在实例化时主动完成IO操作。

  ```PHP
  class Database {
      protected mysqli $conn;
      function __construct(){
          $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
          if ($this->conn->connect_error) {
              die("数据库连接失败: " . $this->conn->connect_error);
          }
      }
  
      function __destruct(){
          $this->conn->close();
      }
  }
  ```

  ​	2、定义三个子类，继承父类数据库连接，分别封装操作三张数据表的方法，增加代码扩展性和可读性。

  ```php
  class User extends Database {
  
      public function login($email, $password): bool{}
  
      public function register($username, $email, $hashed_password): bool{}
  
  }
  
  class Blog extends Database{
  
      public function create($title, $content, $author_email): bool{}
  
      public function search_blog($page, $author_email = null): array {}
  
      public function get_total_pages($author_email = null): int {}
  
      public function get_blogs_content($id): array{}
  }
  
  class Comment extends Database{
      
      public function submit_comment($blog_id, $author_email, $commenter_email, $content): bool{}
  
      public function get_comments($blog_id): array{}
  
      public function delete_comment($id): bool{}
  
  }
  ```

+ **用户管理控制器设计:**

  ​	1、定义一个UserController类，用于调用User类中相关方法，控制用户注册、登录、登出：

  ```php
  class UserController {
      private USER $user;
      private SessionManager $sessionManager;
  
      public function __construct() {
          $this->user = new User();
          $this->sessionManager = new SessionManager();
      }
  
      public function userLogin(): void {}
  
      public function userRegister(): void {}
  
      public function userLogout(): void {}
  ```

  ​	2、定义SessionManager类，用于控制会话权限。

  ```php
  class SessionManager {
      public function __construct() {
          if (session_status() == PHP_SESSION_NONE) {
              session_start();
          }
      }
  
      public function setUserSession($email): void {}
  
      public function destroyUserSession(): void {}
  ```

+ **路由控制器设计**

  ​        1、路由器主要用于解析前端请求，分发路由，调用对应的方法，首先是路由控制器的设计。定义一个路由类，用于增加不同类型的路由，然后解析分发路由：

  ```php
  <?php
  
  class Router {
      private array $getRoutes = [];
      private array $postRoutes = [];
  
      public function addGetRoute($route, $callback): void {}
  
      public function addPostRoute($route, $callback): void {}
  
      public function dispatch($route): void {}
  ```

  ​        2、创建路由器，用于实现路由控制器方法。

  ```php
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
  ```

+ **数据接口控制器设计：**

  ​        创建DataController文件，用于实现Blog类和Comment类的方法，并为路由器提供数据接口。：
  ```php
  <?php
  require_once "app/model/common.php";
  
  class DataController {
      private Blog $blog;
      private Comment $comment;
  
      public function __construct() {
          $this->blog = new Blog();
          $this->comment = new Comment();
      }
  
      public function createBlog(): void {}
  
      public function getBlogs(): void {}
  
      public function getPosts(): void {}
  
      public function getTotalPages(): void {}
  
      public function deleteComment(): void {}
  }
  ```

