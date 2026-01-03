<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT id FROM admins WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['admin_id'] = $result->fetch_assoc()['id'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>

<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: "Poppins", sans-serif;
        background: linear-gradient(135deg, rgb(237, 242, 240), rgb(156, 237, 190));
        animation: fadeIn 1.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .login-box {
        background: #ffffffdd;
        padding: 45px 50px;
        width: 400px;
        border-radius: 25px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        backdrop-filter: blur(6px);
        text-align: center;
        animation: slideDown 0.8s ease-out;
    }

    @keyframes slideDown {
        from { transform: translateY(-30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    h2 {
        margin-bottom: 25px;
        color: #2d2d2d;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .login-box input {
        width: 100%;
        padding: 12px 15px;
        margin: 12px 0;
        border-radius: 8px;
        border: 1px solid #bbb;
        background: #f9f9f9;
        font-size: 15px;
    }

    .login-box input:focus {
        border-color: #4c62dd;
        outline: none;
        background: #fff;
        box-shadow: 0 0 5px rgba(76,98,221,0.3);
    }

    .login-btn {
        width: 100%;
        background: #4c62dd;
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
        background: #6a80ff;
    }

    .home-btn {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 18px;
        background: #ffffff;
        border: 2px solid #4c62dd;
        color: #4c62dd;
        border-radius: 8px;
        font-size: 14px;
        text-decoration: none;
        transition: 0.3s;
        font-weight: 600;
    }

    .home-btn:hover {
        background: #4c62dd;
        color: #fff;
    }

    .error {
        margin-top: 10px;
        color: #ff3b3b;
        font-size: 14px;
        font-weight: 500;
    }
</style>
</head>

<body>

<div class="login-box">
    <h2>Admin Login</h2>

    <form method="POST">
        <input name="username" placeholder="Enter Username" required>
        <input name="password" type="password" placeholder="Enter Password" required>

        <button class="login-btn" type="submit">Login</button>
    </form>

    <a href="../index.html" class="home-btn">← Back to Home</a>

    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
</div>

</body>
</html>
