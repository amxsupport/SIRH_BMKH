<?php
class User {
    private $db;
    private $table = 'users';

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
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    public function register($data) {
        $query = "INSERT INTO {$this->table} (username, email, password_hash, first_name, last_name, role) 
                 VALUES (:username, :email, :password_hash, :first_name, :last_name, :role)";
        
        $stmt = $this->db->prepare($query);
        
        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password_hash' => $this->hashPassword($data['password']),
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':role' => isset($data['role']) ? $data['role'] : 'user'
        ];
        
        return $stmt->execute($params);
    }

    public function login($username, $password) {
        try {
            error_log("Starting login process for username: " . $username);

            // Verify database connection
            if (!$this->db) {
                error_log("Database connection is null");
                return false;
            }

            // First check if the username exists without the active check
            $query = "SELECT * FROM {$this->table} WHERE username = :username";
            error_log("Executing query: " . $query);
            
            $stmt = $this->db->prepare($query);
            if (!$stmt) {
                error_log("Failed to prepare statement: " . json_encode($this->db->errorInfo()));
                return false;
            }

            $stmt->execute([':username' => $username]);
            if (!$stmt) {
                error_log("Failed to execute statement: " . json_encode($stmt->errorInfo()));
                return false;
            }

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Query result: " . ($user ? "User found" : "No user found"));
            
            if ($user) {
                error_log("User details: " . json_encode(array_intersect_key($user, array_flip(['username', 'role', 'is_active']))));
            }
            
            if (!$user) {
                error_log("No user found with username: " . $username);
                return false;
            }

            // If user is not active, return special message
            if (!$user['is_active']) {
                error_log("User account is disabled: " . $username);
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Votre compte est désactivé'];
                return false;
            }

            error_log("User found, verifying password for: " . $username);
            if (password_verify($password, $user['password_hash'])) {
                error_log("Password verified successfully for: " . $username);
                $this->updateLastLogin($user['user_id']);
                return $user;
            }
            
            error_log("Password verification failed for: " . $username);
            return false;
        } catch (PDOException $e) {
            error_log("Database error during login: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error during login: " . $e->getMessage());
            return false;
        }
    }

    public function updateLastLogin($userId) {
        $query = "UPDATE {$this->table} SET last_login = CURRENT_TIMESTAMP WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':user_id' => $userId]);
    }

    public function getAll() {
        $query = "SELECT user_id, username, email, role, first_name, last_name, is_active, created_at, last_login 
                 FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT user_id, username, email, role, first_name, last_name, is_active, created_at, last_login 
                 FROM {$this->table} WHERE user_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $updateFields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if ($key !== 'user_id' && $key !== 'password') {
                $updateFields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }

        if (isset($data['password'])) {
            $updateFields[] = "password_hash = :password_hash";
            $params[':password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $updateFields) . " WHERE user_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    public function toggleStatus($id) {
        $query = "UPDATE {$this->table} SET is_active = NOT is_active WHERE user_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
