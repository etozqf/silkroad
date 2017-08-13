<?php $this->display('header');?>
<!--list-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/list/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.list.js"></script>

<!--selectree-->
<script src="<?=IMG_URL?>js/lib/cmstop.selectree.js"></script>
<link rel="stylesheet" href="<?=IMG_URL?>js/lib/selectree/selectree.css">
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>

<!-- 时间选择器 -->
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.datepicker.js"></script>
<link href="<?php echo IMG_URL;?>js/lib/datepicker/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="apps/spider/js/scrollfeed.js"></script>
<script type="text/javascript" src="apps/spider/js/tasklist.js"></script>
<script type="text/javascript" src="apps/epaper/js/import.js"></script>
<link rel="stylesheet" type="text/css" href="apps/epaper/css/import.css" />

<div class="bk_10"></div>
<div class="mar_l_8">
    <select name="epaperid" class="selectree">
    	<?php foreach($epaper as $item):?>
    	<option value="<?php echo $item['epid'];?>"<?php if($item['epid']==$epaperid):?> selected="selected"<?php endif;?>><?php echo $item['name'];?></option>
    	<?php endforeach;?>
    </select>
    <select name="editionid" class="selectree">
    	<?php foreach($edition as $item):?>
    	<option value="<?php echo $item;?>"<?php if($item==$editionid):?> selected="selected"<?php endif;?>><?php echo date('Y-m-d', $item);?></option>
    	<?php endforeach;?>
    </select>
    <div id="edition_datepicker" class="epaper-datepicker"></div>
    <input type="text" id="edition_datepicker_input" value="" style="visibility:hidden; float:left;" >
    <div class="f_r mar_r_8"><input class="button_style_1" type="button" value="一键导入本期全部文章" onclick="autoRun();" style="float:right;" /></div>
</div>
<div class="bk_8"></div>
<div class="box_10 mar_l_8 layout" style="width:950px;height:500px;" id="box">
	<div class="f_l bdr_r" style="width:179px;" id="leftBox">
		<h3 class="navi">
		    <span class="f_l dis_b b">版面列表</span>
		</h3>
		<div id="leftList" style="height:475px;">
			<ul class="channel"></ul>
		</div>
	</div>
	<div class="f_l bdr_r" style="width:469px;" id="centerBox">
		<h3 class="navi tag_span">
			<span class="f_l" index="0">最新(<em></em>)</span>
			<span class="f_l" index="1">已查看(<em></em>)</span>
			<span class="f_l" index="2">已抓取(<em></em>)</span>
		</h3>
		<div style="height:435px;" id="centerList">
			<ul class="viewer-container"></ul>
		</div>
		<div class="btn_area ctrl_area">
			<button type="button" class="button_style_1" onclick="addAlltask()">全选</button>
		</div>
	</div>
	<div class="f_l" style="width:300px;" id="rightBox">
		<h3 class="navi"><span>已选择(<em>0</em>)</span><a href="javascript:;" onclick="rightList.find('tbody').find('.del').click();rightCount();" style="float:right;">清空</a></h3>
		<div style="height:435px;" id="rightList">
			<table width="100%" cellspacing="0" cellpadding="0" class="table_list">
				<tbody></tbody>
			</table>
		</div>
		<div class="btn_area ctrl_area">
			<select id="status" name="status">
				<option value="">请选择</option>
				<option value="1"<?php if($default_state==1):?> selected="selected"<?php endif;?>>草稿</option>
				<option value="3"<?php if($default_state==3):?> selected="selected"<?php endif;?>>待审</option>
				<option value="6"<?php if($default_state==6):?> selected="selected"<?php endif;?>>已发</option>
			</select>
			<button type="button" class="button_style_1" style="float:right" onclick="run()">抓取</button>
			<button type="button" class="button_style_1" style="display:none;float:right" onclick="App.pause(this)">暂停</button>
		</div>
	</div>
</div>
<script type="text/javascript">
var default_state = "<?php echo $default_state;?>";
var runFlag = false;
var stopFlag = false;
var autoFlag = false;
$('.selectree').selectlist().bind('changed', function(e){
	var o = $(e.currentTarget);
	o.next().next().change();
});
$(document).ready(function(){
	$('#edition_datepicker').bind('click', function(){
		$('#edition_datepicker_input').click();
		$('.datepanel').one('mousedown', function(){
			setTimeout(function(){
				var _time = new Date($('#edition_datepicker_input').val().replace(/-/g, '/'));
				if (_time.toString() == 'Invalid Date') {
					return;
				}
				_time = _time.getTime() / 1000;
				$('input[name=editionid]').val(_time).change();
			},500)
		});
	})
	$('#edition_datepicker_input').DatePicker({'format':'yyyy-MM-dd'});
});
</script>