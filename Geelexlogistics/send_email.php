<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

function sendBookingEmail($toEmail, $pickupLocation, $itemType, $destinationLocation, $bookingTime) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'mail.geelexlogistics.co.ke'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'info@geelexlogistics.co.ke'; // your domain email
        $mail->Password = 'Geelex-2025?'; // your email password
        $mail->SMTPSecure = 'tls'; // or 'ssl'
        $mail->Port = 587; // 465 for SSL, 587 for TLS

        // Sender & recipient
        $mail->setFrom('info@geelexlogistics.co.ke', 'Geelex Logistics');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation - Geelex Logistics';
        $mail->Body = "
            Hi, <br><br>
            Your booking has been received. Here are the details:<br>
            <strong>Pickup Location:</strong> $pickupLocation <br>
            <strong>Item Type:</strong> $itemType <br>
            <strong>Destination Location:</strong> $destinationLocation <br>
            <strong>Booking Time:</strong> $bookingTime <br><br>
            Thank you for choosing Geelex Logistics!";
        
        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>