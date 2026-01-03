<?php
session_start();
require 'db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

$sql = "
SELECT 
    a.id AS appointment_id,
    p.name AS patient_name,
    a.doctor_name,
    a.appointment_date,
    a.status
FROM appointments a
JOIN patients p ON a.user_id = p.id
ORDER BY a.appointment_date DESC
";

$result = $conn->query($sql);

if(!$result){
    die("SQL ERROR: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin - Appointments</title>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f3f6fb;
        margin: 0;
        padding: 0;
    }

    .navbar {
        background: #008080;
        padding: 18px 25px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .navbar b {
        font-size: 22px;
    }

    .navbar a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        padding: 8px 14px;
        transition: 0.3s;
        border-radius: 6px;
    }

    .navbar a:hover {
        background: #05ebe3;
        color: #004d4d;
    }

    .container {
        width: 90%;
        margin: 30px auto;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #2c3e50;
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
        text-align: center;
    }

    td {
        padding: 14px;
        font-size: 15px;
        border-bottom: 1px solid #e2e2e2;
        text-align: center;
    }

    tr:hover {
        background: #eef3f8;
        transition: 0.2s;
    }

    select {
        padding: 6px 8px;
        font-size: 14px;
        border-radius: 6px;
        border: 1px solid #aaa;
        outline: none;
    }

    button {
        padding: 7px 15px;
        background: #28a745;
        border: none;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        margin-left: 6px;
        transition: 0.2s;
    }

    button:hover {
        background: #1e7e34;
    }

    .empty {
        text-align: center;
        padding: 20px;
        font-size: 18px;
        color: #666;
    }
</style>

</head>

<body>

<div class="navbar">
    <b>Admin Panel – Appointments</b>
    <a href="admin_dashboard.php">Dashboard</a>
</div>

<div class="container">

<h2>All Appointments</h2>

<table>
<tr>
    <th>Patient</th>
    <th>Doctor</th>
    <th>Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo "<tr>";
        echo "<td>".$row['patient_name']."</td>";
        echo "<td>".$row['doctor_name']."</td>";
        echo "<td>".$row['appointment_date']."</td>";
        echo "<td>".$row['status']."</td>";

        echo "<td>
                <form method='POST' action='admin_update_status.php' style='display:flex; justify-content:center; align-items:center;'>
                    <input type='hidden' name='appointment_id' value='".$row['appointment_id']."'>
                    
                    <select name='status'>
                        <option ".($row['status']=='Pending'?'selected':'').">Pending</option>
                        <option ".($row['status']=='Paid'?'selected':'').">Paid</option>
                    </select>

                    <button type='submit'>Update</button>
                </form>
              </td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5' class='empty'>No appointments found.</td></tr>";
}
?>
</table>

</div>

</body>
</html>
