<?php
require_once "functions/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the posted values
    $id = $_POST['tenantID'];
    $name = $_POST['tenant_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phoneNo = $_POST['phone_number'];
    $houseNo = $_POST['houseNumber'];

    // Check if a password is provided
    if (!empty($password)) {
        // Update the database with the new password
        $sql = "UPDATE tenants SET tenant_name = ?, email = ?, password = ?, phone_number = ?, houseNumber = ? WHERE tenantID = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ssssii", $name, $email, $password, $phoneNo, $houseNo, $id);
    } else {
        // Update the database without changing the password
        $sql = "UPDATE tenants SET tenant_name = ?, email = ?, phone_number = ?, houseNumber = ? WHERE tenantID = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "sssii", $name, $email, $phoneNo, $houseNo, $id);
    }

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Redirect back with a success message
        header("Location: users.php?success=edit");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: users.php?error=edit_failed");
        exit();
    }
}
?>
