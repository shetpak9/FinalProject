<?php
    $messtxt = '' ;
    $error = $_GET['error'] ?? '';
    if($error == 'invalid'){
        $messtxt = "Invalid Username or Password";
    } else if($error == 'verify'){
        $messtxt = "Please verify your email first";
    } else if($error == 'reset'){
        $messtxt = "Check your email for reset link";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="view/css/reset.css">
    <link rel="stylesheet" href="view/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="login-box">

        <!-- LEFT SIDE -->
        <div class="login-form">
            <h1>Welcome Back</h1>
            <p class="subtitle">Hello we are excited to explore with you!</p>
            <form method="POST" action="/FinalProject/controllers/login.php">
                <label>Enter your username</label>
                <input type="text" name="username" placeholder="Username" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>

                <button>Login</button>
            </form>
            <p class="register-text">
                You are not Registered? 
                <a href="register">Make an account</a><br>
                Forgot Password?
                <a href="forgot">Fuck you</a>
            </p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="logo-section">
            <img src="view/images/Copilot_20260424_221814-removebg-preview.png" alt="">
        </div>

    </div>
</div>
</div>
<?php
if(!$error == null):
?>
<div class="fade_mess">
    <?= $messtxt ?>
</div>
<?php endif ?>
</body>
</html>
<script>
  if (window.location.search) {
    const cleanUrl = window.location.origin + window.location.pathname;
    window.history.replaceState({}, document.title, cleanUrl);
  }
</script>


