<?php
class Auth {
    private $db;

    public function __construct(PDO $databaseConnection) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = $databaseConnection;
    }

    // public function register($firstname, $lastname, $username, $email, $password, $role = 'staff') {
    //     $checkStmt = $this->db->prepare("SELECT user_id FROM account WHERE username = :username OR email = :email LIMIT 1");
    //     $checkStmt->execute(['username' => $username, 'email' => $email]);
    //     if ($checkStmt->fetch()) {
    //         return "Username or Email already exists.";
    //     }

    //     $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    //     $sql = "INSERT INTO account (firstname, lastname, username, pasword_hash, email, role, is_active) 
    //             VALUES (:firstname, :lastname, :username, :password_hash, :email, :role, 1)";
        
    //     $stmt = $this->db->prepare($sql);
    //     return $stmt->execute([
    //         ':firstname'     => $firstname,
    //         ':lastname'      => $lastname,
    //         ':username'      => $username,
    //         ':password_hash' => $passwordHash,
    //         ':email'         => $email,
    //         ':role'          => $role
    //     ]);
    // }

   public function login($identifier, $password) {
        $stmt = $this->db->prepare("SELECT * FROM account WHERE (username = :username_check OR email = :email_check) AND is_active = 1 LIMIT 1");
        $stmt->execute([
            'username_check' => $identifier,
            'email_check' => $identifier
        ]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['pasword_hash'])) {
            $_SESSION['user_id'] = $user['user_id']; 
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['department_id'] = $user['department_id'];

            $updateStmt = $this->db->prepare("UPDATE account SET last_login = NOW() WHERE user_id = :user_id");
            $updateStmt->execute(['user_id' => $user['user_id']]);

            return true;
        }
        return false;
    }

    public function logout() {
        $_SESSION = [];
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