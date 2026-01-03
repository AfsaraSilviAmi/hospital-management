<?php
session_start();
require 'db.php';

// Redirect if user is not logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

// Validate GET parameter
if(!isset($_GET['appointment_id']) || empty($_GET['appointment_id'])){
    die("<h2 style='color:red;text-align:center;margin-top:50px;'>Invalid Appointment Request</h2>");
}

$appointment_id = intval($_GET['appointment_id']);

// If payment form submitted
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $method = $_POST['method'];

    // Update payment
    $stmt = $conn->prepare("UPDATE payments SET payment_method=?, status='Paid' WHERE appointment_id=?");
    $stmt->bind_param("si", $method, $appointment_id);
    $stmt->execute();

    // Mark appointment as paid
    $stmt = $conn->prepare("UPDATE appointments SET status='Paid' WHERE id=?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

// Fetch appointment details
$stmt = $conn->prepare("
    SELECT a.id, a.doctor_name, a.appointment_date, pm.amount
    FROM appointments a
    JOIN payments pm ON pm.appointment_id = a.id
    WHERE a.id = ? AND a.user_id = ?
");
$stmt->bind_param("ii", $appointment_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc() ?? null;

if(!$appointment){
    die("<h2 style='color:red;text-align:center;margin-top:50px;'>Appointment Not Found or Not Assigned to Your Account</h2>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Complete Payment</title>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #e6f2f2ff, #b1f2deff);
        margin: 0;
        padding: 0;
    }

    .navbar {
        background: #008080;
        padding: 12px 25px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .navbar a {
        color: white;
        margin-left: 18px;
        text-decoration: none;
        font-weight: bold;
    }

    .navbar a:hover {
        color: #05ebe3;
    }

    .pay-container {
        background: white;
        max-width: 480px;
        margin: 40px auto;
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.15);
        text-align: center;
        animation: fadeIn 0.5s;
    }

    h2 {
        color: #008080;
        margin-bottom: 20px;
        font-size: 24px;
    }

    p {
        font-size: 16px;
        margin: 10px 0;
        color: #444;
    }

    label {
        display: block;
        margin-top: 20px;
        font-size: 16px;
        font-weight: bold;
        color: #333;
        text-align: left;
    }

    select {
        width: 100%;
        padding: 12px;
        margin-top: 8px;
        border-radius: 8px;
        border: 1px solid #bbb;
        font-size: 16px;
    }

    button {
        width: 100%;
        padding: 14px;
        margin-top: 25px;
        background: #11b059ff;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 17px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: #1bd9f2ff;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>

<body>

<div class="navbar">
    <div><strong>AR Hospital Portal</strong></div>
    <div>
        <a href="../index.html">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="pay-container">
    <h2>Complete Your Payment</h2>

    <p><strong>Doctor:</strong> <?= htmlspecialchars($appointment['doctor_name']); ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($appointment['appointment_date']); ?></p>
    <p><strong>Amount:</strong> <?= htmlspecialchars($appointment['amount']); ?> BDT</p>

    <form method="POST">
        <label>Select Payment Method</label>
        <select name="method" required>
            <option value="">Choose Method</option>
            <option value="Bkash">Bkash</option>
            <option value="Nagad">Nagad</option>
            <option value="Upay">Upay</option>
            <option value="Rocket">Rocket</option>
            <option value="Cash">Cash</option>
        </select>

        <button type="submit">Confirm Payment</button>
    </form>
</div>

</body>
</html>
