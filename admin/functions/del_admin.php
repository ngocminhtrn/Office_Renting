<?php 

 
require_once "db.php";

if (isset($_POST["id"])) {

	$id = $_POST["id"];

	$sql = "DELETE FROM admin WHERE id=?";

$stmt = $db->prepare($sql);


    try {
      $stmt->execute([$id]);
      header('Location:../admins.php?deleted');

      }

     catch (Exception $e) {
        $e->getMessage();
        echo "Error";
    }

}
else {
	header('Location:../admins.php?del_error');
}

	

?>