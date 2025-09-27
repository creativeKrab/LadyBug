<?php

$email = $_POST["email"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60*30);

$mysqli = require __DIR__ . "/connection.php";

$sql = "UPDATE ladybugusers
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);

$stmt ->bind_param("sss", $token_hash, $expiry, $email);

$stmt -> execute();

if ($mysqli->affected_rows) {
    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("theladybug4444@gmail.com"); 
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END
    
    Hey Human!

    We received a request to reset your password. Please click the link below to reset your password:

    <a href="http://localhost/php_backend/reset-password.php?token=$token">Reset Password</a>   
    
    If you did not request a password reset, please ignore this email or contact support if you have questions.

    Best regards,
    Team Ladybug

    END;

    try {

        $mail->send();

    } catch (Exception $e) {

        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";

    }
}

header("Location: http://localhost/php_backend/send-pass-reset.html");