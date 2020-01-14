<?php
include_once 'connect.php';
$render['include']='
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="bootstrap/bootstrap-theme.min.css" rel="stylesheet" type="text/css">
<link href="style.css" rel="stylesheet" type="text/css">
<script src="bootstrap/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="bootstrap/bootstrap.min.js" type="text/javascript"></script>
<script src="bootstrap/jqui.min.js" type="text/javascript" asyc></script>
<script src="jquery.form.min.js" type="text/javascript"></script>
<script src="jquery.timeago.js" type="text/javascript"></script>
<script src="script.js" type="text/javascript"></script>
';

$render['headbar']='
<div id="header" class="box fixed">
<div class="container" id="mainbar"><div class="col-xs-2 hidden-xs">
<a href="index.php"><img itemprop="logo" src="logo2.png" id="mainlogo"></a></div>
<div id="search" class="col-xs-10 col-sm-8"><form action="" method="get">
<div class="input-group searchback">
<input type="text" id="searchbox" class="form-control input comment-box" placeholder="Search a Tag" name="tagsearch" value="'.$tagsearch.'">
<span class="input-group-btn">
<button class="cbtn sbtn mybtn"><span class="glyphicon glyphicon-search"></span></button>
</span></div></form></div>
<div class="col-xs-2">
<div class="pull-right dropdown">';
    if($_avatar!=''){
    $render['headbar']=$render['headbar'].'<img class="menu-btn avatar img-circle" src="avatars/'.$_avatar.'">';
    }
else {
    if($_user==''){
        $_user="Unknown";
    }
$render['headbar']=$render['headbar']
.'<div class="menu-btn avatar noavatar">'.substr($_user, 0, 1).'</div>';
}
$render['headbar']=$render['headbar'].'

</div><div class="pull-right name hidden-xs">Hello '.ucfirst($_user).' </div>
</div></div>
</div>
';

$render['footer']='
<div id="footer">
<div id="foot_container" class="container">
<div class="row"><p>Disclaimer: Neither SloganAdda.com nor the Development Team is responsible for the content.</p>
<span class="glyphicon glyphicon-copyright-mark"></span> '.date('Y').' <a href="">SloganAdda.com</a>
</div>
</div>
</div>
';
?>
