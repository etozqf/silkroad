<?php $this->display('header', 'system');?>

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.contextMenu.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>
<script type="text/javascript" src="apps/space/js/space.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/autocomplete/style.css" />
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>

<!-- 文本编辑器引入 -->
<script src="<?=ADMIN_URL?>tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script src="<?=ADMIN_URL?>tiny_mce/editor.js" type="text/javascript"></script>

<div class="bk_10"></div>
<div class="tag_1">
	<div class="search search_icon" style="float:right;">

	</div>
	<div class="f_l">
		<button type="button" class="button_style_4 f_l" onclick="space.add_sub_type()">添加分类</button>
	</div>

</div>
<table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list mar_l_8">
	<thead>
		<tr>
			<th width="85">咨询类别</th>
			<th>子分类名</th>
			<th width="85">排序</th>
			<th >状态</th>
			<th width="100">管理操作</th>
			
		</tr>
	</thead>
	<tbody id="list_body"></tbody>
</table>


<script type="text/javascript">
$.validate.setConfigs({
	xmlPath:'/apps/space/validators/'
});
var manage_td     ='<img src="images/edit.gif" alt="编辑"  title="编辑" width="16" height="16" class="manage" onclick="space.edit_sub_type({sid})"/> &nbsp;<img src="images/delete.gif" alt="删除" title="删除" width="16" height="16" class="manage" onclick="space.del_sub_type(\'{sid}\');"/>';
var row_template  ='<tr id="row_{sid}" right_menu_id="right_menu_{status}">';

	row_template +='	<td class="t_c">机构研究</td>';
	row_template +='	<td class="t_c">{name}</td>';
	row_template +='	<td class="t_c">{sort}</td>';
	row_template +='	<td class="t_c">{status}</td>';
	row_template +='	<td class="t_c" id="manage_{spaceid}" name="manage" value="manage">'+manage_td+'</td>';
	row_template +='</tr>';

var tableApp = new ct.table('#item_list', {
		rowIdPrefix : 'row_',
		pageSize : 15,
		rowCallback: 'init_row_event',
		dblclickHandler : 'space.edit',
		jsonLoaded : 'json_loaded',
		template : row_template,
		baseUrl  : '?app=space&controller=index&action=space_sub_type'
});
function init_row_event(id, tr) {
	tr.find('a.title_list').attrTips('tips');
	tr.find('.add_0,.add_1,.add_2').remove();
}

function json_loaded(json) {
	$('#pagetotal').html(json.total);
}

$(function() {
	tableApp.load();
	$('#pagesize').val(tableApp.getPageSize());
	$('#pagesize').blur(function(){
		var p = $(this).val();
		tableApp.setPageSize(p);
		tableApp.load();
	});
	$('img.tips').attrTips('tips', 'tips_green', 200, 'top');
	$('.tag_list a').click(function(){
		$('.tag_list a.s_3').removeClass('s_3');
		$(this).addClass('s_3');
		var status = $(this).attr('value');
		tableApp.load('status='+status);
		$('.mulCss').hide();
		//mul_open mul_recommend mul_ban mul_cancel
		if(status ==0) {
			$('#mul_open,#mul_del').show();
		} else if(status == 1) {
			$('#mul_open,#mul_del').show();
		} else if(status == 2) {
			$('#mul_open,#mul_del').show();
		} else if(status == 3) {
			$('#mul_ban,#mul_recommend').show();
		} else if(status == 4) {
			$('#mul_ban,#mul_cancel').show();
		}
	}).focus(function(){
		this.blur();
	});
});
</script>
<?php $this->display('footer', 'system');?>