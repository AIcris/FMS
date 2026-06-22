<?php
class Feedback {
    private $db;

    public function __construct(PDO $databaseConnection) {
        $this->db = $databaseConnection;
    }

    public function save($data) {
        $controlNumber = 'CLSU-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
        $sql = "INSERT INTO feedback (
            control_number, client_type, client_classification, transaction_type, 
            sex, age, region_of_residence, service_availed, name_of_office, 
            service_provider_name, service_provider_position, cc1, cc2, cc3, 
            sqd0, sqd1, sqd2, sqd3, sqd4, sqd5, sqd6, sqd7, sqd8, 
            experienced_harassment, harassment_details, would_recommend_clsu, 
            suggestions, email_address, qr_id
        ) VALUES (
            :control_number, :client_type, :client_classification, :transaction_type, 
            :sex, :age, :region_of_residence, :service_availed, :name_of_office, 
            :service_provider_name, :service_provider_position, :cc1, :cc2, :cc3, 
            :sqd0, :sqd1, :sqd2, :sqd3, :sqd4, :sqd5, :sqd6, :sqd7, :sqd8, 
            :experienced_harassment, :harassment_details, :would_recommend_clsu, 
            :suggestions, :email_address, :qr_id
        )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':control_number' => $controlNumber,
            ':client_type' => $data['client_type'] ?? null,
            ':client_classification' => $data['client_classification'] ?? null,
            ':transaction_type' => $data['transaction_type'] ?? null,
            ':sex' => $data['sex'] ?? null,
            ':age' => !empty($data['age']) ? (int)$data['age'] : null,
            ':region_of_residence' => $data['region_residence'] ?? null,
            ':service_availed' => $data['service_availed'] ?? null,
            ':name_of_office' => $data['office_name'] ?? null,
            ':service_provider_name' => !empty($data['provider_name']) ? $data['provider_name'] : null,
            ':service_provider_position' => !empty($data['provider_position']) ? $data['provider_position'] : null,
            ':cc1' => $data['cc1'] ?? null,
            ':cc2' => $data['cc2'] ?? null,
            ':cc3' => $data['cc3'] ?? null,
            ':sqd0' => $data['sqd0'] ?? null,
            ':sqd1' => $data['sqd1'] ?? null,
            ':sqd2' => $data['sqd2'] ?? null,
            ':sqd3' => $data['sqd3'] ?? null,
            ':sqd4' => $data['sqd4'] ?? null,
            ':sqd5' => $data['sqd5'] ?? null,
            ':sqd6' => $data['sqd6'] ?? null,
            ':sqd7' => $data['sqd7'] ?? null,
            ':sqd8' => $data['sqd8'] ?? null,
            ':experienced_harassment' => $data['harassment'] ?? null,
            ':harassment_details' => !empty($data['harassment_details']) ? $data['harassment_details'] : null,
            ':would_recommend_clsu' => $data['recommend_clsu'] ?? null,
            ':suggestions' => !empty($data['suggestions']) ? $data['suggestions'] : null,
            ':email_address' => !empty($data['email_address']) ? $data['email_address'] : null,
            ':qr_id' => !empty($data['qr_id']) ? (int)$data['qr_id'] : null
        ]);
    }
}