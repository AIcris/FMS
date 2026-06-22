<?php
class DepartmentManager {
    private $db;

    public function __construct(PDO $databaseConnection) {
        $this->db = $databaseConnection;
    }

    public function getAllDepartments() {
        $stmt = $this->db->query("SELECT * FROM department ORDER BY department_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDepartmentById($id) {
        $stmt = $this->db->prepare("SELECT * FROM department WHERE department_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   // In src/DepartmentManager.php

public function createDepartment($name, $desc) {
    $stmt = $this->db->prepare("INSERT INTO department (department_name, description) VALUES (?, ?)");
    return $stmt->execute([$name, $desc]);
}

public function updateDepartment($id, $name, $desc) {
    $stmt = $this->db->prepare("UPDATE department SET department_name = ?, description = ? WHERE department_id = ?");
    return $stmt->execute([$name, $desc, $id]);
}
public function deleteDepartment($id) {
    // Optional: Add a check here to ensure no staff are assigned to this dept
    // before deleting, to maintain database integrity.
    $stmt = $this->db->prepare("DELETE FROM department WHERE department_id = ?");
    return $stmt->execute([$id]);
}
}