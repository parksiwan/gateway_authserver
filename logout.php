<?php
session_start();
include("autoloader.php");
unset($_SESSION["account_id"]);
unset($_SESSION["username"]);


//$origin = $_SERVER["HTTP_REFERER"];
// redirect user to the origin
header("location: login.php");

?>