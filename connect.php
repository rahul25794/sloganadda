<?php
define("ENCRYPTION_KEY", "!@#$%^&*");
$db_hostname="localhost";
$db_database="infocv7d_villegers";
$db_username="infocv7d";
$db_password="info.1988";
extract($_GET);
extract($_POST);
session_start();

$db1=new mysqli($db_hostname, $db_username, $db_password, $db_database);
$db=new mysqli($db_hostname, $db_username, $db_password, $db_database);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

$_SESSION['tagsearch']=$tagsearch;

function encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}

/**
 * Returns decrypted original string
 */
function decrypt($encrypted_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return $decrypted_string;
}

// user handling
$_loggedin=FALSE;
if(isset($_SESSION['id'])){
    $_loggedin=TRUE;
    $_id=$_SESSION['id'];
    $_user=$_SESSION['user'];
    $_avatar=$_SESSION['avatar'];
}
else{
    if(isset($_COOKIE['access_key'])){
    $_loggedin=TRUE;
    $_id= base64_decode(decrypt($_COOKIE['access_key'], ENCRYPTION_KEY));
    $query=$db->prepare("select name,image from users where id=?");
        $query->bind_param("i",$id);
        $id=$_id;
        $query->execute();
        $query->bind_result($pass,$_user,$_avatar);
        $query->fetch();
        $_SESSION['id']=$_id;
        $_SESSION['user']=$_user;
        $_SESSION['avatar']=$_avatar;
}
 else {
     if($loghash==$_SESSION['loghash'] && $loghash!=''){
        $query=$db->prepare("insert into users values(0,?,?,?,?,?,?)");
        $query->bind_param("ssssss",$i,$n,$e,$p,$ip,$s);
        $e="";
        $n="";
        $p="";
        $ip=$_SERVER['REMOTE_ADDR'];
        $s=time();
        $i='';
        $query->execute();
        $_id=$query->insert_id;
        $_user="Anonymous";
        $_loggedin=TRUE;
        setcookie("access_key", base64_encode(encrypt($_id, ENCRYPTION_KEY)), time()+3600*24*365*90,'/', '.localhost');
        $_SESSION['id']=$_id;
        $_SESSION['user']=$_user;
        $_SESSION['avatar']=$_avatar;
        header('Location: index.php');
     }
     else{
         $loghash=base64_encode(encrypt(time(), ENCRYPTION_KEY));
         $_SESSION['loghash']=$loghash;
     }
}
}

function image_resize($src, $dst, $width, $height, $crop=0){
 
  if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";
 
  $type = strtolower(substr(strrchr($src,"."),1));
   if($type == 'jpeg') $type = 'jpg';
   switch($type){
     case 'bmp': $img = imagecreatefromwbmp($src); break;
     case 'gif': $img = imagecreatefromgif($src); break;
     case 'jpg': $img = imagecreatefromjpeg($src); break;
     case 'png': $img = imagecreatefrompng($src); break;
     default : return "Unsupported picture type!";
   }
 
  // resize
   if($crop){
     if($w < $width or $h < $height) return "Picture is too small!";
     $ratio = max($width/$w, $height/$h);
     $h = $height / $ratio;
     $x = ($w - $width / $ratio) / 2;
     $w = $width / $ratio;
   }
   else{
     if($w < $width and $h < $height) return "Picture is too small!";
     $ratio = min($width/$w, $height/$h);
     $width = $w * $ratio;
     $height = $h * $ratio;
     $x = 0;
   }
 
  $new = imagecreatetruecolor($width, $height);
 
  // preserve transparency
   if($type == "gif" or $type == "png"){
     imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
     imagealphablending($new, false);
     imagesavealpha($new, true);
   }
 
  imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
 
  switch($type){
     case 'bmp': imagewbmp($new, $dst); break;
     case 'gif': imagegif($new, $dst); break;
     case 'jpg': imagejpeg($new, $dst); break;
     case 'png': imagepng($new, $dst); break;
   }
   return true;
 }
require_once("Autolink.php");
?>