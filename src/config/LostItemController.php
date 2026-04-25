<?php

class LostItemController{
    public function __construct(private LostItemGateway $gateway)
    {}

    public function processRequest(string $method, $id = null): void {
        if($id){
            $this->processResourceRequest($method, $id);
        } else {
           $this->collectionResourceRequest($method);
        }
    }
    private function processResourceRequest(string $method, $id){
        $item = $this->gateway->getId($id);

        if(!$item){
            http_response_code(404);
            echo json_encode(["message" => "Item not found"]);
            return;
        }
        switch($method){
            case "GET":
                echo json_encode($item);
                break;
        }
    }
    private function collectionResourceRequest(string $method){
        switch($method){
            case "GET":
                $data = $this->gateway->getAll();
                echo json_encode($data);
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $id = $this->gateway->create($data);
                
                // Log the action
                if(session_status() === PHP_SESSION_NONE){
                    session_start();
                }
                $this->logAction(
                    $_SESSION['user_id'] ?? 0,
                    'REPORT_ITEM',
                    'lost_item',
                    $id,
                    [
                        'item_type' => $data['item_type'] ?? null,
                        'location' => $data['location'] ?? null
                    ]
                );

                echo json_encode(["message" => "Lost item created",
                                 "id" => $id]);
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