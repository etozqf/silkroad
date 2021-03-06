<?php $this->display('header');?>
<form name="vote_edit" id="vote_edit" method="post" action="?app=vote&controller=vote&action=edit">
<input type="hidden" name="modelid" id="modelid" value="<?=$modelid?>" />
<input type="hidden" name="contentid" id="contentid" value="<?=$contentid?>" />
<?php if($status == 6): ?>
<input type="hidden" name="url" id="url" value="<?=$url?>" />
<?php endif; ?>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<tr>
		<th width="80"><span class="c_red">*</span> 栏目：</th>
		<td><?=element::category('catid', 'catid', $catid)?></td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 标题：</th>
		<td><?=element::title('title', $title, $color)?></td>
	</tr>
	<tr id="tr_subtitle" style="display:<?=($subtitle ? 'table-row' : 'none')?>">
		<th>副题：</th>
		<td><input type="text" name="subtitle" id="subtitle" value="<?=$subtitle?>" size="100" maxlength="120" /></td>
	</tr>
	<tr>
		<th>类型：</th>
		<td>
			<input name="type" type="radio" class="checkbox_style" value="radio"<?php if($type == "radio"){?> checked="checked"<?php } ?> onclick="$('#maxoptions_span').hide();" /> 单选
			<input name="type" type="radio" class="checkbox_style" value="checkbox"<?php if($type == "checkbox"){?> checked="checked"<?php } ?> onclick="$('#maxoptions_span').show();" /> 多选
			<span id="maxoptions_span" <?php if($type == "radio"){?>style="display:none;"<?php } ?>>最多可选  <input id="maxoptions" name="maxoptions" type="text" size="2" value="<?=$maxoptions ? $maxoptions : ''?>" /> 项 <?=element::tips('留空为不限制')?></span>
		</td>
	</tr>
</table>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="mar_l_8">
	<tr>
		<th width="80" style="color:#077AC7;font-weight:normal;" class="t_r"><span class="c_red">*</span> 选项：</th>
		<td>
			<table id="vote_options" width="692" border="0" cellspacing="0" cellpadding="0" class="table_info">
				<thead>
					<tr>
						<th width="30"><div class="move_cursor"></div></th>
						<th width="360">选项</th>
						<th width="106">链接</th>
						<th width="106">图片</th>
						<th width="60">当前票数</th>
						<th width="30">删</th>
					</tr>
				</thead>
				<tbody id="options" style="position: relative;"></tbody>
			</table>
		</td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td><div class="mar_l_8 mar_5"><input type="button" name="add_option_btn" value="增加选项" class="hand button_style" onclick="option.add()" /></div></td>
	</tr>
</table>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="mar_l_8 mar_t_10 table_form">
    <tr>
        <th width="80">模式：</th>
        <td class="lh_24">
            <label><input onclick="$(this).parent().siblings('span').css('visibility', 'hidden');$('[data-role=thumb]').hide()" name="display" type="radio" value="list"<?php if ($display != 'thumb'):?> checked="checked"<?php endif; ?> class="checkbox_style" /> 普通模式</label>
            <label><input onclick="$(this).parent().siblings('span').css('visibility', 'visible');$('[data-role=thumb]').show()" name="display" type="radio" value="thumb"<?php if ($display == 'thumb'):?> checked="checked"<?php endif; ?> class="checkbox_style" /> 评选模式</label>
            <span<?php if ($display != 'thumb'):?> style="visibility:hidden;"<?php endif; ?>>
                图片大小：<input type="text" placeholder="宽" name="thumb_width" size="3" value="<?=$thumb_width?>" /> x <input type="text" placeholder="高" name="thumb_height" size="3" value="<?=$thumb_height?>" /> px
            </span>
        </td>
    </tr>
    <tr data-role="thumb"<?php if ($display != 'thumb'):?> style="display: none;"<?php endif; ?>>
        <th>页面背景图：</th>
        <td><?=element::image('bgimg', $bgimg, 60)?></td>
    </tr>
    <tr>
		<th>介绍：</th>
		<td><textarea name="description" id="description" maxLength="255" style="width:710px;height:40px" class="bdr"><?=$description?></textarea> </td>
	</tr>
    <tr>
        <th> Tags：</th>
        <td><?=element::tag('tags', $tags)?></td>
    </tr>
	<tr>
		<th>缩略图：</th>
		<td><?=element::image('thumb', $thumb, 60)?></td>
	</tr>
	<tr>
		<th>防刷限制：</th>
		<td>同IP <input id="mininterval" name="mininterval" type="text" value="<?php if($mininterval){ echo $mininterval; }?>" size="4" />小时内不得重复投票 <?=element::tips('0或者留空为不限制')?></td>
	</tr>
    <tr>
        <th>验证码类型：</th>
        <td>
            <label><input name="seccode_type" type="radio" value="normal" <?php if($seccode_type == "normal"){?> checked="checked"<?php } ?> class="checkbox_style" /> 普通 </label>
            <label><input name="seccode_type" type="radio" value="advanced" <?php if($seccode_type == "advanced"){?> checked="checked"<?php } ?>  class="checkbox_style" /> 高级 </label>
        </td>
    </tr>
	<?php if(!empty($area)):?>
	<tr>
		<th width="120">开启省份限制：</th>
		<td>
			<div class="switchpanel">
				<input class="switch" type="checkbox" name="arealimit" value="1" <?php if($arealimit):?>checked="checked" <?php endif;?>/>&nbsp;(<a href="javascript:ct.assoc.open('?app=vote&controller=setting&action=index', 'newtab');">管理</a>)
			</div>
		</td>
	</tr>
	<?php endif;?>
	<tr>
		<th>开始时间：</th>
		<td><input id="starttime" name="starttime" type="text" class="input_calendar" value="<?=$starttime ? date('Y-m-d H:i:s', $starttime) : ''?>" size="20"/></td>
	</tr>
	<tr>
		<th>截止时间：</th>
		<td><input id="endtime" name="endtime" type="text" class="input_calendar" value="<?=$endtime ? date('Y-m-d H:i:s', $endtime) : ''?>" size="20"/></td>
	</tr>
	<tr>
		<th>属性：</th>
		<td><?=element::property("proid", "proids", $proids)?></td>
	</tr>
	<tr>
		<th><?=element::tips('权重将决定文章在哪里显示和排序')?> 权重：</th>
		<td>
		<?=element::weight($weight, $myweight);?>
		</td>
		</tr>
</table>

<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<tr>
		<th width="80"><?=element::tips('投票定时发布时间')?> 上线：</th>
		<input type="hidden" name="oldpublished" value="<?=$published?>" />
		<td width="170"><input type="text" name="published" id="published" class="input_calendar" value="<?=$published?>" size="20"/></td>
		<th width="80">下线：</th>
		<td><input type="text" name="unpublished" id="unpublished" class="input_calendar" value="<?=$unpublished?>" size="20"/></td>
	</tr>
	<?php if(priv::aca('system', 'related')): ?>
		<tr>
			<th class="vtop">相关：</th>
			<td colspan="3"><?=element::related($contentid)?></td>
		</tr>
	<?php endif;?>
    <tr>
        <th>评论：</th>
        <td colspan="3"><label><input type="checkbox" name="allowcomment" id="allowcomment" value="1" <?php if ($allowcomment) echo 'checked';?> class="checkbox_style"/> 允许</label></td>
    </tr>
    <tr>
        <th>状态：</th>
        <td colspan="3"><?=table('status', $status, 'name')?></td>
    </tr>
</table>

<div id="field" onclick="field.expand(this.id)" class="mar_l_8 hand title" title="点击展开" style="display:none;"><span class="span_close">扩展字段</span></div>
<table id="field_c" width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
</table>

<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<tr><th width="80"></th>
		<td width="60">
			<input type="submit" value="保存" class="button_style_2" style="float:left"/>
		</td>
		<td width="60">
			<input type="button" value="预览" onclick="preview(<?=$contentid?>, <?=$catid?>, <?=$modelid?>)" class="button_style_2" style="float:left"/>
		</td>
		<td style="color:#444;text-align:left">按Ctrl+S键保存</td>
	</tr>
</table>
</form>

<?php $this->display('content/success', 'system');?>

<link href="<?=IMG_URL?>js/lib/colorInput/style.css" rel="stylesheet" type="text/css" />
<script src="<?=IMG_URL?>js/lib/cmstop.colorInput.js" type="text/javascript"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script type="text/javascript" src="apps/system/js/datapicker.js"></script>
<script type="text/javascript" src="apps/vote/js/option.js"></script>
<script type="text/javascript" src="js/related.js"></script>
<script src="apps/system/js/field.js" type="text/javascript" ></script>
<script type="text/javascript">
var get_tag = <?php echo $get_tag;?> + 0;
<?php foreach ($option as $k=>$r):?>
option.add('<?=$r['name'];?>', '<?=$r['votes'];?>', '<?=$r['sort'];?>', '<?=$r['optionid'];?>', '<?=$r['link'];?>', '<?=$r['thumb'];?>');
<?php endforeach;?>
// 获取自定义字段
$(function() {
    var catid = $('#catid');
    function categoryReady() {
        catid.data('selectree') && catid.data('selectree').bind('checked', function(item) {
            item.catid && field.getbycid(<?=$contentid?>, item.catid);
            item.catid && content.isModelEnabled(item.catid);
        });
    }
    if (catid.data('selectree')) {
        categoryReady();
    } else {
        catid.bind('categoryReady', categoryReady);
    }
    if(catid.val()) field.getbycid(<?=$contentid?>, catid.val());
});
//预览功能
var preview = function (contentid, catid, modelid) {
	window.open(APP_URL+'?app=system&controller=content&action=preview&contentid='+contentid+'&catid='+catid+'&modelid='+modelid);
}
</script>

<?php $this->display('footer');