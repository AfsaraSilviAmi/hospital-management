<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all payments with patient details
$sql = "SELECT p.id, pt.name AS patient_name, p.amount, p.created_at
        FROM payments p
        JOIN appointments a ON p.appointment_id = a.id
        JOIN patients pt ON a.user_id = pt.id
        ORDER BY p.id DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payments | Admin Panel</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 85%;
            margin: 40px auto;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        th {
            background: #34495e;
            color: white;
            padding: 14px;
            font-size: 16px;
        }

        td {
            text-align: center;
            padding: 14px;
            font-size: 15px;
            color: #333;
            border-bottom: 1px solid #e2e2e2;
        }

        tr:hover {
            background: #eef3f8;
            transition: 0.2s;
        }

        .empty {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #666;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            background: #2ecc71;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 15px;
            transition: 0.2s;
        }

        .back-btn:hover {
            background: #27ae60;
        }
    </style>

</head>
<body>

<div class="container">

    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

    <h2>All Payments</h2>

    <table>
        <tr>
            <th>Patient</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['patient_name']; ?></td>
                    <td>৳ <?= $row['amount']; ?></td>
                    <td><?= $row['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="empty">No payment records found.</td>
            </tr>
        <?php endif; ?>

    </table>
</div>

</body>
</html>
