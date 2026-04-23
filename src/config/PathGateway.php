<?php
class PathGateway{
    private PDO $conn;
    public function __construct(Database $database)
    {
        $this->conn = $database->getconnection();
    }

    public function getAll(): array{
        $sql = "SELECT * FROM path";

        $stmt = $this->conn->query($sql);

        $data = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
        
        return $data;
    }
    public function create($data): string {
    
        $sql = "INSERT INTO path (start_location_id, end_location_id, distance, coordinates)
                VALUES (:start, :end, :distance, :coordinates)";
    
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bindValue(":start", $data["start_location_id"], PDO::PARAM_INT);
        $stmt->bindValue(":end", $data["end_location_id"], PDO::PARAM_INT);
        $stmt->bindValue(":distance", $data["distance"], PDO::PARAM_INT);
        $stmt->bindValue(":coordinates", json_encode($data["coordinates"]), PDO::PARAM_STR);
    
        $stmt->execute();
    
        return $this->conn->lastInsertId();
    }
}