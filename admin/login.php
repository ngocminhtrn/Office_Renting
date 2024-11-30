<?php
ob_start();
session_start();
/* DATABASE CONNECTION */
require "functions/db.php";
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';
require './PHPMailer-master/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$conn = $connection; // Ensure the database connection is properly assigned

// Default form state
$form_state = 'login'; // By default, show the login form

// Initialize variables for errors and success messages
$email_err = $name_err = $password_err = $confirm_password_err = $register_success = $reset_success = $reset_email_err = "";

// Handle Login Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $form_state = 'login'; // Ensure the login form stays active
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate input fields
    if (empty($email)) {
        $email_err = 'Please enter your email.';
    }
    if (empty($password)) {
        $password_err = 'Please enter your password.';
    }

    // If no errors, proceed with database query
    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT email, password, role, active FROM admin WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $db_email, $db_password, $db_role, $active);

                    if (mysqli_stmt_fetch($stmt)) {
                        // Verify the password
                        if (password_verify($password, $db_password) && $active = 1) {
                            // Set session and redirect to the dashboard
                            $_SESSION['email'] = $db_email;
                            $_SESSION['role'] = $db_role;
                            header("Location: index.php");
                            exit;
                        } else {
                            $form_state = 'login';
                            $password_err = 'Invalid password.';
                        }
                    }
                } else {
                    $form_state = 'login';
                    $email_err = 'No account found with this email.';
                }
            } else {
                $form_state = 'login';
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Handle Registration Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $form_state = 'register'; // Stay on the register form if submission fails
    $email = trim($_POST["email"]);
    $name = trim($_POST["name"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($email)) {
        $email_err = 'Please enter an email.';
    } else {
        $sql = "SELECT id FROM admin WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $email_err = 'This email is already taken.';
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    if (empty($name)) {
        $name_err = "Please enter a name.";
    } elseif (preg_match('/[0-9]/', $name)) {
        $name_err = "Name cannot contain numbers.";
    }

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

    // Generate activation code and expiry
    $activation_code = bin2hex(random_bytes(16)); // Random 32-character string
    $activation_expiry = date('Y-m-d H:i:s', strtotime('+1 day')); // Expires in 24 hours

    // If no errors, proceed with registration
    if (empty($email_err) && empty($name_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO admin (email, name, password, activation_code, activation_expiry) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "sssss", $email, $name, $hashed_password, $activation_code, $activation_expiry);
            if (mysqli_stmt_execute($stmt)) {
                

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'ksv36519@gmail.com'; // Your Gmail address
                    $mail->Password = 'dhye ucgh vzfo nfru'; // Gmail App Password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true,
                        ),
                    );
                    $mail->setFrom('ksv36519@gmail.com', 'Admin');
                    $mail->addAddress($email);

                    $activation_link = "http://localhost/Office_Renting/admin/activate.php?code=" . urlencode($activation_code);

                    $mail->isHTML(true);
                    $mail->Subject = 'Account Activation';
                    $mail->Body = "<p>Click <a href='{$activation_link}'>here</a> to activate your account. This link will expire in 24 hours.</p>";

                    $mail->send();
                    $register_success = "Registration successful! Check your email to activate your account.";
                    $form_state = 'register'; // Switch to login form
                } catch (Exception $e) {
                    // Email sending failed, delete the user
                    $delete_sql = "DELETE FROM admin WHERE email = ?";
                    if ($delete_stmt = mysqli_prepare($conn, $delete_sql)) {
                        mysqli_stmt_bind_param($delete_stmt, "s", $email);
                        mysqli_stmt_execute($delete_stmt);
                        mysqli_stmt_close($delete_stmt);
                        $form_state = 'register';
                    }
                    $email_err =  $e;
                }
            } else {
                $form_state = 'register';
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['forgot_password'])) {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        $reset_email_err = 'Please enter your email.';
    } else {
        // Check if the email exists in the database
        $sql = "SELECT id FROM admin WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Generate reset token and expiry
                    $reset_token = bin2hex(random_bytes(16));
                    $reset_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    // Update the database with reset token and expiry
                    $update_sql = "UPDATE admin SET activation_code = ?, activation_expiry = ? WHERE email = ?";
                    if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                        mysqli_stmt_bind_param($update_stmt, "sss", $reset_token, $reset_expiry, $email);
                        mysqli_stmt_execute($update_stmt);
                        mysqli_stmt_close($update_stmt);

                        // Send reset email
                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'ksv36519@gmail.com'; // Your Gmail address
                            $mail->Password = 'dhye ucgh vzfo nfru'; // Gmail App Password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;
                            $mail->SMTPOptions = array(
                                'ssl' => array(
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true,
                                ),
                            );
                            $mail->setFrom('ksv36519@gmail.com', 'Admin');
                            $mail->addAddress($email);

                            $reset_link = "http://localhost/Office_Renting/admin/changepassword.php?token=" . urlencode($reset_token);

                            $mail->isHTML(true);
                            $mail->Subject = 'Password Reset';
                            $mail->Body = "<p>Click <a href='{$reset_link}'>here</a> to reset your password. This link will expire in 1 hour.</p>";
                            $mail->send();
                            $form_state = 'forgot_password';
                            $reset_success = "Password reset email sent! Check your email.";
                        } catch (Exception $e) {
                            $reset_email_err = "Failed to send email. Error: {$mail->ErrorInfo}";
                        }
                    }
                } else {
                    $reset_email_err = 'No account found with this email.';
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Office Renting Website - Login</title>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>
<section id="wrapper" class="login-register">
    <div class="login-box">
        <div class="white-box">

            <!-- Login Form -->
            <form class="form-horizontal form-material <?php echo ($form_state === 'login') ? '' : 'hidden'; ?>" id="loginform" action="login.php" method="post">
                <h3 class="box-title m-b-20 text-center">Login</h3>
                <?php if (!empty($email_err)) echo '<p class="text-danger">'.$email_err.'</p>'; ?>
                <?php if (!empty($password_err)) echo '<p class="text-danger">'.$password_err.'</p>'; ?>
                <div class="form-group">
                    <input class="form-control" type="email" name="email" required placeholder="Email">
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="password" required placeholder="Password">
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-info btn-lg btn-block" type="submit" name="login">Login</button>
                </div>
                <div class="form-group text-center">
                    <a href="javascript:void(0)" id="to-register" class="text-dark">Register</a> |
                    <a href="javascript:void(0)" id="to-forgot" class="text-dark">Forgot Password?</a>
                </div>
            </form>

            <!-- Register Form -->
            <form class="form-horizontal form-material <?php echo ($form_state === 'register') ? '' : 'hidden'; ?>" id="registerform" action="login.php" method="post">
                <h3 class="box-title m-b-20 text-center">Register</h3>
                <?php if (!empty($register_success)) echo '<p class="text-success text-center">'.$register_success.'</p>'; ?>
                <p class="text-danger"><?php echo $email_err; ?></p>
                <p class="text-danger"><?php echo $name_err; ?></p>
                <p class="text-danger"><?php echo $password_err; ?></p>
                <p class="text-danger"><?php echo $confirm_password_err; ?></p>
                <div class="form-group">
                    <input class="form-control" type="email" name="email" required placeholder="Email">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="name" required placeholder="Name">
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="password" required placeholder="Password">
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="confirm_password" required placeholder="Confirm Password">
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-success btn-lg btn-block" type="submit" name="register">Register</button>
                </div>
                <div class="form-group text-center">
                    <a href="javascript:void(0)" id="back-to-login-register" class="text-dark">Back to Login</a>
                </div>
            </form>

            <!-- Forgot Password Form -->
            <form class="form-horizontal form-material <?php echo ($form_state === 'forgot_password') ? '' : 'hidden'; ?>" id="forgotform" action="login.php" method="post">
                <h3 class="box-title m-b-20 text-center">Forgot Password</h3>
                <?php if (!empty($reset_success)) echo '<p class="text-success text-center">'.$reset_success.'</p>'; ?>
                <p class="text-danger"><?php echo $reset_email_err; ?></p>
                
                <div class="form-group">
                    <input class="form-control" type="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary btn-lg btn-block" type="submit" name="forgot_password">Send Reset Link</button>
                </div>
                <div class="form-group text-center">
                    <a href="javascript:void(0)" id="back-to-login-forgot" class="text-dark">Back to Login</a>
                </div>
            </form>

        </div>
    </div>
</section>

<!-- JavaScript for Toggling Forms -->
<script>
    document.getElementById("to-register").addEventListener("click", function() {
        document.getElementById("loginform").classList.add("hidden");
        document.getElementById("registerform").classList.remove("hidden");
        document.getElementById("forgotform").classList.add("hidden");
    });

    document.getElementById("to-forgot").addEventListener("click", function() {
        document.getElementById("loginform").classList.add("hidden");
        document.getElementById("forgotform").classList.remove("hidden");
        document.getElementById("registerform").classList.add("hidden");
    });

    document.getElementById("back-to-login-register").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("registerform").classList.add("hidden");
        document.getElementById("loginform").classList.remove("hidden");
    });

    document.getElementById("back-to-login-forgot").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("forgotform").classList.add("hidden");
        document.getElementById("loginform").classList.remove("hidden");
    });
</script>
</body>
</html>
