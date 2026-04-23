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

                echo json_encode(["message" => "Lost item created",
                                 "id" => $id]);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }
}