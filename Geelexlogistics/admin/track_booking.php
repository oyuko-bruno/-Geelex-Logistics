<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

?>

<?php include 'auth.php'; ?>
<?php include 'header.php'; ?>
<?php include 'db.php'; ?>


<h2>Track Booking</h2>
<form method="post">
    <input type="text" name="booking_id" placeholder="Enter Booking ID" required>
    <button type="submit">Track</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['booking_id'];
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->execute([$id]);
    $track = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($track) {
        echo "<p><strong>Status:</strong> " . htmlspecialchars($track['verification_status']) . "</p>";
        echo "<p><strong>Pickup:</strong> " . htmlspecialchars($track['pickup_location']) . "</p>";
        echo "<p><strong>Destination:</strong> " . htmlspecialchars($track['destination_location']) . "</p>";
    } else {
        echo "<p style='color:red;'>Booking not found.</p>";
    }
}
?>

<?php include 'footer.php'; ?>
