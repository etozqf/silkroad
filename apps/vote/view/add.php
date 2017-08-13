<?php $this->display('header');?>
<style type="text/css">
.check-repeat-panel .icon {background: url(<?=IMG_URL?>js/lib/dropdown/bg.gif) no-repeat scroll 0 -50px transparent;	margin-right: 8px;	width: 16px;height: 20px;float: left;}
</style>
<form name="vote_add" id="vote_add" method="post" action="?app=vote&controller=vote&action=add">
	<input type="hidden" name="modelid" id="modelid" value="<?=$modelid?>">
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
          <tr>
            <th width="80"><span class="c_red">*</span> 栏目：</th>
            <td><?=element::category('catid', 'catid', $catid)?>&nbsp;&nbsp;<?=element::referto()?></td>
          </tr>
		<tr>
			<th><span class="c_red">*</span> 标题：</th>
			<td>
				<?=element::title('title', $title, $color)?>
				<label><input type="checkbox" name="has_subtitle" id="has_subtitle" value="1" <?=($subtitle ? 'checked' : '')?> class="checkbox_style" onclick="show_subtitle()" /> 副题</label>
			</td>
		</tr>
		<tr id="tr_subtitle" style="display:<?=($subtitle ? 'table-row' : 'none')?>">
			<th>副题：</th>
			<td><input type="text" name="subtitle" id="subtitle" value="<?=$subtitle?>" size="100" maxlength="120" /></td>
		</tr>
		<tr>
			<th>类型：</th>
			<td class="lh_24"><input name="type" type="radio" value="radio" checked="checked" class="checkbox_style" onclick="$('#maxoptions_span').hide();" /> 单选
			    <input name="type" type="radio" class="checkbox_style" value="checkbox" onclick="$('#maxoptions_span').show();" /> 多选
			    <span id="maxoptions_span" style="display: none">最多可选  <input id="maxoptions" name="maxoptions" type="text" size="2" value="<?=$maxoptions?>" /> 项 <?=element::tips('留空为不限制')?></span>
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
							<th width="60">初始票数</th>
							<th width="30">删</th>
						</tr>
					</thead>
					<tbody id="options" style="position: relative;"></tbody>
				</table>
			</td>
	    </tr>
		<tr>
			<th>&nbsp;</th>
			<td><div class="mar_l_8 mar_5"><input name="add_option_btn" type="button" value="增加选项" class="hand button_style" onclick="option.add()" /></div></td>
		</tr>
	</table>
	
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <tr>
            <th width="80">模式：</th>
            <td class="lh_24">
                <label><input onclick="$(this).parent().siblings('span').css('visibility', 'hidden');$('[data-role=thumb]').hide()" name="display" type="radio" value="list" checked="checked" class="checkbox_style" /> 普通模式</label>
                <label><input onclick="$(this).parent().siblings('span').css('visibility', 'visible');$('[data-role=thumb]').show()" name="display" type="radio" value="thumb" class="checkbox_style" /> 评选模式</label>
                <span style="visibility:hidden;">
                    图片大小：<input type="text" placeholder="宽" name="thumb_width" size="3" value="185" /> x <input type="text" placeholder="高" name="thumb_height" size="3" value="135" /> px
                </span>
            </td>
        </tr>
        <tr data-role="thumb" style="display: none;">
            <th>页面背景图：</th>
            <td><?=element::image('bgimg', '', 60)?></td>
        </tr>
        <tr>
            <th>介绍：</th>
            <td><textarea name="description" id="description" maxLength="255" style="width:710px;height:40px" class="bdr"><?=$description?></textarea></td>
        </tr>
        <tr>
            <th> Tags：</th>
            <td><?=element::tag('tags', $tags)?></td>
        </tr>
        <tr>
            <th>缩略图：</th>
            <td><?=element::image('thumb', '', 60)?></td>
        </tr>
		<tr>
			<th>防刷限制：</th>
			<td>同IP <input id="mininterval" name="mininterval" type="text" value="<?=$mininterval?>" size="4" />小时内不得重复投票 <?=element::tips('0或者留空为不限制')?></td>
		</tr>
        <tr>
            <th>验证码类型：</th>
            <td>
                <label><input name="seccode_type" type="radio" value="normal" checked="checked" class="checkbox_style" /> 普通 </label>
                <label><input name="seccode_type" type="radio" value="advanced" class="checkbox_style" /> 高级 </label>
            </td>
        </tr>
		<?php if(!empty($area)):?>
		<tr>
			<th width="120">开启省份限制：</th>
			<td>
				<div class="switchpanel">
					<input class="switch" type="checkbox" name="arealimit" value="1" />&nbsp;(<a href="javascript:ct.assoc.open('?app=vote&controller=setting&action=index', 'newtab');">管理</a>)
				</div>
			</td>
		</tr>
		<?php endif;?>
		<tr>
			<th>开始时间：</th>
			<td><input id="starttime" name="starttime" type="text" class="input_calendar" value="<?=$starttime?>" size="20"/></td>
		</tr>
		<tr>
			<th>截止时间：</th>
			<td><input id="endtime" name="endtime" type="text" class="input_calendar" value="<?=$endtime?>" size="20"/></td>
		</tr>
		<tr>
			<th>属性：</th>
			<td><?=element::property()?></td>
		</tr>
        <tr>
            <th><?=element::tips('权重将决定文章在哪里显示和排序')?> 权重：</th>
            <td>
            <?=element::weight($weight, $myweight);?>
            </td>
        </tr>
	</table>
<?php
$catid && $allowcomment = table('category', $catid, 'allowcomment');
?>
	 <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
		<tr>
		  <th width="80"><?=element::tips('投票定时发布时间')?> 上线：</th>
		  <td width="170"><input type="text" name="published" id="published" class="input_calendar" value="<?=$published?>" size="20"/></td>
		  <th width="80">下线：</th>
		  <td><input type="text" name="unpublished" id="unpublished" class="input_calendar" value="<?=$unpublished?>" size="20"/></td>
		</tr>
        <?php if(priv::aca('system', 'related')): ?>
		<tr>
			<th class="vtop">相关：</th>
			<td colspan="3"><?=element::related()?></td>
		</tr>
        <?php endif;?>
         <tr>
             <th>评论：</th>
             <td colspan="3"><label><input type="checkbox" name="allowcomment" id="allowcomment" value="1" <?php if ($allowcomment) echo 'checked';?> class="checkbox_style"/> 允许</label></td>
         </tr>
         <tr>
             <th>状态：</th>
             <td colspan="3">
                 <?php
                 $workflowid = table('category', $catid, 'workflowid');
                 if (priv::aca($app, $app, 'publish')){
                     ?>
                     <label><input type="radio" name="status" id="status" value="6" checked="checked"/> 发布</label> &nbsp;
                     <?php
                 }
                 elseif ($workflowid && priv::aca($app, $app, 'approve')){
                     ?>
                     <label><input type="radio" name="status" id="status" value="3" checked="checked"/> 送审</label> &nbsp;
                     <?php }?>
                 <label><input type="radio" name="status" id="status" value="1" <?php if ($status == 1) echo 'checked';?>/> 草稿</label>&emsp;
             </td>
         </tr>
	 </table>

	<div id="field" onclick="field.expand(this.id)" class="mar_l_8 hand title" title="点击展开" style="display:none;"><span class="span_close">扩展字段</span></div>
	<table id="field_c" width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	</table>

	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
		<tr>
			<th width="80"></th>
			<td width="60">
				<input type="submit" value="保存" class="button_style_2" style="float:left"/>
			</td>
			<td width="60">
				<input type="button" value="预览" onclick="preview(<?=modelid?>)" class="button_style_2" style="float:left"/>
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
option.add();
option.add();
$(document).ready(function() {
	checkRepeat.init(<?=$repeatcheck?>);
});

// 获取自定义字段
$(function() {
    var catid = $('#catid');
    function categoryReady() {
        catid.data('selectree') && catid.data('selectree').bind('checked', function(item) {
            item.catid && field.get(item.catid);
            item.catid && content.isModelEnabled(item.catid);
        });
    }
    if (catid.data('selectree')) {
        categoryReady();
    } else {
        catid.bind('categoryReady', categoryReady);
    }
    if(catid.val()) field.get(catid.val());
});
//预览功能
var preview = function (contentid, catid, modelid) {
	$.post('?app=system&controller=content&action=preview', $('#vote_add').serialize(), function (json){
		if (json.state) {
			window.open(APP_URL+'?app=system&controller=content&action=preview&key='+json.key);
		} else {
			ct.error(json.error);
		}
	}, "json");
}
</script>
<?php $this->display('footer');