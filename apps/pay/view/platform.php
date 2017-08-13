<?php $this->display('header');?>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<script type="text/javascript" src="apps/pay/js/platform.js"></script>
<div class="bk_8"></div>
<div class="table_head">
	<input type="button" id="add" value="添加接口" class="button_style_4 f_l"/>
	<form onsubmit="tableApp.load($('#search_f'));return false;" action="" id="search_f" name="search_f" method="GET">
		<div class="search_icon search f_r">
			<input type="text" name="keywords" id="keywords" value="<?=$keywords?>" size="15"/>
			<a onclick="tableApp.load($('#search_f'));" href="javascript:;" id="submit">搜素</a>
		</div>
	<form>
</div>
<div class="bk_8"></div>
<table width="99%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list" style="margin-left:6px;">
  <thead>
    <tr>
      <th width="25" class="t_l bdr_3"><input type="checkbox" id="check_all" /></th>
	  <th width="50" class="sorter t_l"><div>排序</div></th>
      <th width="50">名称</th>
      <th width="200">LOGO</th>
      <th>描述</th>
      <th width="70">收手续比例</th>
      <th width="50">状态</th>
      <th width="120">操作</th>
    </tr>
  </thead>
  <tbody id="list_body">
  </tbody>
</table>
<div class="clear"></div>
<div class="table_foot" style="width:98%">
<div id="pagination" class="pagination f_r"></div>
<div class="f_r">
	 共有<span id="pagetotal">0</span>条记录&nbsp;&nbsp;&nbsp;
</div>
<div class="f_l">
	<p class="f_l">
		<input type="button" onclick="platform.enable()" value="启 用" class="button_style_1"/>
		<input type="button" onclick="platform.disable()" value="禁 用" class="button_style_1"/>
		<input type="button" onclick="platform.del()" value="删 除" class="button_style_1"/>
	</p>
</div>
</div>
<!--右键菜单-->
<ul id="right_menu" class="contextMenu">
   <li class="edit"><a href="#platform.add">编辑</a></li>
   <li class="del"><a href="#platform.del">删除</a></li>
</ul>

<script type="text/javascript">
var app = '<?=$app?>';
var controller = '<?=$controller?>';
var row_template = '\
<tr id="tr_{apiid}" style="line-height:22px;height:45px;">\
	<td><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_{apiid}" value="{apiid}" /></td>\
	<td class="t_c"> {sort} </td>\
	<td class="t_l"><a href="javascript:;" class="edit"> {name}</a> </td>\
    <td class="t_l"> <img src="{logo}" /> </td>\
    <td class="t_l"> {description} </td>\
    <td class="t_c"> {payfee} </td>\
    <td class="t_c"> {disabled} </td>\
    <td class="t_c">\
    	<a target="_blank"  href="{url}" /><img title="查看" src="images/view.gif" class="hand view"/></a>\
    	<img title="基本设置" src="images/edit.gif" alt="基本设置" class="hand edit" />\
    	<img title="启用" src="images/sh.gif" alt="启用" class="hand enable" />\
    	<img title="禁用" src="images/lock.gif" alt="禁用" class="hand disable" />\
    	<img title="删除" src="images/delete.gif" alt="删除" class="hand delete" />\
    </td>\
</tr>';

var tableApp = new ct.table('#item_list', {
    rowIdPrefix : 'tr_',
    rightMenuId : 'right_menu',
    pageVar : 'page',
	pageSize : <?=$pagesize?>,
    dblclickHandler : 'platform.add',
    rowCallback     : 'init_event',
    template : row_template,
	jsonLoaded : 'json_loaded',
    baseUrl  : '?app=<?=$app?>&controller=<?=$controller?>&action=page'
});

$(function (){
	tableApp.load();
	$('#add').click(platform.add);
});

// 显示总条数
function json_loaded(json) {
	$('#pagetotal').html(json.total);
}

//init function
function init_event(id, tr) {
	tr.find('a.edit, img.edit').click(function (){platform.add(id);});
	tr.find('img.delete').click(function (){platform.del(id);});
	tr.find('img.filter').click(function(){platform.fadd(id);});
	tr.find('img.enable').click(function(){platform.enable(id);});
	tr.find('img.disable').click(function(){platform.disable(id);});
}
</script>
<?php $this->display('footer','system');?>