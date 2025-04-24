<?php
include 'db.php'; // Include the database connection file

// Handle the form submission and fetch tracking data based on booking ID
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Prepare and execute the query to get tracking updates for the specified booking ID
    $stmt = $pdo->prepare("SELECT * FROM tracking_updates WHERE booking_id = ? ORDER BY created_at DESC");
    $stmt->execute([$booking_id]);
    $updates = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Track Goods - Geelex Logistics</title>
  <style>
    * {
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f0f2f5;
  margin: 0;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

h2 {
  color: #333;
  margin-bottom: 20px;
  text-align: center;
}

form {
  width: 100%;
  max-width: 500px;
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  margin-bottom: 30px;
}

label {
  font-weight: bold;
  display: block;
  margin-bottom: 8px;
}

input[type="text"] {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

button {
  padding: 10px 20px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s ease;
}

button:hover {
  background-color: #0056b3;
}

table {
  width: 100%;
  max-width: 1000px;
  border-collapse: collapse;
  background: #fff;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  margin-top: 30px;
}

th, td {
  border: 1px solid #ddd;
  padding: 12px 15px;
  text-align: left;
  word-wrap: break-word; /* Added to prevent word overflow */
  white-space: normal; /* Ensures long words will break and not overflow */
}

th {
  background-color: #007BFF;
  color: white;
}

tr:nth-child(even) {
  background-color: #f9f9f9;
}

p {
  font-size: 16px;
  color: #555;
  text-align: center;
}

/* Responsive Design for Tables */
/* Base styles for larger screens are already defined */

/* For extra small devices (phones, < 480px) */
@media (max-width: 480px) {
  table, thead, tbody, th, td, tr {
    display: block;
    width: 100%;
  }

  thead {
    display: none;
  }

  tr {
    margin-bottom: 15px;
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  td {
    padding: 10px;
    text-align: right;
    position: relative;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
  }

  td::before {
    content: attr(data-label);
    position: absolute;
    left: 15px;
    width: 45%;
    text-align: left;
    font-weight: bold;
    color: #333;
    white-space: nowrap;
  }

  td:last-child {
    border-bottom: 1px solid #ddd;
  }
}

/* For small devices (landscape phones, < 768px) */
@media (max-width: 768px) {
  table, thead, tbody, th, td, tr {
    display: block;
    width: 100%;
  }

  thead {
    display: none;
  }

  tr {
    margin-bottom: 15px;
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  td {
    padding: 10px;
    text-align: right;
    position: relative;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
  }

  td::before {
    content: attr(data-label);
    position: absolute;
    left: 15px;
    width: 40%;
    text-align: left;
    font-weight: bold;
    color: #333;
  }

  td:last-child {
    border-bottom: 1px solid #ddd;
  }
}

/* For very small devices like old feature phones (< 300px) */
@media (max-width: 360px) {
  td::before {
    font-size: 12px;
  }

  td {
    font-size: 13px;
    padding: 8px;
  }
}


  </style>
</head>
<body>

<h2>Track Your Goods</h2>

<!-- Form to input booking ID -->
<form method="GET" action="">
  <label for="booking_id">Enter Your Booking Invoice No.:</label>
  <input type="text" name="booking_id" id="booking_id" placeholder="e.g. GEE1234" required>
  <button type="submit">Track</button>
</form>

<?php
// If tracking updates are found, display them in a table
if (isset($updates) && $updates) {
    echo "<table>
            <thead>
              <tr>
                <th>Location</th>
                <th>Status Update</th>
                <th>Date & Time</th>
              </tr>
            </thead>
            <tbody>";
    foreach ($updates as $update) {
        echo "<tr>
                <td data-label='Location'>{$update['location']}</td>
                <td data-label='Status Update'>{$update['status_update']}</td>
                <td data-label='Date & Time'>{$update['created_at']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    // If no updates are found, show a message
    if (isset($booking_id)) {
        echo "<p>No tracking updates found for Booking ID <strong>" . htmlspecialchars($booking_id) . "</strong>.</p>";
    }
}
?>

</body>
</html>
