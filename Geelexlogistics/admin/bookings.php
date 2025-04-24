<?php include 'auth.php'; ?>
<?php include 'header.php'; ?>
<?php include 'db.php';

$stmt = $pdo->query("SELECT b.*, c.full_name FROM bookings b JOIN clients c ON b.client_id = c.id ORDER BY booking_time DESC");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Bookings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f9fc;
            margin: 0;
            padding: 0 10px 30px;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }

        .table-container {
            overflow-x: auto;
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        th {
            background-color: #007bff;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        select, button {
            padding: 6px 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 13px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            table {
                font-size: 13px;
            }

            th, td {
                padding: 10px 6px;
            }

            select, button {
                width: 100%;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>

<h2>📋 All Client Bookings</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Pickup</th>
                <th>Destination</th>
                <th>Item</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b['full_name']) ?></td>
                <td><?= htmlspecialchars($b['pickup_location']) ?></td>
                <td><?= htmlspecialchars($b['destination_location']) ?></td>
                <td><?= htmlspecialchars($b['item_type']) ?></td>
                <td><?= htmlspecialchars($b['booking_time']) ?></td>
                <td><?= htmlspecialchars($b['verification_status']) ?></td>
                <td>
                    <form method="post" action="update_status.php">
                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                        <select name="status">
                            <option value="Verified">Verify</option>
                            <option value="Rejected">Reject</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
