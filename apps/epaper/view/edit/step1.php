<?php $this->display('header');?>
<!-- 时间选择器 -->
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.datepicker.js"></script>
<link href="<?php echo IMG_URL;?>js/lib/datepicker/style.css" rel="stylesheet" type="text/css" />
<!--list-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/list/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.list.js"></script>

<div class="epaper-nav">
	<div class="epaper-nav-item">1.基本设置</div>
	<div class="epaper-nav-item cur">2.抓取规则</div>
	<div class="epaper-nav-item">3.默认选项设置</div>
</div>
<form id="epaper_add" name="epaper_add" action="?app=epaper&controller=epaper&action=edit&type=step1&id=<?php echo $epid;?>" method="post">
	<table class="table_form mar_l_8" border="0" width="98%" cellspacing="0" cellpadding="0">
		<caption>基本设置</caption>
		<tbody class="get_url">
			<tr>
				<th width="180"><span class="c_red">*</span>期首页网址规则：</th>
				<td width="600">
					<input class="w526" type="text" name="post[epaper_rule]" value="<?php echo $epaper_rule;?>" />
					<p />
					<a class="abtn" href="javascript:;" onclick="insertInput($('[name=post[epaper_rule]]')[0], '(Y)');">年(4位)</a>|
					<a class="abtn" href="javascript:;" onclick="insertInput($('[name=post[epaper_rule]]')[0], '(y)');">年(2位)</a>|
					<a class="abtn" href="javascript:;" onclick="insertInput($('[name=post[epaper_rule]]')[0], '(M)');">月(2位)</a>|
					<a class="abtn" href="javascript:;" onclick="insertInput($('[name=post[epaper_rule]]')[0], '(m)');">月(1位)</a>|
					<a class="abtn" href="javascript:;" onclick="insertInput($('[name=post[epaper_rule]]')[0], '(D)');">日(2位)</a>|
					<a class="abtn" href="javascript:;" onclick="insertInput($('[name=post[epaper_rule]]')[0], '(d)');">日(1位)</a>
				</td>
				<td>数字报每一期的首页页面网址规则，使用(Y)(y)(M)(m)(D)(d)来自动生成时间日期信息。</td>
			</tr>
			<tr>
				<th><span class="c_red">*</span>发布周期：</th>
				<td>
					<input type="hidden" name="post[edition_cycle]" value="<?php echo $edition_cycle;?> " />
					<input type="text" size="3" name="edition_cycle" value="<?php echo intval($edition_cycle);?> " />
					<select id="cycle_unit">
						<option value="M"<?php if(stripos($edition_cycle, 'M')):?> selected="selected"<?php endif;?>>月</option>
						<option value="D"<?php if(stripos($edition_cycle, 'D')):?> selected="selected"<?php endif;?>>日</option>
					</select>
				</td>
				<td>填写报纸发布的周期时间，例如周报为7，日报为1。默认单位为1，如果填写错误，系统将自动过滤无效的日期。</td>
			</tr>
			<tr>
				<th><span class="c_red">*</span>最近一期电子报日期：</th>
				<td><input class="input_calendar" type="text" name="post[first_time]" class="" value="<?php echo $first_date;?>" /> <a href="javascript:;" onclick="nextEdition($('[name=post[first_time]]').val(), $('#edition_next'));">测试</a><div id="edition_next" class="success w526" style="display:none;">下一期地址是:<span></span></div></td>
				<td>填写任意一期报纸的发布时间，以便于系统计算起始时间点。</td>
			</tr>
		</tbody>
	</table>
	<table class="table_form mar_l_8" border="0" width="98%" cellspacing="0" cellpadding="0">
		<caption>版面列表</caption>
		<tr>
			<th width="180"><span class="c_red">*</span>范围：</th>
			<td width="600"> 从 <textarea class="w242" name="post[list_start]"><?php echo $list_start;?></textarea> 到 <textarea class="w242" name="post[list_end]"><?php echo $list_end;?></textarea></td>
			<td>获取版面列表所在的区域</td>
		</tr>
		<tr>
			<th><span class="c_red">*</span>版面文章列表网址规则：</th>
			<td><input type="text" name="post[list_rule]" class="w526" placeholder="" value="<?php echo $list_rule;?>" /><a class="abtn" href="javascript:;" onclick="insertInput($('[name=post[list_rule]]')[0], '(*)');">(*)</a></td>
			<td>系统将抓取范围区域内指向到此地址的链接作为版面页面地址，支持通配符。</td>
		</tr>
	</table>
	<table class="table_form mar_l_8" border="0" width="98%" cellspacing="0" cellpadding="0">
		<caption>版面文章列表</caption>
		<tr>
			<th width="180"><span class="c_red">*</span>范围：</th>
			<td width="600"> 从 <textarea class="w242" name="post[content_start]"><?php echo $content_start;?></textarea> 到 <textarea class="w242" name="post[content_end]"><?php echo $content_end;?></textarea></td>
			<td>获取版面文章列表所在的区域</td>
		</tr>
		<tr>
			<th><span class="c_red">*</span>文章内容页网址规则：</th>
			<td><input type="text" name="post[content_rule]" class="w526" placeholder="" value="<?php echo $content_rule;?>" /><a class="abtn" href="javascript:;" onclick="insertInput($('[name=post[content_rule]]')[0], '(*)');">(*)</a></td>
			<td>系统将抓取范围区域内指向到此地址的链接作为文章页面地址，支持通配符。</td>
		</tr>
	</table>
	<table class="table_form mar_l_8" border="0" width="98%" cellspacing="0" cellpadding="0">
		<caption>内容设置</caption>
		<tr>
			<th width="180">范围：</th>
			<td width="600"> 从 <textarea class="w242" name="post[content_scope_start]"><?php echo $content_scope_start;?></textarea> 到 <textarea class="w242" name="post[content_scope_end]"><?php echo $content_scope_end;?></textarea></td>
			<td>缩小内容页面采集范围，下面的标题、正文、作者等信息需在本区域中。留空将从整个页面采集。</td>
		</tr>
		<tr>
			<th><span class="c_red">*</span>标题：</th>
			<td> 从 <textarea class="w242" name="post[content_title_start]"><?php echo $content_title_start;?></textarea> 到 <textarea class="w242" name="post[content_title_end]"><?php echo $content_title_end;?></textarea></td>
			<td>获取文章标题</td>
		</tr>
		<tr>
			<th><span class="c_red">*</span>正文：</th>
			<td> 从 <textarea class="w242" name="post[content_article_start]"><?php echo $content_article_start;?></textarea> 到 <textarea class="w242" name="post[content_article_end]"><?php echo $content_article_end;?></textarea></td>
			<td>获取文章正文</td>
		</tr>
		<tr>
			<th>作者：</th>
			<td> 从 <textarea class="w242" name="post[content_author_start]"><?php echo $content_author_start;?></textarea> 到 <textarea class="w242" name="post[content_author_end]"><?php echo $content_author_end;?></textarea></td>
			<td>获取文章作者</td>
		</tr>
		<tr>
			<th>来源：</th>
			<td> 从 <textarea class="w242" name="post[content_source_start]"><?php echo $content_source_start;?></textarea> 到 <textarea class="w242" name="post[content_source_end]"><?php echo $content_source_end;?></textarea></td>
			<td>获取文章来源</td>
		</tr>
		<tr>
			<th><span class="c_red">*</span>标签保留：</th>
			<td><input type="text" name="post[allow_tags]" class="w526" value="<?php echo $allow_tags;?>" /></td>
			<td>系统将只采集此处填写的标签内容</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<a class="button_style_2" type="button" href="?app=epaper&controller=epaper&action=edit&type=step0&id=<?php echo $epid;?>">返回</a>
				<input class="button_style_2" type="submit" value="下一步" />
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
var nextEdition = function(t, div) {
	var t = new Date(t),
	url = $('[name=post[epaper_rule]]').val(),
	cycle = $('[name=post[edition_cycle]]').val().trim(),
	d = {};
	switch (cycle.substr('-1')) {
		case 'M':
			t.setMonth(t.getMonth() + parseInt(cycle));
			break;
		case 'D':
			t.setDate(t.getDate() + parseInt(cycle));
			break;
	}
	d['Y'] = t.getFullYear().toString();
	d['M'] = (t.getMonth() > 8 ? '' : '0') + (t.getMonth() + 1);
	d['D'] = (t.getDate() > 9 ? '' : '0') + t.getDate();
	d['y'] = d['Y'].substr(2);
	d['m'] = (t.getMonth() + 1).toString();
	d['d'] = t.getDate().toString();
	$.each(d, function(i,k) {
		url = url.replace('('+i+')', k);
	});
	div.show().children('span').html(url);
}

var cycleUnit = function(s) {
	var c = $('input[name=edition_cycle]').val(),
	u = $('#cycle_unit').val();
	if (!parseInt(c)) {
		$('input[name=edition_cycle]').val(1);
		c = 1;
	}
	$('input[name=post[edition_cycle]]').val((c+u).trim());
}

var timeToString = function(s) {
	var d;
	if (s.toString().indexOf('-') == '-1') {
		d = new Date(parseInt(s) * 1000 );
		if (d.toString() == 'Invalid Date') {
			s = '';
		} else {
			s = d.getFullYear() + '-' + (d.getMonth() > 8 ? '' : '0') + (d.getMonth() + 1) + '-' + (d.getDate() > 9 ? '' : '0') + d.getDate();
		}
	}
	return s;
}

$(document).ready(function() {
	$('input[name=post[get_url_type]]').bind('change', function(e) {
		$('.get_url').hide().eq(e.currentTarget.value).show();
	}).eq(<?php echo $get_url_type;?>).click().change();
	$('input.input_calendar').val(timeToString($('input.input_calendar').val())).DatePicker({'format':'yyyy-MM-dd'});
	$('#epaper_add').ajaxForm(function(json) {
		if (json.state) {
			location.href = '?app=epaper&controller=epaper&action=edit&type=step2&id=<?php echo $epid;?>';
		}
	}, null, function(form) {
		var r = true;
		$.each(form[0], function(i, k) {
			if (['post[content_scope_start]', 'post[content_scope_end]', 'post[content_author_start]', 'post[content_author_end]', 'post[content_source_start]', 'post[content_source_end]',].indexOf(k.name) > -1) {
				return true;
			}
			if(/post\[/.test(k.name) && !k.value) {
				r = false;
			}
		});
		if (!r) {
			ct.error('有项目未填写');
			return false;
		}
	});
	$('input[name=edition_cycle]').bind('change', function() {
		cycleUnit();
	});
	$('#cycle_unit').selectlist().bind('changed', function() {
		cycleUnit();
	});
});
</script>