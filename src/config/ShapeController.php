<?php
class ShapeController{
    public function __construct(private ShapeGateway $gateway)
    {}

    public  function processRequest(string $method, $id = null){
        if(!$id){
            $this->collectionResourceRequest($method);
        } else{
            $this->processResourceRequest($method, $id);
        }
    }
    private function processResourceRequest(string $method, $id){
        $shape = $this->gateway->getById($id);
        
        if(!$shape){
            http_response_code(404);
            echo json_encode(["message" => "Shape not found"]);
            return;
        }

        switch($method){
            case "GET":
                $data = $this->gateway->getById($id);
                echo json_encode($data);
                break;
            case "PUT":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $id = $this->gateway->update($shape, $data);

                echo json_encode(["message" => "Shape updated",
                                 "rows" => $id]);
                break;
            case "DELETE":
                $this->gateway->delete($id);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, PUT, DELETE");
                break;
        }
    }
    private function collectionResourceRequest($method){
        switch($method){
            case "GET":
                $data = $this->gateway->getAll();
                echo json_encode($data);
                break;
            case "POST":
                $data = json_decode(file_get_contents("php://input"), true);
                $this->gateway->create($data);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }
}