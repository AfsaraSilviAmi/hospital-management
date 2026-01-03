<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require 'db.php';

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$doctor = $_POST['doctor'];
$appointment_date = $_POST['appointment_date'];
$amount = $_POST['amount'];

// Check if patient exists
$stmt = $conn->prepare("SELECT id FROM patients WHERE email=? AND phone=?");
$stmt->bind_param("ss", $email, $phone);
$stmt->execute();
$result = $stmt->get_result();

if($row = $result->fetch_assoc()){
    $user_id = $row['id'];
}else{
    $stmt = $conn->prepare("INSERT INTO patients (name, phone, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $phone, $email);
    $stmt->execute();
    $user_id = $stmt->insert_id;
}

// Insert appointment
$stmt = $conn->prepare("INSERT INTO appointments (user_id, doctor_name, appointment_date) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $doctor, $appointment_date);
$stmt->execute();
$appointment_id = $stmt->insert_id;

// Insert payment (pending)
$stmt = $conn->prepare("INSERT INTO payments (appointment_id, amount) VALUES (?, ?)");
$stmt->bind_param("id", $appointment_id, $amount);
$stmt->execute();

$_SESSION['appointment_id'] = $appointment_id;

// Redirect to SMS confirmation
header("Location: send_sms.php");
exit();
?>
