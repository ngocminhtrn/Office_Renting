<?php
require "functions/db.php"; // Include your database connection

// Initialize variables for success or error message
$activation_message = "";
$success = false;

if (isset($_GET['code'])) {
    $activation_code = $_GET['code'];

    $sql = "SELECT id FROM admin WHERE activation_code = ? AND activation_expiry > NOW()";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $activation_code);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Activate the account
                mysqli_stmt_bind_result($stmt, $id);
                mysqli_stmt_fetch($stmt);

                $update_sql = "UPDATE admin SET active = 1, activated_at = NOW(), activation_code = NULL, activation_expiry = NULL WHERE id = ?";
                if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, "i", $id);
                    mysqli_stmt_execute($update_stmt);
                    mysqli_stmt_close($update_stmt);

                    $success = true;
                    $activation_message = "You have successfully activated your account!";
                }
            } else {
                $activation_message = "Invalid or expired activation link.";
            }
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $activation_message = "No activation code provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Activation</title>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .hidden { display: none; }
        .modal-content {
            text-align: center;
            padding: 20px;
        }
        .btn-login {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<section id="wrapper" class="login-register">
    <div class="login-box">
        <div class="white-box">
            <!-- Modal -->
            <div class="modal-content">
                <?php if ($success): ?>
                    <h3 class="box-title m-b-20 text-center text-success">Success</h3>
                    <p><?php echo $activation_message; ?></p>
                    <button class="btn btn-info btn-lg btn-login" onclick="window.location.href='login.php'">Login</button>
                <?php else: ?>
                    <h3 class="box-title m-b-20 text-center text-danger">Error</h3>
                    <p><?php echo $activation_message; ?></p>
                    <button class="btn btn-info btn-lg btn-login" onclick="window.location.href='login.php'">Back to Login</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script src="bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>