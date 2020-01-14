<?php
include_once 'include.php';
$time=base64_encode(time());
$count=25;
if($page==''){ $limit=0;}
else{
    $page=$page-1;
    $limit=$page*$count;
}
if(!$ajax){
?>

<!Doctype html>
<html lang="en">
<head>
<title><?php echo $tagsearch;?> Slogan Adda | The Open World Sharing Platform.</title>
<meta charset="UTF-8">
<link rel="shortcut icon" href="icon.png" type="image/png" />
<link rel=icon type="image/png" href="icon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Let The World Hear It. An Ultra Open Photo & Message Sharing Platform. The Open World Sharing Platform.">
<meta name="keywords" content="Sharing,Share,Photo,microblogging,blogging,open,world,Ultra Open,sloganadda<?php echo ','.$tagsearch;?>">
<meta name="author" content="Sloganadda.com Team">
<?php echo $render['include']; ?>
</head>
<body onload="refresh()">
    <?php echo $render['headbar']; if(!$_loggedin) {?>
    <div id="banner" class="box">
        <div class='container'><div class="col-xs-12 col-sm-8"><h2>Welcome to The Open World</h2>
                      <p><i>A brand new Sharing Platform which provides a different, new & Amazing Sharing Experience. No more Rules, No more Policies. So Lets Share...</i></p>
           </div>
           <div id="login" class=" col-xs-12 col-sm-4 pull-right login-form">
                   <form action="" method="post">
                    <h4>When we say open, we mean it :)</h4> 
                    <h6>*No Login or Signup required :p</h6>
                   <input type="hidden" name="loghash" value="<?php echo $_SESSION['loghash'];?>">
                   <input type="submit" value="Enable Access" class="mybtn">
                   </form>
               </div>
           </div>
        
    </div>
    <?php } ?>
<div id="main_content" class="container">
    <div class="col-sm-8 col-xs-12">
    <?php if($_loggedin) {?>
        
        <div id="login" class=" col-xs-12 login-form visible-xs" style="margin-bottom: 20px; display: none;">&nbsp;
    <div class="instruction col-xs-12">
<h5>Set your name and upload an Avatar to personalize your experience.</h5>
<h6>*name is mandatory to update information.</h6><hr>
    </div>
                   <form action="namestore.php" method="post" enctype="multipart/form-data">Your Name:<br>
                   <input type="text" class="form-control input" name="newname" Placeholder="Your Name">
                   Upload Avatar:<br>
                   <input type="file" name="avatarimage" class="form-control"><br>
                   <input type="submit" value="Save" class="col-xs-12 mybtn savebtn">
                   </form>
            </div>
    <div class="form box tile box-round col-xs-12">        
        <div id="uploadprogress" class="progress">
            <div id="upload-progress" class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
        </div></div>
        <div id="success-upload" class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  Posted Successfully :)
</div>
        <form id="post-form" action="post.php" method="post" enctype="multipart/form-data">
            <textarea name="slogan" id="slogan-text" class="form-control input" placeholder="#Share something with the world . . ."></textarea>
            <input type="file" name="image" placeholder="poster" accept="image/*">
        <button id="post-btn" class="btn mybtn pull-right">Post</button>
        </form>
        </div>
            <?php }
 echo '<div id="post-container">';
 }
  ob_flush(); flush();
 if($down==1){
     $sym='<';
 }
 else {
     $sym='>';
 }
 $temp=0;
 $toprepare="select u.id,u.name,u.image,p.p_id,p.p_image,p.tags,p.stamp from wallpost p LEFT JOIN users u ON p.user=u.id where p.tags like ? AND p.p_id$sym? order by p.stamp DESC limit $limit, $count";
        $query=$db->prepare($toprepare);
        $query->bind_param("si",$p,$com);
        $p="%".$_SESSION['tagsearch']."%";
        if($down==1){
        $com=  base64_decode($low);
        }
        else{
                $com=0;
        }
        $query->execute();
        $query->bind_result($id,$user,$avatar,$post,$imagepost,$tag,$stamp);
        $first=True;
        while($query->fetch()){
            if($down!=1){$lower=$post;}
            $user=  ucfirst($user);            
                    if($user==''){ $user="Anonymous";}
                   if($avatar!=""){ $avatar='<img src="avatars/'.$avatar.'" class="avatar img-responsive img-circle">';}
                   else {
                       $avatar='<div class="avatar noavatar">'.substr($user, 0, 1).'</div>';
                   }
                   $obj= new Autolink($tag);
                   $tag=$obj->addLinksToHashtags();
                   $forstamp=date('d-M-y g:i A',$stamp);
                   $iso=date('c',$stamp);
            echo<<<_END
         <div class="posts box tile box-round col-xs-12">
               <ul class="col-xs-12 title-holder"><li>$avatar $user</li><li class="time"><span class="glyphicon glyphicon-time"></span> <abbr class="timeago" title="$iso">$forstamp</abbr></li></ul>
               <div class="tag-holder">$tag</div>
_END;
            if($imagepost!=''){ echo<<<_END
                   <div class="col-xs-12 img-holder"><img src="post/$imagepost" class="center-block img-responsive"></div>
_END;
            }
            echo '
                    <div class="bottom-container">
                    <div id="comcan_'.$post.'" class="comment-container">';
            
        $cresult=$db1->query("select u.name,u.image,c.comment,c.stamp,c.id from comments c LEFT JOIN users u ON c.user=u.id where c.wallpost=".$post." order by c.stamp DESC limit 0,11");
        $rnum=mysqli_num_rows($cresult);
        $comlist='';
        $more=false;
        $lastcomment='';
        if($rnum>10){ 
            $rnum=10;
            $more=true;
        }
        for($i=0;$i<$rnum;$i++){
            $row=mysqli_fetch_row($cresult);
            $lastcomment=$row[4];
                   $comout= '<div class="comment">';
                   if($row[0]==''){ $row[0]="Anonymous";}
                   if($row[1]!=""){ $comout=$comout.'<img class="avatar" src="avatars/'.$row[1].'">';}
                   else {
                       $comout=$comout.'<div class="avatar noavatar">'.ucfirst(substr($row[0], 0, 1)).'</div>';
                   }
                   $obj= new Autolink($row[2]);
                   $tag=$obj->addLinksToHashtags();                   
                   $forstamp=date('d-M-y g:i A',$row[3]);
                   $iso=date('c',$row[3]);
                   $comout=$comout.'<span class="commentor">'.$row[0].'</span><span class="time pull-right"><span class="glyphicon glyphicon-time"></span> <abbr class="timeago" title="'.$iso.'">'.$forstamp.'</abbr></span><p>'.$tag.'</p></div>';
                   $comlist=$comout.$comlist;
        }
        if($more){
            echo '<div id="old_'.$post.'" class="morecomment"><input type="hidden" id="oldc_'.$post.'" value="'.base64_encode($lastcomment).'"><div id="morecomment" onClick="oldcomment('."'".$post."'".')">Load More Comments</div></div>';
        }
             echo $comlist.' </div>';
             if($_loggedin){
                 $formid=$post;
                    echo '<form id="form_'.$formid.'" action="comment.php" onsubmit="newcomment('."'".$formid."'".');" method="POST"><div class="input-group">';
            echo '<input type="hidden" id="hid_'.$formid.'" name="forwhat" value="'.base64_encode($post).'">
                    <input type="text" id="com_'.$formid.'" class="form-control input comment-box" placeholder="Leave a comment" name="comment">
                    <span class="input-group-btn"><span id="wait_'.$formid.'" class="btn waitstate"><span class="glyphicon glyphicon-time"></span></span>
                    <button id="btn_'.$formid.'" class="cbtn mybtn"><span class="glyphicon glyphicon-send"></span></button>
                    </span>
                    </div>
             </form>';}echo '
                    </div>
        </div>
';
             $temp=1;
        }
        
        $query->close();
        if(!$ajax){
            if($temp==0&&$down!=1){
            echo '</div><div class="load">Thats all for now :(</div>';
        }else{
            $nextpage=$page+2;
            echo '</div><div id="status">
    <div id="loading" class="load">Loading...</div>
<a href="?low='.base64_encode($lower).'&tagsearch='.$tagsearch.'&page='.$nextpage.'"><div id="load_more" class="load">Load More...</div></a></div>';
        }
        ?>
    
    </div>
    <input type="hidden" value="<?php echo $time;?>" id="now">
    <input type="hidden" value="<?php echo base64_encode($lower);?>" id="low">
    <input type="hidden" value="<?php echo $tagsearch;?>" id="tagsearch">
    <div class="col-sm-4">
        <?php if($_loggedin){?>
    
<div id="login" class=" col-xs-12 login-form hidden-xs">
    <div class="instruction col-xs-12">
<h5>Set your name and upload an Avatar to personalize your experience.</h5>
<h6>*name is mandatory to update information.</h6><hr>
    </div>
                   <form action="namestore.php" method="post" enctype="multipart/form-data">Your Name:<br>
                   <input type="text" class="form-control input" name="newname" Placeholder="Your Name">
                   Upload Avatar:<br>
                   <input type="file" name="avatarimage" class="form-control"><br>
                   <input type="submit" value="Save" class="col-xs-12 mybtn savebtn">
                   </form>
   </div>
        <?php }?>
        <div class="col-xs-12 hidden-xs box tile box-round taglist"><h5>Popular tags</h5><ul>
        <?php
        $query=$db->prepare("select name,times from tags where name like ? order by times DESC limit 0,50");
        $query->bind_param("s",$p);
        $p="%%";
        $query->execute();
        $query->bind_result($tagname,$tagtimes);
        while($query->fetch()){
            echo "<li><a href='?tagsearch=%23".$tagname."'>#".$tagname."</a></li>";
        }
        ?>
                </ul></div>

</div>
</div>
    <?php echo $render['footer'];?>
</body>
</html>
        <?php } $db->close(); $db1->close();?>