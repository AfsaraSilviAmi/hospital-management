<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patient Login</title>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #e4eff0ff, #9dfefaff);
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .login-box {
        background: #ffffff;
        border-radius: 15px;
        padding: 40px 45px;
        width: 370px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        animation: popUp 0.8s ease-out;
    }

    @keyframes popUp {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    h2 {
        margin-bottom: 20px;
        color: #2c2c2c;
        font-weight: 600;
        font-size: 26px;
    }

    label {
        display: block;
        text-align: left;
        font-weight: 600;
        margin-bottom: 6px;
        color: #333;
    }

    .login-box input {
        width: 90%;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ccc;
        margin-bottom: 18px;
        font-size: 10px;
        background: #f7f7f7;
        transition: 0.3s ease;
    }

    .login-box input:focus {
        background: #fff;
        border-color: #2575fc;
        box-shadow: 0 0 5px rgba(37,117,252,0.3);
        outline: none;
    }

    .login-btn {
        width: 100%;
        padding: 15px;
        background: #209a9dff;
        color: white;
        font-weight: 600;
        font-size: 16px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s ease;
    }

    .login-btn:hover {
        background: #90b7e1;
    }

    .error {
        padding: 10px;
        background: #ffdddd;
        color: #c20000;
        border-left: 4px solid red;
        margin-bottom: 15px;
        border-radius: 5px;
        font-size: 14px;
    }

    .back-link {
        display: inline-block;
        margin-top: 15px;
        color: #4167a8;
        font-size: 15px;
        text-decoration: none;
        transition: 0.3s ease;
    }

    .back-link:hover {
        color: #6fa6ff;
        text-decoration: underline;
    }
</style>

</head>
<body>

<div class="login-box">
    <h2>Patient Login</h2>

    <?php if(isset($_SESSION['login_error'])): ?>
        <div class="error"><?= $_SESSION['login_error']; ?></div>
        <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <form action="php/login.php" method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="phone">Phone Number</label>
        <input type="text" name="phone" id="phone" required>

        <button class="login-btn" type="submit">Login</button>
    </form>

    <a class="back-link" href="appointment.html">← Back to Book Appointment</a>
</div>

</body>
</html>
