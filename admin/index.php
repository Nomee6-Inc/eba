<?php
session_start();
$getusername = $_SESSION['username'];
$jsonitem = file_get_contents("../userdata.json");

$objitems = json_decode($jsonitem);
$findByrole = function($id) use ($objitems) {
    foreach ($objitems as $role) {
        if ($role->id == $id) return $role->role;
    }
};
if ($findByrole($getusername) == "Yönetici") {
    header("Location: panel.php");
} else {
    header("Location: ../index.php");
}
include_once '../config.php';
$result = mysqli_query($conn,"SELECT * FROM employee");
?>