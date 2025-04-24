<?php
include 'auth.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE bookings SET verification_status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    header("Location: bookings.php");
    exit();
}
