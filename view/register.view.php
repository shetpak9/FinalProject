<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="view/css/reset.css">
    <link rel="stylesheet" href="view/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
</head>
<body>
<div class="container">
    <div class="login-box">

        <!-- LEFT SIDE -->
        <div class="login-form">
            <h1>Create an Account</h1>
            <form method="POST" action="/FinalProject/controllers/register.php">
                <label>Email</label>
                <input type="email" name="email" placeholder="Email" required>

                <label>Display Name</label>
                <input type="text" name="name" placeholder="Display Name" required>

                <label>Username</label>
                <input type="text" name="username" placeholder="Username" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>

                <button>Create Account</button>
            </form>
            <p class="register-text">
                Already Have an Account? 
                <a href="login"> Login</a><br>
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