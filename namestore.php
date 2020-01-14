<?php
include_once 'connect.php';
if($_loggedin){
if($newname!=''){
if($_FILES)
{
$name=$_FILES['avatarimage']['name'];
switch($_FILES['avatarimage']['type'])
{
case 'image/jpeg': $ext = '.jpg'; break;
case 'image/gif': $ext = '.gif'; break;
case 'image/png': $ext = '.png'; break;
default: $ext = ''; break;
}
$size=$_FILES['avatarimage']['size'];
if($size!='0'&&$ext!='')
{
$hasimage=true;
}
}
$query=$db->prepare("update users set name=?,image=? where id=?");
        $query->bind_param("ssi",$n,$i,$u);
        $u=$_id;
        $n=$newname;
        $i=$_avatar;
        $s=time();
        if($hasimage){ $i=$s.$ext;}  
        $query->execute();
 if($hasimage){
$image = "avatars/$i";
$temp="temp".$ext;
move_uploaded_file($_FILES['avatarimage']['tmp_name'], $temp);
image_resize($temp, $image, 200, 200);
unlink($temp);
unlink("avatars/".$_avatar);
$_SESSION['avatar']=$i;
}
$_SESSION['user']=$newname;
}
}
$db->close();
$db1->close();
header("Location:index.php");
?>

