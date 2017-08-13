<?php $this->display('header');?>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="tiny_mce/editor.js"></script>
<?php $workflowid = table('category', $catid, 'workflowid');?>
  <div class="bk_8"></div>
  <div class="tag_1">
   <?php $this->display('view_header');?>
  </div>
  
<div class="bk_5"></div>
<div id="chat_scroll" class="w_650 h_350 box_6 box_8 mar_l_8" style="border:4px solid #E3F0F4;">
  <ul id="chat">
  </ul>
</div>
<div class="bk_5"></div>
<div class="w_650 interview">
<form id="chat_add" action="?app=interview&controller=chat&action=add" method="POST">
  <ul>
    <li>
      <p id="guests" style="overflow: hidden;">
        <a href="javascript:chat.guestid()" class="s_5" id="guest_">主持人</a>
        <?php foreach ($guest as $r) { ?>
        <a href="javascript:chat.guestid(<?=$r['guestid']?>)" id="guest_<?=$r['guestid']?>" style="color:<?php echo $r['color'];?>"><?=$r['name']?></a>
        <?php } ?>
      </p>
    </li>
    <li>
      <input type="hidden" name="guestid" id="guestid" value="">
      <input type="hidden" name="contentid" id="contentid" value="<?=$contentid?>">
      <textarea name="content" id="content" cols="120" rows="5"  style="width:635px;" class="c_gray h_80"></textarea>
    </li>
    <li><input type="submit" name="submit" id="submit" value="发送" class="button_style_2"/><span class="c_green">按 <strong>Ctrl+Enter</strong> 键即可发送</span></li>
  </ul>
</form>
</div>
<div class="clear"></div>
<script type="text/javascript" src="apps/interview/js/chat.js"></script>
<script type="text/javascript">
var data = <?=$data?>;
chat.load(data);
chat.reset();

$('#chat_add').ajaxForm('chat.add_submit');

$(document).keydown(function(event){
	event = event || window.event;
	if(event.ctrlKey && event.keyCode == 13) {
        $('#content').val(tinyMCE.activeEditor.getContent());
        tinyMCE.activeEditor.setContent('');
        $('#chat_add').submit();
    }
    else if(event.ctrlKey && event.keyCode == 37)
    {
    	var previd = $('.s_5').prev().is('a') ? $('.s_5').prev().attr('id') : $('#guests > a:last').attr('id');
    	var guestid = previd.substring(6);
    	chat.guestid(guestid);
    }
    else if(event.ctrlKey && event.keyCode == 39)
    {
    	var nextid = $('.s_5').next().is('a') ? $('.s_5').next().attr('id') : $('#guests > a:first').attr('id');
    	var guestid = nextid.substring(6);
    	chat.guestid(guestid);
    }
});
$('#content').editor('mini');
$('#submit').click(function(){
  $('#content').val(tinyMCE.activeEditor.getContent());
  tinyMCE.activeEditor.setContent('');
});
</script>
<?php $this->display('footer');