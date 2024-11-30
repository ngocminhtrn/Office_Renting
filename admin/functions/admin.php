<?php
    require_once "db.php"; // Adjust the path if necessary

    $response = ["success" => false, "message" => "Invalid request."];

    // Check if it's an AJAX request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_id'])) {
            // Handle update operation
            $update_id = intval($_POST['update_id']);
            $updated_name = trim($_POST['name']);
            $updated_email = trim($_POST['email']);
            $updated_role = trim($_POST['role']);

            $errors = [];
            if (empty($updated_name)) $errors[] = "Name cannot be empty.";
            if (empty($updated_email) || !filter_var($updated_email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
            if (empty($updated_role)) $errors[] = "Role must be selected.";

            if (empty($errors)) {
                $sql_update = "UPDATE admin SET name = ?, email = ?, role = ? WHERE id = ?";
                $stmt = mysqli_prepare($connection, $sql_update);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssi", $updated_name, $updated_email, $updated_role, $update_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $response = ["success" => true, "message" => "Admin details updated successfully."];
                    } else {
                        $response = ["success" => false, "message" => "Failed to update admin details."];
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $response = ["success" => false, "message" => "Failed to prepare the update statement."];
                }
            } else {
                $response = ["success" => false, "errors" => $errors];
            }
        }

        if (isset($_POST['delete_id'])) {
            // Handle delete operation
            $delete_id = intval($_POST['delete_id']);
            $sql_delete = "DELETE FROM admin WHERE id = ?";
            $stmt = mysqli_prepare($connection, $sql_delete);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $delete_id);
                if (mysqli_stmt_execute($stmt)) {
                    $response = ["success" => true, "message" => "Admin account deleted successfully."];
                } else {
                    $response = ["success" => false, "message" => "Failed to delete admin account."];
                }
                mysqli_stmt_close($stmt);
            } else {
                $response = ["success" => false, "message" => "Failed to prepare delete statement."];
            }
        }
    }

    echo json_encode($response);
?>