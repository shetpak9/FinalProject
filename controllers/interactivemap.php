<?php
require 'src/display/location_view.php';
require 'src/display/favorite_view.php';
require 'src/display/lost_view.php';

$events = $gateway->getEvents();
$fav = $favgateway->getUserFav($_SESSION['user_id']);
$lost = $lostgateway->getAll();

$css = '<link rel="stylesheet" href="view/css/interactivemap.css">';

if(isset($_POST['delete_fav']) && isset($_POST['fav_id'])){
    $favgateway->deleteFav($_POST['fav_id']);

    // prevent duplicate delete on refresh
    header("Location: /FinalProject/interactivemap");
    exit;
}

require 'view/partials/header.php';

require 'view/interactivemap.view.php';



?>

<script src="js/function.js"></script>
<script src="js/interactivemap.leaflet.map.js"></script>