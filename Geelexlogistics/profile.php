<?php
session_start();
include 'db.php'; // this creates $pdo

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

// Display success message if set
if (isset($_SESSION['message'])) {
    echo '<div class="alert">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']); // Clear the message after it's displayed
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Client Profile - Geelex Logistics</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
    }
    .profile-container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 10px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .profile-info p {
      margin: 10px 0;
      font-size: 16px;
    }
    .btn {
      display: inline-block;
      padding: 10px 15px;
      background: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      margin-top: 20px;
    }
    .btn:hover {
      background: #0056b3;
    }
    .profile-image {
      text-align: center;
      margin-bottom: 20px;
    }
    .profile-image img {
      border-radius: 50%;
      width: 150px;
      height: 150px;
      object-fit: cover;
    }
    .alert {
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      margin-bottom: 20px;
      border-radius: 5px;
      text-align: center;
    }
  </style>
</head>
<body>

<form action="upload_picture.php" method="POST" enctype="multipart/form-data">
  <input type="file" name="profile_pic" accept="image/*" required>
  <button type="submit">Upload Profile Picture</button>
</form>

<div class="profile-container">
  <h2>Welcome, <?php echo htmlspecialchars($client['full_name']); ?> 👋</h2>
  
  <!-- Display Profile Picture -->
  <div class="profile-image">
    <?php 
      // Check if a profile picture exists
      if (!empty($client['profile_picture'])): 
        $image_path = 'uploads/' . $client['profile_picture'];  // Ensure path is relative
    ?>
      <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Profile Picture">
    <?php else: ?>
      <img src="uploads/default-profile.jpg" alt="Default Profile Picture">
    <?php endif; ?>
  </div>

  <div class="profile-info">
    <p><strong>Email:</strong> <?php echo htmlspecialchars($client['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($client['phone_number']); ?></p>
    <p><strong>Joined:</strong> <?php echo htmlspecialchars($client['created_at']); ?></p>

    <a href="edit_profile.php" class="btn">Edit Profile</a>
    <a href="logout.php" class="btn">Logout</a>
  </div>
</div>

</body>
</html>
