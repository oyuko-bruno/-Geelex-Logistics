<?php
session_start();
include 'db.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Fetch client details
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if (isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);

    try {
        $update = $pdo->prepare("UPDATE clients SET full_name = ?, email = ?, phone_number = ? WHERE id = ?");
        $update->execute([$full_name, $email, $phone_number, $client_id]);

        $_SESSION['message'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (password_verify($current_password, $client['password'])) {
        if ($new_password === $confirm_password) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE clients SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $client_id]);
            $success_pass = "Password updated successfully!";
        } else {
            $error_pass = "New passwords do not match.";
        }
    } else {
        $error_pass = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Geelex Logistics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .btn {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        hr {
            margin: 30px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Profile</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (isset($success_pass)) echo "<p class='success'>$success_pass</p>"; ?>
    <?php if (isset($error_pass)) echo "<p class='error'>$error_pass</p>"; ?>

    <form method="POST">
        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($client['full_name']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>" required>

        <label>Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($client['phone_number']); ?>" required>

        <button type="submit" name="update_profile" class="btn">Update Profile</button>
    </form>

    <hr>

    <h3>Change Password</h3>
    <form method="POST">
        <label>Current Password:</label>
        <input type="password" name="current_password" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" name="change_password" class="btn">Change Password</button>
    </form>

    <br>
    <a href="profile.php" class="btn" style="background: #6c757d;">Back to Profile</a>
</div>

</body>
</html>
