<?php
session_start();
require 'bootstrap.php';

ensureTableExist($pdo);

$stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
$stmt->execute([$_POST['username']]);
$user = $stmt->fetch();


if (!$user || !password_verify($_POST['password'], $user['password'])) {
    $error = true;
    header("Location: /FinalProject/login?error=invalid");
    exit;
}

if (!$user['email_verified']) {
    header("Location: /FinalProject/login?error=verify");
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
        header("Location: /FinalProject/mfa");
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

function ensureTableExist(PDO $pdo): void{
    $sql = "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `name` VARCHAR(100) DEFAULT NULL,
            `role` ENUM('admin','student') DEFAULT 'student',
            `email_verified` TINYINT(1) DEFAULT 0,
            `mfa_secret` VARCHAR(255) DEFAULT NULL,
            `mfa_enabled` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_username` (`username`),
            UNIQUE KEY `unique_email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    try{
        $pdo->exec($sql);
    }catch(PDOException $e){
        throw $e;
    }

    $sql = "CREATE TABLE IF NOT EXISTS `password_resets` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `user_id` INT NOT NULL,
            `token` VARCHAR(255) NOT NULL,
            `expires_at` DATETIME NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_token` (`token`),
            INDEX `idx_user_id` (`user_id`),
            CONSTRAINT `fk_password_resets_user`
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    try{
        $pdo->exec($sql);
    }catch(PDOException $e){
        throw $e;
    }

    $sql = "CREATE TABLE IF NOT EXISTS `password_resets` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `user_id` INT NOT NULL,
            `token` VARCHAR(255) NOT NULL,
            `expires_at` DATETIME NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_token` (`token`),
            INDEX `idx_user_id` (`user_id`),
            CONSTRAINT `fk_password_resets_user`
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    try{
        $pdo->exec($sql);
    }catch(PDOException $e){
        throw $e;
    }

    $sql = "CREATE TABLE IF NOT EXISTS `backup_codes` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `user_id` INT NOT NULL,
            `code_hash` VARCHAR(255) NOT NULL,
            `used` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `used_at` DATETIME DEFAULT NULL,
            PRIMARY KEY (`id`),
            INDEX `idx_user_id` (`user_id`),
            CONSTRAINT `fk_backup_codes_user`
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    try{
        $pdo->exec($sql);
    }catch(PDOException $e){
        throw $e;
    }
}