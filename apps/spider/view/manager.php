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

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/dropdown/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.dropdown.js"></script>

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<div class="bk_10"></div>
<div class="table_head">
  <span class="button_style_2 f_l" style="width: 40px;" id="import">导入</span>
  <button type="button" class="button_style_2 f_l" onclick="App.add();">添加</button>
  <span class="f_l" style="line-height:22px;">按网站查看：</span>
  <div class="f_l"><?=$sitedropdown?></div>
</div>
<div class="bk_5"></div>
<table width="98%" id="item_list" cellpadding="0" cellspacing="0"  class="tablesorter table_list mar_l_8" style="empty-cells:show;">
  <thead>
    <tr>
      <th width="30" class="bdr_3 t_c"><input type="checkbox" /></th>
      <th class="sorter"><div>名称</div></th>
      <th width="200" >所属网站</th>
      <th width="80">字符集</th>
      <th width="80">创建者</th>
      <th width="120">创建时间</th>
      <th width="100">操作</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
<div class="table_foot">
  <div id="pagination" class="pagination f_r"></div>
  <p class="f_l">
  	<button type="button" onclick="App.xport();" class="button_style_1">导 出</button>
  	<button type="button" onclick="App.del();" class="button_style_1">删 除</button>
  </p>
</div>
<!--右键菜单-->
<ul id="right_menu" class="contextMenu">
   <li class="edit"><a href="#App.edit">编辑</a></li>
   <li class="xport"><a href="#App.xport">导出</a></li>
   <li class="delete"><a href="#App.del">删除</a></li>
</ul>

<div class="bk_10"></div>
<div class="suggest w_650 mar_l_8">
  <h2>清除采集缓存</h2>
  <p>
    采集器浏览到的内容会被缓存在系统中<br />
    修改规则后如果需要改变以前采集到的内容<br />
    需要点击清除按钮手动清理缓存
  </p>
</div>
<div class="bk_5"></div>
<button type="button" class="button_style_2 f_l mar_l_8" onclick="App.clearCache();">清除</button>
<script type="text/javascript" src="apps/spider/js/manage.js"></script>
<script type="text/javascript">
var row_template = '\
<tr id="row_{ruleid}">\
	<td class="t_c">\
	   <input type="checkbox" class="checkbox_style" value="{ruleid}" />\
	</td>\
	<td><span style="cursor:pointer;" tips="{description}">{name}</span></td>\
	<td>{sitename}</td>\
	<td>{charset}</td>\
	<td class="t_c"><a href="javascript: url.member({createdby});">{username}</a></td>\
	<td>{created}</td>\
	<td class="t_c">\
	   <img class="manage edit" height="16" width="16" alt="编辑" title="编辑" src="images/edit.gif"/>\
       <img class="manage xport" height="16" width="16" alt="导出" title="导出" src="images/down.gif"/>\
       <img class="manage delete" height="16" width="16" alt="删除" title="删除" src="images/delete.gif"/>\
	</td>\
</tr>',
init_row_event = function(id, tr){
	tr.find('img.edit').click(function(){
    	App.edit(id, tr);
    	return false;
    });
    tr.find('img.xport').click(function(){
    	App.xport(id);
    	return false;
    });
    tr.find('img.delete').click(function(){
    	App.del(id);
    	return false;
    });
    tr.find('span[tips]').attrTips('tips');
},
table;
$(function(){
App.init('<?=$pagesize?>', '<?=$_GET['siteid']?>');
});
</script>
</body>
</html>