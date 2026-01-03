<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$total_patients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];
$total_doctors = $conn->query("SELECT COUNT(*) AS total FROM doctors")->fetch_assoc()['total'];
$total_appointments = $conn->query("SELECT COUNT(*) AS total FROM appointments")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, rgb(237, 242, 240), rgb(156, 237, 190));
        margin: 0;
        padding: 0;
        color: #333;
    }

    header {
      background-color: #008080;

        color: white;
        padding: 25px;
        text-align: center;
        font-size: 28px;
        font-weight: 600;
        letter-spacing: 1px;
        box-shadow: 0 5px 12px rgba(0,0,0,0.15);
        backdrop-filter: blur(5px);
    }

    nav {
        background: #ffffffcc;
        padding: 15px 0;
        display: flex;
        justify-content: center;
        gap: 35px;
        backdrop-filter: blur(3px);
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    }

    nav a {
        text-decoration: none;
        color: #1b3c8a;
        font-weight: 600;
        font-size: 16px;
        padding: 10px 18px;
        border-radius: 8px;
        transition: 0.3s;
    }

    nav a:hover {
        background: #126e66ff;
        color: white;
    }

    .dashboard {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 35px;
        margin: 50px auto;
        width: 85%;
    }

    .card {
        width: 260px;
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(8px);
        padding: 30px;
        border-radius: 18px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transition: 0.3s ease;
        border: 1px solid rgba(255,255,255,0.5);
    }

    .card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 12px 35px rgba(0,0,0,0.25);
    }

    .card h3 {
        font-size: 18px;
        color: #3d3d3d;
        margin-bottom: 10px;
    }

    .card p {
        font-size: 40px;
        font-weight: 700;
        color: #1b3c8a;
        margin: 0;
    }
</style>

</head>
<body>

<header>Hospital Admin Dashboard</header>

<nav>
    <a href="admin_appointments.php">Appointments</a>
    <a href="admin_payments.php">Payments</a>
    <a href="admin_logout.php">Logout</a>
</nav>

<div class="dashboard">
    <div class="card">
        <h3>Total Patients</h3>
        <p><?= $total_patients ?></p>
    </div>

    <div class="card">
        <h3>Total Doctors</h3>
        <p>8</p>
    </div>

    <div class="card">
        <h3>Total Appointments</h3>
        <p><?= $total_appointments ?></p>
    </div>
</div>

</body>
</html>
