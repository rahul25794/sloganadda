<?php
include 'connect.php';
session_destroy();
setcookie("access_key",'',time()-100);
header('Location: index.php');
?>

