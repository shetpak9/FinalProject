<!DOCTYPE html>
<html>
<head>
    <title>Backup Code</title>
</head>
<body>

<h2>Backup Login</h2>

<form method="POST" action="../controllers/verify-backup.php">
    <input type="text" name="code" placeholder="Backup code" required><br><br>
    <button type="submit">Login</button>
</form>

<br>
<a href="mfa.view">Back to MFA</a>

</body>
</html>