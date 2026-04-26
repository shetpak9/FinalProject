<?php
class ShapeGateway{
    private PDO $conn;

    public function __construct(Database $database){
        $this->conn = $database->getconnection();
        $this->ensureTableExist();
    }

    private function ensureTableExist(): void{
        $sql = "CREATE TABLE IF NOT EXISTS `map_shapes` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `type` VARCHAR(50) NOT NULL,
                `geojson` JSON NOT NULL,
                `zone_type` VARCHAR(50),
                `floor` INT,
                `description` VARCHAR(255),
                `color` VARCHAR(20),
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                INDEX `idx_floor` (`floor`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        try{
            $this->conn->exec($sql);
        }catch(PDOException $e){
            throw $e;
        }
    }

    public function getAll(): array {
        $sql = "SELECT * FROM map_shapes ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id): array{
        $sql = "SELECT * FROM map_shapes WHERE id=$id";

        $stmt = $this->conn->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($data){
        $sql = "INSERT INTO map_shapes 
                (type, geojson, zone_type, floor, description) 
                VALUES (:type, :geojson, :zone_type, :floor, :description)";

        $stmt = $this->conn->prepare($sql);

        

        $stmt->bindValue(":type", $data['geojson']['type']);
        $stmt->bindValue(":geojson", json_encode($data['geojson']));
        $stmt->bindValue(":zone_type", $data['zone_type']);
        $stmt->bindValue(":floor", $data['floor']);
        $stmt->bindValue(":description", $data['description']);

        $stmt->execute();
    }
    public function update(array $current, array $new){
        $sql = "UPDATE map_shapes SET  
                geojson = :geojson,
                created_at = CURRENT_TIMESTAMP 
                WHERE id=:id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":geojson", json_encode($new['geojson'] ?? $current['geojson']));
        $stmt->bindValue(":id", $current['id']);

        $stmt->execute();

        return $stmt->rowCount();
    }
    public function delete($id){
        $sql = "DELETE FROM map_shapes 
                WHERE id= :id";

        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();
    }
}