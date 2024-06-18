<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>博客系统</title>
    <link rel="stylesheet" href="static/css/index.css">
    <link rel="stylesheet" href="static/css/create_blog.css">
    <script src="static/js/jquery-3.7.1.min.js"></script>
    <?php
    require_once "../model/common.php";
    check_login();
    ?>
</head>
<body>
<header>
    <div class="branding">
        <h1 style="color: white;">发表博客</h1>
    </div>
    <nav class="nav">
        <a href="user_index.php" class="nav-link">欢迎你：<?php echo $_SESSION['email']?></a>
        <a href="../controller/logout.php" class="nav-link">注销登录</a>
        <a href="sys_index.php" class="nav-link">返回首页</a>
    </nav>
</header>
<div class="container">
    <div class="blog-form-container">
        <h2>发表新博客</h2>
        <form id="blog-form">
            <input type="text" id="post-title-input" placeholder="博客标题" required>
            <textarea id="post-content-input" placeholder="博客内容" required></textarea>
            <button type="submit" id="create-post-button">发表</button>
        </form>
    </div>
</div>

<div id="posts-section" class="posts-section">
    <!-- 新博客条目将被添加到这里 -->
</div>

<script src="static/js/create_blog.js"></script>
</body>
</html>
