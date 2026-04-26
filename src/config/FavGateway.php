<?php
class FavGateway{
    private PDO $conn;
    public function __construct(Database $database)
    {
        $this->conn = $database->getconnection();
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void{
        $sql = "CREATE TABLE IF NOT EXISTS `favorites` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `user_id` INT NOT NULL,
                `location_id` INT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_user_location` (`user_id`, `location_id`),
                INDEX `idx_location_id` (`location_id`),
                CONSTRAINT `fk_fav_user`
                    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
                    ON DELETE CASCADE,
                CONSTRAINT `fk_fav_location`
                    FOREIGN KEY (`location_id`) REFERENCES `location`(`id`)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        try{
            $this->conn->exec($sql);
        }catch(PDOException $e){
            throw $e;
        }
    }

    public function getUserFav($user_id): array {
        $sql = "SELECT 
                f.id AS favorite_id,
                f.created_at,
                l.*, 
                st.status_type,
                lt.name AS loc_type
            FROM favorites f
            JOIN location l ON f.location_id = l.id
            JOIN status_type st ON l.status_id = st.id
            JOIN location_type lt ON l.type_id = lt.id
            WHERE f.user_id = :user_id
            ORDER BY f.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function create($user_id, $location_id){
        $sql = "INSERT INTO favorites (user_id, location_id)
            VALUES (:user_id, :location_id)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":location_id", $location_id, PDO::PARAM_INT);
    
        $stmt->execute();
    }
    public function deleteFav($favorite_id){
        $sql = "DELETE FROM favorites
                WHERE id= :favorite_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":favorite_id", $favorite_id,PDO::PARAM_INT);
        $stmt->execute();

    }
}