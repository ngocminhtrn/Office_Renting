<?php
require_once "functions/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the posted values
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = trim($_POST["role"] ?? "");
    $password = $_POST['password']; // Password field

    if (!empty($password)) {
        // If a new password is provided, hash it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update the database with the new password
        $sql = "UPDATE admin SET name = ?, email = ?, role = ?, password = ? WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $role, $hashed_password, $id);
    } else {
        // Update the database without changing the password
        $sql = "UPDATE admin SET name = ?, email = ?, role = ? WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $role, $id);
    }

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Redirect back with a success message
        header("Location: admins.php?success=edit");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: admins.php?error=edit_failed");
        exit();
    }
}
?>
