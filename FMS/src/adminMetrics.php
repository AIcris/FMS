<?php
class AdminMetrics {
    private $db;

    public function __construct(PDO $databaseConnection) {
        $this->db = $databaseConnection;
    }

    public function getCardTelemetry() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM feedback WHERE qr_id IS NULL");
        $total = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM feedback WHERE qr_id IS NULL AND would_recommend_clsu = 'YES'");
        $recommend = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM feedback WHERE qr_id IS NULL AND experienced_harassment = 'YES'");
        $harassment = $stmt->fetchColumn();

        $rate = $total > 0 ? ($recommend / $total) * 100 : 0;

        return [
            'total' => $total,
            'recommend_rate' => $rate,
            'harassment' => $harassment
        ];
    }

    public function getDetailedRows() {
        $stmt = $this->db->query("SELECT * FROM feedback WHERE qr_id IS NULL ORDER BY submitted_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSqdStackedData() {
        $sqdFields = ['sqd0', 'sqd1', 'sqd2', 'sqd3', 'sqd4', 'sqd5', 'sqd6', 'sqd7', 'sqd8'];
        $data = [];
        foreach ($sqdFields as $field) {
            $data[$field] = ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, 'NA' => 0];
            $stmt = $this->db->query("SELECT $field, COUNT(*) as count FROM feedback WHERE qr_id IS NULL GROUP BY $field");
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
        
        $stmt = $this->db->query("SELECT cc1 as label, COUNT(*) as value FROM feedback WHERE qr_id IS NULL GROUP BY cc1");
        $ccData['cc1'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->query("SELECT cc2 as label, COUNT(*) as value FROM feedback WHERE qr_id IS NULL GROUP BY cc2");
        $ccData['cc2'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->query("SELECT cc3 as label, COUNT(*) as value FROM feedback WHERE qr_id IS NULL GROUP BY cc3");
        $ccData['cc3'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ccData;
    }

    public function getDemographics() {
        $demo = ['client_type' => [], 'client_classification' => [], 'transaction_type' => []];

        $stmt = $this->db->query("SELECT client_type as label, COUNT(*) as value FROM feedback WHERE qr_id IS NULL GROUP BY client_type");
        $demo['client_type'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->query("SELECT client_classification as label, COUNT(*) as value FROM feedback WHERE qr_id IS NULL GROUP BY client_classification");
        $demo['client_classification'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->query("SELECT transaction_type as label, COUNT(*) as value FROM feedback WHERE qr_id IS NULL GROUP BY transaction_type");
        $demo['transaction_type'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $demo;
    }

   public function getOfficeSatisfactionAverages() {
    $stmt = $this->db->query("SELECT name_of_office, AVG(CAST(sqd0 AS UNSIGNED)) as avg_score FROM feedback WHERE qr_id IS NULL AND sqd0 IN ('1','2','3','4','5') GROUP BY name_of_office");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getStaffCount() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM account WHERE role = 'staff'");
        return $stmt->fetchColumn();
    }
}