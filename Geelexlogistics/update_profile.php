<?php
session_start();
require 'db.php';

$id = $_SESSION['client_id'];
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone_number'];

$stmt = $conn->prepare("UPDATE clients SET full_name=?, email=?, phone_number=? WHERE id=?");
$stmt->execute([$full_name, $email, $phone, $id]);
header("Location: profile.php");
?>
