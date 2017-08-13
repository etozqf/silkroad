<?php $this->display('header');?>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<div class="bk_8"></div>
<div class="table_head">
<div class="tag_list_1 pad_8 layout mar_l_8" id="bytime_list">
	<a href="javascript:tableApp.load('rwkeyword=');" id="all" class="s_5">全部</a>
	<a href="javascript: tableApp.load('published=<?= date('Y-m-d', TIME)?>');">今天</a>
	<a href="javascript: tableApp.load('published=<?= date('Y-m-d', strtotime('yesterday'))?>&unpublished=<?= date('Y-m-d', strtotime('yesterday'))?>');">昨天</a>
	<a href="javascript: tableApp.load('published=<?= date('Y-m-d', strtotime('last monday'))?>');">本周</a>
	<a href="javascript: tableApp.load('published=<?= date('Y-m-d', strtotime('last month'))?>');">本月</a>
	<div class="clear"></div>
</div>
<div class="bk_10"></div>
<table class="table_form mar_5 mar_l_8" cellpadding="0" cellspacing="0" width="99%">
	<tr>
		<td align="left">
			<input name="rwkeyword" id="rwkeyword" type="text" size="20" value="请输入支付单号或者用户名" onfocus="this.value == '请输入支付单号或者用户名' && (this.value = '')" onblur=" this.value || (this.value = '请输入支付单号或者用户名')" style="width:250px"/>
			<input type="button" id="rw" value="查询" class="button_style_1"/>
		</td>
        <td align="right">
	<form  name="search_f" id="search_f" action="?app=comment&controller=comment&action=page" method="GET" onsubmit="tableApp.load($('#search_f'));return false;">
		<input type="text" name="published" id="published" class="input_calendar" value="" size="20"/>
	至 
		<input type="text" name="unpublished" id="unpublished" class="input_calendar" value="" size="20"/>
		<input type="submit" value="查询" class="button_style_1"/>
	</form>
        </td>
	</tr>
</table>
</div>
<div class="bk_8"></div>
<table width="99%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list mar_l_8">
  <thead>
    <tr>
		<th width="15%" class="sorter bdr_3"><div>支付单号</div></th>
		<th width="15%" class="sorter"><div>交易金额</div></th>
		<th width="15%" class="sorter"><div>付款状态</div></th>
		<th width="15%" class="sorter"><div>支付方式</div></th>
		<th width="15%" class="sorter"><div>支付时间</div></th>
		<th>用户名</th>
		<th>IP地址</th>
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
var row_template = '\
<tr id="tr_{chargeid}">\
	<td class="t_c"> {orderno} </td>\
	<td class="t_c"> {amount}  </td>\
    <td class="t_c"> {status_cn}  </td>\
    <td class="t_c"> {name}    </td>\
    <td class="t_c"> {created} </td>\
	<td class="t_c"><a href="javascript:url.member({userid});">{inputedby}</a></td>\
    <td class="t_c"> {inputip} </td>\
</tr>';

var tableApp = new ct.table('#item_list', {
    rowIdPrefix : 'tr_',
    pageVar : 'page',
	pageSize : <?=$pagesize?>,
    template : row_template,
	jsonLoaded : 'json_loaded',
    baseUrl  : '?app=<?=$app?>&controller=<?=$controller?>&action=page'
});

$(function (){
	tableApp.load();
});

//快速检索
$('#rw').click(function(){
	var value = $('#rwkeyword').val();
	tableApp.load('rwkeyword='+value);
});

// 全部 -- 今天 ..
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
</script>
<?php $this->display('footer','system');?>