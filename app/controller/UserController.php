<?php

require_once "app/model/common.php";

class UserController {
    private USER $user;
    private SessionManager $sessionManager;

    public function __construct() {
        $this->user = new User();
        $this->sessionManager = new SessionManager();
    }

    public function userLogin(): void {
        $email = test_input($_POST['email']);
        $password = test_input($_POST['password']);
        try {
            if ($this->user->login($email, $password)) {
                $this->sessionManager->setUserSession($email);
                echo "login-pass";
            }
        } catch (Exception $e) {
            echo "login-failed";
        }
    }

    public function userRegister(): void {
        $username = test_input($_POST["username"]);
        $email = test_input($_POST["email"]);
        $password = test_input($_POST["password"]);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            if ($this->user->register($username, $email, $hashed_password)) {
                echo "register-pass";
            }
        } catch (Exception $e) {
            echo "register-failed";
        }
    }

    public function userLogout(): void {
        $this->sessionManager->destroyUserSession();
        header("Location: ../view/index.php");
        exit;
    }
}

// SessionManager 类用于管理会话
class SessionManager {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function setUserSession($email): void {
        $_SESSION['email'] = $email;
        $_SESSION['isLogin'] = true;
    }

    public function destroyUserSession(): void {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}

