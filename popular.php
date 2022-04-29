<?php
session_start();
header("Location: index.php");
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

include_once 'config.php';
$result = mysqli_query($conn,"SELECT * FROM employee");
?>