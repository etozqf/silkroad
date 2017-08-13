<?php $this->display('header', 'system');?>
<!--tablesorter-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<!--contextmenu-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.contextMenu.js"></script>
<!--pagination-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.pagination.js"></script>

<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.colorPicker.js"></script>
<script type="text/javascript" src="apps/system/js/yqcategory.js"></script>
<div class="bk_10"></div>
<div class="table_head">
	<div class="search search_icon f_r">
		<form method="POST" name="search_f" id="search_f" action="" onsubmit="tableApp.load($('#search_f'));return false;">
			<input type="text" size="30" id="name" name="name" value="" />
			<a href="javascript:;" onclick="tableApp.load($('#search_f'));" title="搜索">搜索</a>
			<a href="javascript:;" class="more_search" id="search" title="高级搜索" style="display:none;">高级搜索</a>
		</form>
	</div>
	<div class="f_l">
		<input type="button" name="add" id="add" value="添加" class="button_style_2 f_l" onclick="yqcategory.add();  return false;"/>
	</div>
</div>
<div class="bk_8"></div>
<table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list mar_l_8">
	<thead><tr>
		<th width="30" class="t_l bdr_3"><input type="checkbox" id="check_all" /></th>
		<th width="20%" class="ajaxer"><div name="sort">类别排序</div></th>
		<th class="ajaxer"><div name="tag">园区类别</div></th>
		<th width="20%" class="ajaxer"><div name="sort">类别颜色</div></th>
		<th width="20%" class="ajaxer"><div name="sort">所属类型</div></th>
		<th width="15%" >管理操作</th>
	</tr></thead>
	<tbody></tbody>
</table>
<div class="table_foot">
	<div id="pagination" class="pagination f_r"></div>
	<p class="f_l">
		<input type="button" name="button" id="button" onclick="yqcategory.del(); return false;" value="删 除" class="button_style_1"/>
	</p>
</div>
<!--右键菜单-->
<ul id="right_menu" class="contextMenu">
	<li class="edit"><a href="#yqcategory.edit">编辑</a></li>
	<li class="delete"><a href="#yqcategory.del">删除</a></li>
</ul>

<script type="text/javascript">
var row_template = '<tr id="row_{cateid}">\
	<td><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_{cateid}" value="{cateid}" /></td>\
	<td class="t_c" value="{sort}" size="20">{sort}</td>\
	<td class="t_c" value="{value}" size="20" style="color: {style};">{value}</td>\
	<td class="t_c" value="{color}" size="20">{color}</td>\
	<td class="t_c" value="{category}" size="20">{category}</td>\
	<td class="t_c"><img src="images/edit.gif" alt="编辑" width="16" height="16" class="manage common_edit" /> &nbsp;<img src="images/delete.gif" alt="删除" width="16" height="16" class="hand del_row"/></td>\
</tr>';
</script>
<script type="text/javascript">
$.validate.setConfigs({
    xmlPath:'apps/<?=$app?>/validators/yqcategory/'
});
var tableApp = new ct.table('#item_list', {
	rowIdPrefix : 'row_',
	rightMenuId : 'right_menu',
	pageField : 'page',
	pageSize : 15,
	dblclickHandler : 'yqcategory.edit',
	rowCallback     : 'init_row_event',
	template : row_template,
	baseUrl  : '?app=<?=$app?>&controller=<?=$controller?>&action=page'
});
function init_row_event(id, tr) {
	tr.find('img.del_row').click(function(){
		yqcategory.del(id,tr);
	});
	tr.find('img.common_edit').click(function(){
		yqcategory.edit(id,tr);
	});
}
tableApp.load();
$(function (){
	$('#search').click(yqcategory.search);
})
</script>
<?php $this->display('footer', 'system');