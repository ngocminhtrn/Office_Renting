<?php
    $pgnm="Người dùng";
    $error=' ';

    //require the global file for errors
    require_once "functions/errors.php";
    
    ob_start();
    require_once "functions/db.php";

    // Initialize the session

    session_start();

    // If session variable is not set it will redirect to login page

    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){

      header("location: login.php");

      exit;
    }

    if(!isset($_SESSION['role']) || $_SESSION['role'] == 'level-0' && $_SESSION['role'] == 'level-1'&& $_SESSION['role'] == 'level-2'){

        header("location: login.php");
  
        exit;
      }
    if (is_logged_in_temporary()) {
        #allow access
    

    $email = $_SESSION['email'];

   $sql = "SELECT `tenantID`,`houseNumber`,`tenant_name`,`email`, `password`,`phone_number`,`dateAdmitted`,`agreement_file`, `house_name`,`number_of_rooms`,`house_status`,`rent_amount`,`houseID` FROM `tenants`LEFT join `houses` ON `tenants`.`houseNumber`=`houses`.`houseID`";

   /*$sql="select * from `tenantsView`";*/

    $query = mysqli_query($connection, $sql);
    
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
                        <h4 class="page-title"> Xin chào <?php echo $username;?>,</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="index.php">Dashboard</a></li>
                            <li><a href="#" class="active">Người dùng</a></li>
                            
                            <?php if ( !$_SESSION['role'] == 'level-2') : ?>
                                <li><button href="new-user.php" type="submit" class="btn btn-success btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Thêm người dùng</button></li>
                            <?php else : ?>
                                <li>
                                    <button class="btn btn-success btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light disabled" onclick="showUnauthorizedPopup()">Thêm người dùng</button>
                                </li>
                            <?php endif; ?>
                            
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /row -->
                <div class="row">
                   
                    
                    <div class="col-sm-12">
                        <div class="white-box">

                        		<?php
                                echo $error;

                                if (isset($_GET["success"])) {
                                        echo 
                                        '<div class="alert alert-success" >
                                              <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                             <strong>DONE!! </strong><p> Người dùng mới đã được thêm thành công.</p>
                                        </div>'
                                        ;
                                    }
                                    elseif (isset($_GET["deleted"])) {
                                        echo 
                                        '<div class="alert alert-warning" >
                                              <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                             <strong>DELETED!! </strong><p> Người dùng đã được xóa thành công.</p>
                                        </div>'
                                        ;
                                    }
                                    elseif (isset($_GET["del_error"])) {
                                        echo 
                                        '<div class="alert alert-danger" >
                                              <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                             <strong>ERROR!! </strong><p> Đã xảy ra vấn đề. Xin vui lòng thử lại .</p>
                                        </div>'
                                        ;
                                    }
								?>	

                            <h3 class="box-title m-b-0">Danh sách người dùng ( <x style="color: orange;"><?php echo @mysqli_num_rows($query);?></x> )</h3>
                            <div class="table-responsive">
                                <table id="example23" class="display nowrap" cellspacing="0" width="100%">

                                <?php
                            if (@mysqli_num_rows($query) == 0) {
                                echo "<i style='color:brown;'>No Tenants to Display :( </i>";
                            } else {
                                echo '
                                <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>Căn hộ đang thuê</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Tiền thuê</th>
                                    <th>Ngày đăng kí</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody>';
                            }

                            while ($row = @mysqli_fetch_array($query)) {
                                echo '
                                <tr>
                                    <td>' . $row["tenant_name"] . '</td>
                                    <td style="padding-left: 50px;">' . $row["house_name"] . '</td>
                                    <td>' . $row["email"] . '</td>
                                    <td style="padding-left: 25px;">' . $row["phone_number"] . '</td>
                                    <td style="padding-left: 25px;">' . $row["rent_amount"] . '</td>
                                    <td style="padding-left: 28px;">' . $row["dateAdmitted"] . '</td>
                                    <td>';
                                if ($_SESSION['role'] == 'level-2') {
                                    echo '
                                    <a href="#" onclick="showUnauthorizedPopup()">
                                        <i class="fa fa-pencil" style="color:gray; margin-left: 25px; cursor:not-allowed;" title="Chỉnh sửa"></i>
                                    </a>
                                    <a href="#" onclick="showUnauthorizedPopup()">
                                        <i class="fa fa-trash" style="color:gray; margin-left: 20px; cursor:not-allowed;" title="Xóa"></i>
                                    </a>';
                                } else {
                                    echo '
                                    <a href="#" data-toggle="modal" data-target="#edit-modal' . $row["tenantID"] . '">
                                        <i class="fa fa-pencil" style="color:blue; margin-left: 25px;" title="Chỉnh sửa"></i>
                                    </a>
                                    <a href="#" data-toggle="modal" data-target="#responsive-modal' . $row["tenantID"] . '">
                                        <i class="fa fa-trash" style="color:red; margin-left: 20px;" title="Xóa"></i>
                                    </a>';
                                }
                                echo '
                                <!-- Edit Modal -->
                                            <div id="edit-modal'.$row["tenantID"].'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel'.$row["tenantID"].'" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Chỉnh sửa tài khoản người dùng</h4>
                                                    </div>
                                                    <form action="edit_user.php" method="post">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="tenantID" value="'.$row['tenantID'].'">
                                                            <div class="form-group">
                                                                <label for="tenant_name">Tên:</label>
                                                                <input type="text" name="tenant_name" class="form-control" value="'.$row['tenant_name'].'" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Email:</label>
                                                                <input type="email" name="email" class="form-control" value="'.$row['email'].'" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Mật khẩu:</label>
                                                                <input type="password" name="password" class="form-control" minlength="6" placeholder="Nhập ít nhất 6 ký tự" >
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="phone_number">Số điện thoại:</label>
                                                                <input type="int" name="phone_number" class="form-control" value="'.$row['phone_number'].'" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="houseNumber">Văn phòng:</label>
                                                                <input type="int" name="houseNumber" class="form-control" value="'.$row['houseNumber'].'" required>
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
                                 <!-- /.modal -->
                                            <div id="responsive-modal'.$row["tenantID"].'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <h4 class="modal-title">Bạn có chắc muốn xóa người dùng này vĩnh viễn? '.$row["tenant_name"].'</h4>
                                                            </div>
                                                        <div class="modal-footer">
                                                        <form action="functions/del_user.php" method="post">
                                                        <input type="hidden" name="id" value="'. $row["tenantID"].'"/>
                                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Quay lại</button>
                                                            <button type="submit" name="deleteTenant" class="btn btn-danger waves-effect waves-light">Xóa</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <!-- End Modal -->    
                                </td>
                                </tr>';
                                
                                
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
                            columns: [0,1,2,3,4,5,6] 
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6] 
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6] 
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6] 
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6] 
                        }
                    }
                ]
            });
        });
    </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
<?php
}
else{
    header('location:index.php');
}
?>