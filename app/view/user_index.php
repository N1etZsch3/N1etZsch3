<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>博客系统</title>
    <link rel="stylesheet" href="static/css/index.css">
    <script src="static/js/jquery-3.7.1.min.js"></script>
    <?php
    require_once "../model/common.php";
    check_login();
    ?>
</head>
<body>
<header>
    <div class="branding">
        <h1 style="color: white;">用户主页</h1>
    </div>
    <nav class="nav">
        <a href="user_index.php" class="nav-link"><?php echo $_SESSION['email']?> 个人主页</a>
        <a href="../controller/router.php?action=user_logout" class="nav-link">注销登录</a>
        <a href="sys_index.php" class="nav-link">返回首页</a>
        <a href="user_submit.php" class="nav-link">发表博客</a>
    </nav>
</header>
<main>
    <section class="post-preview-container">
        <!-- 文章预览 -->
    </section>
    <nav class="pagination">
        <span class="page-number">1</span>
        <span class="page-number">2</span>
        <span class="page-number">3</span>
    </nav>
</main>
<script src="static/js/user_index.js"></script>
</body>
</html>