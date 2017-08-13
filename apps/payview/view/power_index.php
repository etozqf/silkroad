<?php $this->display('header');?>
<!--tablesorter-->
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<script type="text/javascript" src="apps/payview/js/payview_power.js"></script>

<div class="bk_10"></div>
<div class="table_head">
  <div class="f_l">
	<input type="button" value="过期清理" class="button_style_4 f_l" onclick="payview_power.clear_past();" title="删除权限表中过期的权限数据"/>
	<input type="button" value="缓存清理" class="button_style_4 f_l" onclick="payview_power.clear_cache();" title="删除用户缓存权限数据"/>
	<input type="button" value="重载权限" class="button_style_4 f_l" onclick="payview_power.reload();" title="清空权限表，重新载入用户权限数据"/>
  </div>
<div class="bk_10"></div>
<table class="table_form mar_5 mar_l_8" cellpadding="0" cellspacing="0" width="99%">
	<tr>
		<td align="left">
			<input name="rwkeyword" id="rwkeyword" type="text" size="20" value="请输入单号或者订阅姓名" onfocus="this.value == '请输入单号或者订阅姓名' && (this.value = '')" onblur=" this.value || (this.value = '请输入单号或者订阅姓名')" style="width:250px"/>
			到期时间
			<input type="text" name="published" id="published" class="input_calendar" value="" size="20"/>
			至 
			<input type="text" name="unpublished" id="unpublished" class="input_calendar" value="" size="20"/>
			<input type="button" id="rw" value="查询" class="button_style_1"/>
		</td>
	</tr>
</table>
</div>
<div class="bk_8"></div>
<table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list" style="margin-left:6px;">
  <thead>
    <tr>
    	<th width="100">订阅用户</th>
    	<th width="150">订阅栏目组</th>
		<th width="200">订阅期限</th>
		<th width="100">管理操作</th>
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
<script type="text/javascript">
var row_template = '<tr id="row_{oid}">\
                	<td class="t_c">{username}</td>\
                	<td class="t_c">{title}</td>\
                	<td class="t_c">{starttime}至{endtime}</td>\
                	<td class="t_c">\
                		<img src="images/edit.gif" title="修改" alt="修改" width="16" height="16" class="manage" onclick="payview_power.edit(\'{oid}\');"/>&nbsp;\
						<img src="images/delete.gif" alt="删除" width="16" height="16" class="hand delete" onclick="payview_power.del(\'{oid}\',\'{username}--{title}\');" />\
                	</td>\
                </tr>';

var tableApp = new ct.table('#item_list', {
    rowIdPrefix : 'row_',
    rightMenuId : 'right_menu',
    pageField : 'page',
    pageSize : '<?=$pagesize?>',
    dblclickHandler : '',
    rowCallback     : 'init_row_event',
    template : row_template,
	jsonLoaded : 'json_loaded',
    baseUrl  : '?app=<?=$app?>&controller=<?=$controller?>&action=page'
});

$(function (){
	tableApp.load();
});

function init_row_event(id, tr)
{

}
//快速检索
$('#rw').click(function(){
	var value = $('#rwkeyword').val();
	var published = $('#published').val();
	var unpublished = $('#unpublished').val();
	value = value == '请输入单号或者订阅姓名' ? '' : value;
	tableApp.load('rwkeyword='+value+'&published='+published+'&unpublished='+unpublished);
});

// 日历
$('input.input_calendar').focus(function(){
	WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
});

// 显示总条数
function json_loaded(json) {
	$('#pagetotal').html(json.total);
}
</script>

<?php $this->display('footer', 'system');