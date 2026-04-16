<?php 
require 'src/display/location_view.php';

$data = $gateway->getAll();

$locations = count($data);

$available = count(array_filter($data, fn($row) => $row['status_id'] == 1));

$capacity = array_sum(array_column($data, 'capacity'));

$floor = $gateway->getFloors();

$types = $gateway->getTypes();
$classroomCount = count(array_filter($data, fn($row) => $row['type_id'] == 1));
$labCount = count(array_filter($data, fn($row) => $row['type_id'] == 2));
$officeCount = count(array_filter($data, fn($row) => $row['type_id'] == 3));
$otherCount = count(array_filter($data, fn($row) => $row['type_id'] == 4));

$announcements = $gateway->getAnnouncements();

/*HTML*/

$css = '<link rel="stylesheet" href="view/css/style.css">';

require 'view/partials/header.php';

require 'view/dashboard.view.php';
?>
<script src="js/function.js"></script>
<script src="js/dashboard.leaflet.map.js"></script>