<html lang="zh-CN">
<?php
require_once "../model/common.php";
check_login();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>博客系统</title>
    <link rel="stylesheet" href="static/css/index.css">
    <link rel="stylesheet" href="static/css/blog.css">
    <link rel="stylesheet" href="static/css/comment.css">
    <?php
    // 获取博客内容的函数
    function get_blogs($id): array {
        $blog = new Blog();
        return $blog->get_blogs_content($id);
    }

    function get_comments($id): array{
        $comment = new Comment();
        return $comment->get_comments($id);
    }

    $id = 0;
    $title = '';
    $author = '';
    $content = '';
    $time = '';

    // 处理 POST 请求
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['id'], $_POST['author_email'])) {
            $comment = new Comment();
            $blog_id = test_input($_POST['id']);
            $author_email = test_input($_POST['author_email']);
            $commenter_email = $_SESSION['email'];
            $content = test_input($_POST['comment_content']);
            if ($comment->submit_comment($blog_id, $author_email, $commenter_email, $content)) {
                echo "<script>alert('评论成功！');location.href='blog_view.php?id=$blog_id'</script>";
            } else {
                echo "<script>alert('评论失败！');location.href='blog_view.php?id=$blog_id'</script>";
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        // 处理 GET 请求
        $id = test_input($_GET['id']);
        $blog = get_blogs($id)[0];
        $title = $blog['title'];
        $author = $blog['author_email'];
        $content = $blog['content'];
        $time = $blog['publish_time'];
    } else {
        echo "<script>alert('非法访问！');location.href='sys_index.php'</script>";
    }

    // 获取文章评论
    get_comments($id);
    ?>
</head>
<body>
<header>
    <div class="branding">
        <h1 style="color: white;">阅览博客</h1>
    </div>
    <nav class="nav">
        <a href="user_index.php" class="nav-link">欢迎你：<?php echo $_SESSION['email']?></a>
        <a href="../controller/logout.php" class="nav-link">注销登录</a>
        <a href="sys_index.php" class="nav-link">返回首页</a>
    </nav>
</header>
<main>
    <article class="blog-post">
        <h2 class="post-title"><?php echo $title?></h2>
        <div class="post-meta"><span class="author"><?php echo $author?></span> 发表于 <time class="publish-date"><?php echo $time?></time></div>
        <div class="post-content">
            <p><?php echo $content?></p>
        </div>
        <section class="comments-section">
            <form class="comment-form" method="post" action="blog_view.php" >
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="author_email" value="<?php echo $author; ?>">
                <textarea name="comment_content" placeholder="留下您的评论..." required></textarea>
                <button type="submit">提交评论</button>
            </form>
            <br>
            <h3>评论</h3>
            <ul class="comments-list">
                <?php
                    foreach (get_comments($id) as $comment) {
                        echo "<li class=\"comment\">
                                <div class=\"comment-meta\">
                                    <span class=\"commenter\">{$comment['commenter_email']}</span> 评论于 <time class=\"comment-time\">{$comment['comment_time']}</time>
                                </div>
                                <div class=\"comment-content\">
                                    <p>{$comment['content']}</p>
                                </div>";
                        if ($_SESSION['email'] === $comment['commenter_email'] || $_SESSION['email'] === $comment['author_email']){
                            echo "<div class=\"comment-delete\">
                                    <a href=\"../controller/router.php?action=delete_comment&id={$comment['id']}&blog_id=$id\">删除评论</a>
                                </div>";
                        }
                        echo "</li>";
                    }
                ?>
            </ul>
        </section>
    </article>
</main>

</body>
</html>