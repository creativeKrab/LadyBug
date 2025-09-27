<?php

$token = $_GET["token"];

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="signupstyles.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="about.html">About Us</a></li>
        <li><a href="contactus.html">Contact Us</a></li>
    </ul> 
</nav>
   <div class = "main">  
   <h1 class="hello">Reset your Password</h1> 
   <p></p>
    <form method="post" action="process-reset-password.php">

      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

      <label for="password">Enter new password</label>
      <input type="password" id="password" name="password">

      <label for="password_confirmation">Confirm password</label>
      <input type="password" id="password_confirmation"
            name="password_confirmation">
      <p></p>
      <button>Send</button>
     </form>
    </div>
  <footer> <p>@LadyBug, All rights reserved.</p> </footer>
</body>
</html>
