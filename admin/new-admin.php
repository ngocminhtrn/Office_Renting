<?php 

    ob_start();
    require_once "functions/db.php";

    // If session variable is not set it will redirect to login page
    session_start();

    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){

      header("location: login.php");

      exit;
    }

    if(!isset($_SESSION['role']) ||$_SESSION['role'] == 'level 0' ){

        header("location: login.php");
  
        exit;
      }

    if (is_logged_in_temporary()) {
    // Allow access
    $email = $_SESSION['email'];

    // Initialize error variables
    $name_err = $email_err = $role_err = $password_err = $confirm_password_err = $successmessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $response = ["errors" => [], "success" => false];
    
        $name = trim($_POST["uname"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $role = trim($_POST["role"] ?? "");
        $password = trim($_POST["password"] ?? "");
        $confirm_password = trim($_POST["password2"] ?? "");
    
        // Validate name
        if (empty($name)) {
            $response["errors"]["name"] = "Please enter a name.";
        } elseif (preg_match('/[0-9]/', $name)) {
            $response["errors"]["name"] = "Name cannot contain numbers.";
        }
    
        // Validate email
        if (empty($email)) {
            $response["errors"]["email"] = "Please enter an email.";
        } else {
            $sql = "SELECT id FROM admin WHERE email = ?";
            if ($stmt = mysqli_prepare($connection, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        $response["errors"]["email"] = "This email is already taken.";
                    }
                }
                mysqli_stmt_close($stmt);
            }
        }
    
        // Validate role
        if (empty($role)) {
            $response["errors"]["role"] = "Please select an admin role.";
        }
    
        // Validate password
        if (empty($password)) {
            $response["errors"]["password"] = "Please enter a password.";
        } elseif (strlen($password) < 6) {
            $response["errors"]["password"] = "Password must have at least 6 characters.";
        }
    
        // Validate confirm password
        if (empty($confirm_password)) {
            $response["errors"]["confirm_password"] = "Please confirm the password.";
        } elseif ($password !== $confirm_password) {
            $response["errors"]["confirm_password"] = "Passwords do not match.";
        }
        
        // Insert into database if no errors
        if (empty($response["errors"])) {
            $sql = "INSERT INTO admin (name, email, role, password, active) VALUES (?, ?, ?, ?, 1)";
            if ($stmt = mysqli_prepare($connection, $sql)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $role, $hashed_password);
                if (mysqli_stmt_execute($stmt)) {
                    $response["success"] = true;
                    $response["message"] = "Bạn đã tạo người dùng mới thành công!";
                }
                mysqli_stmt_close($stmt);
            }
        }
    
        // Return JSON response
        echo json_encode($response);
        exit;
    }
    /*******************************************************
                    introduce the admin header
    *******************************************************/
    require "admin_header0.php";


    /*******************************************************
                    Add the left panel
    *******************************************************/
    require "admin_left_panel.php";

    ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo $username;?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Admin</a></li>
                            <li class="active">Thêm</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Tạo admin</h3>
                            <p class="text-muted m-b-30 font-13"> Hãy điền thông tin phía dưới </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                        <?php
                                            // Retrieve error messages from session
                                            $errors = $_SESSION['errors'] ?? [];
                                            $name_err = $errors['name_err'] ?? "";
                                            $email_err = $errors['email_err'] ?? "";
                                            $role_err = $errors['role_err'] ?? "";
                                            $password_err = $errors['password_err'] ?? "";
                                            $confirm_password_err = $errors['confirm_password_err'] ?? "";
                                            $successmessage = $_SESSION['success_message'] ?? "";
                                            // Clear errors after displaying
                                            unset($_SESSION['errors']);
                                            ?>
                                            <?php if (!empty($successmessage)): ?>
                                                <p class="text-success"><?php echo $successmessage; ?></p>
                                            <?php endif; ?>
                                                <form id="adminForm" method="post">
                                                    <div id="errorMessages"></div>
                                                    <div class="form-group">
                                                        <label for="uname">Tên người dùng</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="fa fa-pencil"></i></div>
                                                            <input type="text" name="uname" class="form-control" id="uname" placeholder="e.g Nguyễn Văn A" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Email</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="ti-email"></i></div>
                                                            <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="example@gmail.com" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="role">Quyền admin</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="fa fa-institution"></i></div>
                                                            <select name="role" class="form-control" id="role" required>
                                                                <option value="">**Chọn quyền admin**</option>
                                                                <option value="level-0">Cấp độ 0</option>
                                                                <option value="level-1">Cấp độ 1</option>
                                                                <option value="level-2">Cấp độ 2</option>
                                                                
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="Password">Mật khẩu</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="ti-lock"></i></div>
                                                            <input type="password" name="password" id="Password" class="form-control" placeholder="Hãy điền mật khẩu" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="ConfirmPassword">Xác nhận mật khẩu</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class="ti-lock"></i></div>
                                                            <input type="password" name="password2" id="ConfirmPassword" class="form-control" placeholder="Hãy xác nhận mật khẩu" required>
                                                        </div>
                                                        <div id="msg" style="padding-left: 10px;"></div>
                                                    </div>
                                                    

                                                    <button type="submit" class="btn btn-success">Thêm admin</button>
                                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
           
                    <div id="successPopup" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 1000; width: 300px; background: #d4edda; color: #155724; border-left: 5px solid #28a745; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 4px;">
                        <div style="padding: 10px; font-size: 16px; font-weight: bold; background: #28a745; color: white; border-radius: 4px 4px 0 0;">
                            Thông báo
                        </div>
                        <div style="padding: 10px;">
                            Bạn đã thêm mới thành công
                        </div>
                    </div>
                </div>
                <!--./row-->
            
                <!-- /.right-sidebar -->
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> 2024 &copy; Tạo Admin </footer>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/tether.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="js/jasny-bootstrap.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>

    <!-- CHECK IF PASSWORDS MATCH -->
        <script>
                
            function showSuccessPopup() {
                const popup = document.getElementById('successPopup');
                popup.style.display = 'block';

                // Auto-hide after 3 seconds
                setTimeout(() => {
                    popup.style.display = 'none';
                }, 3000);
            }
            $(document).ready(function () {
                $("#adminForm").on("submit", function (e) {
                    e.preventDefault(); // Prevent default form submission

                    $.ajax({
                        url: "", // Current page
                        method: "POST",
                        data: $(this).serialize(),
                        success: function (response) {
                            console.log(response); // Log the entire response for debugging
                            const data = JSON.parse(response); // Parse JSON response
                            if (data.errors) {
                                let errorHTML = "";
                                for (let field in data.errors) {
                                    errorHTML += `<p class="text-danger">${data.errors[field]}</p>`;
                                }
                                $("#errorMessages").html(errorHTML);
                            }
                            if (data.success) {
                                // Show success popup
                                showSuccessPopup();
                                setTimeout(() => {
                                    window.location.href = "admins.php"; // Redirect after success
                                }, 3000);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX Error:", xhr.responseText); // Log the error response for debugging
                            $("#errorMessages").html("<p class='text-danger'>Something went wrong. Please try again later.</p>");
                        },
                    });
                });
            });
            $(document).ready(function(){
                    $("#ConfirmPassword").keyup(function(){
                         if ($("#Password").val() != $("#ConfirmPassword").val()) {
                             $("#msg").html("Mật khẩu không giống nhau").css("color","red");
                         }else{
                             $("#msg").html("Mật khẩu giống nhau").css("color","green");
                        }
                  });
            });
            </script> 
    <!--END CHECK IF PASSWORDS MATCH -->

</body>

</html>
<?php
}
else{
    header('location:../login.php');
}
?>
