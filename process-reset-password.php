<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/connection.php";

$sql = "SELECT * FROM ladybugusers
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

if (strlen($_POST["password"]) < 8) {
  echo "<script> alert('Password must be at least 8 characters')</script>";
 }
 
 if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
   echo "<script> alert('Password must contain at least one letter')</script>";
 }
 
 if ( ! preg_match("/[0-9]/", $_POST["password"])) {
   echo "<script> alert('Password must contain at least one number')</script>";
 }
 
 if ($_POST["password"] !== $_POST["password_confirmation"]) {
   echo "<script> alert('Passwords must match')</script>";
 }

 $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

 $sql = "UPDATE ladybugusers
        SET password_hash = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $password_hash, $user["id"]);

$stmt->execute();

echo "Password updated. You can now login.";

header("Location: http://localhost/php_backend/login.php");