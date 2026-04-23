<?php
class PathController{
    public function __construct(private PathGateway $gateway)  
    {}

    public function processRequest($method, ?string $id): void {
        if($id) {

        } else {
            $this->processCollectionRequest($method);
        }
    }

    private function processCollectionRequest($method): void{
        switch($method){
            case "GET":
                if (isset($_GET['start'], $_GET['end'])) {

                    $start = $_GET['start'];
                    $end = $_GET['end'];

                    $paths = $this->gateway->getAll(); // edges
                    $graph = $this->buildGraph($paths);

                    $nodePath = $this->dijkstra($graph, $start, $end);

                    $coords = $this->buildFullPath($nodePath, $paths);

                    echo json_encode($coords);
                    return;
                }

                $data = $this->gateway->getAll();
                echo json_encode($data);
                break;
            case "POST":
                $data  = json_decode(file_get_contents("php://input"),  true);

                $id = $this->gateway->create($data);

                echo json_encode(
                    ["message" => "Path Created",
                    "id" => $id]
                );
                break;
        }
    }
    private function buildGraph($paths){
        $graph = [];

        foreach ($paths as $p) {
            $start = $p['start_location_id'];
            $end = $p['end_location_id'];
            $weight = $p['distance'];

            $graph[$start][] = ["node" => $end, "weight" => $weight];
            $graph[$end][] = ["node" => $start, "weight" => $weight];
        }

        return $graph;
    }
    private function dijkstra($graph, $start, $end){
        $distances = [];
        $prev = [];
        $visited = [];
                
        foreach ($graph as $node => $edges) {
            $distances[$node] = INF;
        }
                
        $distances[$start] = 0;
                
        while (true) {
            $closest = null;
                
            foreach ($distances as $node => $dist) {
                if (!isset($visited[$node]) &&
                    ($closest === null || $dist < $distances[$closest])) {
                    $closest = $node;
                }
            }
                
            if ($closest === null || $closest == $end) break;
                
            $visited[$closest] = true;
                
            foreach ($graph[$closest] as $neighbor) {
                $newDist = $distances[$closest] + $neighbor['weight'];
                
                if ($newDist < $distances[$neighbor['node']]) {
                    $distances[$neighbor['node']] = $newDist;
                    $prev[$neighbor['node']] = $closest;
                }
            }
        }
                
        // reconstruct path
        $path = [];
        $curr = $end;
                
        while (isset($curr)) {
            array_unshift($path, $curr);
            if (!isset($prev[$curr])) break;
            $curr = $prev[$curr];
        }
                
        return $path;
    }
    private function buildFullPath($nodePath, $allPaths){
        $fullCoords = [];

        for ($i = 0; $i < count($nodePath) - 1; $i++) {

            $from = $nodePath[$i];
            $to = $nodePath[$i + 1];

            $segment = null;

            foreach ($allPaths as $p) {
                if (
                    ($p['start_location_id'] == $from && $p['end_location_id'] == $to) ||
                    ($p['start_location_id'] == $to && $p['end_location_id'] == $from)
                ) {
                    $segment = $p;
                    break;
                }
            }

            if ($segment) {
                $coords = json_decode($segment['coordinates'], true);

                /*if ($i > 0) {
                    array_shift($coords);
                }*/

                $fullCoords = array_merge($fullCoords, $coords);
            }
        }

        return $fullCoords;
    }
}