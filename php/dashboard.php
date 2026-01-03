<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT a.id, a.doctor_name, a.appointment_date, pm.amount, a.status 
    FROM appointments a
    JOIN payments pm ON pm.appointment_id=a.id
    WHERE a.user_id=?
    ORDER BY a.appointment_date DESC
");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<style>
*{box-sizing:border-box;}

body{
    font-family:'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #e6f2f2ff, #b1f2deff);
    margin:0;
}

/* NAVBAR */
.navbar{
    background:#006666;
    padding:15px 35px;
    color:white;
    display:flex;
    align-items:center;
    justify-content:space-between;
    box-shadow:0 3px 8px rgba(0,0,0,0.2);
}
.navbar a{
    color:white;
    text-decoration:none;
    margin-left:20px;
    font-weight:500;
    transition:0.3s;
}
.navbar a:hover{
    color: #1bd9f2ff;
}

/* CONTAINER */
.container{
    padding:40px;
}

/* HEADER */
h2{
    margin-bottom:20px;
    color:#004d4d;
    text-align:left;
    font-size:28px;
    letter-spacing:0.5px;
}

/* TABLE STYLING */
.table-wrapper{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th{
    background:#29ab82;
    color:white;
    padding:14px;
    font-size:16px;
}

td{
    padding:14px;
    text-align: center;
    border-bottom:1px solid #ddd;
    font-size:15px;
}

tr:hover{
    background:#f5ffff;
}

/* STATUS BADGES */
.status-paid{
    background:#28a745;
    color:white;
    padding:6px 12px;
    border-radius:20px;
    font-size:14px;
}

.status-pending{
    background:#ff9800;
    color:white;
    padding:6px 12px;
    border-radius:20px;
    font-size:14px;
}

/* PAY NOW BUTTON */
.pay-btn{
    padding:8px 16px;
    background:#007bff;
    border:none;
    color:white;
    border-radius:6px;
    cursor:pointer;
    font-size:14px;
    transition:0.3s;
}
.pay-btn:hover{
    background:#0056b3;
    transform:scale(1.05);
}

/* NO APPOINTMENTS */
.empty{
    text-align:center;
    padding:30px;
    font-size:18px;
    color:#777;
}

</style>
</head>

<body>

<!-- NAVIGATION -->
<div class="navbar">
  <div style="font-size:20px;font-weight:bold;">AR Hospital Portal</div>
  <div>
    <a href="../index.html">Home</a>
    <a href="../chatbot.html">Chatbot</a>
    <a href="logout.php">Logout</a>
  </div>
</div>


<div class="container">
<h2>My Appointments</h2>

<div class="table-wrapper">
<table>
<tr>
<th>Doctor</th>
<th>Date</th>
<th>Amount</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
if($result->num_rows>0){
    while($row=$result->fetch_assoc()){
        echo "<tr>";
        echo "<td>".$row['doctor_name']."</td>";
        echo "<td>".$row['appointment_date']."</td>";
        echo "<td>".$row['amount']." BDT</td>";

        // Status Badge
        if($row['status']=="Pending"){
            echo "<td><span class='status-pending'>Pending</span></td>";
        } else {
            echo "<td><span class='status-paid'>Paid</span></td>";
        }

        // Actions
        if($row['status']=="Pending"){
            echo "<td>
                <form method='GET' action='payment.php'>
                <input type='hidden' name='appointment_id' value='".$row['id']."'>
                <button class='pay-btn' type='submit'>Pay Now</button>
                </form>
            </td>";
        } else {
            echo "<td style='color:green;font-weight:bold;'>✔ Completed</td>";
        }

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5' class='empty'>No appointments yet.</td></tr>";
}
?>

</table>
</div>
</div>

</body>
</html>
