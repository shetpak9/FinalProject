<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="view/css/reset.css">
    <link rel="stylesheet" href="view/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="login-box">

        <!-- LEFT SIDE -->
        <div class="login-form">
            <h1>RESET PASSWORD</h1>
            <form method="POST" action="/FinalProject/controllers/reset-password?token=<?php echo htmlspecialchars($_GET['token']); ?>">
                <label>Enter New Password</label>
                <input type="password" name="password" placeholder="New password" required>

                <button>Update Password</button>
            </form>
        </div>

        <!-- RIGHT SIDE -->
        <div class="logo-section">
            <img src="view/images/Copilot_20260424_221814-removebg-preview.png" alt="">
        </div>

    </div>
</div>
</body>
</html>