<?php
class LocationController{
    public function __construct(private LocationGateway $gateway)
    {}

    public function processRequest(string $method, ?string $id): void {
        if($id) {
            $this->processsResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }   

    private function processsResourceRequest($method, $id){
        $location = $this->gateway->get($id);

        if(!$location) {
            http_response_code(404);
            echo json_encode(["message" => "Location not found"]);
            return;
        }

        switch($method){
            case "GET":
                echo json_encode($location);
                break;
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $rows = $this->gateway->update($location, $data);
                
                // Log the update
                if(session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $this->logAction($_SESSION['user_id'] ?? 0, 'UPDATE', 'location', $id, $data);

                echo json_encode(["message" => "Location updated",
                                 "rows" => $rows]);
                break;
            case "DELETE":
                $this->gateway->delete($id);
                
                // Log the deletion
                if(session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $this->logAction($_SESSION['user_id'] ?? 0, 'DELETE', 'location', $id, ['room' => $location['room'] ?? null]);

                echo json_encode(["message" => "Location deleted"]);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }
    private function processCollectionRequest($method): void {
        switch($method){
            case "GET":
                $keyword = $_GET["keyword"] ?? '';
                $type_id = $_GET["type_id"] ?? '';
                $status_id = $_GET["status_id"] ?? '';
                $floor = $_GET["floor"] ?? '';
                $room = $_GET["room"] ?? '';
                        
                if ($keyword || $type_id || $status_id || $floor || $room) {
                    $data = $this->gateway->search($keyword, $type_id, $status_id, $floor, $room);
                } else {
                    $data = $this->gateway->getAll();
                }           
                echo json_encode($data);
                break;
            case "POST":
                $data = $_POST;
                            
                // Handle image upload
                if(isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
                    $filename = time() . "_" . $_FILES["image"]["name"];
                    $target = __DIR__ . "/../../uploads/" . $filename;
                            
                    move_uploaded_file($_FILES["image"]["tmp_name"], $target);
                            
                    $data["image"] = $filename;
                } else {
                    $data["image"] = null;
                }
                            
                $id = $this->gateway->create($data);
                
                // Log the creation
                if(session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $this->logAction($_SESSION['user_id'] ?? 0, 'CREATE', 'location', $id, [
                    'room' => $data['room'] ?? null,
                    'floor' => $data['floor'] ?? null,
                    'type_id' => $data['type_id'] ?? null
                ]);
                            
                http_response_code(201);
                echo json_encode([
                    "message" => "Location created",
                    "id" => $id
                ]);
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