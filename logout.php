<?php
session_start();
include 'config.php';
error_reporting(0);
session_destroy();

$getsesscookie = $_COOKIE['sess_id'];

$query = $db->prepare("UPDATE sessions SET
logged_in = :new_logged_in
WHERE sess_token = :sesstoken");
$logoutquery = $query->execute(array(
     "new_logged_in" => "0",
     "sesstoken" => "$getsesscookie"
));
if ( $logoutquery ){
}

setcookie("sess_id", "", time() - 3600, "/");
header("Refresh:1 url=index.php");
?>
