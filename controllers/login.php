<?php
session_start();
require 'bootstrap.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
$stmt->execute([$_POST['username']]);
$user = $stmt->fetch();


if (!$user || !password_verify($_POST['password'], $user['password'])) {
    header("Location: ../view/login.view?error=Invalid");
    exit;
}

if (!$user['email_verified']) {
    header("Location: ../view/login.view?error=Please verify your email first.");
    exit;
}

/* =========================
   STORE PENDING USER (MFA FLOW)
========================= */
$_SESSION['auth_pending'] = [
    'id' => $user['id'],
    'role' => $user['role']
];

/* =========================
   MFA CHECK
========================= */
if ($user['mfa_enabled']) {
    if (!empty($user['mfa_secret'])) {
        header("Location: ../view/mfa.view");
    } else {
        header("Location: ../controllers/setup-mfa");
    }
    exit;
}

/* =========================
   NO MFA → DIRECT LOGIN
========================= */
$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];

unset($_SESSION['auth_pending']);

header("Location: /FinalProject/");
exit;