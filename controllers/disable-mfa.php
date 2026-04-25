<?php
require 'bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /FinalProject/view/login.view.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Check if user has MFA enabled and get their role
$stmt = $pdo->prepare("SELECT mfa_secret, role FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user || empty($user['mfa_secret'])) {
    header("Location: /FinalProject/");
    exit;
}

$isAdmin = $user['role'] === 'admin';

// If admin, disable MFA for all users
if ($isAdmin) {
    $pdo->prepare("UPDATE users SET mfa_enabled = 0")->execute();
} else {
    // Otherwise, disable MFA only for the current user
    $pdo->prepare("UPDATE users SET mfa_enabled = 0 WHERE id = ?")
        ->execute([$userId]);
}

// Redirect back to dashboard
header("Location: /FinalProject/");
exit;
?>
