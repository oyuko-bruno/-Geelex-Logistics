<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Assumes $pdo is defined

$message = '';
$client_id = $_SESSION['client_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup = htmlspecialchars($_POST['pickup_location']);
    $item_type = htmlspecialchars($_POST['item_type']);
    $destination = htmlspecialchars($_POST['destination_location']);

    $booking_time = date('Y-m-d H:i:s');
    $verification_status = 'Pending';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (client_id, pickup_location, item_type, destination_location, booking_time, verification_status) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$client_id, $pickup, $item_type, $destination, $booking_time, $verification_status]);

        $message = "✅ Booking successful! Status: <strong>$verification_status</strong>. Check your Email!";

        // Send email confirmation
        $stmtUser = $pdo->prepare("SELECT email, full_name FROM clients WHERE id = ?");
        $stmtUser->execute([$client_id]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $clientEmail = $user['email'];
            $clientName = $user['full_name'] ?? 'Client';

            $subject = "📦 Booking Confirmation - Geelex Logistics";
            $headers = "From: Geelex Logistics <no-reply@geelexlogistics.co.ke>\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            $emailBody = "
                <html>
                <body>
                    <h3>Hello $clientName,</h3>
                    <p>Your booking has been successfully received.</p>
                    <p><strong>Pickup Location:</strong> $pickup</p>
                    <p><strong>Destination:</strong> $destination</p>
                    <p><strong>Item Type:</strong> $item_type</p>
                    <p><strong>Booking Time:</strong> $booking_time</p>
                    <p><strong>Status:</strong> $verification_status (will be verified by admin)</p>
                    <br>
                    <p>Thank you for choosing Geelex Logistics!</p>
                </body>
                </html>
            ";

            mail($clientEmail, $subject, $emailBody, $headers);
        }

    } catch (PDOException $e) {
        $message = "❌ Booking failed: " . htmlspecialchars($e->getMessage());
    }
}

// Handle cancellation if set
if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $cancel_id = $_GET['cancel'];
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ? AND client_id = ?");
    $stmt->execute([$cancel_id, $client_id]);
    header("Location: booking.php");
    exit();
}

// Fetch bookings
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
        .form-container {
            max-width: 700px;
            margin: 30px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .form-container h2, .form-container h3 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        form input, form select {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            font-size: 1.1em;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        form button:hover {
            background-color: #2980b9;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .message.success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #3498db;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        @media screen and (max-width: 600px) {
            .form-container {
                padding: 20px;
                margin: 20px;
            }

            .form-container h2 {
                font-size: 1.6em;
            }

            table th, table td {
                font-size: 14px;
            }
        }

        .action-link {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }

        .action-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="form-container">
    <h2>Book a Delivery</h2>

    <?php if (!empty($message)): ?>
        <div class="message <?php echo str_starts_with($message, '✅') ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
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
                        <td>In Transit</td> <!-- Placeholder status -->
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
