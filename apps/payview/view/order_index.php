<?php $this->display('header');?>
<!--tablesorter-->
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<script type="text/javascript" src="apps/payview/js/payview_order.js"></script>

<div class="bk_10"></div>
<div class="table_head">
  <div class="f_l">
	<input type="button" name="add" id="add" value="添加" class="button_style_2 f_l" onclick="payview_order.add();"/>
  </div>
<div class="tag_list_1 pad_8 layout mar_l_8" id="bytime_list">
	<a href="javascript: tag_select('status','');" id="all" class="s_5">全部</a>
	<a href="javascript: tag_select('status','1');">已支付</a>
	<a href="javascript: tag_select('status','2');">未支付</a>
	<a href="javascript: tag_select('type','1');">线上付费</a>
	<a href="javascript: tag_select('type','2');">线下付费</a>
	<a href="javascript: tag_select('is_invoice','1');">开发票</a>
	<div class="clear"></div>
</div>
<div class="bk_10"></div>
<table class="table_form mar_5 mar_l_8" cellpadding="0" cellspacing="0" width="99%">
	<tr>
		<td align="left">
			<input type="hidden" id="tag_key" value="status"/>
			<input type="hidden" id="tag_value" value=""/>
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
<table width="99%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list mar_l_8">
  <thead>
    <tr>
    	<th width="150" class="sorter"><div>订单号</div></th>
    	<th width="100">订阅用户</th>
    	<th width="150">订阅栏目组</th>
		<th width="200">订阅期限</th>
		<th width="80">订阅费用</th>
    	<th width="80">付费类型</th>
		<th width="80">付费状态</th>
		<th width="80" class="sorter"><div>开发票</div></th>
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
var app = '<?=$app?>';
var controller = '<?=$controller?>';
var row_template = '<tr id="row_{oid}">\
                	<td class="t_c">{orderno}</td>\
                	<td class="t_c">{username}</td>\
                	<td class="t_c">{title}</td>\
                	<td class="t_c">{starttime}至{endtime}</td>\
                	<td class="t_c">{payfee}</td>\
                	<td class="t_c">{type_text}</td>\
                	<td class="t_c">{status_text}</td>\
                	<td class="t_c">{is_invoice_text}</td>\
                	<td class="t_c">\
                		<img src="images/txt.gif" title="查看" alt="查看" width="16" height="16" class="manage" onclick="payview_order.view(\'{oid}\');"/>\
                		<img src="images/edit.gif" title="修改" alt="修改" width="16" height="16" class="manage" onclick="payview_order.edit(\'{oid}\');"/>\
						<a href="javascript:payview_order.update_power(\'{oid}\');">更新权限</a>\
                	</td>\
                </tr>';

var tableApp = new ct.table('#item_list', {
    rowIdPrefix : 'row_',
    rightMenuId : 'right_menu',
    pageField : 'page',
    pageSize : '<?=$pagesize?>',
    dblclickHandler : 'payview_order.edit',
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
	var tag_key = $('#tag_key').val();
	var tag_value = $('#tag_value').val();
	value = value == '请输入单号或者订阅姓名' ? '' : value;
	tableApp.load('rwkeyword='+value+'&published='+published+'&unpublished='+unpublished+'&'+tag_key+'='+tag_value);
});

// 全部 -- ..
$('#bytime_list > a').click(function(){
	$('#bytime_list > a.s_5').removeClass('s_5');
	$(this).addClass('s_5');
}).focus(function(){
	this.blur();
});
// 日历
$('input.input_calendar').focus(function(){
	WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
});

// 显示总条数
function json_loaded(json) {
	$('#pagetotal').html(json.total);
}

function tag_select(key,value){
	$('#tag_key').val(key);
	$('#tag_value').val(value);
	tableApp.load(key+'='+value);
}

</script>
<style>
#cmstopAttrHiddenDivtips_orange label{
	float: left;
	width: 60px;
}
</style>
<?php $this->display('footer', 'system');