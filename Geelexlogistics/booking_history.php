<?php
session_start();
include 'db.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Handle booking cancellation
if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $booking_id = $_GET['cancel'];
    $stmtCancel = $pdo->prepare("DELETE FROM bookings WHERE id = ? AND client_id = ?");
    $stmtCancel->execute([$booking_id, $client_id]);
    $_SESSION['cancel_msg'] = "Booking #$booking_id has been cancelled.";
    header("Location: booking_history.php");
    exit();
}

// Pagination settings
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch bookings
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE client_id = ? ORDER BY booking_time DESC LIMIT ?, ?");
$stmt->bindValue(1, $client_id, PDO::PARAM_INT);
$stmt->bindValue(2, $start, PDO::PARAM_INT);
$stmt->bindValue(3, $limit, PDO::PARAM_INT);
$stmt->execute();
$bookings = $stmt->fetchAll();

// Count total bookings
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE client_id = ?");
$countStmt->execute([$client_id]);
$totalBookings = $countStmt->fetchColumn();
$totalPages = ceil($totalBookings / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking History - Geelex Logistics</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .container {
        max-width: 800px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 12px;
        text-align: left;
    }
    .pagination {
        text-align: center;
    }
    .pagination a {
        display: inline-block;
        padding: 8px 12px;
        margin: 2px;
        border: 1px solid #ddd;
        border-radius: 5px;
        color: #007bff;
        text-decoration: none;
    }
    .pagination a.active {
        background: #007bff;
        color: white;
    }
    .btn-cancel {
        color: red;
        text-decoration: underline;
        cursor: pointer;
    }
    .alert {
        background: #e0ffe0;
        padding: 10px;
        border: 1px solid #a2d5a2;
        color: green;
        margin-bottom: 15px;
        text-align: center;
    }
  </style>
</head>
<body>

<div class="container">
    <h2>Your Booking History</h2>

    <?php if (isset($_SESSION['cancel_msg'])): ?>
        <div class="alert"><?php echo $_SESSION['cancel_msg']; unset($_SESSION['cancel_msg']); ?></div>
    <?php endif; ?>

    <?php if (count($bookings) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pickup</th>
                    <th>Destination</th>
                    <th>Item Type</th>
                    <th>Booked</th>
                    <th>Est. Delivery</th>
                    <th>Cancel</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $index => $booking): ?>
                    <tr>
                        <td><?php echo $start + $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($booking['pickup_location']); ?></td>
                        <td><?php echo htmlspecialchars($booking['destination_location']); ?></td>
                        <td><?php echo htmlspecialchars($booking['item_type']); ?></td>
                        <td><?php echo htmlspecialchars($booking['booking_time']); ?></td>
                        <td><?php echo htmlspecialchars($booking['estimated_delivery_time']); ?></td>
                        <td>
                            <a class="btn-cancel" href="?cancel=<?php echo $booking['id']; ?>" onclick="return confirm('Cancel this booking?')">Cancel</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php else: ?>
        <p style="text-align:center;">You have no bookings yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
