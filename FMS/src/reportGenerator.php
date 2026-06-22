<?php
class ReportGenerator {
    private $db;

    public function __construct(PDO $databaseConnection) {
        $this->db = $databaseConnection;
    }

    public function generateMonthlyDraft($departmentId, $userId, $month, $year) {
        $monthNum = date('m', strtotime($month));
        
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM feedback f 
            JOIN qr_code q ON f.qr_id = q.qr_id 
            WHERE q.department_id = ? 
            AND MONTH(f.submitted_at) = ? 
            AND YEAR(f.submitted_at) = ?
        ");
        $stmt->execute([$departmentId, $monthNum, $year]);
        $totalResponses = $stmt->fetchColumn();

        $summaryData = json_encode([
            'total_responses' => $totalResponses,
            'report_type' => 'Monthly Departmental CS Measurement',
            'status_log' => 'Draft initialized by Focal Person'
        ]);

        $sql = "INSERT INTO report (month, year, summary, status, department_id, user_id) 
                VALUES (?, ?, ?, 'Draft', ?, ?)";
        $insertStmt = $this->db->prepare($sql);
        
        return $insertStmt->execute([$month, $year, $summaryData, $departmentId, $userId]);
    }

    public function getStaffReports($departmentId, $userId) {
        $stmt = $this->db->prepare("
            SELECT * FROM report 
            WHERE department_id = ? AND user_id = ? 
            ORDER BY generated_at DESC
        ");
        $stmt->execute([$departmentId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}