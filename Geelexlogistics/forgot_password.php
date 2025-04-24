<?php
require_once 'db.php';
require_once 'send_email.php';

$message = '';
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("UPDATE clients SET reset_token = ? WHERE email = ?");
        $stmt->execute([$token, $email]);

        $resetLink = "http://geelexlogistics.co.ke/Geelexlogistics/reset_password.php?token=$token";

        if (sendPasswordReset($email, $resetLink)) {
            $message = "✅ A password reset link has been sent to your email.";
            $messageClass = "success";
        } else {
            $message = "❌ Failed to send email. Please try again later.";
            $messageClass = "error";
        }

    } else {
        $message = "⚠️ Email not found.";
        $messageClass = "warning";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Geelex Logistics</title>
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
        form input[type="email"] {
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
        .error {
            background-color: #fce4e4;
            color: #c62828;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
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
    <h2>Forgot Password</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
