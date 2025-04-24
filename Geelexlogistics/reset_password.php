<?php
require_once 'db.php';
$message = '';
$messageClass = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("<div style='text-align:center;padding:2rem;font-family:sans-serif;'>⚠️ Invalid or expired token. <br><a href='forgot_password.php'>Try Again</a></div>");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE clients SET password = ?, reset_token = NULL WHERE id = ?");
        $stmt->execute([$new_password, $user['id']]);
        $message = "✅ Password successfully updated. <a href='login.php'>Login here</a>";
        $messageClass = "success";
    }
} else {
    die("<div style='text-align:center;padding:2rem;font-family:sans-serif;'>❌ Token is required.</div>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Geelex Logistics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f2f4f8;
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 400px;
            margin: 10vh auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        form button {
            width: 100%;
            padding: 12px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #005bb5;
        }
        .message {
            padding: 12px;
            margin-top: 15px;
            border-radius: 6px;
            font-size: 15px;
        }
        .success {
            background-color: #e0f8e9;
            color: #2e7d32;
        }

        @media (max-width: 480px) {
            .form-container {
                margin: 5vh 15px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Reset Password</h2>
    <form method="post">
        <input type="password" name="new_password" placeholder="New Password" required>
        <button type="submit">Reset Password</button>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
