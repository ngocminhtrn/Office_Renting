<?php

    ob_start();
    require_once "functions/db.php";

    // Initialize the session
    session_start();

    // If session variable is not set, redirect to login page
    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
        header("location: login.php");
        exit;
    }

    if (is_logged_in_temporary()) {
        $email = $_SESSION['email'];

        $sql = "SELECT * FROM admin WHERE email != '$email'";
        $query = mysqli_query($connection, $sql);

        require "admin_header0.php";
        require "admin_left_panel.php";
?>

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo $username;?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Admin</a></li>
                    <li class="active"><a href="new-admin.php" class="btn btn-success btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Tạo admin</a></li>
                </ol>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <?php
                        if (isset($_GET["success"])) {
                            if ($_GET["success"] == "edit") {
                                echo '<div class="alert alert-success">Tài khoản admin đã được cập nhật thành công.</div>';
                            } elseif ($_GET["success"] == "create") {
                                echo '<div class="alert alert-success"><strong>DONE!! </strong><p> Tài khoản admin mới đã được tạo thành công.</p></div>';
                            }
                        } elseif (isset($_GET["deleted"])) {
                            echo '<div class="alert alert-warning"><strong>DELETED!! </strong><p> Tài khoản admin đã được xóa thành công.</p></div>';
                        } elseif (isset($_GET["del_error"])) {
                            echo '<div class="alert alert-danger"><strong>ERROR!! </strong><p> Hiện đang xảy ra lỗi. Xin hãy thử lại sau.</p></div>';
                        }
                    ?>  

                    <h3 class="box-title m-b-0">Tài khoản admin ( <x style="color: orange;"><?php echo mysqli_num_rows($query);?></x> )</h3>
                    <div class="table-responsive">
                        <table id="example23" class="display nowrap" cellspacing="0" width="100%">
                            <?php 
                                if (mysqli_num_rows($query) == 0) {
                                    echo "<i style='color:brown;'>Không có tài khoản admin nào :( </i> ";
                                } else {
                                    echo '
                                        <thead>
                                            <tr>
                                                <th>Tên</th>
                                                <th>Email</th>
                                                <th>Quyền</th>
                                                <th>Ngày tạo</th>
                                                <th>Ngày sửa</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    ';
                                }

                            
                    while ($row = mysqli_fetch_array($query)) {
                    echo '
                        <tr>
                          <td>'.$row["name"].'</td>
                          <td>'.$row["email"].'</td>
                          <td style="padding-left: 20px;" >'.$row["role"].'</td>
                          <td>'.$row["Create_at"].'</td>
                          <td>'.$row["update_at"].'</td>
                          <td>
                           <a href="#" data-toggle="modal" data-target="#edit-modal' . $row["id"].'">
                            <i class="fa fa-pencil" style="color:blue; margin-left: 20px; " title="Chỉnh sửa"></i>
                           </a>
                            &nbsp; <!-- Add some spacing between icons -->
                           <a href="#" data-toggle="modal" data-target="#responsive-modal' . $row["id"].'">
                            <i class="fa fa-trash" style="color:red; margin-left: 25px; " title="Xóa"></i>
                           </a>
                          </td>
                        </tr>
                        
        
        <!-- Edit Modal -->
        <div id="edit-modal' . $row["id"] . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel' . $row["id"] . '" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Chỉnh sửa tài khoản admin</h4>
                    </div>
                    <form action="edit_admin.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="' . $row["id"] . '">
                            <div class="form-group">
                                <label for="name">Tên:</label>
                                <input type="text" name="name" class="form-control" value="'.$row['name'].'" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" name="email" class="form-control" value="' .$row['email']. '" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Quyền admin</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-institution"></i></div>
                                        <select name="role" class="form-control" id="role" required>
                                            <option value="">**Chọn quyền admin**</option>
                                            <option value="level-0" ' . ($row["role"] == "level-0" ? "selected" : "") . '>Cấp độ 0</option>
                                            <option value="level-1" ' . ($row["role"] == "level-1" ? "selected" : "") . '>Cấp độ 1</option>
                                            <option value="level-2" ' . ($row["role"] == "level-2" ? "selected" : "") . '>Cấp độ 2</option>
                                                                                    
                                        </select>
                                    </div>
                                </div>
                            <div class="form-group">
                                <label for="password">Mật khẩu:</label>
                                <input type="password" name="password" class="form-control" minlength="6" placeholder="Nhập ít nhất 6 ký tự" >
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Quay lại</button>
                            <button type="submit" class="btn btn-primary">Thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        
        <!-- Delete Modal -->
        <div id="responsive-modal' . $row["id"] . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Bạn có chắc muốn xóa tài khoản admin này không?</h4>
                    </div>
                    <div class="modal-footer">
                        <form action="functions/del_admin.php" method="post">
                            <input type="hidden" name="id" value="' . $row["id"] . '"/>
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Quay lại</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    ';
}
?>

                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php require "admin_footer.php"; ?>
    
    <script>
        $(document).ready(function() {
            $('#example23').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [0,1,2] 
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [0,1,2]
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0,1,2]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0,1,2]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0,1,2]
                        }
                    }
                ]
            });
        });
    </script>

    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>

<?php
}
else {
    header('location:index.php');
}
?>
