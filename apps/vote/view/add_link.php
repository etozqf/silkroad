<style type="text/css">
div,a,input,td {font-size: 12px;}
.button_style_1 { background: url("css/images/bg_x.gif") repeat-x scroll 0 -27px transparent; border: 1px solid #94C5E5; color: #077AC7; }
</style>
<div class="operation_area layout">
	<div class="search_icon">
		<form method="GET" id="system_related_search" action="?app=system&controller=related&action=related">
			<input type="text" name="keywords" id="keywords" size="10" title="请输入关键词" value="<?=$keywords?>" class="w_160">&nbsp;
			<label><input type="checkbox" name="thumb" value="1" />有缩略图</label>
			<?=element::model('modelid', 'modelid', $model)?>&nbsp;
			<?=element::category('catid_related', 'catid', $catid, 1, null, '请选择', true, true)?>
			<input type="submit" value="搜索" class="button_style_1"/>
		</form>
	</div>	
</div>
<table id="contentTable" class="table_list mar_l_8 clear" border="0" width="95%" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th class="bdr_3" width="30">&nbsp;</th>
			<th>标题</th>
			<th width="116">时间</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script type="text/javascript">
var contentRow = new Array(
	'<tr id="contentid_{contentid}">',
		'<td>',
			'<input type="radio" name="hit" value="{url}" />',
		'</td>',
		'<td>{title}</td>',
		'<td>{time}</td>',
	'</tr>'
).join('\r\n');
var contentTable = new ct.table('#contentTable', {
	rowIdPrefix: 'contentid_',
	pageSize: 12,
	rowCallback: function(id,tr) {
		tr.bind('click', function(e) {
			if (!$(e.target).is('input')) {
				tr.find('input').click();
			}
		});
	},
//	dbclickHandler: '',
	template: contentRow,
	baseUrl: '?app=system&controller=related&action=related'
});
contentTable.load();
$('#system_related_search').bind('submit', function() {
	contentTable.load($('#system_related_search'));
	return false;
});
</script>