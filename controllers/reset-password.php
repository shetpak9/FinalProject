<?php
require 'bootstrap.php';

$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token=?");
$stmt->execute([$_GET['token']]);
$data = $stmt->fetch();

if (!$data || strtotime($data['expires_at']) < time()) {
    die("Invalid or expired token");
}

$userId = $data['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['reset_requested']) && time() - $_SESSION['reset_requested'] < 60) {
        die("Please wait before requesting again.");
    }

    $_SESSION['reset_requested'] = time();

    $newPass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $pdo->prepare("UPDATE users SET password=? WHERE id=?")
        ->execute([$newPass, $userId]);

    $pdo->prepare("DELETE FROM password_resets WHERE token=?")
        ->execute([$_GET['token']]);

    header("Location: ../view/login.view.php");
    exit;
}
?>