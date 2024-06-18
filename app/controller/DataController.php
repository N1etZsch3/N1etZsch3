<?php
require_once "../model/common.php";

class DataController {
    private Blog $blog;
    private Comment $comment;

    public function __construct() {
        $this->blog = new Blog();
        $this->comment = new Comment();
    }

    public function createBlog(): void {
        $title = test_input($_POST["title"]);
        $content = test_input($_POST["content"]);

        if ($this->blog->create($title, $content, $_SESSION['email'])) {
            echo "create-pass";
        } else {
            echo "create-failed";
        }
    }

    public function getBlogs(): void {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $this->blog->get_blogs_content($id);
            echo json_encode($result);
        } else {
            // Handle error for missing id
            echo "Error: No blog ID provided";
        }
    }

    public function getPosts(): void {
        $page = $_GET['page'] ?? 1;
        if ($_GET['type'] === 'user'){
        $author_email = $_SESSION['email'];
        $result = $this->blog->search_blog($page, $author_email);
        }
        else{
            $result = $this->blog->search_blog($page);
        }
        echo json_encode($result);
    }

    public function getTotalPages(): void {
        $author_email = null;
        if (isset($_GET['type']) && $_GET['type'] === 'user') {
            $author_email = $_SESSION['email'];
        }
        $totalPages = $this->blog->get_total_pages($author_email);
        echo $totalPages;
    }

    public function deleteComment(): void {
        if (isset($_GET['id'])) {
            $blog_id = $_GET['blog_id'];
            $id = $_GET['id'];
            $result = $this->comment->delete_comment($id);
            if ($result) {
                echo "<script>alert('删除成功！');location.href='../view/blog_view.php?id=$blog_id'</script>";
            } else {
                echo "<script>alert('删除失败！');location.href='../view/blog_view.php?id=$blog_id'</script>";
            }
        } else {
            // Handle error for missing id
            echo "Error: No comment ID provided";
        }
    }
}


