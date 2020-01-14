<?php
include_once 'connect.php';
if(!$ajax){ header("Location:index.php");}
$result=$db1->query("select u.name,u.image,c.comment,c.wallpost,c.stamp,c.id from comments c LEFT JOIN users u ON c.user=u.id where c.wallpost=".$forwhat." and c.id<".base64_decode($before)." order by c.stamp DESC limit 0,15");
$rows=mysqli_num_rows($result);
$lastcomment='0';
echo '{"comment":';
if($rows>0){
    echo '[';
for($j=0; $j<$rows; $j++)
{
$row=mysqli_fetch_row($result);
$lastcomment=$row[5];
$obj= new Autolink($row[2]);
$row[2]=str_replace('"', "'", $obj->addLinksToHashtags());
$iso=date('c',$row[4]);
echo '{"id":"'.$row[3].'","sender1":"'.ucfirst(substr($row[0], 0, 1)).'","sender":"'.$row[0].'","comment":"'.$row[2].'","avatar":"'.$row[1].'","time":"'.$iso.'"}';
if($rows>1 && $j!=$rows-1){
    echo ',';
}
}
echo ']';
}
else {
        echo '""';
}
echo ',"time":"'.  base64_encode($lastcomment).'"}';
$db->close();
$db1->close();
?>