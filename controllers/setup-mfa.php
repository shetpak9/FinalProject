<?php
require 'bootstrap.php';
require_once '../vendor/autoload.php';

use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\Providers\Qr\BaconQrCodeProvider;

if (!isset($_SESSION['user_id'])) {
    if (isset($_SESSION['auth_pending'])) {
        $_SESSION['user_id'] = $_SESSION['auth_pending']['id'];
    } else {
        header("Location: /FinalProject/view/login.view.php");
        exit;
    }
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, role, mfa_enabled, mfa_secret FROM users WHERE id=?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

$isAdmin = $user['role'] === 'admin';
$hasEnabled = !empty($user['mfa_enabled']);
$hasSecret = !empty($user['mfa_secret']);

if (!$isAdmin && !($hasEnabled && !$hasSecret)) {
    die("Access denied. Only admins can enable MFA system-wide, or users with MFA enabled can set it up.");
}

$alreadyEnabled = $hasEnabled && $hasSecret;
$secret = $_SESSION['mfa_setup_secret'] ?? null;
$error = '';

$tfa = new TwoFactorAuth(new BaconQrCodeProvider(4, '#ffffff', '#000000', 'svg'), 'FinalProject');

if (!$alreadyEnabled) {
    if (!$secret) {
        $secret = $tfa->createSecret();
        $_SESSION['mfa_setup_secret'] = $secret;
    }
    $qrCode = $tfa->getQRCodeImageAsDataUri("{$user['username']}@FinalProject", $secret, 250);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');

    if (empty($code)) {
        $error = "Please enter the code from your authenticator app.";
    } elseif (!$secret) {
        $error = "Setup secret missing. Refresh the page to start again.";
    } elseif (!$tfa->verifyCode($secret, $code)) {
        $error = "Invalid code. Try again with the latest code from your app.";
    } else {
        if ($isAdmin) {
            // Enable MFA for all users
            $pdo->prepare("UPDATE users SET mfa_enabled = 1 WHERE mfa_enabled = 0")->execute();
        }

        // Set user's secret
        $pdo->prepare("UPDATE users SET mfa_secret = ?, mfa_enabled = 1 WHERE id = ?")
            ->execute([$secret, $userId]);

        // Set session
        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $user['role'];
        unset($_SESSION['auth_pending']);

        unset($_SESSION['mfa_setup_secret']);

        header("Location: /FinalProject/");
        exit;
    }
}

$isSystemWide = $isAdmin;

$css = '<link rel="stylesheet" href="view/css/style.css">';
require '../view/partials/header.php';
require '../view/setup-mfa.view.php';
