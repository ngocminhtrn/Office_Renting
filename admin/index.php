<?php
    ob_start();
    require_once "functions/db.php";
    //page name
    $pgnm='Dashboard';
    $error=' '; //error variable

    //require the global file for errors
    require_once "functions/errors.php";

    // Initialize the session

    session_start();

    //die($_COOKIE['themeselection']);

    // If session variable is not set it will redirect to login page

    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){

      header("location: login.php");

      exit;
    }
    if (is_logged_in_temporary()) {
        //allow access


    $email = $_SESSION['email'];

    //main querries
    //houses
    $sql_houses = "SELECT * FROM houses";
    $query_houses = mysqli_query($connection, $sql_houses);
    //tenants
    $sql_tenants = "SELECT * FROM tenants";
    $query_tenants = mysqli_query($connection, $sql_tenants);
    //invoices
    $sql_invoices = "SELECT * FROM invoices";
    $query_invoices = mysqli_query($connection, $sql_invoices);
    //payments
    $sql_payments = "SELECT * FROM payments";
    $query_payments = mysqli_query($connection, $sql_payments);



    //other querries
    $sql_posts = "SELECT * FROM posts";
    $query_posts = mysqli_query($connection, $sql_posts);
    $sql_contacts = "SELECT * FROM contacts";
    $query_contacts = mysqli_query($connection, $sql_contacts);
    $sql_subscribers = "SELECT * FROM subscribers";
    $query_subscribers = mysqli_query($connection, $sql_subscribers);
    $sql_comments = "SELECT * FROM comments";
    $query_comments = mysqli_query($connection, $sql_comments);

    /*the admin header*/
    require "admin_header0.php";

    /*Add the left panel*/
    require "admin_left_panel.php";
?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <!-- salute the admin -->
                        <h4 class="page-title"><?php echo 'Xin chào '.$username.'';?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li onclick=""><a href="#">Dashboard</a></li>
                            <li class="active">Home</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <?php 

                 if (isset($_GET['set'])) {
                    echo'<div class="alert alert-success" >
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                   <strong>DONE!! </strong><p> Mật khẩu mới của bạn đã được cập nhật</p>
                     </div>';
                        }
                        echo $error;

                ?>

                <div style="width:20em; height:auto; max-height:25em; background-color: transparent;position:fixed; top:4.5em; right: 10px; z-index: 1; padding:1.2em; overflow-x:auto;">
                <?php

                $sqnotifications=mysqli_query($conn, "SELECT * FROM `transactions` WHERE `seen`='NO' order by `id` desc");

                while ($recc=mysqli_fetch_array($sqnotifications,MYSQLI_BOTH)) 
                {
                    $id=$recc['id'];
                    $actor=$recc['actor'];
                    $desc=$recc['description'];
                    $acttime=$recc['time'];

                    echo "<div id='dlt' class='w3-container alert alert-slim w3-card-8 w3-yellow fade in'> 
                         <h1 onclick=\"deleteNotifications('notifications','$id') \"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> 
                            <img src='../images/Close.png' title='close message'> </a> 
                         </h1>
                        <div style='text-align:center; align-self:center; font-size:14px'>
                            <strong>From $actor ... </strong><br>  
                            <p class='pcontent'>\"...$desc.\"</p>
                            <p class='badge badge-info'> $acttime </p>
                        </div>
                                                
                    </div>";
                }
                ?>

                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <div class="row row-in">
                               
                                <div class="col-lg-3 col-sm-6 row-in-br">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-institution fa-5x text-success"></i>
                                            <h5 class="text-muted vb">Văn phòng</h5> </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-success"><?php echo mysqli_num_rows($query_houses);?></h3> </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">

                                            <div  class="progress">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%"> <span class="sr-only">50% Complete (success)</span> </div>
                                            </div>
                                            <p>
                                                <!--<span class="text-success"><a href="houses.php"> <i class="fa fa-eye"></i> Xem chi tiết</a></span> 
                                                || 
                                                <span class="text-success">
                                                <a href="new-house.php"> <i class="fa fa-plus-circle"></i> Thêm</a></span> -->
                                                
                                                <!--placeholder-->
                                                <span class="text-success"><a href="index.php"> <i class="fa fa-eye"></i> Xem chi tiết</a></span> 
                                                || 
                                                <span class="text-success">
                                                <a href="index.php"> <i class="fa fa-plus-circle"></i> Thêm</a></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-users fa-5x text-primary"></i>
                                            <h5 class="text-muted vb">Người thuê </h5> </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-primary"><?php echo mysqli_num_rows($query_tenants);?></h3> </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%"> <span class="sr-only">50% Complete (success)</span> </div>
                                            </div>
                                            <p>
                                                <!--<span class="text-primary"><a href="tenants.php"> <i class="fa fa-eye"></i> Xem chi tiết</a></span> 
                                                || 
                                                <span class="text-primary">
                                                <a href="new-tenant.php"> <i class="fa fa-plus-circle"></i> Thêm</a></span>-->

                                                <!--placeholder-->
                                                <span class="text-primary"><a href="index.php"> <i class="fa fa-eye"></i> Xem chi tiết</a></span> 
                                                || 
                                                <span class="text-primary">
                                                <a href="index.php"> <i class="fa fa-plus-circle"></i> Thêm</a></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-6 row-in-br">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-credit-card fa-5x text-megna"></i>
                                            <h5 class="text-muted vb">Hóa đơn</h5> </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-megna"><?php echo mysqli_num_rows($query_invoices);?></h3> </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-megna" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%"> <span class="sr-only">50% Complete (success)</span> </div>
                                            </div>
                                            <p>
                                                <!--<span class="text-megna"><a href="invoices.php"> <i class="fa fa-eye"></i> Xem chi tiết</a></span> 
                                                || 
                                                <span class="text-megna">
                                                <a href="new-invoice.php"> <i class="fa fa-plus-circle"></i> Thêm</a></span>-->

                                                <!--placeholder-->
                                                <span class="text-megna"><a href="index.php"> <i class="fa fa-eye"></i> Xem chi tiết</a></span> 
                                                || 
                                                <span class="text-megna">
                                                <a href="index.php"> <i class="fa fa-plus-circle"></i> Thêm</a></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-6  b-0">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-money fa-5x text-danger"></i>
                                            <h5 class="text-muted vb">Chi tiêu</h5> </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-danger"><?php echo mysqli_num_rows($query_payments);?></h3> </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%"> <span class="sr-only">50% Complete (success)</span> </div>
                                            </div>
                                             <p>
                                                <!--<span class="text-danger"><a href="payments.php"> <i class="fa fa-eye"></i> Xem chi tiết</a></span> 
                                                || 
                                                <span class="text-danger">
                                                <a href="new-payment.php"> <i class="fa fa-plus-circle"></i> Thêm</a></span>-->

                                                <!--placeholder-->
                                                <span class="text-danger"><a href="index.php"> <i class="fa fa-eye"></i> Xem chi tiết</a></span> 
                                                || 
                                                <span class="text-danger">
                                                <a href="index.php"> <i class="fa fa-plus-circle"></i> Thêm</a></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!--row -->

                <!-- /.row to show snapshot of major statuses -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <div class="row row-in">

                                <div class="col-lg-3 col-sm-6 row-in-br">
                                    <div class="w3-green w3-card-4" style="padding:0.4em;margin-top:0.2em;margin-bottom:0.2em;">
                                    <div class="w3-white" style="border-radius: 1em;">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-briefcase fa-5x"></i>
                                            <h5 class="text-muted vb">Doanh thu hàng tháng (<?php echo date('Y-m')?>)</h5> </div>
                                         <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-success" style="font-size: 2.2em;">
                                                <?php
                                                    $month=date('Y-m'); 
                                                    $total=0;
                                                    $sq_pay="SELECT `amountPaid`, `dateofPayment` from `payments` where `dateofPayment` like '%$month%'";
                                                    $rec=mysqli_query($conn,$sq_pay);
                                                    while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                        $total+=$row['amountPaid'];
                                                    }
                                                    echo "$total";
                                                ?>
                                                   <br> <span style="margin-top: 0.1em; font-size:0.5em"> (VND) </span>
                                                </h3> 
                                         </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>

                                <div class="col-lg-3 col-sm-6 row-in-br w3-white">
                                    <div class="w3-red w3-card-4" style="padding:0.4em; margin-top:0.2em;margin-bottom:0.2em;">
                                    <div class="w3-white" style="border-radius: 1em;">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-usd fa-5x"></i>
                                            <h5 class="text-muted vb">Hóa đơn đang nợ</h5> </div>
                                         <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-danger" style="font-size: 2.2em;">
                                                <?php
                                                    $total=0;
                                                    $sq_pay="SELECT `amountDue`, `status` from `invoices` where `status`='unpaid'";
                                                    $rec=mysqli_query($conn,$sq_pay);
                                                    while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                        $total+=$row['amountDue'];
                                                    }
                                                    echo number_format($total, 0, '', '.');
                                                ?>
                                                   <br> <span style="margin-top: 0.1em; font-size:0.5em"> (VND) </span>
                                                </h3> 
                                         </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>

                                <div class="col-lg-3 col-sm-6 row-in-br w3-white">
                                    <div class="w3-orange w3-card-4" style="padding:0.4em;margin-top:0.2em;margin-bottom:0.2em;">
                                    <div class="w3-white" style="border-radius: 1em;">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-usd fa-5x"></i>
                                            <h5 class="text-muted vb">Tiền cần được thu</h5> </div>
                                         <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15" style="font-size: 2.2em;color:orange;">
                                                <?php
                                                   
                                                    $total=0;
                                                    $sq_pay="SELECT `amountDue`, `status` from `invoices` where `status`='paid'";
                                                    $rec=mysqli_query($conn,$sq_pay);
                                                    while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                        $total+=$row['amountDue'];
                                                    }
                                                    echo number_format($total, 0, '', '.');
                                                ?>
                                                   <br> <span style="margin-top: 0.1em; font-size:0.5em"> (VND) </span>
                                                </h3> 
                                         </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>

                                <div class="col-lg-3 col-sm-6 row-in-br w3-white">
                                    <div class="w3-blue w3-card-4" style="padding:0.4em;margin-top:0.2em;margin-bottom:0.2em;">
                                    <div class="w3-white" style="border-radius: 1em;">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-home fa-5x"></i>
                                            <h5 class="text-muted vb">Số văn phòng còn trống</h5> </div>
                                         <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-info" style="font-size: 2.2em;">
                                                <?php
                                                   
                                                    $total=0;
                                                    $sq_pay="SELECT `number_of_rooms`,`house_status` from `houses` where `house_status`='Vacant'";
                                                    $rec=mysqli_query($conn,$sq_pay);
                                                    while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                        $total+=$row['number_of_rooms'];
                                                    }
                                                    echo "$total";
                                                ?>
                                                   <br> <span style="margin-top: 0.1em; font-size:0.5em"> (phòng) </span>
                                                </h3> 
                                         </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <h3 style="text-align: center;">Giao dịch gần đây</h3>
                            <div class="row row-in">

                             <div style="overflow-x:auto; overflow-y:auto; max-width:80%; max-height:400px; margin-right:auto;margin-left:auto;">
                                <table class="table w3-card-4 w3-table w3-bordered w3-striped table-responsive " id="rates" style="border:0.2em solid white;">
                                    <tr>
                                        <th>Người thuê</th>
                                        <th>Ghi chú</th>
                                        <th>Thời gian</th>
                                        </tr>
                                        <?php
                                            $sq_trans="SELECT * from `transactions` order by `id` desc limit 10";
                                            $rec=mysqli_query($conn,$sq_trans);
                                            $i=1; //for coloring
                                            while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) 
                                            {
                                                $actor=$row['actor'];
                                                $description=$row['description'];
                                                $time=$row['time'];

                                                $color='w3-blue';
                                                if ($i%2==0) {
                                                    $color='w3-grey';
                                                }
                                                $i++;

                                                echo "
                                                <tr class='$color'>
                                                    <td>$actor</td>
                                                    <td>$description</td>
                                                    <td>$time</td>
                                                </tr>
                                                ";
                                            }
                                        ?>
                                    

                                </table>
                             </div>

                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <h3 style="text-align: center;">Blog</h3>
                            <div class="row row-in">

                                 <div class="col-lg-3 col-sm-6 row-in-br">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i data-icon="E" class="linea-icon linea-basic"></i>
                                            <h5 class="text-muted vb">Bài đăng của công ty</h5> </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-danger"><?php echo mysqli_num_rows($query_posts);?></h3> </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="linea-icon linea-basic" data-icon="&#xe01b;"></i>
                                            <h5 class="text-muted vb">Bình luận</h5> </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-megna"><?php echo mysqli_num_rows($query_comments);?></h3> </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-megna" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 row-in-br">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="linea-icon linea-basic" data-icon="&#xe00b;"></i>
                                            <h5 class="text-muted vb">Chi tiết liên hệ</h5> </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-primary"><?php echo mysqli_num_rows($query_contacts);?></h3> </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6  b-0">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="linea-icon linea-basic" data-icon="&#xe016;"></i>
                                            <h5 class="text-muted vb">Người theo dõi</h5> </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h3 class="counter text-right m-t-15 text-success"><?php echo mysqli_num_rows($query_subscribers);?></h3> </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--row -->
             
                <div class="row">
                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Bình luận gần đây</h3>
                            <div class="comment-center">
                                <div class="comment-body">
                                    <div class="mail-contnet">
                                      <?php
                                             if (mysqli_num_rows($query_comments)==0) {
                                                 echo "<i style='color:brown;'>Hiện tại chưa có bình luận nào :( </i> ";
                                                    }
                                                    else{

                                    $counter = 0;
                                    $max = 3;

                                    while ($row2 = mysqli_fetch_array($query_comments)) {
                                    $blogid = $row2["blogid"];
                                       $sql2 = "SELECT * FROM posts WHERE id='$blogid'";
                                            $query2 = mysqli_query($connection, $sql2);

                                       while (($row3 = mysqli_fetch_assoc($query2)) and ($counter < $max)) {
                                        
                                    echo '                
                                    
                                        <b>'.$row2["name"].'</b>
                                        <h5>Blog Title : '.$row3["title"].'</h5>
                                        <span class="mail-desc">
                                        '.$row2["comment"].'
                                        </span> <span class="time pull-right">'.$row2["date"].'</span>
                                    ';
                                    $counter++;
                                        } } }
                                    ?>
                                    <hr>
                                    <!--href="comments.php"-->
                                     <a href="index.php" class="btn btn-info btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Xem toàn bộ bình luận</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <div class="white-box">
                            <!-- <h3 class="box-title">Recent Blog Posts
                              <div class="col-md-3 col-sm-4 col-xs-6 pull-right">
                                <select class="form-control pull-right row b-none">
                                  <option>March 2018</option>
                                  <option>April 2018</option>
                                  <option>May 2018</option>
                                  <option>June 2018</option>
                                  <option>July 2018</option>
                                </select>
                              </div>
                            </h3> -->
                            <div class="row sales-report">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h2>Swinburne  <?php echo 2000+date('y'); ?></h2>
                                    <p>Bài đăng</p>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 ">
                                    <h1 class="text-right text-success m-t-20"><?php echo mysqli_num_rows($query_posts);?></h1> </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table ">

                                    <?php
                                             if (mysqli_num_rows($query_posts)==0) {
                                                 echo "<i style='color:brown;'>No Posts Yet :( Upload Company's first blog post today! </i> ";
                                                    }
                                                    else
                                                        
                                                    {
                                                        echo '
                                                             <thead>
                                                            <tr>
                                                                <th>TITLE</th>
                                                                <th>NGÀY</th>
                                                                <th>BÌNH LUẬN</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        ';
                                                    }
                                                        $counter = 0;
                                                        $max = 3;

                                                while (($row = mysqli_fetch_array($query_posts)) and ($counter < $max) )
                                                {
                                                    $postid = $row["id"];
                                                    $sql2 = "SELECT * FROM comments WHERE blogid=$postid";
                                                    $query2 = mysqli_query($connection, $sql2);

                                              echo '
                                        <tr>
                                            <td class="txt-oflo">'.$row["title"].'</td>
                                           <td class="txt-oflo">'.$row["date"].'</td>
                                            <td><span class="text-success">'.mysqli_num_rows($query2).'</span></td>
                                        </tr>
                                    ';
                                    $counter++;
                                        }
                                    ?>

                                    </tbody>

                                </table> 
                                       <a href="posts.php" class="btn btn-info btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Xem toàn bộ bài đăng</a>
                                     </div>
                        </div>
                    </div>
                </div>

                <!-- /.row  right sidebar for colors-->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Thay đổi giao diện <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul>
                                <li><b>Lựa chọn</b></li>
                                <li>
                                    <div class="checkbox checkbox-info">
                                        <input id="checkbox1" type="checkbox" class="fxhdr">
                                        <label for="checkbox1"> Sửa lại header </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="checkbox checkbox-warning">
                                        <input id="checkbox2" type="checkbox" checked="" class="fxsdr">
                                        <label for="checkbox2"> Sửa lại Sidebar </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="checkbox checkbox-success">
                                        <input id="checkbox4" type="checkbox" class="open-close">
                                        <label for="checkbox4"> Thay đổi Sidebar </label>
                                    </div>
                                </li>
                            </ul>
                            <ul id="themecolors" class="m-t-20">
                                <li><b>Sidebar màu trắng</b></li>
                                <li><a href="javascript:void(0)" theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" theme="gray" class="yellow-theme">3</a></li>
                                <li><a href="javascript:void(0)" theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" theme="megna" class="megna-theme">6</a></li>
                                <li><b>Sidebar màu tối</b></li>
                                <br/>
                                <li><a href="javascript:void(0)" theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" theme="gray-dark" class="yellow-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" theme="megna-dark" class="megna-dark-theme">12</a></li>
                            </ul>
                            </div>
                    </div>
                </div>
                
                <!-- /.right-sidebar -->


            </div>
<?php require "admin_footer.php"; ?>
    <script type="text/javascript">

    function deleteNotifications(opt,str) 
    {
        //declare server submitting variable
        var xmlhttp="";
        //initialize as per browser
         if (window.XMLHttpRequest) {
        
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
        //submit to server for procesing
        xmlhttp.open("GET","liveActions.php?act="+opt+"&&q="+str,true);
        xmlhttp.send();
    }
    </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
<?php
}
else{
    header('location:../index.php');
}
?>
