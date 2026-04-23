<?php
    if(isset($_GET['error']) && $_GET['error'] == 'Invalid'){
        echo "<script>alert('Invalid username or password');</script>";
    }
    else if(isset($_GET['error']) && $_GET['error'] == 'Please verify your email first.'){
        echo "<script>alert('Please verify your email first.');</script>";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<form method="POST" action="../controllers/login.php">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit">Login</button>
</form>

<br>
<a href="register.view">Register</a><br>
<a href="forgot.view">Forgot Password?</a>

</body>
</html>
