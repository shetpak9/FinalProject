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

// Check MFA status for current user
$mfaEnabled = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $database->getconnection()->prepare("SELECT mfa_enabled, mfa_secret FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $mfaEnabled = $user && !empty($user['mfa_secret']);
}

/*HTML*/

$css = '<link rel="stylesheet" href="view/css/style.css">';

require 'view/partials/header.php';

require 'view/dashboard.view.php';
?>