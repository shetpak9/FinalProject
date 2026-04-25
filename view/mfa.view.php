<?php
    $messtxt = '' ;
    $error = $_GET['error'] ?? '';
    if($error == 'invalid'){
        $messtxt = "Invalid MFA Code";
    } else if($error == 'require'){
        $messtxt = "MFA Code require";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>MFA Verification</title>
    <link rel="stylesheet" href="view/css/reset.css">
    <link rel="stylesheet" href="view/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="login-box">

        <!-- LEFT SIDE -->
        <div class="login-form">
            <h1>MFA VERIFICATION</h1>
            <form method="POST" action="/FinalProject/controllers/verify-mfa.php">
                <label>Enter MFA Code</label>
                <input type="text" name="code" placeholder="6-digit code" required>

                <button>Verify</button>
            </form>
            <p class="register-text">
                <a href="backup">Use Backup Code</a><br>
            </p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="logo-section">
            <img src="view/images/Copilot_20260424_221814-removebg-preview.png" alt="">
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
