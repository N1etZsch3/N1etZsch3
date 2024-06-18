<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册页面</title>
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
        <h2>注册</h2>
        <form id="register-form">
            <input type="text" id="register-username" placeholder="用户名" required>
            <input type="email" id="register-email" placeholder="邮箱" required>
            <input type="password" id="register-password" placeholder="密码" required>
            <button type="submit">注册</button>
        </form>
        <p>已有账户？<a href="login.php">登录</a></p>
    </div>
</div>
</body>
<script src="static/js/log_reg.js"></script>
</html>
