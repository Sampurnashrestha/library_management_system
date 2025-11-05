<?php
include '../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $connection->real_escape_string($_POST['email']);
    $pass = $connection->real_escape_string($_POST['pass']);
    $cpass = $connection->real_escape_string($_POST['cpass']);

    if ($pass !== $cpass) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

        $update = "UPDATE users SET password='$hashedPassword' WHERE email='$email'";
        if ($connection->query($update) === TRUE) {
            echo "<script>alert('Password updated successfully!'); window.location.href='loginofuser.php';</script>";
        } else {
            echo "Error updating password: " . $connection->error;
        }
    } else {
        echo "<script>alert('Email not found!'); window.history.back();</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="usercss.css">
</head>

<body>
    <div class="login-box">
        <h2>Forgot Your Password?</h2>
        <form action="" method="POST">
            <div class="input-group">
                <label for="forgot-email">Email Address</label>
                <input type="email" id="forgot-email" name="email" placeholder="Email" required><br /><br />

                <label for="pass">New Password</label>
                <input type="password" id="pass" name="pass" placeholder="New Password" required><br /><br />

                <label for="cpass">Confirm Password</label>
                <input type="password" id="cpass" name="cpass" placeholder="Confirm Password" required><br /><br />
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>
