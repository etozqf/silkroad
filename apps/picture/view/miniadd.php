<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<title><?=$head['title']?></title>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<link rel="stylesheet" type="text/css" href="apps/system/css/content.css"/>
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/validator/style.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.validator.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.cookie.js"></script>
<link href="<?=IMG_URL?>js/lib/treeview/treeview.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.treeview.js"></script>
<link href="<?=IMG_URL?>js/lib/tree/style.css" rel="stylesheet" type="text/css" />
<script src="<?=IMG_URL?>js/lib/cmstop.tree.js" type="text/javascript"></script>
<!-- 时间选择器 -->
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.datepicker.js"></script>
<link href="<?=IMG_URL?>js/lib/datepicker/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>

<script type="text/javascript">
$.validate.setConfigs({
    xmlPath:'apps/picture/validators/'
});
$(function(){
    ct.listenAjax();
});
</script>
</head>
<script type="text/javascript">
    window.toolbox = {
        'model': 'picture',
        'action': 'miniadd'
    };
</script>
<script type="text/javascript" src="apps/system/js/content.js"></script>
<body>
<link rel="stylesheet" type="text/css" href="apps/system/css/toolbox.css" />
<style type="text/css">
    .image-list {
        margin-top: 5px;
        min-height: 175px;
        overflow-x: hidden;
        overflow-y: auto;
    }
</style>
<form name="picture_add" id="picture_add" method="POST" action="?app=picture&controller=picture&action=add">
    <div class="mini-main">
        <input type="hidden" name="modelid" id="modelid" value="2" />
        <input type="hidden" name="toolbox" value="1" />
        <table width="98%" border="0" id="tabledata" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
            <tr>
                <th width="80"><span class="c_red">*</span> 栏目：</th>
                <td><?=element::category('catid', 'catid', $catid)?>&nbsp;&nbsp;<?=element::referto()?></td>
            </tr>
            <tr>
                <th><span class="c_red">*</span> 标题：</th>
                <td>
                    <?=element::title('title', $title, $color)?>
                    <label><input type="checkbox" name="has_subtitle" id="has_subtitle" value="1" <?=($subtitle ? 'checked' : '')?> class="checkbox_style" onclick="show_subtitle()"/> 副题</label>
                </td>
            </tr>
            <tr id="tr_subtitle" style="display:<?=($subtitle ? 'table-row' : 'none')?>">
                <th>副题：</th>
                <td><input type="text" name="subtitle" id="subtitle" value="<?=$subtitle?>" size="100" maxlength="120"/>
                </td>
            </tr>
            <tr>
                <th>摘要：</th>
                <td>
                    <textarea name="description" id="description" maxLength="255" style="width:626px;height:40px" class="bdr"><?=$description?></textarea>
                </td>
            </tr>
            <tr>
                <th> Tags：</th>
                <td><?=element::tag('tags', $tags)?></td>
            </tr>
            <tr>
                <th>缩略图：</th>
                <td><?php echo element::image('thumb', '', 45);?></td>
            </tr>
            <tr>
                <th><span class="c_red">*</span> 组图：</th>
                <td>
                    <input type="button" data-role="group-add" class="button_style_1" value="添加图片" />
                    <div class="image-list" style="position: relative;">
                        <div id="local_image_list">
                            <ul>
                                <li class="image-thumb-empty">暂无图片</li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>来源：</th>
                <td class="c_077ac7">
                    <input type="text" name="source" autocomplete="1" value="<?=$source?>" url="?app=system&controller=source&action=suggest&q=%s" class="source_input" />
                    <label>
                        <input id="reserved" type="checkbox" class="mar_l_8" /> 转载
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="editor">编辑： </label>
                    <input type="text" name="editor" value="<?=$editor?>" size="15" class="source_input" />
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
                <td>
                    <?=element::weight($weight, $myweight);?>
                </td>
            </tr>
        </table>

        <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
            <tr>
                <th width="80"><?=element::tips('组图定时发布时间')?> 上线：</th>
                <td width="170"><input type="text" name="published" id="published" class="input_calendar" value="<?=$published?>" size="20"/></td>
                <th width="80">下线：</th>
                <td><input type="text" name="unpublished" id="unpublished" class="input_calendar" value="<?=$unpublished?>" size="20"/></td>
            </tr>
            <?php if (priv::aca('system', 'related')): ?>
            <tr>
                <th class="vtop">相关：</th>
                <td colspan="3"><?=element::related()?></td>
            </tr>
            <?php endif;?>
            <tr>
                <th>评论：</th>
                <td colspan="3">
                    <label><input type="checkbox" name="allowcomment" id="allowcomment" value="1" <?php if ($allowcomment) echo 'checked';?> class="checkbox_style"/> 允许</label>
                </td>
            </tr>
            <tr>
                <th>状态：</th>
                <td colspan="3">
                    <?php
                    $workflowid = table('category', $catid, 'workflowid');
                    if (priv::aca($app, $app, 'publish')) {
                        ?>
                        <label><input type="radio" name="status" id="status" value="6" checked="checked"/> 发布</label> &nbsp;
                        <?php
                    }
                    elseif ($workflowid && priv::aca($app, $app, 'approve')) {
                        ?>
                        <label><input type="radio" name="status" id="status" value="3" checked="checked"/> 送审</label> &nbsp;
                        <?php }?>
                    <label><input type="radio" name="status" id="status" value="1"/> 草稿</label>
                </td>
            </tr>
        </table>

    	<div id="field" onclick="field.expand(this.id)" class="mar_l_8 hand title" title="点击展开" style="display:none;"><span class="span_close">扩展字段</span></div>
    	<table id="field_c" width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
    	</table>
    </div>
    <div class="cmstop-message-box-bd-fd mini-footer">
        <a href="javascript:_close('取消发布');" title="" class="btn-cancel">取消</a>
        <input type="submit" name="" class="btn-ok" value="保存" />
    </div>
</form>
<?php $this->display('content/success', 'system');?>
<link href="<?=IMG_URL?>js/lib/autocomplete/style.css" rel="stylesheet" type="text/css"/>
<link href="<?=IMG_URL?>js/lib/colorInput/style.css" rel="stylesheet" type="text/css"/>
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>
<script src="<?=IMG_URL?>js/lib/cmstop.colorInput.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.lightbox.js"></script>
<script type="text/javascript" src="js/related.js"></script>
<script type="text/javascript" src="apps/picture/js/group.js"></script>
<script type="text/javascript" src="apps/page/js/section.js"></script>
<script type="text/javascript" src="apps/special/js/push.js"></script>
<link rel="stylesheet" type="text/css" href="css/imagesbox.css" media="screen"/>
<script type="text/javascript" src="js/cmstop.imageList.js"></script>
<script src="apps/system/js/field.js" type="text/javascript" ></script>
<link rel="stylesheet" type="text/css" href="js/imageList/style.css" />
<script type="text/javascript">
    var get_tag = <?php echo $get_tag;?> + 0;
    $(document).ready(function () {
        checkRepeat.init(<?=$repeatcheck?>);
        group.init();
        // 远程图片本地化
        var romoteList = <?php echo json_encode($content ? $content : array());?>;
        var localImageList = $('#local_image_list').find('ul');
        var saveRomote = function(romoteList, isWriteThumb) {
            if (!romoteList.length) return true;
            var romoteItem = romoteList.shift();
            localImageList.append('<li id="romoting" class="image-thumb-item"><a class="image-thumb-item-a" href="javascript:;" title=""></a></li>');
            $.post('?app=picture&controller=picture&action=remote', {'modelid':2, 'remote_pictures':romoteItem['pic']}, function(response) {
                $('#romoting').remove();
                if (response.state) {
                    var hasMore = (romoteList.length > 0), item = [];
                    response.data = response.data[0].split('|');
                    item.aid = response.data[0];
                    item.image = response.data[1];
                    item.desc = romoteItem.desc;
                    group.add(item, hasMore);

                    if (isWriteThumb) {
                        // 设置为缩略图
                        $('input[name="thumb"]').val(response.data[1]);
                    }
                }
                return saveRomote(romoteList); // 递归
            }, 'json');
        }
        if (saveRomote(romoteList, true)) {
            ct.ok('图片采集完成', undefined, 1);
        }
    });
    var frm = $('#picture_add');
    var elements = frm.find('input,textarea,select').not(':button,:submit,:image,:reset,[disabled]');
    $.fn.autocomplete && elements.filter(function(){
        return (!!this.getAttribute('autocomplete') && this.getAttribute('url'));
    }).autocomplete({
        autoFill:false,
        showEvent:'focus'
    });
    $('input[name*=related_keywords]').val($('#tags').val());
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
        if(catid.val()) field.getbycid(catid.val());

        var miniMainHeight = $(window).height()-40;
        $('.mini-main').height(miniMainHeight);
        $('#picture_add').ajaxForm(function(json) {
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
        }, null, null);
        $('.input_calendar').DatePicker({'format':'yyyy-MM-dd HH:mm:ss'});
	});
    function _close(s) {
        if (location.hash == "#ie") {
            // 兼容旧版IE右键转载
            window.close();
        }
        location.href = IMG_URL+'apps/system/close.html?s='+s;
    }
</script>
<?php $this->display('footer');