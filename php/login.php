<?php
session_start();
require 'db.php';

// Capture error message if redirected back
$error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : null;
unset($_SESSION['login_error']);

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, name FROM patients WHERE email=? AND phone=?");
    if(!$stmt){ die("Prepare failed: " . $conn->error); }
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid email or phone number.";
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patient Login</title>

<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: "Poppins", sans-serif;
        background: linear-gradient(135deg, #6fb7ff, #4d6ee8);
        animation: fadeIn 1.2s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .login-box {
        background: #ffffffee;
        padding: 40px 35px;
        width: 380px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        backdrop-filter: blur(6px);
        animation: popUp 0.8s ease-out;
        text-align: center;
    }

    @keyframes popUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    h2 {
        margin-bottom: 25px;
        color: #2c2c2c;
        font-weight: 600;
    }

    .login-box input {
        width: 100%;
        padding: 12px 15px;
        margin: 12px 0;
        border-radius: 8px;
        border: 1px solid #bbb;
        background: #f7f7f7;
        font-size: 15px;
    }

    .login-box input:focus {
        border-color: #4d6ee8;
        outline: none;
        background: #fff;
        box-shadow: 0 0 5px rgba(77,110,232,0.3);
    }

    .login-btn {
        width: 100%;
        background: #4d6ee8;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        color: white;
        cursor: pointer;
        margin-top: 10px;
        transition: 0.3s;
    }

    .login-btn:hover {
        background: #6f8dff;
    }

    .home-btn {
        display: inline-block;
        margin-top: 15px;
        padding: 9px 16px;
        background: #ffffff;
        border: 2px solid #4d6ee8;
        color: #4d6ee8;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
    }

    .home-btn:hover {
        background: #4d6ee8;
        color: #fff;
    }

    .error {
        margin-top: 12px;
        color: #ff3b3b;
        font-size: 14px;
        font-weight: 500;
    }
</style>
</head>

<body>

<div class="login-box">
    <h2>Patient Login</h2>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="text" name="phone" placeholder="Enter Phone Number" required>

        <button class="login-btn" type="submit">Login</button>
    </form>

    <a href="index.php" class="home-btn">← Back to Home</a>

    <?php if($error) echo "<div class='error'>$error</div>"; ?>
</div>

</body>
</html>
