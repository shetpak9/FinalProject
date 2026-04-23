<?php
session_start();
require 'bootstrap.php';

/* =========================
   CHECK SESSION
========================= */
if (!isset($_SESSION['auth_pending'])) {
    die("Session expired. Please login again.");
}

$userId = $_SESSION['auth_pending']['id'];
$role   = $_SESSION['auth_pending']['role'];

$inputCode = $_POST['code'] ?? '';

if (empty($inputCode)) {
    die("MFA code required.");
}

/* =========================
   GET USER MFA SECRET
========================= */
$stmt = $pdo->prepare("SELECT mfa_secret FROM users WHERE id=?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user || !$user['mfa_secret']) {
    header("Location: /FinalProject/controllers/setup-mfa");
    exit;
}

/* =========================
   VERIFY TOTP CODE
   (Google Authenticator style)
========================= */
require_once '../vendor/autoload.php';

use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\Providers\Qr\BaconQrCodeProvider;

$qrProvider = new BaconQrCodeProvider(4, '#ffffff', '#000000', 'svg');
$tfa = new TwoFactorAuth($qrProvider, 'FinalProject');

$isValid = $tfa->verifyCode($user['mfa_secret'], $inputCode);

if (!$isValid) {
    die("Invalid MFA code.");
}

/* =========================
   SUCCESS LOGIN
========================= */
$_SESSION['user_id'] = $userId;
$_SESSION['role'] = $role;

unset($_SESSION['auth_pending']);

/* =========================
   REDIRECT BASED ON ROLE
========================= */
if ($role === 'admin') {
    header("Location: /FinalProject/");
} else {
    header("Location: /FinalProject/");
}

exit;