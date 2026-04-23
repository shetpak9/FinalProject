<!DOCTYPE html>
<html>
<head>
    <title>MFA Verification</title>
</head>
<body>

<h2>Enter MFA Code</h2>

<form method="POST" action="../controllers/verify-mfa.php">
    <input type="text" name="code" placeholder="6-digit code" required><br><br>
    <button type="submit">Verify</button>
</form>

<br>
<a href="backup.view">Use Backup Code</a>

</body>
</html>