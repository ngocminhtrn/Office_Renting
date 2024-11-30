<?php
require "functions/db.php";

if ($connection) {
    echo "Connected successfully!";
} else {
    echo "Connection failed.";
}
?>
