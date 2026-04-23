<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>

<h2>Reset Password</h2>

<form method="POST" action="../controllers/reset-password.php?token=<?php echo htmlspecialchars($_GET['token']); ?>">
    <input type="password" name="password" placeholder="New password" required><br><br>
    <button type="submit">Update Password</button>
</form>

</body>
</html>