<?php
class LocationGateway{
    private PDO $conn;
    public function __construct(Database $database)
    {
        $this->conn = $database->getconnection();
    }

    public function getAll(): array {
        $sql = "SELECT * FROM location";

        $stmt = $this->conn->query($sql);

        $data = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function create($data): string{
        $sql = "INSERT INTO location (room, latitude, longtitude, type_id, floor, description, capacity) " .
               "VALUES (:room, :latitude, :longitude, :type_id, :floor, :description, :capacity)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":room", $data["room"], PDO::PARAM_STR);
        $stmt->bindValue(":latitude", $data["latitude"]);
        $stmt->bindValue(":longitude", $data["longitude"]);
        $stmt->bindValue(":type_id", $data["type_id"], PDO::PARAM_INT);
        $stmt->bindValue(":floor", $data["floor"], PDO::PARAM_INT);
        $stmt->bindValue(":description", $data["description"] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(":capacity", $data["capacity"], PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get($id): array{
        $sql = "SELECT * FROM location " .
               "WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    public function update(array $current, array $new): int{
        $sql = "UPDATE location SET " .
               "room = :room, " .
               "latitude = :latitude, " .
               "longtitude = :longitude, " .
               "type_id = :type_id, " .
               "floor = :floor, " .
               "description = :description, " .
               "capacity = :capacity, " .
               "status = :status " .
               "WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":room", $new["room"] ?? $current["room"], PDO::PARAM_STR);
        $stmt->bindValue(":latitude", $new["latitude"] ?? $current["latitude"]);
        $stmt->bindValue(":longitude", $new["longitude"] ?? $current["longitude"]);
        $stmt->bindValue(":type_id", $new["type_id"] ?? $current["type_id"], PDO::PARAM_INT);
        $stmt->bindValue(":floor", $new["floor"] ?? $current["floor"], PDO::PARAM_INT);
        $stmt->bindValue(":description", $new["description"] ?? $current["description"], PDO::PARAM_STR);
        $stmt->bindValue(":capacity", $new["capacity"] ?? $current["capacity"], PDO::PARAM_INT);
        $stmt->bindValue(":status", $new["status"] ?? $current["status"], PDO::PARAM_STR);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete($id){
        $sql = "DELETE FROM location " .
               "WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function getFloors(): array{
        $sql = "SELECT room, floor FROM location ".
               "ORDER BY floor";
        $stmt = $this->conn->query($sql);

        $data = [];

        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $floor = $row["floor"];
            $room = $row["room"];

            $data[$floor][] = $room;
        }
        return $data;
    }

    public function getTypes(): array{
        $sql = "SELECT * FROM location_type";
        $stmt = $this->conn->query($sql);

        $data = [];
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $id = $row["id"];
            $name = $row["name"];

            $data[$id] = $name;
        }

        return $data; 
    }
}