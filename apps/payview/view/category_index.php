<?php $this->display('header');?>
<!--tablesorter-->
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<script type="text/javascript" src="apps/payview/js/payview_category.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>

<div class="bk_10"></div>
<div class="table_head">
  <div class="f_l">
	<input type="button" name="add" id="add" value="添加" class="button_style_2 f_l" onclick="payview_category.add();"/>
  </div>
</div>
<div class="bk_8"></div>
<table width="99%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list mar_l_8">
  <thead>
    <tr>
    	<th width="150">LOGO</th>
    	<th>栏目组名称</th>
    	<th width="300">订阅栏目</th>
		<th width="80">订阅期限</th>
		<th width="80">订阅费用</th>
		<th width="80">订阅类型</th>
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
var row_template = '<tr id="row_{pvcid}">\
                	<td class="t_c"><img src="<?=UPLOAD_URL?>{logo}" style="width:120px;" /></td>\
                	<td class="t_l">{title}</td>\
                	<td class="t_l">{categorys_name}</td>\
                	<td class="t_c">{timetype}个月</td>\
                	<td class="t_c">{fee}</td>\
                	<td class="t_c">{type_text}</td>\
                	<td class="t_c">\
						<a class="disabled" href="javascript:;">{disabled_text}</a>\
                		<img src="images/edit.gif" title="修改" alt="修改" width="16" height="16" class="manage" onclick="payview_category.edit(\'{pvcid}\');"/>\
                	</td>\
                </tr>';

var tableApp = new ct.table('#item_list', {
    rowIdPrefix : 'row_',
    rightMenuId : 'right_menu',
    pageField : 'page',
    pageSize : '<?=$pagesize?>',
    dblclickHandler : 'payview_category.edit',
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

	
	tr.find('a.disabled').each(function (i, e){
		if(e.innerHTML == '启用') {
			e.title = '点击禁用';
		}else{
			e.title = '点击启用';
			e.style.color = '#d00';
		}
	}).click(function (){
		var state = this.innerHTML == '启用' ? 1 : 0;
		var e = this;
		$.getJSON('?app=<?=$app?>&controller=<?=$controller?>&action=disabled&pvcid='+id+'&disabled=' + state, function (data){
			if(data.state) {
				e.style.color = state ? '#d00' : '#077AC7';
				e.title = state ? '点击启用' : '点击禁用';
				e.innerHTML = state ? '禁用' : '启用';
				ct.ok('修改成功');
			}else{
				ct.warn('修改出错，请联系cmstop');
			}
		});
	});

}

// 显示总条数
function json_loaded(json) {
	$('#pagetotal').html(json.total);
}
</script>
<?php $this->display('footer', 'system');