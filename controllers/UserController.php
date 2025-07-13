<?php
require_once 'models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct($db = null) {
        if ($db === null) {
            $db_config = require_once 'config/database.php';
            $db = new PDO(
                "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8",
                $db_config['username'],
                $db_config['password']
            );
        }
        $this->db = $db;
        $this->user = new User($db);
        
        // Set PDO to throw exceptions on error
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function login() {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->user->login($username, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Connexion réussie'];
                header('Location: index.php');
                exit;
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nom d\'utilisateur ou mot de passe incorrect'];
            }
        }

        // Set the content and include the login layout
        ob_start();
        require 'views/users/login.php';
        $content = ob_get_clean();
        require 'views/users/login_layout.php';
        exit; // Stop further execution to prevent layout.php from being included
    }

    public function register() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Unauthorized access'];
            header('Location: index.php?controller=user&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'role' => $_POST['role'] ?? 'user'
            ];

            if ($this->user->register($data)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'User registered successfully'];
                header('Location: index.php?controller=user&action=index');
                exit;
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Registration failed'];
            }
        }
        require 'views/users/register.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?controller=user&action=login');
        exit;
    }

    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Unauthorized access'];
            header('Location: index.php?controller=user&action=login');
            exit;
        }

        $users = $this->user->getAll();
        require 'views/users/index.php';
    }

    public function edit($id = null) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Unauthorized access'];
            header('Location: index.php?controller=user&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'role' => $_POST['role'] ?? 'user'
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }

            if ($this->user->update($id, $data)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'User updated successfully'];
                header('Location: index.php?controller=user&action=index');
                exit;
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Update failed'];
            }
        }

        $user = $this->user->getById($id);
        require 'views/users/edit.php';
    }

    public function toggleStatus($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Unauthorized access'];
            header('Location: index.php?controller=user&action=login');
            exit;
        }

        if ($this->user->toggleStatus($id)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'User status updated'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Status update failed'];
        }

        header('Location: index.php?controller=user&action=index');
        exit;
    }
}
