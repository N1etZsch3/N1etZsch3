<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>登录页面</title>
  <link rel="stylesheet" href="static/css/login.css">
  <script src="static/js/jquery-3.7.1.min.js"></script>
    <?php
    require_once "../model/common.php";
    check_if_in_login();
    ?>
</head>
<body>
<div class="auth-container">
  <div class="form-container">
    <h2>登录</h2>
    <form id="login-form">
      <input type="text" id="login-email" placeholder="邮箱" required>
      <input type="password" id="login-password" placeholder="密码" required>
      <button type="submit">登录</button>
    </form>
    <p>没有账户？<a href="register.php">注册</a></p>
  </div>
</div>
</body>
<script src="static/js/log_reg.js"></script>
</html>
