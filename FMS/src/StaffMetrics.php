<?php
class StaffMetrics {
    private $db;
    private $departmentId;

    public function __construct(PDO $databaseConnection, $departmentId) {
        $this->db = $databaseConnection;
        $this->departmentId = (int)$departmentId;
    }

    public function getDepartmentName() {
        $stmt = $this->db->prepare("SELECT department_name FROM department WHERE department_id = ?");
        $stmt->execute([$this->departmentId]);
        return $stmt->fetchColumn() ?: 'Unknown Department';
    }

    public function getPendingTelemetry() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM feedback f JOIN qr_code q ON f.qr_id = q.qr_id WHERE q.department_id = ?");
        $stmt->execute([$this->departmentId]);
        $total = $stmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM feedback f JOIN qr_code q ON f.qr_id = q.qr_id WHERE q.department_id = ? AND f.experienced_harassment = 'YES'");
        $stmt->execute([$this->departmentId]);
        $harassment = $stmt->fetchColumn();

        return [
            'total_pending' => $total,
            'harassment_alerts' => $harassment
        ];
    }

    public function getPendingFeedbackRows() {
        $stmt = $this->db->prepare("SELECT f.* FROM feedback f JOIN qr_code q ON f.qr_id = q.qr_id WHERE q.department_id = ? ORDER BY f.submitted_at DESC");
        $stmt->execute([$this->departmentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSqdStackedData() {
        $sqdFields = ['sqd0', 'sqd1', 'sqd2', 'sqd3', 'sqd4', 'sqd5', 'sqd6', 'sqd7', 'sqd8'];
        $data = [];
        foreach ($sqdFields as $field) {
            $data[$field] = ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, 'NA' => 0];
            $stmt = $this->db->prepare("SELECT $field, COUNT(*) as count FROM feedback f JOIN qr_code q ON f.qr_id = q.qr_id WHERE q.department_id = ? GROUP BY $field");
            $stmt->execute([$this->departmentId]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $val = $row[$field];
                if (isset($data[$field][$val])) {
                    $data[$field][$val] = (int)$row['count'];
                }
            }
        }
        return $data;
    }

    public function getCcBreakdown() {
        $ccData = ['cc1' => [], 'cc2' => [], 'cc3' => []];
        
        $stmt = $this->db->prepare("SELECT cc1 as label, COUNT(*) as value FROM feedback f JOIN qr_code q ON f.qr_id = q.qr_id WHERE q.department_id = ? GROUP BY cc1");
        $stmt->execute([$this->departmentId]);
        $ccData['cc1'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("SELECT cc2 as label, COUNT(*) as value FROM feedback f JOIN qr_code q ON f.qr_id = q.qr_id WHERE q.department_id = ? GROUP BY cc2");
        $stmt->execute([$this->departmentId]);
        $ccData['cc2'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("SELECT cc3 as label, COUNT(*) as value FROM feedback f JOIN qr_code q ON f.qr_id = q.qr_id WHERE q.department_id = ? GROUP BY cc3");
        $stmt->execute([$this->departmentId]);
        $ccData['cc3'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ccData;
    }

    public function getDemographics() {
        $demo = ['client_type' => [], 'client_classification' => [], 'transaction_type' => []];

        $stmt = $this->db->prepare("SELECT client_type as label, COUNT(*) as value FROM feedback f JOIN qr_code q ON f.qr_id = q.qr_id WHERE q.department_id = ? GROUP BY client_type");
        $stmt->execute([$this->departmentId]);
        $demo['client_type'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("SELECT client_classification as label, COUNT(*) as value FROM feedback f JOIN qr_code q ON f.qr_id = q.qr_id WHERE q.department_id = ? GROUP BY client_classification");
        $stmt->execute([$this->departmentId]);
        $demo['client_classification'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("SELECT transaction_type as label, COUNT(*) as value FROM feedback f JOIN qr_code q ON f.qr_id = q.qr_id WHERE q.department_id = ? GROUP BY transaction_type");
        $stmt->execute([$this->departmentId]);
        $demo['transaction_type'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $demo;
    }
}