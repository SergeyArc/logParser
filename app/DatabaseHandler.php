<?php

class DatabaseHandler
{
    private PDO $db;

    public function __construct(
        private string $host,
        private string $dbname,
        private string $username,
        private string $password,
        private string $table
    ) {
        $this->db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insertData(array $data): void
    {
        try {
            $sql = "INSERT INTO $this->table (inv_id, out_sum, signature_value) VALUES (:InvId, :OutSum, :SignatureValue)";
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':InvId', $data['InvId']);
            $stmt->bindParam(':OutSum', $data['OutSum']);
            $stmt->bindParam(':SignatureValue', $data['SignatureValue']);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
        }
    }
}