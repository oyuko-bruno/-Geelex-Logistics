<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db.php';

// Booking Verification & Rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['action'] === 'verify' ? 'Verified' : 'Rejected';
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$status, $booking_id]);
}

// Tracking update
$tracking_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location']) && isset($_POST['status_update'])) {
    $booking_id = $_POST['booking_id'];
    $location = $_POST['location'];
    $status_update = $_POST['status_update'];

    $stmt = $pdo->prepare("INSERT INTO tracking_updates (booking_id, location, status_update) VALUES (?, ?, ?)");
    $tracking_message = $stmt->execute([$booking_id, $location, $status_update])
        ? "✅ Tracking update posted successfully."
        : "❌ Failed to post tracking update.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Geelex Logistics</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      padding: 10px;
      margin: 0;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .section-title {
      font-size: 1.2rem;
      margin: 20px 0 10px;
      color: #333;
    }

    .table-responsive {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      min-width: 600px;
    }

    table th, table td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }

    button {
      padding: 7px 12px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
    }

    button:hover {
      background-color: #0056b3;
    }

    .delete-btn {
      background-color: #dc3545;
    }

    .delete-btn:hover {
      background-color: #c82333;
    }

    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 12px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    textarea {
      resize: vertical;
    }

    .message {
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
      font-weight: bold;
    }

    .success {
      background-color: #d4edda;
      color: #155724;
    }

    .error {
      background-color: #f8d7da;
      color: #721c24;
    }

    @media (max-width: 600px) {
      .container {
        padding: 10px;
      }

      table {
        font-size: 14px;
      }

      button {
        font-size: 12px;
        padding: 6px 10px;
      }

      .section-title {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Geelex Logistics - Admin Dashboard</h2>

  <!-- Display Registered Users -->
  <div class="section-title">Registered Users</div>
  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $users = $pdo->query("SELECT * FROM clients");
        while ($user = $users->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr>
                  <td>{$user['id']}</td>
                  <td>{$user['full_name']}</td>
                  <td>{$user['email']}</td>
                  <td>{$user['phone_number']}</td>
                  <td>
                    <form method='POST' action='delete_user.php'>
                      <input type='hidden' name='user_id' value='{$user['id']}'>
                      <button type='submit' class='delete-btn'>Delete</button>
                    </form>
                  </td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Add New User -->
  <div>
    <div class="section-title">Add New User</div>
    <form action="add_user.php" method="POST">
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="phone_number" placeholder="Phone Number" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Add User</button>
    </form>
  </div>

  <!-- Post Tracking Update -->
  <div>
    <div class="section-title">Post Tracking Update</div>
    <?php if (!empty($tracking_message)): ?>
      <div class="message <?= strpos($tracking_message, '✅') !== false ? 'success' : 'error' ?>">
        <?= $tracking_message ?>
      </div>
    <?php endif; ?>
    <form method="POST" action="post_tracking.php">
      <select name="booking_id" required>
        <option value="">Select Booking</option>
        <?php
        $result = $pdo->query("
          SELECT bookings.id, clients.full_name 
          FROM bookings 
          JOIN clients ON bookings.client_id = clients.id
        ");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          echo "<option value='{$row['id']}'>#{$row['id']} - {$row['full_name']}</option>";
        }
        ?>
      </select>
      <input type="text" name="location" placeholder="Current Location" required>
      <textarea name="status_update" placeholder="Status Update" required></textarea>
      <button type="submit">Send Tracking Update</button>
    </form>
  </div>
</div>

</body>
</html>
