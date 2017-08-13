<style type="text/css">
div,a,input,td {font-size: 12px;}
.button_style_1 { background: url("css/images/bg_x.gif") repeat-x scroll 0 -27px transparent; border: 1px solid #94C5E5; color: #077AC7; }
</style>
<div class="operation_area layout">
	<div class="search_icon">
		<form method="GET" id="system_related_search" action="?app=system&controller=related&action=related">
			<input type="text" name="keywords" id="keywords" size="10" title="请输入关键词" value="<?=$keywords?>" class="w_160">&nbsp;
			<label><input type="checkbox" name="thumb" value="1" />缩略图</label>
			<?=element::model('modelid', 'modelid', $model)?>&nbsp;
			<?=element::category('catid_related', 'catid', $catid, 1, null, '请选择', true, true)?>
			<input type="submit" value="搜索" class="button_style_1"/>
		</form>
	</div>	
</div>
<div id="scroll_div" class="scroll_div" style="height:300px;">
	<div class="bk_10"></div>
	<table id="contentTable" class="table_list mar_l_8 clear" border="0" width="96%" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="30" style="border-left: 1px solid #D0E6EC;">&nbsp;</th>
				<th>标题</th>
				<th width="116">时间</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<div style="padding:6px 0 0 8px;margin-right:0px;"><div id="pagination" class="pagination f_r"></div></div>
</div>

<script type="text/javascript">
var contentRow = new Array(
	'<tr id="contentid_{contentid}" style="height:34px;">',
		'<td>',
			'<input type="radio" name="hit" value="{contentid}" class="repost_content_id" />',
		'</td>',
		'<td><a href="{url}" target="_blank">{title}</a></td>',
		'<td>{time}</td>',
	'</tr>'
).join('\r\n');
var contentTable = new ct.table('#contentTable', {
	rowIdPrefix: 'contentid_',
	pageSize: 10,
	rowCallback: function(id,tr) {
		tr.bind('click', function(e) {
			if (!$(e.target).is('input')) {
				tr.find('input').click();
			}
		}).find('.tweeted').attrTips('tips');
	},
//	dbclickHandler: '',
	template: contentRow,
	jsonLoaded : function(json) {
		for (var i=0,item;item=json.data[i]; i++) {
			if (item.tweeted) {
				json.data[i]['title'] += '<div class="tweeted" tips="在'+item.tweeted+'被转发"></div>';
			}
			if (item.thumb) {
				json.data[i]['title'] = '<img class="thumb" src="images/thumb.gif" alt="" />' + json.data[i]['title'];
			}
		}
	},
	baseUrl: '?app=system&controller=related&action=related&time_format=Y-m-d H:i'
});
contentTable.load();
$('#system_related_search').bind('submit', function() {
	contentTable.load($('#system_related_search'));
	return false;
});
</script>