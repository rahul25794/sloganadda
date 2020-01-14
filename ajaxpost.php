<?php
include_once 'connect.php';
$toprepare="select u.id,u.name,u.image,p.p_id,p.p_image,p.tags,p.stamp from wallpost p LEFT JOIN users u ON p.user=u.id where p.tags like ? AND p.stamp>".base64_decode($now)." order by p.stamp DESC limit 0,25";
        $query=$db->prepare($toprepare);
        $query->bind_param("s",$p);
        $p="%".$_SESSION['tagsearch']."%";
        $query->execute();
        $query->bind_result($id,$user,$avatar,$post,$imagepost,$tag,$stamp);
        while($query->fetch()){
            $user=  ucfirst($user);            
                    if($user==''){ $user="Anonymous";}
                   if($avatar!=""){ $avatar='<img src="avatars/'.$avatar.'" class="avatar img-responsive img-circle">';}
                   else {
                       $avatar='<div class="avatar noavatar">'.substr($user, 0, 1).'</div>';
                   }
                   $obj= new Autolink($tag);
                   $tag=$obj->addLinksToHashtags();
                   $iso=date('c',$stamp);
            echo<<<_END
         <div class="posts box tile box-round col-xs-12">
               <ul class="col-xs-12 title-holder"><li>$avatar $user</li><li class="time"><span class="glyphicon glyphicon-time"></span> <abbr class="timeago" title="$iso">Just now</abbr></li></ul>
               <div class="tag-holder">$tag</div>
_END;
            if($imagepost!=''){ echo<<<_END
                   <div class="col-xs-12 img-holder"><img src="post/$imagepost" class="center-block img-responsive"></div>
_END;
            }
            echo '
                    <div class="bottom-container">
                    <div id="comcan_'.$post.'" class="comment-container"></div>';
             if($_loggedin){
                 $formid=$post;
                    echo '<form id="form_'.$formid.'" action="comment.php" onsubmit="newcomment('."'".$formid."'".');" method="POST"><div class="input-group">';
            echo '<input type="hidden" id="hid_'.$formid.'" name="forwhat" value="'.base64_encode($post).'">
                    <input type="text" id="com_'.$formid.'" class="form-control input comment-box" placeholder="Leave a comment" name="comment">
                    <span class="input-group-btn">
                    <button id="btn_'.$formid.'" class="cbtn mybtn"><span class="glyphicon glyphicon-send"></span></button>
                    </span>
                    </div>
             </form>';}echo '
                    </div>
        </div>
';
        }
$db->close();
$db1->close();
if(!$ajax){ header("Location:index.php");}
?>

