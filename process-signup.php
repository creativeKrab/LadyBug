<?php

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
  echo "<script> alert('Valid email is required') </script>";
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

$mysqli = require __DIR__ . "/connection.php";

$sql = "INSERT INTO ladybugusers (email, password_hash)
        VALUES  (?, ?)";

$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
  echo "<script>alert('SQL error: " . $mysqli->error . "');</script>";
}

$stmt->bind_param("ss", 
                   $_POST["email"], 
                   $password_hash);

if ($stmt->execute()) {
    header("Location: http://localhost/php_backend/login.php");
    exit;

} else {
    if ($mysqli->errno === 1062) {
      echo "<script> alert('Email already taken')</script>";
  } else {
      echo "<script> alert(''Error: " . $stmt->error . " (" . $stmt->errno . ")'')</script>";
  }
}