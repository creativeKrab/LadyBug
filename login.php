<?php
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mysqli = require __DIR__ . "/connection.php";
    
    $sql = sprintf("SELECT * FROM ladybugusers
                    WHERE email = '%s'",
                   $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    
    if ($user) {
        
        if (password_verify($_POST["password"], $user["password_hash"])) {
            
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["id"];
            
            header("Location: http://localhost/php_backend/ladybugwebsite/mainpage.html");
            exit;
        }
    }
    
    $is_invalid = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Eagle+Lake&family=Explora&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Eagle+Lake&family=Explora&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginstyles.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="about.html">About Us</a></li>
            <li><a href="contactus.html">Contact Us</a></li>
        </ul> 
    </nav>
    <div class="main">
        <p class="hello">Hello Human!</p>
        <p class="log">LOGIN</p>
        <?php if ($is_invalid): ?>
           <em>Invalid login</em>
        <?php endif; ?>
        
        <form method="post">
            <label for="email">Enter your Email</label>
            <input type="email" name="email" id="email"
                value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
            
            <label for="password">Enter your Password</label>
            <input type="password" name="password" id="password">
            <button>Login</button>
        </form>
    <div class ="opt">
        <a href="http://localhost/php_backend/signup.html" id="newAccountLink" class="pass1">New User? Create your account!</a>
        <a href="forgot_password.php" class="pass1">Forgot password?</a>
    </div>
        <p id="or">OR</p>
        <button type = "submit" id="google-login-btn" class="google"> 
            <img src="google.svg" alt="" class="goo">
            <span>Sign in with Google</span>
        </button>
    </div>

    <img src="ladybug.svg" alt="" class="bug">
    <footer>
        <p>@LadyBug, All rights reserved</p>
    </footer>

    <script src="js/loginjsnew.js"></script>
    <script src="signin.js" defer type ="module"></script>
</body>
</html>
