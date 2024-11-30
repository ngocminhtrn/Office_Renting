<?php
/* DATABASE CONNECTION */
require "db.php";
/* DATABASE CONNECTION */

if (isset($_POST['admitTenant'])) {
    $tname = isset($_POST['tenant_name']) ? trim($_POST['tenant_name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $phone = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : null;
    $dateAdmitted = date('Y-m-d');

    // Validate inputs
    if (!$tname || !$email || !$password) {
        echo "Please fill in all required fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email không hợp lệ";
        exit;
    }

    // Check if email exists
    $sql = "SELECT `tenantID` FROM `tenants` WHERE `email` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email này đã tồn tại!";
        exit;
    }

    // Hash the password

    // Insert new user
    $sql = "INSERT INTO `tenants` (`tenant_name`, `email`, `password`, `phone_number`, `dateAdmitted`) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $tname, $email, $password, $phone, $dateAdmitted);

    try {
        if ($stmt->execute()) {
            header('Location:../users.php?success=create');
            exit;
        } else {
            echo "Error executing query: " . $stmt->error;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
