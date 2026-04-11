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

                echo json_encode(["message" => "Location updated",
                                 "rows" => $rows]);
                break;
            case "DELETE":
                $this->gateway->delete($id);

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
                echo json_encode($this->gateway->getAll());
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $id =$this->gateway->create($data);

                http_response_code(201);
                echo json_encode(["message" => "Location created",
                                 "id" => $id]);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }

    }
}