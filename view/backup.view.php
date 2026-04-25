<!DOCTYPE html>
<html>
<head>
    <title>Backup Code</title>
    <link rel="stylesheet" href="view/css/reset.css">
    <link rel="stylesheet" href="view/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<div class="container">
    <div class="login-box">

        <!-- LEFT SIDE -->
        <div class="login-form">
            <h1>BACKUP LOGIN</h1>
            <form method="POST" action="/FinalProject/controllers/verify-backup.php">
                <label>Enter Backup Code</label>
                <input type="text" name="code" placeholder="Backup code" required>

                <button>Login</button>
            </form>
            <p class="register-text">
                <a href="mfa">Back to MFA</a><br>
            </p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="logo-section">
            <img src="view/images/Copilot_20260424_221814-removebg-preview.png" alt="">
        </div>

</div>
</body>
</html>