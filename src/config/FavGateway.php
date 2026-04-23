<?php
class FavGateway{
    private PDO $conn;
    public function __construct(Database $database)
    {
        $this->conn = $database->getconnection();
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