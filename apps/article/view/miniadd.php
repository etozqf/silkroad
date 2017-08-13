<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<title><?=$head['title']?></title>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<link rel="stylesheet" type="text/css" href="apps/system/css/content.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/validator/style.css"/>

<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.validator.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>
<link href="<?=IMG_URL?>js/lib/treeview/treeview.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.treeview.js"></script>

<link href="<?=IMG_URL?>js/lib/tree/style.css" rel="stylesheet" type="text/css" />
<script src="<?=IMG_URL?>js/lib/cmstop.tree.js" type="text/javascript"></script>

<!-- 时间选择器 -->
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.datepicker.js"></script>
<link href="<?=IMG_URL?>js/lib/datepicker/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
var app = 'article';
var controller = 'article';
var action = 'add';
var catid = '<?=$catid?>';
var modelid = '1';
var repeatcheck = <?=$repeatcheck?> + 0;
var contentid = '<?=$contentid?>';
var get_tag = <?php echo $get_tag;?> + 0;
$.validate.setConfigs({
    xmlPath:'apps/article/validators/'
});
$(function(){
	var miniMainHeight = $(window).height()-40;
	$('.mini-main').height(miniMainHeight);
    ct.listenAjax();
});
window.toolbox = {
    'model': 'article',
    'action': 'miniadd'
};
</script>
<script type="text/javascript" src="apps/system/js/content.js"></script>
</head>
<style type="text/css">
	.mini-footer{ height:42px; background-color:#ccc;width:100%;}
	.mini-main{overflow-x:hidden; overflow-y:auto; position:relative;} /* fix IE7 bug */
	form{overflow:hidden;}
	body{overflow:hidden; position:relative;}
	.mini-footer {height:40px;background-image:url(css/images/ct-toolbox-bar.png);margin:0}
	.mini-footer .btn-ok{position:relative;float:right;top:2px;color:#fff;display:block;width:72px;height:32px;background:transparent url(css/images/ct-toolbox-boxico.png) no-repeat -202px top;border-width:0;}
	.mini-footer .btn-cancel{position:relative;float:right;top:6px;display:block;width:72px;height:32px;background:transparent url(css/images/ct-toolbox-boxico.png) no-repeat -202px -34px;color:#666;text-align:center;line-height:30px; margin-right:18px;}
	body, h1, p, a, img {margin:0;padding:0;}
	#related_keywords {margin-left:2px;padding-left:2px;}
    .tree.selectree {
         max-height:200px;
     }
</style>
<body onload="self.focus()">
	<form name="article_add" id="article_add" method="POST" action="?app=article&controller=article&action=add">
		<div class="mini-main">
			<input type="hidden" name="modelid" id="modelid" value="1" />
			<input type="hidden" name="toolbox" value="1" />
			<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
				<tr>
					<th width="80"><span class="c_red">*</span> 栏目：</th>
					<td>  
						<?=element::category('catid', 'catid', $catid)?>
						&nbsp;&nbsp;<?=element::referto()?>
					</td>
				</tr>
				<tr>
					<th><span class="c_red">*</span> 标题：</th>
					<td><?=element::title('title', $title, $color, 80)?>
					<label><input type="checkbox" name="has_subtitle" id="has_subtitle" value="1" <?=($subtitle ? 'checked' : '')?> class="checkbox_style" onclick="show_subtitle()" /> 副题</label>
					</td>
				</tr>
				<tr id="tr_subtitle" style="display:<?=($subtitle ? 'table-row' : 'none')?>">
					<th>副题：</th>
					<td><input type="text" name="subtitle" id="subtitle" value="<?=$subtitle?>" size="100" maxlength="120" /></td>
				</tr>
			</table>
			<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form" >
				<tr>
					<th width="80">&nbsp;&nbsp;</th>
					<td width="540" style="padding-left:9px;">
						<textarea name="content" id="content" style="visibility:hidden;height:450px;width:630px;"><?=$content?></textarea>
					</td>
					<td class="vtop"></td>
				</tr>
			</table>
			<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form" >
				<tr>
					<th width="80">&nbsp;</th>
					<td>
						<table>
							<tr>
								<td width="483">
									<label>
										<input type="checkbox" name="saveremoteimage" id="saveremoteimage" value="1" <?php if ($saveremoteimage) echo 'checked';?> class="checkbox_style"/>
										远程图片本地化
									</label>
								</td>
								<td width="70"><button id="get_thumb_button" type="button" class="button_style_1" style="width: 80px;" onclick="get_thumb();">提取缩略图</button></td>
								<td>
									<div id="multiUp" class="uploader"></div>
								</td>
							</tr>
						</table>
						<div id="get_thumb" class="get_thumb"></div>
					</td>
				</tr>
                <tr>
                    <th><?=element::tips('输入词组后以回车确认增加，关键词最多5个')?>Tags：</th>
                    <td><?=element::tag('tags', $tags)?></td>
                </tr>
				<tr>
					<th>摘要：</th>
					<td>
						<textarea name="description" id="description" maxLength="255" style="width:627px;height:40px;" class="bdr"><?=$description?></textarea>
					</td>
				</tr>
				<tr>
					<th>缩略图：</th>
					<td><?=element::image('thumb', '', 60)?></td>
				</tr>
                <tr>
                    <th>来源：</th>
                    <td class="c_077ac7">
                        <input type="text" name="source" autocomplete="1" value="<?=$source?>" url="?app=system&controller=source&action=suggest&q=%s" class="source_input" />
                        <label>
                            <input id="reserved" type="checkbox" class="mar_l_8" /> 转载
                        </label>
                        &nbsp;&nbsp;
                        <label for="author">作者： </label>
                        <input type="text" name="author" autocomplete="1" value="<?=$author?>" url="?app=space&controller=index&action=suggest&q=%s" size="9" class="source_input" />
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="editor">编辑： </label>
                        <input type="text" name="editor" value="<?=$editor?>" size="9" class="source_input" />
                    </td>
                </tr>
                <tr class="source_panel">
                    <th>来源标题：</th>
                    <td class="c_077ac7">
                        <input type="text" name="source_title" value="<?php echo $title;?>" class="source-link-input" />
                    </td>
                </tr>
                <tr class="source_panel">
                    <th>来源链接：</th>
                    <td class="c_077ac7">
                        <input id="source_link" type="url" name="source_link" value="<?php echo $source_link;?>" class="source-link-input" />
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a id="open_source_link" href="javascript:;">打开链接</a>
                    </td>
                </tr>
				<tr>
					<th>属性：</th>
					<td><?=element::property()?></td>
				</tr>
				<tr>
					<th><?=element::tips('权重将决定文章在哪里显示和排序')?> 权重：</th>
					<td><?=element::weight($weight, $myweight);?></td>
				</tr>
			</table>

			<?php
			$catid && $allowcomment = table('category', $catid, 'allowcomment');
			?>
			<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
				<tr>
                    <th width="80"><?=element::tips('文章定时发布时间')?> 上线：</th>
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
                        <label><input type="radio" name="status" id="status" value="1"/> 草稿</label>
                    </td>
                </tr>
			</table>

			<div id="field" onclick="field.expand(this.id)" class="mar_l_8 hand title" title="点击展开"><span class="span_close">扩展字段</span></div>
			<table id="field_c" width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
			</table>
		</div>
		<div class="cmstop-message-box-bd-fd mini-footer">
			<a href="javascript:_close('取消发布');" title="" class="btn-cancel">取消</a>
			<input type="submit" name="" class="btn-ok" value="保存" />
		</div>
	</form>
	<?php $this->display('content/success', 'system');?>
    <div class="ct_tips warning success-msg" id="repeat-tips">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="2" style="text-align:center"><strong>检测到相似文章:</strong></th>
        </tr>
        <tr>
            <td colspan="2" class="t_c" data-role="buttons">
                <button id="goonwhenrepeat" class="button_style_4" type="button">继续保存</button>
                <button id="cancelwhenrepeat" class="button_style_2" type="button">取消</button>
            </td>
        </tr>
    </table>
</div>
<link href="<?=IMG_URL?>js/lib/autocomplete/style.css" rel="stylesheet" type="text/css" />
<link href="<?=IMG_URL?>js/lib/colorInput/style.css" rel="stylesheet" type="text/css" />
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>
<script src="<?=IMG_URL?>js/lib/cmstop.colorInput.js" type="text/javascript"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script src="tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script src="tiny_mce/editor.js" type="text/javascript"></script>
<script src="js/related.js" type="text/javascript"></script>
<script src="apps/article/js/field.js" type="text/javascript"></script>

<?php
foreach(explode('|', UPLOAD_FILE_EXTS) as $exts) {
	$allow .=  '*.'.$exts.';';
}
?>
<script type="text/javascript">
$(function(){
	var ed = null;
	$("#multiUp").uploader({
			script : '?app=editor&controller=filesup&action=upload',
			fileDataName : 'multiUp',
			fileExt : '<?=$allow?>',
			buttonImg : 'images/multiup.gif',
			complete:function(response, data){
				response =(new Function("","return "+response))();
				if(response.state) {
					ed || (ed = tinyMCE.get('content'));
					ed.execCommand('mceInsertContent', false, response.code);
					ct.ok(response.msg);
				} else {
					ct.error(response.msg);
				}
			}
	});
	if ($('#title').val())
	{
		$.post('?app=system&controller=tag&action=get_tags', $('form#article_add').serialize(), function(response){
			if (response.state)
			{
				$('#tags').val(response.data);
			}
		}, 'json');
	}
	var elements = $('form').find('input,textarea,select').not(':button,:submit,:image,:reset,[disabled]');
	$.fn.colorInput && elements.filter('input.color-input').colorInput();
});

$('#content').editor();

$('#article_add').ajaxForm(function(json) {
	if (json.state){
		content.contentid = json.contentid;
		success = $('#success-msg');
		success.find('[data-role="title"]').html($('#title').val());
		if (json.url) {
			success.find('[data-role="url"]').attr('href', json.url).html(json.url);
		} else {
			// 非发布状态
			success.find('[data-role="message"]').html('转载成功，进入待审');
			success.find('[data-role="pushto"]').remove();
			success.find('[data-role="url"]').parents('tr').remove();
		}
		$('<button class="button_style_1">关闭</button>').appendTo(success.find('[data-role="buttons"]')).bind('click', function() {
			_close('发布成功');
		});
		success.css('z-index','999').show();
		$('body').append('<div style="position: fixed; z-index: 998; left: 0px; top: 0px; width: 100%; height: 100%; margin: 0px; padding: 0px; opacity: 0.8; background: none repeat scroll 0% 0% rgb(255, 255, 255);"></div>');
	}else{
		content.error(json);
	}
}, null, function () {
    // 内容重复检测
    if ($('#article_add').data('needPrefab')) {
        $('#article_add').trigger('prefab');
        return false;
    }
});

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
	$("div.cs_sb").find('input').keyup(function(){
        catid.val() && field.get(catid.val());
	});
	$('div.cs_mitem').click(function(){
        catid.val() && field.get(catid.val());
	});

	$('#title').keyup();
	$(function (){
		//$("#catid option[childids='1']").attr("disabled", "disabled");
		
		$('.tips').attrTips('tips', 'tips_green');
		var frm = $('#article_add');
		var elements = frm.find('input,textarea,select').not(':button,:submit,:image,:reset,[disabled]');
		$.fn.autocomplete && elements.filter(function(){
			return (!!this.getAttribute('autocomplete') && this.getAttribute('url'));
		}).autocomplete({
			autoFill:false,
			showEvent:'focus'
		});
		elements.filter('input.input_calendar').DatePicker({'format':'yyyy-MM-dd HH:mm:ss'});
		elements.filter('[name=title]').focus();
	});
});

function _close(s) {
	if (location.hash == "#ie") {
		// 兼容旧版IE右键转载
		window.close();
	}
	location.href = IMG_URL+'apps/system/close.html?s='+s;
}
$(document).ready(function() {
	
});
</script>