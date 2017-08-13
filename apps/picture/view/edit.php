<?php $this->display('header'); ?>
<style type="text/css">
    .image-list {
        margin-top: 5px;
        min-height: 175px;
        overflow-x: hidden;
        overflow-y: auto;
    }
</style>
<form name="picture_edit" id="picture_edit" method="POST" action="?app=picture&controller=picture&action=edit">
    <input type="hidden" name="contentid" id="contentid" value="<?=$contentid?>"/>
    <input type="hidden" name="modelid" id="modelid" value="<?=$modelid?>"/>
    <input type="hidden" name="old_total" id="modelid" value="<?=$total?>"/>
    <? if ($status == 6): ?>
    <input type="hidden" name="url" id="url" value="<?=$url?>"/>
    <? endif; ?>
    <table width="98%" border="0" id="tabledata" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <tr>
            <th width="80"><span class="c_red">*</span> 栏目：</th>
            <td><?=element::category('catid', 'catid', $catid)?></td>
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
                <textarea name="description" id="description" maxLength="255" style="width:710px;height:40px" class="bdr"><?=$description?></textarea>
            </td>
        </tr>
        <tr>
            <th> Tags：</th>
            <td><?=element::tag('tags', $tags)?></td>
        </tr>
        <tr>
            <th>缩略图：</th>
            <td><?php echo element::image('thumb', $thumb, 45);?></td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 组图：</th>
            <td>
                <input type="button" data-role="group-add" class="button_style_1" value="添加图片" />
                <input type="button" id="asc_order" class="button_style_1" value="按名称升序" />
                <input type="button" id="desc_order" class="button_style_1" value="按名称降序" />
                <input type="button" id="set_desc" class="button_style_1" value="批量设置图说" />
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
                <input type="text" name="source" autocomplete="1" value="<?=$source?>" url="?app=system&controller=source&action=suggest&q=%s" />
                <label>
                    <input id="reserved" type="checkbox" class="mar_l_8" /> 转载
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label for="editor">编辑： </label>
                <input type="text" name="editor" value="<?=$editor?>" size="15" />
            </td>
        </tr>
        <tr class="source_panel">
            <th>来源标题：</th>
            <td class="c_077ac7">
                <input type="text" name="source_title" value="<?php echo $source_title;?>" class="source-link-input" />
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
            <th width="80"><?=element::tips('组图定时发布时间')?> 上线：</th>
            <input type="hidden" name="oldpublished" value="<?=$published?>" />
            <td width="170"><input type="text" name="published" id="published" class="input_calendar" value="<?=$published?>" size="20"/></td>
            <th width="80">下线：</th>
            <td><input type="text" name="unpublished" id="unpublished" class="input_calendar" value="<?=$unpublished?>" size="20"/></td>
        </tr>
        <?php if (priv::aca('system', 'related')): ?>
        <tr>
            <th class="vtop">相关：</th>
            <td colspan="3"><?=element::related($contentid)?></td>
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
            <td colspan="3"><?=table('status', $status, 'name')?></td>
        </tr>
        <?php 
        $db = & factory::db();
        $arr = explode(',',$yuanquid);
        $res = '';
        $count = count($arr);
        foreach($arr as $k=>$v){
            if($k<$count-1){
                $res .= table('content',$v,'title').';';
            }elseif($k==$count-1){
                $res .= table('content',$v,'title');
            }
        }
        
        ?>
        <tr>
            <th class="vtop">所属园区：</th>
            <td><textarea type="text" value="" name="yuanqu" class=""><?=$res?></textarea></td>
            <td id="tishi" style="color:red;display:none"></td>
        </tr>
    </table>
    <script type="text/javascript">
        $(function(){
            $("textarea[name=yuanqu]").blur(function(){
                $("#tishi").css('display','none');
                var yuanqu = $(this).val();
                if(yuanqu){
                //发送ajax查询是否存在
                $.getJSON(APP_URL+'?app=system&controller=yuanqu&action=chaxun&jsoncallback=?&yuanqu='+yuanqu,function(data){
                        if(!data.state){
                            $("#tishi").css('display','block').html(data.error);
                        }
                
            });
            }
        })
    })
    </script>
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
                <input type="button" value="预览" onclick="preview(<?=$contentid?>, <?=$catid?>, <?=$modelid?>)" class="button_style_2" style="float:left"/>
            </td>
            <td style="color:#444;text-align:left">按Ctrl+S键保存</td>
        </tr>
    </table>
</form>
<div id="div_set_desc" style="display: none; position: absolute; overflow: hidden; z-index: 1002; outline: 0px none; height: auto; width: 360px; top: 68.45px; left: 427px;" class="ui-dialog dialog-box ui-draggable" tabindex="-1" role="dialog" aria-labelledby="ui-dialog-title-1"><div class="titlebar" unselectable="on" style="-moz-user-select: none;"><span class="pop_title" id="ui-dialog-title-1" unselectable="on" style="-moz-user-select: none;">添加用户</span><span class="close" role="button" unselectable="on" style="-moz-user-select: none;"></span></div><div class="masker"></div><div class="ui-dialog-content ui-widget-content" style="overflow: auto; max-height: 500px; position: relative; height: 136px; min-height: 86px; width: 360px;"><div class="bk_8"></div>
<table cellspacing="0" cellpadding="0" border="0" width="98%" class="table_form">
    <tbody><tr>
        <th width="80">图说：</th>
        <td><textarea id="thumb_desc" style="width:250px;height:110px;"></textarea></td>
    </tr>
</tbody></table>
<div class="validator-infobox validator-error" style="position: absolute; z-index: 100; left: 244.6px; display: block; top: 30px;">用户名长度不够</div></div><div class="btn_area"><button type="button" id="desc_ok">确定</button><button type="button" id="desc_close">取消</button></div></div>
<div class="ui-widget-overlay" style="z-index: 1001;display:none;"></div>

<?php $this->display('content/success', 'system');?>

<link href="<?=IMG_URL?>js/lib/autocomplete/style.css" rel="stylesheet" type="text/css"/>
<link href="<?=IMG_URL?>js/lib/colorInput/style.css" rel="stylesheet" type="text/css"/>
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>
<script src="<?=IMG_URL?>js/lib/cmstop.colorInput.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.lightbox.js"></script>
<script type="text/javascript" src="js/related.js"></script>
<script type="text/javascript" src="apps/picture/js/group.js"></script>
<link rel="stylesheet" type="text/css" href="css/imagesbox.css" media="screen"/>
<script type="text/javascript" src="js/cmstop.imageList.js"></script>
<script src="apps/system/js/field.js" type="text/javascript" ></script>
<link rel="stylesheet" type="text/css" href="js/imageList/style.css" />
<script type="text/javascript">
    var get_tag = <?php echo $get_tag;?> + 0;
    $('input[name*=related_keywords]').val($('#tags').val());
    group.init(<?=json_encode($pictures ? $pictures : array())?>);

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

        $('#asc_order').bind('click', function() {
            group.order('asc');
        });
        $('#desc_order').bind('click', function() {
            group.order('desc');
        });
        $('#desc_ok').click(function(){
            var text = $('#thumb_desc').val();
            $('#local_image_list textarea').val(text);
            $('#local_image_list .image-thumb-item').find('input[data-role="desc"]').val(text);
            $('#div_set_desc').hide().next('.ui-widget-overlay').hide();
        });
        $('#desc_close').click(function(){
            $('#div_set_desc').hide().next('.ui-widget-overlay').hide();
        });
        $('#set_desc').click(function(){
            if ($('#local_image_list li').length <= 1) {
                ct.error('请添加图片！');
                return false;
            }
            $('#div_set_desc').show().next('.ui-widget-overlay').show();
            $('#div_set_desc').find('textarea').val('');
        });
        $('#desc_ok').click(function(){
            var text = $('#thumb_desc').val();
            $('#div_set_desc').hide().next('.ui-widget-overlay').hide();
            setTimeout(function() {
                $('#local_image_list .image-thumb-item').find('input[data-role=desc]').val(text);
                $('#local_image_list').find('textarea').val(text);
                $('#local_image_list').find('.image-thumb-item-desc').trigger('click');
                $(document).trigger('click');
            }, 100);
        });
        $('#desc_close').click(function(){
            $('#div_set_desc').hide().next('.ui-widget-overlay').hide();
        });
    });
        //预览功能
    var preview = function (contentid, catid, modelid) {
        window.open(APP_URL+'?app=system&controller=content&action=preview&contentid='+contentid+'&catid='+catid+'&modelid='+modelid);
    }
</script>
<?php $this->display('footer');