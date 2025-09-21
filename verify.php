<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Apna AI</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
             background: linear-gradient(to bottom, #9500ff, #FFFFFF);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .otp-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.6);
            width: 350px;
            text-align: center;
        }

        .otp-container h2 {
            margin-bottom: 20px;
            color: #00d4ff;
        }

        .otp-container input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: none;
            outline: none;
        }

        .otp-container button {
            padding: 10px 20px;
            background: #00d4ff;
            color: #000;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }

        .otp-container .message {
            margin-top: 10px;
            color: #ff6b6b;
        }
    </style>
</head>

<body>
    <div class="otp-container">
        <h2>Verify OTP & Reset Password</h2>

        <?php if(isset($_SESSION["otp_error"])): ?>
        <p class="message">
            <?php echo $_SESSION["otp_error"]; unset($_SESSION["otp_error"]); ?>
        </p>
        <?php endif; ?>

        <form action="connect.php" method="post">
            <input type="email" name="email" placeholder="Enter your Email" required>
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>

</html>