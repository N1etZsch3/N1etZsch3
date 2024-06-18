<?php
session_start();
require_once "../config/db_config.php";

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

class User extends Database {

    public function login($email, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT `password` FROM `users` WHERE `email` = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return false;
        }
        $row = $result->fetch_assoc();
        return password_verify($password, $row['password']);
    }

    public function register($username, $email, $hashed_password): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO `users`(username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        return $stmt->execute();
    }

}

class Blog extends Database{

    public function create($title, $content, $author_email): bool{
        $stmt = $this->conn->prepare("INSERT INTO `blogs`(title, content, author_email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $author_email);
        return $stmt->execute();
    }

    public function search_blog($page, $author_email = null): array {
        $offset = ($page - 1) * 4;  //计算偏移量

        $sql = "SELECT * FROM `blogs`";
        $params = [];
        $paramTypes = "";

        if ($author_email !== null) {
            // 添加 WHERE 子句和参数
            $sql .= " WHERE `author_email` = ?";
            $params[] = $author_email;
            $paramTypes .= "s"; // 参数是一个字符串
        }

        // 添加 ORDER BY 和 LIMIT 子句
        $sql .= " ORDER BY `id` DESC LIMIT ?, 4";
        $params[] = $offset;
        $paramTypes .= "i"; // 参数是一个整数

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($paramTypes, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get_total_pages($author_email = null): int {
        $sql = "SELECT COUNT(*) FROM `blogs`";

        if ($author_email !== null) {
            $sql .= " WHERE `author_email` = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $author_email);
        } else {
            $stmt = $this->conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return ceil($row['COUNT(*)'] / 4);
    }

    public function get_blogs_content($id): array{
        $stmt = $this->conn->prepare("SELECT * FROM `blogs` WHERE `id` = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

class Comment extends Database{
    public function submit_comment($blog_id, $author_email, $commenter_email, $content): bool{
        $stmt = $this->conn->prepare("INSERT INTO `comments`(blog_id, author_email, commenter_email, content) VALUES (?, ?, ?,?)");
        $stmt->bind_param("isss", $blog_id, $author_email, $commenter_email, $content);
        return $stmt->execute();
    }

    public function get_comments($blog_id): array{
        $stmt = $this->conn->prepare("SELECT * FROM `comments` WHERE `blog_id` = ?");
        $stmt->bind_param("i", $blog_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete_comment($id): bool{
        $stmt = $this->conn->prepare("DELETE FROM `comments` WHERE `id` = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

}

function test_input($data) {
    if (empty($data)) {
        die("表单数据为空");
    }
    return htmlspecialchars(stripslashes(trim($data)));
}

function check_login(): void
{
    if (!isset($_SESSION['isLogin']) && $_SESSION['isLogin'] !== true) {
        header("Location: ../view/login.php");
        exit();
    }
}

function check_if_in_login(): void
{
    if (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === true) {
        header("Location: ../view/sys_index.php");
        exit();
    }
}
