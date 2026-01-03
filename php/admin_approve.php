<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

$conn->query("UPDATE appointments SET status='Approved' WHERE id=$id");

header("Location: admin_appointments.php");
