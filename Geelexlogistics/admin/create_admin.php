<?php
// Enable error reporting to catch issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to your database
$host = 'localhost';
$db   = 'dbtckaig_geelex_logistics';  // change this to your actual DB name
$user = 'dbtckaig_Bruno';     	
       // your DB username (default in XAMPP is 'root')
$pass = '95U4U1tN&6*)';   // change this to your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Hash the password
    $password = password_hash("admin123", PASSWORD_DEFAULT);

    // Insert into admins table
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute(["admin", $password]);

    echo "Admin account created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
