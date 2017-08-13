<?php $this->display('header');?>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<div class="bk_8"></div>
<div class="table_head">
	<form onsubmit="tableApp.load($('#search_f'));return false;" action="" id="search_f" name="search_f" method="GET">
		<div class="search_icon search f_l">
			<input type="text" name="keywords" id="keywords" value="<?=$keywords?>" size="15"/>
			<a onclick="tableApp.load($('#search_f'));" href="javascript:;" id="submit">搜素</a>
		</div>
	<form>
</div>
<div class="bk_8"></div>
<table width="99%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list mar_l_8">
  <thead>
    <tr>
      <th width="12%" class="bdr_3">用户名</th>
	  <th>商品名称</th>
      <th width="20%" class="sorter"><div>付款金额</div></th>
      <th width="15%" class="sorter"><div>付款时间</div></th>
      <th width="15%">IP地址</th>
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
<tr id="tr_{paymentid}">\
	<td class="t_c"><a href="javascript:url.member({userid})">{createdby}</a></td>\
    <td class="t_l"><a href="{url}" target="_blank">{title}</a></td>\
	<td class="t_c">{amount}</td>\
    <td class="t_c">{created}</td>\
    <td class="t_c">{ip}</td>\
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

// 显示总条数
function json_loaded(json) {
	$('#pagetotal').html(json.total);
}
</script>
<?php $this->display('footer','system');?>