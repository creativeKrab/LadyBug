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
    <div class="main">
    <h1 class="hello">Forgot Your Password?</h1>
    <p>A link will be sent to your registered email.</p>
    <form id="forgotPasswordForm" method="POST" action = "send-passwordreset.php">
        <label for="email">Enter your email address</label>
        <input type="email" id="email" name="email" required>
       <button>SEND LINK</button> 
    </form>
    <p style="margin-top: 60px; color: rgb(207, 80, 80)">Note:- Check your spam folder for the message.</p>
</div>

<footer>
      <p>@LadyBug, All rights reserved</p>
  </footer>

</body>
</html>
