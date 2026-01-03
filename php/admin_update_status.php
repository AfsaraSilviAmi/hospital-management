<?php
session_start();
require 'db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    die("Invalid request method.");
}

if(!isset($_POST['appointment_id']) || !isset($_POST['status'])){
    die("Missing data.");
}

$appointment_id = intval($_POST['appointment_id']);
$status = $_POST['status'];

// Validate status value to avoid bad input
$allowed = ['Pending','Paid'];
if(!in_array($status, $allowed)){
    die("Invalid status value.");
}

$stmt = $conn->prepare("UPDATE appointments SET status=? WHERE id=?");
if(!$stmt){
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("si", $status, $appointment_id);

if($stmt->execute()){
    header("Location: admin_appointments.php?updated=1");
    exit();
} else {
    die("Execute failed: " . $conn->error);
}
