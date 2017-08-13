<?php $this->display('header');?>
<style>
.uploaderexcle{display: inline-block;height: 20px;line-height: 20px;margin-left: 10px;overflow: hidden;position: relative;text-align: center;width: 70px;border: 1px solid #BED9E8;background: #F8FBFD;color: #077AC7;}
</style>   
<form name="article_edit" id="article_edit" method="POST" action="?app=<?=$app?>&controller=<?=$app?>&action=edit">
<input type="hidden" name="contentid" id="contentid" value="<?=$contentid?>" />
<? if($status == 6): ?>
<input type="hidden" name="url" id="url" value="<?=$url?>" />
<? endif; ?>
<input type="hidden" name="modelid" id="modelid" value="<?=$modelid?>">
<input type="hidden" name="old_pagecount" id="old_pagecount" value="<?=$pagecount?>">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<tr>
		<th width="80"><span class="c_red">*</span> 栏目：</th>
		<td><?=element::category('catid', 'catid', $catid)?></td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 标题：</th>
		<td><?=element::title('title', $title, $color, 200, 200, '900px')?>
		<label><input type="checkbox" name="has_subtitle" id="has_subtitle" value="1" <?=($subtitle ? 'checked' : '')?> class="checkbox_style" onclick="show_subtitle()" /> 副题</label>
		</td>
	</tr>
		<tr id="tr_subtitle" style="display:<?=($subtitle ? 'table-row' : 'none')?>">
		<th>副题：</th>
		<td><input type="text" name="subtitle" id="subtitle" value="<?=$subtitle?>" size="100" maxlength="120" /></td>
	</tr>
</table>

<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tr>
		<th width="80">&nbsp;&nbsp;</th>
		<td width="540" style="padding-left:9px;position:relative"><textarea name="content" id="content" style="visibility:hidden;height:450px;width:630px;"><?=$content?></textarea></td>
		<td class="vtop"></td>
	</tr>
</table>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tr>
		<th width="80"></th>
		<td>
			<table>
				<tr>
					<?php if($o2h_allowed):?>
                    <td width="350">
                        <input type="hidden" name="saveremoteimage" value="<?=($saveremoteimage ? '1' : '0')?>" />
                        <label>
                            <input type="checkbox" id="saveremoteimage" onclick="$(this).closest('td').find('[name=saveremoteimage]').val(this.checked ? '1' : '0');" <?php if ($saveremoteimage) echo 'checked';?> class="checkbox_style"/>
                            远程图片本地化
                        </label>
                    </td>
					<td width="200">
                        <button id="get_thumb_button" type="button" class="button_style_1" style="width: 80px;" onclick="get_thumb();">提取缩略图</button>
                        <span style="visibility:hidden;" id="wordUp" class="button">上传Office文档</span>
					</td>
					<?php else:?>
					<td width="460">
                        <input type="hidden" name="saveremoteimage" value="<?=($saveremoteimage ? '1' : '0')?>" />
                        <label>
                            <input type="checkbox" id="saveremoteimage" onclick="$(this).closest('td').find('[name=saveremoteimage]').val(this.checked ? '1' : '0');" <?php if ($saveremoteimage) echo 'checked';?> class="checkbox_style"/>
                            远程图片本地化
                        </label>
                    </td>
					<td width="80">
                        <button id="get_thumb_button" type="button" class="button_style_1" style="width: 80px;" onclick="get_thumb();">提取缩略图</button>
					</td>
					<?php endif;?>
					<td>
						<div id="multiUp" class="uploader"></div>
					</td>
                    <td>
                            <div id="excelUp" class="uploaderexcle">Excel上传</div>
                    </td>
				</tr>
			</table>
			<div id="get_thumb" class="get_thumb"></div>
		</td>
	</tr>
    <?php if (priv::aca('addon', 'addon')): ?>
    <tr>
        <th>内容挂件：</th>
        <td>
            <?=element::addon($contentid)?>
        </td>
    </tr>
    <?php endif; ?>
    <tr>
        <th><?=element::tips('输入词组后以回车确认增加，关键词最多5个')?>Tags：</th>
        <td><?=element::tag('tags', $tags)?></td>
    </tr>
    <tr>
        <th>摘要：</th>
        <td><textarea name="description" id="description" maxLength="255" style="width:627px;height:40px" class="bdr"><?=$description?></textarea></td>
    </tr>
    <tr>
        <th>缩略图：</th>
        <td><?=element::image('thumb', $thumb, 60)?></td>
    </tr>
    <tr>
        <th><?=element::tips('输入词组后以回车确认增加')?>作者：</th>
        <td>
            <input type="text" name="author" id="author" autocomplete="1" value="<?php echo htmlspecialchars($author);?>" url="?app=space&controller=index&action=suggest&q=%s" size="9" class="source_input" paramVal="author" paramTxt="author" anytext="2" />
            <script type="text/javascript">$(function() { window.authorInput = suggest($("#author"), {width:200,limit:100,delimiter:",",url:"?app=space&controller=index&action=suggest&q=%s",paramVal:"author",paramTxt:"author",anytext:"2"}); $('#author').next().find('.listAllBtn').remove()});</script>
        </td>
    </tr>
    <tr>
        <th><?=element::tips('输入词组后以回车确认增加')?>编辑：</th>
        <td>
            <input type="text" name="editor" id="editor" value="<?=$editor?>" size="9" class="source_input" paramVal="editor" paramTxt="editor" anytext="3" />
            <script type="text/javascript">$(function() { window.editorInput = suggest($("#editor"), {width:200,limit:100,delimiter:",",paramVal:"editor",paramTxt:"editor",anytext:"3"});  $('#editor').next().find('.listAllBtn').remove()});</script>
        </td>
    </tr>
    <tr>
        <th>来源：</th>
        <td class="c_077ac7">
            <input type="text" name="source" autocomplete="1" value="<?=$source?>" url="?app=system&controller=source&action=suggest&q=%s" class="source_input" />
            <label>
                <input id="reserved" type="checkbox" class="mar_l_8" /> 转载
            </label>
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
            <input id="source_link" type="url" name="source_link" value="<?=$source_link?>" class="source-link-input" />
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

<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tr>
		<th width="80"><?=element::tips('文章定时发布时间')?> 上线：</th>
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
	<tr>
        <th>是否收費：</th>
        <td>
            <label><input type="radio" name="ischarge" value="1" class="radio_style" <?php if ($ischarge == 1) echo 'checked';?> /> 是</label>
            &nbsp;&nbsp;
            <label><input type="radio" name="ischarge" value="0" class="radio_style" <?php if ($ischarge == 0) echo 'checked';?>> 否</label>
        </td>
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

<div id="field" onclick="field.expand(this.id)" class="mar_l_8 hand title" title="点击展开"><span class="span_close">扩展字段</span></div>
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

<?php $this->display('content/success', 'system');?>

<link href="<?=IMG_URL?>js/lib/autocomplete/style.css" rel="stylesheet" type="text/css" />
<link href="<?=IMG_URL?>js/lib/colorInput/style.css" rel="stylesheet" type="text/css" />
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>
<script src="<?=IMG_URL?>js/lib/cmstop.colorInput.js" type="text/javascript"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="tiny_mce/editor.js"></script>
<script type="text/javascript" src="js/related.js"></script>
<!--2011/05/9 加载自定义字段js-->
<script src="apps/system/js/field.js" type="text/javascript" ></script>
<?php
foreach(explode('|', UPLOAD_FILE_EXTS) as $exts) {
    $allow .=  '*.'.$exts.';';
}
?>
<script type="text/javascript">
var get_tag = <?php echo $get_tag;?> + 0;
$(function(){
	$("#multiUp").uploader({
		script : '?app=editor&controller=filesup&action=upload',
		fileDataName : 'multiUp',
		fileExt : '<?=$allow?>',
		buttonImg : 'images/multiup.gif',
		complete:function(response, data){
			response =(new Function("","return "+response))();
			if(response.state) {
				tinyMCE.activeEditor.execCommand('mceInsertContent', false, response.code);
				ct.ok(response.msg);
			} else {
				ct.error(response.msg);
			}
		}
	});
    $.ajax({type: 'GET', url: 'http://o2h.cmstop.com/cmstop.o2h.js', success: function() {
        var wordUp = document.getElementById('wordUp');
        if (! wordUp) return false;
        wordUp.style.visibility = 'visible';
        new O2H(wordUp, {
            uploadComplete:function(html){
                tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
            },
            uploadError:function(err){
                ct.error(err);
            }
        });
    }, dataType: 'script', ifModified: false, cache: true});

    // excel上传
    $("#excelUp").uploader({
        script : '?app=system&controller=upload&action=upload',
        fileDesc : 'Excel上传', 
        fileExt : '*.xls;*.xlsx;',
        nulti : false,
        sizeLimit : 20480000,
        complete:function(data){
            $.ajax({
                type: 'POST', 
                url: '?app=article&controller=upexcel&action=upexcel', 
                data:'json='+data, 
                success: function(json) {
                    if(json.state){
                        tinyMCE.activeEditor.execCommand('mceInsertContent', false,json.html);
                        ct.ok(json.message);
                    }else{
                        ct.error(json.error);
                    }
            }, dataType: 'json'});
        }
    });
});

$('#content').editor(undefined, {'onchange_callback':'editCallback'});
$('input[name*=related_keywords]').val($('#tags').val());

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

$('#title').one('change', function() {
	window.changed = true;
});
var editCallback = function() {
	window.changed = true;
};
//预览功能
var preview = function (contentid, catid, modelid) {
	window.open(APP_URL+'?app=system&controller=content&action=preview&contentid='+contentid+'&catid='+catid+'&modelid='+modelid);
}
</script>
<?php $this->display('footer');
