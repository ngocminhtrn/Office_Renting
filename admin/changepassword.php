<?php
ob_start();
session_start();
/* DATABASE CONNECTION */
require "functions/db.php";

// Initialize variables for errors and success messages
$password_err = $confirm_password_err = $reset_success = $reset_error = "";

// Handle Password Reset Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $token = $_POST['token'];
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($password)) {
        $password_err = 'Please enter a password.';
    } elseif (strlen($password) < 6) {
        $password_err = 'Password must have at least 6 characters.';
    }

    if (empty($confirm_password)) {
        $confirm_password_err = 'Please confirm the password.';
    } elseif ($password != $confirm_password) {
        $confirm_password_err = 'Passwords do not match.';
    }

    if (empty($password_err) && empty($confirm_password_err)) {
        // Verify reset token and expiry
        $sql = "SELECT id FROM admin WHERE activation_code = ? ";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $token);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id);
                    mysqli_stmt_fetch($stmt);

                    // Update password
                    $update_sql = "UPDATE admin SET password = ?, activation_code = NULL, activation_expiry = NULL WHERE id = ?";
                    if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($update_stmt, "si", $hashed_password, $id);
                        mysqli_stmt_execute($update_stmt);
                        mysqli_stmt_close($update_stmt);

                        $reset_success = "Your password has been reset successfully!";
                    }else{
                        $reset_error = "something when wrong.";
                    }
                } else {
                    $reset_error = $token;
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
} elseif (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<section id="wrapper" class="login-register">
    <div class="login-box">
        <div class="white-box">
            <form class="form-horizontal form-material" id="resetform" action="changepassword.php" method="post">
                <h3 class="box-title m-b-20 text-center">Change Password</h3>
                <?php if (!empty($reset_success)) echo '<p class="text-success text-center">'.$reset_success.'</p>'; ?>
                <?php if (!empty($reset_error)) echo '<p class="text-danger text-center">'.$reset_error.'</p>'; ?>
                <input type="hidden" name="token" value="<?php echo isset($token) ? htmlspecialchars($token) : ''; ?>">
                <p class="text-danger"><?php echo $password_err; ?></p>
                <p class="text-danger"><?php echo $confirm_password_err; ?></p>
                <div class="form-group">
                    <input class="form-control" type="password" name="password" required placeholder="New Password">
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="confirm_password" required placeholder="Confirm Password">
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-success btn-lg btn-block" type="submit" name="reset_password">Reset Password</button>
                    <a href="login.php" class="text-dark">Login</a> 
                </div>
            </form>
        </div>
    </div>
</section>
</body>
</html>