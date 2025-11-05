<?php
include '../../connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Signup - Library System</title>
  <link rel="stylesheet" href="usercss.css">
</head>

<body>
  <div class="login-box">
    <h2>Sign Up</h2>
    <form action="signupofuser.php" method="post" enctype="multipart/form-data">
      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>
      </div>
      <div class="input-group">
        <label for="img">Image</label>
        <input type="file" id="img" name="img" required>
      </div>

      <div class="input-group">
        <label for="signup-email">Email Address</label>
        <input type="email" id="signup-email" name="email" placeholder="your@example.com" required>
      </div>
      <div class="input-group">
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>
      <div class="input-group">
        <label for="signup-password">Password</label>
        <input type="password" id="signup-password" name="password" placeholder="Enter your password" required>
      </div>
      <div class="input-group">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" name="cpassword" placeholder="Confirm your password" required>
      </div>
      <button type="submit">Sign Up</button>
      <p class="note">
        Already have an account? <a href="loginofuser.php">Login here</a>
      </p>
    </form>
  </div>



  <?php
  include '../../connection.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $connection->real_escape_string($_POST["username"]);
    $filename = $_FILES["img"]["name"];
    $tempname = $_FILES["img"]["tmp_name"];
    $folder = "../user/image/" . $filename;

    move_uploaded_file($tempname, $folder);

    $email = $connection->real_escape_string($_POST["email"]);
    $gender = $connection->real_escape_string($_POST["gender"]);
    $pass = $connection->real_escape_string($_POST["password"]);
    $cpassword = $connection->real_escape_string($_POST["cpassword"]);

    if ($pass === $cpassword) {

      $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);


      $sql = "INSERT INTO users (Username, Image  , Email, Gender, Password) 
                VALUES ('$username','$folder', '$email', '$gender', '$hashedPassword')";


      if ($connection->query($sql) === TRUE) {
        echo "<script>alert('Signup successful'); window.location.href='loginofuser.php';</script>";
        exit;
      } else {
        echo "<script>alert('Error:');</script> " . $connection->error;
      }
    } else {
      echo "<script>alert('Passwords do not match');</script>";
    }
  }
  ?>


</body>

</html>