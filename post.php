<?php
include_once 'connect.php';
include_once 'Extractor.php';
if($_loggedin){
if($slogan!=''){$hasslogan=true;
$db2=new mysqli($db_hostname, $db_username, $db_password, $db_database);
$tobj=new Twitter_Extractor($slogan);
$taglist=$tobj->extractHashtags();
$query=$db->prepare("select id from tags where name=?");
$query->bind_param("s",$u);
$query1=$db1->prepare("insert into tags values(0,?,1)");
$query1->bind_param("s",$t);
$query2=$db2->prepare("update tags set times=times+1 where id=?");
$query2->bind_param("s",$tim);
$savelist[]=array();
foreach ($taglist as $value) {
        $u=$value;   
        $query->execute();
        $query->bind_result($checkid);
        $query->fetch();
        if($checkid==''){        
        $t=$value;   
        $query1->execute();
        }
        else{
            $tim=$checkid;
            $query2->execute();
        }
}
$query->close();
$query1->close();
$query2->close();
}
if($_FILES)
{
$name=$_FILES['image']['name'];
switch($_FILES['image']['type'])
{
case 'image/jpeg': $ext = '.jpg'; break;
case 'image/gif': $ext = '.gif'; break;
case 'image/png': $ext = '.png'; break;
default: $ext = ''; break;
}
$size=$_FILES['image']['size'];
if($size!='0'&&$ext!='')
{
$hasimage=true;
}
}
if($hasslogan || $hasimage){
$query=$db->prepare("insert into wallpost values(0,?,?,?,?,?)");
        $query->bind_param("sssss",$u,$i,$t,$ip,$s);
        $u=$_id;
        $i='';
        $s=time();
        if($hasimage) $i=$s.$ext;
        $t=$slogan;
        $ip=$_SERVER['REMOTE_ADDR'];   
        $query->execute();
 if($hasimage){
$image = "post/$i";
$temp=$newid.$ext;
move_uploaded_file($_FILES['image']['tmp_name'], $temp);
image_resize($temp, $image, 700, 700);
unlink($temp);
}
}
}
$db->close();
$db1->close();
$db2->close();
if(!$ajax){ header("Location:index.php");}
?>

