<?php
class ShapeGateway{
    private PDO $conn;

    public function __construct(Database $database){
        $this->conn = $database->getconnection();
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