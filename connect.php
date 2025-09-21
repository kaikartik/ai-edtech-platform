<?php
session_start();

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "apna_ai";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// -------- LOGIN --------
if(isset($_POST['phone']) && isset($_POST['password'])){
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM users WHERE mobile='$phone' AND password='$password'";
    $res = $conn->query($sql);

    if($res->num_rows == 1){
        $_SESSION['phone'] = $phone;
        header("Location: edu.html");
        exit();
    }else{
        $_SESSION['login_error'] = "Invalid phone number or password.";
        header("Location: index.php");
        exit();
    }
}

// -------- REGISTER --------
if(isset($_POST["reg_mobile"]) && isset($_POST["reg_password"]) && isset($_POST["Conpass"])){
    $mobile = $conn->real_escape_string($_POST["reg_mobile"]);
    $email = $conn->real_escape_string($_POST["useremail"]);
    $password = $conn->real_escape_string($_POST["reg_password"]);
    $confirm = $conn->real_escape_string($_POST["Conpass"]);

    if($password !== $confirm){
        $_SESSION["reg_error"] = "❌ Passwords do not match!";
        header("Location: index.php#register");
        exit();
    }

    $sql = "INSERT INTO users (mobile,email,password) VALUES ('$mobile','$email','$password')";
    if($conn->query($sql) === TRUE){
        $_SESSION["reg_success"] = "✅ Registration successful! Please login.";
        header("Location: index.php#login");
        exit();
    }else{
        $_SESSION["reg_error"] = "❌ Error: ".$conn->error;
        header("Location: index.php#register");
        exit();
    }
}

// -------- SEND OTP --------
if(isset($_POST["mail"])){
    $email = $_POST["mail"];
    $res = $conn->query("SELECT * FROM users WHERE email='$email'");

    if($res->num_rows == 1){
        // Generate OTP
        $otp = rand(100000,999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $email;
        $_SESSION['otp_expiry'] = time() + 600; // 10 minutes
        // PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 0; 
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'apnaai17749524@gmail.com'; // your Gmail
            $mail->Password   = 'vmrb rbwj fmzl nmho';       // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('apnaai17749524@gmail.com','Apna AI');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'AI EdTech OTP Code';
            $mail->Body = "Your OTP for password reset is: <b>$otp</b>. Valid for 10 minutes.";

            $mail->send();

            $_SESSION["otp_email"] = $email; // store email for OTP page
            $_SESSION["reset_msg"] = "📩 OTP sent to your email!";
            header("Location: verify.php");
            exit();

        } catch (Exception $e){
            $_SESSION["reset_msg"] = "❌ OTP could not be sent. Error: {$mail->ErrorInfo}";
            header("Location: index.php#reset");
            exit();
        }
    } else {
        $_SESSION["reset_msg"] = "❌ Email not found.";
        header("Location: index.php#reset");
        exit();
    }
}

// -------- VERIFY OTP & RESET PASSWORD --------
if(isset($_POST["otp"]) && isset($_POST["new_password"])){
    $email = $_POST['email'];
    $enteredOtp = $_POST['otp'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // OTP expired check
    if(!isset($_SESSION['otp']) || time() > $_SESSION['otp_expiry']){
        $_SESSION['otp_error'] = "❌ OTP expired!";
        header("Location: verify_otp_form.php");
        exit();
    }

    // OTP match check
    if($enteredOtp != $_SESSION['otp']){
        $_SESSION['otp_error'] = "❌ Invalid OTP!";
        header("Location: verify_otp_form.php");
        exit();
    }

    // Password match check
    if($new_pass !== $confirm_pass){
        $_SESSION['otp_error'] = "❌ Passwords do not match!";
        header("Location: verify_otp_form.php");
        exit();
    }

    // Update password in DB
    $new_pass_safe = $conn->real_escape_string($new_pass);
    $conn->query("UPDATE users SET password='$new_pass_safe' WHERE email='$email'");

    $_SESSION['otp_success'] = "✅ Password reset successful!";
    // Clear OTP session
    unset($_SESSION['otp']);
    unset($_SESSION['otp_expiry']);
    unset($_SESSION['otp_email']);

    header("Location: index.php#login");
    exit();
}

$conn->close();
?>