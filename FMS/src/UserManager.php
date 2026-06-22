<?php
class UserManager {
    private $db;

    public function __construct(PDO $databaseConnection) {
        $this->db = $databaseConnection;
    }

    public function getDepartments() {
        $stmt = $this->db->query("SELECT department_id, department_name FROM department ORDER BY department_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers($currentUserId) {
        // Exclude the user currently logged in
        $sql = "SELECT a.user_id, a.firstname, a.lastname, a.username, a.email, a.role, a.is_active, d.department_name, a.last_login
                FROM account a
                LEFT JOIN department d ON a.department_id = d.department_id
                WHERE a.user_id != :current_id
                ORDER BY a.role ASC, a.firstname ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':current_id' => $currentUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        // Prevent duplicate accounts
        $check = $this->db->prepare("SELECT user_id FROM account WHERE username = :username OR email = :email LIMIT 1");
        $check->execute(['username' => $data['username'], 'email' => $data['email']]);
        if ($check->fetch()) {
            return ['status' => 'error', 'message' => 'Username or Email already exists in the system.'];
        }

        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        
        // Admins don't need a department, so we handle NULL values safely
        $deptId = (!empty($data['department_id']) && $data['role'] === 'staff') ? $data['department_id'] : null;

        $sql = "INSERT INTO account (firstname, lastname, username, pasword_hash, email, role, is_active, department_id) 
                VALUES (:firstname, :lastname, :username, :password_hash, :email, :role, 1, :department_id)";
        
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':firstname'     => trim($data['firstname']),
            ':lastname'      => trim($data['lastname']),
            ':username'      => trim($data['username']),
            ':password_hash' => $passwordHash,
            ':email'         => trim($data['email']),
            ':role'          => $data['role'],
            ':department_id' => $deptId
        ]);

        if ($success) {
            return ['status' => 'success', 'message' => 'User account securely generated and assigned.'];
        }
        return ['status' => 'error', 'message' => 'Database failure while creating user.'];
    }
    
    public function updateUser($userId, $data) {
        $sql = "UPDATE account SET firstname = :fn, lastname = :ln, email = :email, 
                role = :role, department_id = :dept_id WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':fn' => $data['firstname'],
            ':ln' => $data['lastname'],
            ':email' => $data['email'],
            ':role' => $data['role'],
            ':dept_id' => ($data['role'] === 'staff' ? $data['department_id'] : null),
            ':id' => $userId
        ]);
    }

    public function deleteUser($userId) {
        // Prevent deleting the currently logged-in admin
        if ($userId == $_SESSION['user_id']) return false;
        
        $stmt = $this->db->prepare("DELETE FROM account WHERE user_id = :id");
        return $stmt->execute([':id' => $userId]);
    }
}