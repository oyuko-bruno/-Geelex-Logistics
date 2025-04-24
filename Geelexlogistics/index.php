<?php
session_start();

if (isset($_SESSION['message'])) {
    echo "<p style='color: green; text-align:center; font-weight:bold'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Africa/Nairobi');
$hour = date('H');
$greeting = '';
$emoji = '';

// Determine time of day and set greeting
if ($hour >= 5 && $hour < 12) {
    $greeting = "Good Morning";
    $emoji = "🌅";
} elseif ($hour >= 12 && $hour < 17) {
    $greeting = "Good Afternoon";
    $emoji = "☀️";
} elseif ($hour >= 17 && $hour < 21) {
    $greeting = "Good Evening";
    $emoji = "🌇";
} else {
    $greeting = "Good Night";
    $emoji = "🌙";
}

include 'db.php'; // Assumes $pdo is defined here

// Fetch the user's profile picture
$client_id = $_SESSION['client_id'];

$stmt = $pdo->prepare("SELECT profile_picture, full_name FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Set profile picture path
$profile_picture = !empty($user['profile_picture']) ? 'uploads/' . $user['profile_picture'] : 'uploads/default.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Geelex Logistics</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <h2><?php echo "$greeting, " . htmlspecialchars($user['full_name']) . "! $emoji"; ?></h2>

    <!-- Display profile picture -->
    <div class="profile-picture">
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width: 70px; height: 70px; border-radius: 50%;">
    </div>

    <div class="home-icons">
        <a href="booking.php" class="icon-btn">
            <i class="fas fa-truck-moving"></i>
            <p>Book Now</p>
        </a>

        <a href="profile.php" class="icon-btn">
            <i class="fas fa-user-circle"></i>
            <p>Profile</p>
        </a>

        <a href="track.php" class="icon-btn">
            <i class="fas fa-map-marker-alt"></i>
            <p>Track</p>
        </a>
        <a href="booking_history.php" class="btn">View My Bookings</a>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
