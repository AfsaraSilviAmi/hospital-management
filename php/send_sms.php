<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Appointment Confirmation</title>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #e8f0f1ff, #bcf4fcff);
        height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.7s ease;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .box {
        width: 420px;
        background: #fff;
        border-radius: 15px;
        padding: 35px;
        text-align: center;
        box-shadow: 0px 10px 30px rgba(0,0,0,0.15);
        animation: pop 0.8s ease;
    }

    @keyframes pop {
        from {opacity: 0; transform: scale(0.95);}
        to {opacity: 1; transform: scale(1);}
    }

    h2 {
        color: #008080;
        margin-bottom: 15px;
        font-size: 26px;
        font-weight: 600;
    }

    p {
        color: #333;
        font-size: 15px;
        line-height: 1.6;
    }

    .btn {
        padding: 12px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        margin: 12px 5px;
        width: 160px;
        transition: 0.3s;
    }

    .login-btn {
        background: #4167a8;
        color: white;
    }

    .login-btn:hover {
        background: #6fa6ff;
    }

    .pay-btn {
        background: #28a745;
        color: white;
    }

    .pay-btn:hover {
        background: #1d7e33;
    }
</style>
</head>
<body>

<div class="box">
    <h2>Appointment Booked ✔</h2>

<?php
if (isset($_SESSION['appointment_id'])) {

    $stmt = $conn->prepare("
        SELECT a.id, p.id AS patient_id, p.name, p.phone, p.email, 
               a.doctor_name, a.appointment_date, pm.amount, a.status 
        FROM appointments a
        JOIN patients p ON a.user_id = p.id
        JOIN payments pm ON pm.appointment_id = a.id
        WHERE a.id = ?
    ");

    if (!$stmt) { die("Prepare failed: " . $conn->error); }

    $stmt->bind_param("i", $_SESSION['appointment_id']);
    $stmt->execute();
    $data = $stmt->get_result();

    if ($row = $data->fetch_assoc()) {

        // 🔥 AUTO LOGIN PATIENT FOR PAYMENT
        $_SESSION['user_id'] = $row['patient_id'];
        $_SESSION['user_name'] = $row['name'];

        echo "<p>Dear <strong>".$row['name']."</strong>,</p>";
        echo "<p>Your appointment with <strong>".$row['doctor_name']."</strong><br>
              on <strong>".$row['appointment_date']."</strong> has been confirmed.</p>";

        echo "<p>Amount Due: <strong>".$row['amount']."</strong><br>
              Status: <strong>".$row['status']."</strong></p>";

        echo "<p>An SMS confirmation has been sent to <strong>".$row['phone']."</strong>.</p>";
    } else {
        echo "<p>Unable to fetch appointment details.</p>";
    }
}
?>

<button class="btn login-btn" onclick="window.location.href='dashboard.php'">Dashboard</button>

<button class="btn pay-btn"
onclick="window.location.href='payment.php?appointment_id=<?php echo $_SESSION['appointment_id']; ?>'">
Pay Now
</button>

</div>
</body>
</html>
