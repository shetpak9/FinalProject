<?php
class LocationGateway{
    private PDO $conn;
    public function __construct(Database $database)
    {
        $this->conn = $database->getconnection();
        $this->ensureTableExist();
    }

    private function ensureTableExist(): void{
        $sql = "CREATE TABLE IF NOT EXISTS location (
                id INT AUTO_INCREMENT PRIMARY KEY,
                room VARCHAR(45) NOT NULL,
                latitude DOUBLE NOT NULL,
                longitude DOUBLE NOT NULL,
                type_id INT,
                floor INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                status_id INT DEFAULT 3,
                description VARCHAR(255),
                capacity INT,
                image VARCHAR(255),
                UNIQUE KEY lat_lng_unique (latitude, longitude)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        try {
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            // Table might already exist
        }

        $sql = "CREATE TABLE IF NOT EXISTS `location_type` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(45) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        try {
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            // Table might already exist
        }

        $sql = "CREATE TABLE IF NOT EXISTS `status_type` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `status_type` VARCHAR(45) NOT NULL,
                UNIQUE KEY `unique_status` (`status_type`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        try {
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            // Table might already exist
        }

        $sql = "CREATE TABLE IF NOT EXISTS `event` (
                `event_id` INT NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(255) NOT NULL,
                `event_status` VARCHAR(45) NOT NULL DEFAULT 'Upcoming',
                `description` VARCHAR(255) DEFAULT NULL,
                `location_id` INT NOT NULL,
                `time` DATETIME NOT NULL,
                `organizer` VARCHAR(45) NOT NULL,
                PRIMARY KEY (`event_id`),
                INDEX `fk_event_location` (`location_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        try {
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            // Table might already exist
        }

        $sql = "CREATE TABLE IF NOT EXISTS `announcement` (
                `idannouncement` INT NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(100) NOT NULL,
                `announcement_type` VARCHAR(50) NOT NULL,
                `description` TEXT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`idannouncement`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        try {
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            // Table might already exist
        }
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
        $sql = "INSERT INTO location (room, latitude, longitude, type_id, floor, description, capacity, image) " .
               "VALUES (:room, :latitude, :longitude, :type_id, :floor, :description, :capacity, :image)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":room", $data["room"], PDO::PARAM_STR);
        $stmt->bindValue(":latitude", $data["latitude"]);
        $stmt->bindValue(":longitude", $data["longitude"]);
        $stmt->bindValue(":type_id", $data["type_id"], PDO::PARAM_INT);
        $stmt->bindValue(":floor", $data["floor"], PDO::PARAM_INT);
        $stmt->bindValue(":description", $data["description"] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(":capacity", $data["capacity"], PDO::PARAM_INT);
        $stmt->bindValue(":image", $data["image"] ?? null, PDO::PARAM_STR);

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
               "longitude = :longitude, " .
               "type_id = :type_id, " .
               "floor = :floor, " .
               "description = :description, " .
               "capacity = :capacity, " .
               "status_id = :status, " .
               "image = :image ". 
               "WHERE id = :id";

        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":room", $new["room"] ?? $current["room"], PDO::PARAM_STR);
        $stmt->bindValue(":latitude", $new["latitude"] ?? $current["latitude"]);
        $stmt->bindValue(":longitude", $new["longitude"] ?? $current["longitude"]);
        $stmt->bindValue(":type_id", $new["type_id"] ?? $current["type_id"], PDO::PARAM_INT);
        $stmt->bindValue(":floor", $new["floor"] ?? $current["floor"], PDO::PARAM_INT);
        $stmt->bindValue(":description", $new["description"] ?? $current["description"], PDO::PARAM_STR);
        $stmt->bindValue(":capacity", $new["capacity"] ?? $current["capacity"], PDO::PARAM_INT);
        $stmt->bindValue(":status", $new["status_id"] ?? $current["status_id"], PDO::PARAM_STR);
        $stmt->bindValue(":image", $new["image"] ?? $current["image"], PDO::PARAM_STR);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete($id){
        $sql = "SELECT image FROM location WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row && !empty($row['image'])){
            $path = dirname(__DIR__) . "/uploads/" . $row['image'];

            if(file_exists($path)){
                unlink($path);
            }
        }

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

    public function getLocDetails(): array{
        $sql = 'SELECT location.id, location.room, location.floor, location.capacity, location_type.name, status_type.status_type FROM location '.
        'JOIN location_type ON location.type_id = location_type.id '.
        'JOIN status_type ON location.status_id = status_type.id';
        $stmt = $this->conn->query($sql);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function search($keyword = '', $type_id = '', $status_id = '', $floor = '', $room = ''): array{
        $sql = "SELECT * FROM location " .
               "WHERE room LIKE :keyword ";

        if (!empty($type_id)) {
            $sql .= "AND type_id = :type_id ";
        }
        if (!empty($status_id)) {
            $sql .= "AND status_id = :status_id ";
        }
        if (!empty($floor)) {
            $sql .= "AND floor = :floor ";
        }
        if (!empty($room)) {
            $sql .= "AND room = :room ";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":keyword", "%$keyword%", PDO::PARAM_STR);
        if (!empty($type_id)) {
            $stmt->bindValue(":type_id", $type_id, PDO::PARAM_INT);
        }
        if (!empty($status_id)) {
            $stmt->bindValue(":status_id", $status_id, PDO::PARAM_INT);
        }
        if (!empty($floor)) {
            $stmt->bindValue(":floor", $floor, PDO::PARAM_INT);
        }
        if (!empty($room)) {
            $stmt->bindValue(":room", $room, PDO::PARAM_STR);
        }
        $stmt->execute();

        $data = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
        
        return $data;
    }
    public function addEvent($data){
        $sql = "INSERT INTO event (title, description, location_id, time, organizer) " .
               "VALUES (:title, :description, :location_id, :time, :organizer)";

        $stmt = $this->conn->prepare($sql);

        $dt = new DateTime($data["time"]);
        $formatted = $dt->format("Y-m-d H:i:s");

        $stmt->bindValue(":title", $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(":description", $data["description"] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(":location_id", $data["location_id"], PDO::PARAM_INT);
        $stmt->bindValue(":time", $formatted, PDO::PARAM_STR);
        $stmt->bindValue(":organizer", $data["organizer"], PDO::PARAM_STR);

        $stmt->execute();
    }
    public function getEvents(): array{
        $sql = "SELECT event.*, location.room, location.floor FROM event " .
               "JOIN location ON event.location_id = location.id " .
               "ORDER BY time DESC";
        $stmt = $this->conn->query($sql);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    public function addAnnouncement($data){
        $sql = "INSERT INTO announcement (title, announcement_type, description) " .
               "VALUES (:title, :announcement_type, :description)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":title", $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(":announcement_type", $data["announcement_type"], PDO::PARAM_STR);
        $stmt->bindValue(":description", $data["description"] ?? null, PDO::PARAM_STR);

        $stmt->execute();
    }
    public function getAnnouncements(): array{
        $sql = "SELECT * FROM announcement ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
}