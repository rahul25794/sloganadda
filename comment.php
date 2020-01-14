<?php
include_once 'connect.php';
if($_loggedin){
if($comment!='' && $forwhat!=""){
$query=$db->prepare("insert into comments values(0,?,?,?,?,?)");
        $query->bind_param("isiss",$u,$c,$w,$ip,$s);
        $u=$_id;
        $c=$comment;
        $w=base64_decode($forwhat);
        $ip=$_SERVER['REMOTE_ADDR'];
        $s=time();
        $query->execute();
}
$query->close();
}
$db->close();
$db1->close();
if(!$ajax){ header("Location:index.php");}
?>

