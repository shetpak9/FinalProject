<?php
class LostItemGateway{
    private PDO $conn;
    public function __construct(Database $database)
    {
        $this->conn = $database->getconnection();
        $this->ensureTableExist();
    }

    private function ensureTableExist(): void{
        $sql = "CREATE TABLE IF NOT EXISTS `lost_item` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(100) NOT NULL,
                `description` TEXT,
                `latitude` DOUBLE NOT NULL,
                `longitude` DOUBLE NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `item_state` ENUM('Lost Item','Item Found') NOT NULL,
                PRIMARY KEY (`id`),
                INDEX `idx_coordinates` (`latitude`, `longitude`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        try{
            $this->conn->exec($sql);
        }catch(PDOException $e){
            throw $e;
        }
    }

    public function getAll(): array {
        $sql = "SELECT * FROM lost_item";

        $stmt = $this->conn->query($sql);

        $data = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
        
        return $data;
    }
    public function create($data) {
        $sql = "INSERT INTO lost_item (name, description, latitude, longitude, item_state) " .
               "VALUES (:name, :description, :latitude, :longitude, :item_state)";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":description", $data["description"] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(":latitude", $data["latitude"], PDO::PARAM_STR);
        $stmt->bindValue(":longitude", $data["longitude"], PDO::PARAM_STR);
        $stmt->bindValue(":item_state", $data['item_state'], PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }
    public function getId($id){
        $sql = "SELECT * FROM lost_item " .
               "WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

}