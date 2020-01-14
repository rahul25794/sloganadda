$(document).ready(function(){
    refresh();
});
function refresh(){    
    $("abbr.timeago").timeago();
    $('.comment-container').scrollTop($(".comment-container").prop('scrollHeight')+3000000000);
}
$(function(){
    
    $('#load_more').click(function(){
        loadmore();
    });
    $('#post-form').ajaxForm({
        resetForm:true,
        beforeSend: function() {
        $('#upload-progress').attr('style',"width:0%;");
        $('#uploadprogress').show();
        $('#post-btn').attr('disabled','disabled');
    },
    uploadProgress: function(event, position, total, percentComplete) {
        $('#upload-progress').attr('style',"width:"+percentComplete+"%;");
    },
    success: function() {
        $('#uploadprogress').hide();
        $('#success-upload').show();
    },
	complete: function() {
		$('#success-upload').fadeOut(2000);
                $('#post-btn').removeAttr('disabled');
                getnew();
	}
    });
});
var pagenum=1;
var end=0;
var send=0;
var myVar=setInterval(function(){getnew()},30000);
function loadmore(){
    send=1;
    $("#load_more").hide();
    $("#loading").show();
    $.get("index.php?down=1",{'low':$("#low").val(),'page':pagenum,'tagsearch':$('#tagsearch').val()},function(data){
        $(data+"").appendTo("#post-container");
        if(data!==""){
        $("#load_more").show();
        pagenum=pagenum+1;
    }
    else{
        var temp="<div class='load'>Thats all for now :(</div>";
        $("#status").html(temp);
        end=1;
    }    
    refresh();
    $("#loading").hide();
    send=0;
    });
    setTimeout(function(){send=0;},10000);
}
function getnew(){
    $.get("ajaxpost.php",{'now':$('#now').val(),'tagsearch':$('#tagsearch').val()},function(data){
        $(data).prependTo("#post-container");
    });
    $.getJSON('getnew.php?now='+$("#now").val()+"", function(data) {
var text='';
var out;
var sender='';
$.each(data.comment, function() {
    text='<div class="comment">';
                   if(this.sender==''){ this.sender="Anonymous";}
                   if(this.avatar==''){
                       text=text+'<div class="avatar noavatar">'+this.senderl+'</div>';
                   }
                   else {
                       text=text+'<img class="avatar" src="avatars/'+this.avatar+'">';
                   }
                   text=text+'<span class="commentor">'+this.sender+'</span><span class="time pull-right"><span class="glyphicon glyphicon-time"></span> <abbr class="timeago" title="'+this.time+'">Just now</abbr></span><p>'+this.comment+'</p></div>';
                   out=text+out;
$(out).appendTo('#comcan_'+this.id);
refresh();
});
$('#now').val(data.time);
});
$("abbr.timeago").timeago();
};
$(function(){
   $(window).scroll(function(){
       if($(document).height()==$(window).scrollTop()+$(window).height()){
          if(end<1&&send<1){ loadmore();}
       }
   });
});
function newcomment(hash){
    event.preventDefault();
    var datat=$('#com_'+hash).val();
    var forwhat=$('#hid_'+hash).val();
    if(datat==''||forwhat==''||datat==null||forwhat==null){
        alert("Comment can't be blank");
    }
    else
    {
        $('#btn_'+hash).css('display','none');  
        $('#wait_'+hash).css('display','block');
$.post('comment.php?now='+$("#now").val()+'', {'forwhat':forwhat,'comment':datat}, function(data) {    
getnew();
$('#com_'+hash).val('');
$('#wait_'+hash).css('display','none');
$('#btn_'+hash).css('display','block');
});
setTimeout(function(){$('#wait_'+hash).css('display','none');$('#btn_'+hash).css('display','block');},10000);
}
};
function oldcomment(hash){
    var before=$("#oldc_"+hash).val();
    var div=$("#old_"+hash);
    div.html("Loading...");
        $.getJSON('getold.php?before='+before+'&forwhat='+hash+'', function(data) {
var text='';
var out;
var sender='';
$.each(data.comment, function() {
    text='<div class="comment">';
                   if(this.sender==''){ this.sender="Anonymous";}
                   if(this.avatar==''){
                       text=text+'<div class="avatar noavatar">'+this.senderl+'</div>';
                   }
                   else {
                       text=text+'<img class="avatar" src="avatars/'+this.avatar+'">';
                   }
                   text=text+'<span class="commentor">'+this.sender+'</span><span class="time pull-right"><span class="glyphicon glyphicon-time"></span> <abbr class="timeago" title="'+this.time+'">Just now</abbr></span><p>'+this.comment+'</p></div>';
                   out=text+out;
$(out).prependTo('#comcan_'+this.id);
});
$("abbr.timeago").timeago();
$("#old_"+hash).remove();
$('<div id="old_'+hash+'" class="morecomment"><input type="hidden" id="oldc_'+hash+'" value="'+data.time+'"><div id="morecomment" onClick="oldcomment('+"'"+hash+"'"+')">Load More Comments</div>').prependTo("#comcan_"+hash);
});
}