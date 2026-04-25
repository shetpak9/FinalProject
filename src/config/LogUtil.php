<?php

/**
 * Log utility functions for tracking user and admin actions
 */

function logAction(
    $pdo,
    int $userId,
    string $action,
    string $entityType,
    ?int $entityId = null,
    ?array $details = null
): void {
    try {
        require_once __DIR__ . '/LogGateway.php';
        $database = new Database("localhost", "school_map", "root", "");
        $logGateway = new LogGateway($database);
        $logGateway->log($userId, $action, $entityType, $entityId, $details);
    } catch (Exception $e) {
        // Silently fail logging to avoid disrupting main functionality
        error_log("Logging failed: " . $e->getMessage());
    }
}

?>
