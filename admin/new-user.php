<?php 

ob_start();
require_once "functions/db.php";

// If session variable is not set it will redirect to login page
session_start();

if(!isset($_SESSION['email']) || empty($_SESSION['email'])){

  header("location: login.php");

  exit;
}
if (is_logged_in_temporary()) {
    // Allow access
    $email = $_SESSION['email'];
    $houses = [];
    $sql_houses = "SELECT houseID, house_name FROM houses";
    $result_houses = mysqli_query($connection, $sql_houses);
    if ($result_houses && mysqli_num_rows($result_houses) > 0) {
        while ($row = mysqli_fetch_assoc($result_houses)) {
            $houses[] = $row;
        }
    }
    // Initialize error variables
    $name_err = $email_err = $phone_err = $password_err = $confirm_password_err = $house_err = $successmessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json'); // Ensure response is JSON
    $response = ["errors" => [], "success" => false];

    $tenant_name = trim($_POST["tenant_name"] ?? "");
    $user_email = trim($_POST["email"] ?? "");
    $phone_number = trim($_POST["phone_number"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirm_password = trim($_POST["password2"] ?? "");
    $house_id = trim($_POST["house_id"] ?? "");

    // Validation
    if (empty($tenant_name)) {
        $response["errors"]["tenant_name"] = "Vui lòng nhập tên người dùng.";
    }

    if (empty($user_email)) {
        $response["errors"]["email"] = "Vui lòng nhập email.";
    } else {
        $sql = "SELECT tenantID FROM tenants WHERE email = ?";
        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $user_email);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $response["errors"]["email"] = "Email này đã được sử dụng.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    }

    if (empty($phone_number)) {
        $response["errors"]["phone_number"] = "Vui lòng điền số điện thoại.";
    } elseif (!preg_match('/^[0-9 +-]*$/', $phone_number)) {
        $response["errors"]["phone_number"] = "Điện thoại chỉ có thể có số.";
    }

    if (empty($password)) {
        $response["errors"]["password"] = "Vui lòng nhập mật khẩu.";
    } elseif (strlen($password) < 6) {
        $response["errors"]["password"] = "Mật khẩu phải có ít nhất 6 ký tự.";
    }

    if ($password !== $confirm_password) {
        $response["errors"]["confirm_password"] = "Mật khẩu không khớp.";
    }

    if (empty($house_id)) {
        $response["errors"]["house_id"] = "Vui lòng chọn nhà.";
    }
    $account = '';
    if (empty($response["errors"])) {
        $sql = "INSERT INTO tenants (tenant_name, email, password, phone_number, houseNumber, dateAdmitted, account) VALUES (?, ?, ?, ?, ?, NOW(), ?)";
        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssss", $tenant_name, $user_email, $password, $phone_number, $house_id, $account);
            if (mysqli_stmt_execute($stmt)) {
                $response["success"] = true;
                $response["message"] = "Bạn đã tạo người dùng mới thành công!";
            } else {
                $response["errors"]["database"] = "Lỗi khi thêm dữ liệu vào cơ sở dữ liệu.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    echo json_encode($response);
    exit;
}
}

    // Initialize the sessio
    if (is_logged_in_temporary()) {
        //allow access

    $email = $_SESSION['email'];

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
                        <h4 class="page-title"><?php echo 'Xin chào '.$username.'!';?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="index.php">Dashboard</a></li>
                            <li><a href="tenants.php">Người dùng</a></li>
                            <li class="active">Tạo người dùng</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0"><i class="fa fa-user fa-3x"></i> Tạo người dùng mới</h3>
                            <p class="text-muted m-b-30 font-13"> Hãy điền thông tin phía dưới </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <?php
                                        // Retrieve error messages from session
                                        $errors = $_SESSION['errors'] ?? [];
                                        $name_err = $errors['tenant_name_err'] ?? "";
                                        $email_err = $errors['email_err'] ?? "";
                                        $phone_err = $errors['phone_number_err'] ?? "";
                                        $house_err = $errors['house_id_err'] ?? "";
                                        $password_err = $errors['password_err'] ?? "";
                                        $confirm_password_err = $errors['confirm_password_err'] ?? "";
                                        $successmessage = $_SESSION['success_message'] ?? "";
                                        // Clear errors after displaying
                                        unset($_SESSION['errors']);
                                    ?>
                                    <?php if (!empty($successmessage)): ?>
                                        <p class="text-success"><?php echo $successmessage; ?></p>
                                    <?php endif; ?>
                                    <form id="newUserForm" method="post">
                                        <div id="errorMessages"></div>
                                        <div class="form-group">
                                            <label for="hname">Tên người dùng: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-pencil"></i></div>
                                                <input type="text" name="tenant_name" class="form-control" id="hname" placeholder="Hãy điền tên người dùng" required=""> </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="temail">Email: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-at"></i></div>
                                                <input type="email" name="email" class="form-control" id="temail" placeholder="example@gmail.com" required=""> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="house">Chọn nhà: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-home"></i></div>
                                                <select name="house_id" class="form-control" id="house" required>
                                                    <option value="">-- Chọn nhà --</option>
                                                    <?php foreach ($houses as $house): ?>
                                                        <option value="<?php echo $house['houseID']; ?>">
                                                            <?php echo $house['house_name']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Mật khẩu: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                                                <input type="password" name="password" class="form-control" id="password" placeholder="Hãy điền mật khẩu" required=""> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputpwd2">Xác nhận mật khẩu: *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                                                <input type="password" name="password2" id="ConfirmPassword" class="form-control" id="exampleInputpwd2" placeholder="Hãy xác nhận mật khẩu" required=""> </div>
                                                <div id="msg" style="padding-left: 10px;"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Số điện thoại: </label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                                <input type="text" name="phone_number" class="form-control" id="phone" placeholder="e.g 0912345678" required=""> </div>
                                        </div>
                                        <button type="submit" class="btn btn-success">Thêm người dùng</button>
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
               
           
               
               
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> 2024 &copy; Thêm người dùng </footer>
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

    <!-- Local Javascript -->
        <script type="text/javascript">
            
        </script>
    <!--END of local JS -->
                <!-- CHECK IF PASSWORDS MATCH -->
                <script>
                $(document).ready(function(){
                    $("#ConfirmPassword").keyup(function(){
                         if ($("#password").val() != $("#ConfirmPassword").val()) {
                             $("#msg").html("Mật khẩu không giống nhau").css("color","red");
                         }else{
                             $("#msg").html("Mật khẩu giống nhau").css("color","green");
                        }
                  });
                });
                function showSuccessPopup() {
                const popup = document.getElementById('successPopup');
                popup.style.display = 'block';

                // Auto-hide after 3 seconds
                setTimeout(() => {
                    popup.style.display = 'none';
                }, 3000);
            }
            $(document).ready(function () {
                $("#newUserForm").on("submit", function (e) {
                    e.preventDefault(); // Prevent default form submission

                    // Clear previous error messages
                    $("#errorMessages").html("");

                    // Perform the AJAX request
                    $.ajax({
                        url: "", // Use the current page for form processing
                        method: "POST",
                        data: $(this).serialize(),
                        dataType: "json", // Expect JSON response
                        beforeSend: function () {
                            // Optionally disable the submit button and show a loading state
                            $("button[type='submit']").prop("disabled", true).text("Đang xử lý...");
                        },
                        success: function (response) {
                            // Check for errors in the response
                            if (response.errors && Object.keys(response.errors).length > 0) {
                                let errorHTML = "";
                                for (const [key, value] of Object.entries(response.errors)) {
                                    errorHTML += `<p class="text-danger">${value}</p>`;
                                }
                                $("#errorMessages").html(errorHTML);
                            }

                            // If success is true, show a success message or perform actions
                            if (response.success) {
                                // Show success popup
                                showSuccessPopup();

                                // Redirect or reload after a short delay
                                setTimeout(() => {
                                    window.location.href = "users.php"; // Adjust this URL as needed
                                }, 3000);
                            }
                        },
                        error: function (xhr, status, error) {
                            // Handle any unexpected errors
                            console.error("AJAX Error:", xhr.responseText);
                            $("#errorMessages").html(
                                "<p class='text-danger'>Có lỗi xảy ra trong hệ thống. Vui lòng thử lại sau.</p>"
                            );
                        },
                        complete: function () {
                            // Re-enable the submit button
                            $("button[type='submit']").prop("disabled", false).text("Thêm người dùng");
                        },
                    });
                });
            });
        
            </script> 
    <!--END CHECK IF PASSWORDS MATCH -->
</body>

</html>
<?php
}
else{
    header('location:../index.php');
}
?>
