<?php
// Include necessary files
require_once "db.php";

if (isset($_POST["deleteTenant"])) {
    // Check if tenantID is passed and valid
    if (isset($_POST['id'])) {
        $tenantID = $_POST['id']; // Tenant ID to be deleted

        // Prepare and execute the deletion query
        $sql = "DELETE FROM `tenants` WHERE `tenantID` = ?";
        
        // Prepare statement to prevent SQL injection
        $stmt = $mysqli->prepare($sql);
        
        // Bind the parameter
        $stmt->bind_param("i", $tenantID);
        
        // Execute the query
        if ($stmt->execute()) {
            // Successfully deleted, redirect with success message
            header('Location:../users.php?deleted');
        } else {
            // Error occurred, redirect with error message
            header('Location:../users.php?del_error');
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // If no tenantID is provided, redirect with an error
        header('Location:../users.php?del_error');
    }
} else {
    // If deleteTenant is not set, redirect with an error
    header('Location:../users.php?del_error');
}
?>
