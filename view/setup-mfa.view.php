<body>
    <div style="max-width: 740px; margin: 40px auto; padding: 28px; background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.08); font-family: 'Poppins', sans-serif;">
        <h1 style="margin-bottom: 16px;">Enable Multi-Factor Authentication</h1>

        <?php if ($alreadyEnabled): ?>
            <p style="font-size: 16px; line-height: 1.6;"><?php echo $isSystemWide ? 'MFA is already enabled system-wide. All users will be required to use MFA on login.' : 'MFA is already enabled for your account. You can now log in using your authenticator app.'; ?></p>
            <button type="button" style="margin-top: 24px; padding: 12px 20px; border: none; background: #3d5afe; color: #fff; border-radius: 8px; cursor: pointer;" onclick="window.location.href='/FinalProject/'"><?php echo $isSystemWide ? 'Back to dashboard' : 'Back to map'; ?></button>
        <?php else: ?>
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;"><?php echo $isSystemWide ? 'Scan the QR code below with your authenticator app (Google Authenticator, Authy, Microsoft Authenticator, etc.). Then enter the 6-digit code from the app to enable MFA system-wide for all users.' : 'Scan the QR code below with your authenticator app to set up MFA for your account.'; ?></p>
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="<?= htmlspecialchars($qrCode) ?>" alt="MFA QR code" style="max-width: 100%; border-radius: 16px;">
            </div>
            <p style="font-size: 14px; color: #444; margin-bottom: 20px;">If your app cannot scan the QR code, enter this secret manually:</p>
            <div style="background: #f4f7ff; padding: 16px; border-radius: 12px; font-weight: 600; letter-spacing: 1px; margin-bottom: 24px;"><?= htmlspecialchars($secret) ?></div>

            <?php if (!empty($error)): ?>
                <div style="color: #b00020; margin-bottom: 20px; font-weight: 600;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="" style="display: grid; gap: 16px;">
                <input type="text" name="code" placeholder="Enter 6-digit code" required style="padding: 14px 16px; border-radius: 12px; border: 1px solid #ccc; font-size: 16px; width: 100%;">
                <button type="submit" style="padding: 14px 16px; border: none; background: #3d5afe; color: #fff; border-radius: 12px; font-size: 16px; cursor: pointer;">Enable MFA</button>
            </form>

            <p style="margin-top: 20px; font-size: 14px; color: #555;"><?php echo $isSystemWide ? 'Once enabled, MFA will be required for all users on future logins.' : 'Once enabled, MFA will be required on your future logins.'; ?></p>
        <?php endif; ?>

        <div style="margin-top: 30px;">
            <a href="/FinalProject/" style="color: #3d5afe; text-decoration: none;">Cancel and return to dashboard</a>
        </div>
    </div>
</body>
</html>
