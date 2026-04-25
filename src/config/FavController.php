<?php

class FavController{
    public function __construct(private FavGateway $gateway)
    {}

    public function processRequest(string $method): void {
        switch($method){
            case "GET":
                if(session_status() === PHP_SESSION_NONE){
                    session_start();
                }

                $user_id = $_SESSION["user_id"];

                $data = $this->gateway->getUserFav($user_id);

                echo json_encode($data);
                break;
            case "POST":
                session_start();

                $user_id = $_SESSION["user_id"]; // must be logged in
                $data = json_decode(file_get_contents("php://input"), true);

                $location_id = $data["location_id"];

                $this->gateway->create($user_id, $location_id);
                
                // Log the action
                $this->logAction($user_id, 'ADD_FAVORITE', 'favorite', $location_id);

                echo json_encode(["message" => "Added to favorites"]);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }
    
    private function logAction(
        int $userId,
        string $action,
        string $entityType,
        ?int $entityId = null,
        ?array $details = null
    ): void {
        try {
            require_once __DIR__ . '/Database.php';
            require_once __DIR__ . '/LogGateway.php';
            $database = new Database("localhost", "school_map", "root", "");
            $logGateway = new LogGateway($database);
            $logGateway->log($userId, $action, $entityType, $entityId, $details);
        } catch (Exception $e) {
            error_log("Logging failed: " . $e->getMessage());
        }
    }
}