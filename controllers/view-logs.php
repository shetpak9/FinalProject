<?php
require 'src/config/LogGateway.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /FinalProject/login");
    exit;
}

$userId = $_SESSION['user_id'];

// Get user role
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Only admins can view logs
if (!$user || $user['role'] !== 'admin') {
    header("Location: /FinalProject/");
    exit;
}

// Initialize LogGateway
$logGateway = new LogGateway(new Database("localhost", "school_map", "root", ""));

// Get pagination parameters
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Get filter parameters
$actionFilter = $_GET['action'] ?? null;
$entityTypeFilter = $_GET['entity_type'] ?? null;

// Get logs
$logs = $logGateway->getAllLogs($limit, $offset, $actionFilter, $entityTypeFilter);
$totalLogs = $logGateway->getLogCount($actionFilter, $entityTypeFilter);
$totalPages = ceil($totalLogs / $limit);

// Get unique actions and entity types for filtering
$allLogsForFilters = $logGateway->getAllLogs(1000, 0);
$actions = array_unique(array_column($allLogsForFilters, 'action'));
$entityTypes = array_unique(array_column($allLogsForFilters, 'entity_type'));

$css = '<link rel="stylesheet" href="view/css/logs.css">';

require 'view/partials/header.php';
require 'view/logs.view.php';
?>
