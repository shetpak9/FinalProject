<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="view/css/reset.css">
    <link rel="stylesheet" href="view/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="login-box">

        <!-- LEFT SIDE -->
        <div class="login-form">
            <h1>Forgot Password</h1>
            <form method="POST" action="/FinalProject/controllers/forgot-password.php">
                <label>Enter your Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>

                <button>Send Reset Link</button>
            </form>
            <p class="register-text">
                <a href="login">Back to Login</a><br>
            </p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="logo-section">
            <img src="view/images/Copilot_20260424_221814-removebg-preview.png" alt="">
        </div>

    </div>
</div>
</body>
</html>