<?php
session_start();
include '../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $connection->real_escape_string($_POST['email']);
    $pass = $connection->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $storedHash = $row['password'];

        if (password_verify($pass, $storedHash)) {
            // ✅ Set session before any output
            $_SESSION['email'] = $row["email"]; // lowercase to match DB
            header('Location: ../userdasboard/front.php');
            exit();
        } else {
            echo "<script>alert('Wrong password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="usercss.css">
  <title>Login</title>
</head>
<body>
  <div class="login-box">
    <h2>Login</h2>
    <form action="#" method="POST">
      <div class="input-group">
        <label for="login-email">Email Address</label>
        <input type="email" id="login-email" name="email" placeholder="Email" required>
      </div>
      <div class="input-group">
        <label for="login-password">Password</label>
        <input type="password" id="login-password" name="password" placeholder="Password" required>
      </div>
      <button type="submit">Login</button>
      <p class="note">
        Forgot your password? <a href="forgetpass.php">Forget Password</a><br />
        Don't have any account? <a href="signupofuser.php">Register</a>
      </p>
    </form>
  </div>
</body>
</html>
