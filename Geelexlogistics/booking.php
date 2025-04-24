<?php
ob_start(); // Start output buffering to avoid header issues
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';
require 'send_email.php'; // Include PHPMailer email function

$message = '';
$client_id = $_SESSION['client_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup = htmlspecialchars($_POST['pickup_location']);
    $item_type = htmlspecialchars($_POST['item_type']);
    $destination = htmlspecialchars($_POST['destination_location']);
    $booking_time = date('Y-m-d H:i:s');
    $verification_status = 'Pending';

    try {
        // Insert booking
        $stmt = $pdo->prepare("INSERT INTO bookings (client_id, pickup_location, item_type, destination_location, booking_time, verification_status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$client_id, $pickup, $item_type, $destination, $booking_time, $verification_status]);

        // Get client email to send notification
        $stmt = $pdo->prepare("SELECT email, full_name FROM clients WHERE id = ?");
        $stmt->execute([$client_id]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client) {
            $clientEmail = $client['email'];
            $clientName = $client['full_name'];
            $subject = "Booking Confirmation - Geelex Logistics";
            $message = "Hello $clientName, your booking has been received. Details: <br> Pickup: $pickup <br> Item Type: $item_type <br> Destination: $destination <br> Time: $booking_time";
            
            // Send email notification
            if (sendBookingEmail($clientEmail, $pickup, $item_type, $destination, $booking_time)) {
                header("Location: booking.php?success=1");
                exit();
            } else {
                $message = "❌ Failed to send booking confirmation email.";
            }
        }

    } catch (PDOException $e) {
        $message = "❌ Booking failed: " . htmlspecialchars($e->getMessage());
    }
}


// Handle cancellation
if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $cancel_id = $_GET['cancel'];
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ? AND client_id = ?");
    $stmt->execute([$cancel_id, $client_id]);
    header("Location: booking.php");
    exit();
}

// Fetch all bookings for user
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE client_id = ? ORDER BY booking_time DESC");
$stmt->execute([$client_id]);
$pastBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking - Geelex Logistics</title>
    <link rel="stylesheet" href="style.css">
    <style>
        <?php include 'booking-style.css'; ?>
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="form-container">
    <h2>Book a Delivery</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="message success">✅ Booking submitted successfully!</div>
    <?php elseif (!empty($message)): ?>
        <div class="message error"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="pickup_location" placeholder="Pickup Location" required>
        <select name="item_type" required>
            <option value="">Select Item Type</option>
            <option value="Full Moving">Full Moving</option>
            <option value="Office Relocation">Office Relocation</option>
            <option value="Residential Moving">Residential Moving</option>
            <option value="Commercial Moving">Commercial Moving</option>
            <option value="Hardware Moving">Hardware Moving</option>
            <option value="Farm Products Moving">Farm Products Moving</option>
            <option value="Consolidation Services">Consolidation Services</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" name="destination_location" placeholder="Destination Location" required>
        <button type="submit">Submit Booking</button>
    </form>

    <?php if (!empty($pastBookings)): ?>
        <h3>📋 Your Booking History</h3>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Pickup</th>
                        <th>Destination</th>
                        <th>Item Type</th>
                        <th>Booking Time</th>
                        <th>Verification Status</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pastBookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['pickup_location']) ?></td>
                            <td><?= htmlspecialchars($booking['destination_location']) ?></td>
                            <td><?= htmlspecialchars($booking['item_type']) ?></td>
                            <td><?= htmlspecialchars($booking['booking_time']) ?></td>
                            <td>
                                <?php
                                    $status = htmlspecialchars($booking['verification_status']);
                                    $color = match($status) {
                                        'Pending' => '#f1c40f',
                                        'Verified' => '#2ecc71',
                                        'Rejected' => '#e74c3c',
                                        default => '#bdc3c7'
                                    };
                                    echo "<span style='color: $color; font-weight: bold;'>$status</span>";
                                ?>
                            </td>
                            <td>In Transit</td>
                            <td>
                                <a href="?cancel=<?= $booking['id'] ?>" class="action-link" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align:center; margin-top: 30px;">You have no bookings yet.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

<?php
ob_end_flush(); // End output buffering and send output
?>
