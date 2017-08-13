<?php $this->display('header');?>
<style type="text/css">
    .over th{background-color:#fffddd;}
    .check-repeat-panel .icon {background: url(<?=IMG_URL?>js/lib/dropdown/bg.gif) no-repeat scroll 0 -50px transparent;	margin-right: 8px;	width: 16px;height: 20px;float: left;}
</style>
<form name="activity_add" id="activity_add" method="POST" action="?app=activity&controller=activity&action=add">
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
        <th>活动介绍：</th>
        <td><textarea name="content" id="content" style="visibility:hidden;height:300px;width:632px"><?=$content?></textarea></td>
    </tr>
    <tr>
        <th>摘要：</th>
        <td><textarea name="description" id="description" maxLength="255" style="width:627px;height:40px;" class="bdr"><?=$description?></textarea> </td>
    </tr>
    <tr>
        <th>Tags：</th>
        <td><?=element::tag('tags', $tags)?></td>
    </tr>
    <tr>
        <th>活动时间：</th>
        <td> <input type="text" name="starttime" id="srarttime" class="input_calendar" size="20" value="<?php echo date("Y-m-d H:i:s",TIME);?>"/>&nbsp;~&nbsp;<input type="text" name="endtime" id="endtime" class="input_calendar" size="20" /></td>
    </tr>
    <tr>
        <th>报名时间：</th>
        <td> <input type="text" name="signstart" id="signstart" class="input_calendar" size="20" />&nbsp;~&nbsp;<input type="text" name="signend" id="signend" class="input_calendar" size="20" /></td>
    </tr>
    <tr>
        <th>人数限制：</th>
        <td> <input type="text" name="maxpersons" id="maxpersons" size="10" >
            <label><input type="radio" class="radio_style" name="gender" id="gender" value="1" />男</label>
            <label><input type="radio" class="radio_style" name="gender" id="gender" value="2" />女</label>
            <label><input type="radio" class="radio_style" name="gender" id="gender" value="0"  checked="checked">不限</label>
        </td>
    </tr>
    <tr>
        <th>防刷限制：</th>
        <td>同IP <input id="mininterval" name="mininterval" type="text" value="<?=$mininterval?>" size="4" />小时内不得重复投票 <?=element::tips('0或者留空为不限制')?></td>
    </tr>
    <tr>
        <th>页面背景图：</th>
        <td><?=element::image('bgimg','',45);?></td>
    </tr>
    <tr>
        <th>活动类型：</th>
        <td><input type="text" name="type" id="type"  value="<?=$type?>" size="10"/></td>
    </tr>
    <tr>
        <th>活动地址：</th>
        <td><input id="point" name="point" type="hidden" /><input type="text" name="address" id="address"  value="<?=$address?>" size="80"/><a href="javascript:;" id="mapswitch" style="margin-left:10px;text-decoration:underline">使用地图</a></td>
    </tr>
    <tr>
        <th></th>
        <td><div style="width:628px;height:340px;border:1px solid #CCC;display:none" id="map"></div></td>
    </tr>
    <tr>
        <th>缩略图：</th>
        <td><?=element::image('thumb','',45);?></td>
    </tr>
    <tr>
        <th><?=element::tips('邮件正文的模板放在 ' . config('template', 'name') . '/activity/mail.html ，如遇到邮件正文无内容的情况，请检查该模板是否存在')?> 接收人：</th>
        <td><input type="text" name="mailto" id="mailto"  value="<?=$mailto?>" size="20"/></td>
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

<div class="mar_l_8 hand title" id="activity-fields" title="点击收起">
    <a href="javascript:void(0);" id="manage_field" style="float:right;margin-right:10px;">字段管理</a>
    <span class="span_open">表单选择</span>
</div>
<div id="inputs"><?php $this->display('field/render'); ?></div>
<script type="text/javascript" src="apps/activity/js/activityField.js" ></script>

<?php
$catid && $allowcomment = table('category', $catid, 'allowcomment');
?>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
    <tr>
        <th width="80"><?=element::tips('活动定时发布时间')?> 上线：</th>
        <td width="170"><input type="text" name="published" class="input_calendar" value="<?=$published?>" size="20"/></td>
        <th width="80">下线：</th>
        <td><input type="text" name="unpublished" class="input_calendar" value="<?=$unpublished?>" size="20"/></td>
    </tr>
    <?php if(priv::aca('system', 'related')): ?>
    <tr>
        <th class="vtop">相关：</th>
        <td colspan="3"><?=element::related()?></td>
    </tr>
    <?php endif;?>
    <tr>
        <th>评论：</th>
        <td colspan="3"><label><input type="checkbox" name="allowcomment" value="1" <?php if ($allowcomment) echo 'checked';?> class="checkbox_style"/> 允许</label></td>
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
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="tiny_mce/editor.js"></script>
<script type="text/javascript" src="apps/system/js/psn.js"></script>
<script type="text/javascript" src="js/related.js"></script>
<script type="text/javascript" src="apps/system/js/field.js" ></script>
<link href="http://api.map.baidu.com/res/11/bmap.css" rel="stylesheet" type="text/css" />
<script src="http://api.map.baidu.com/getscript?v=1.1&services=true&js" type="text/javascript"></script>
<script src="http://api.map.baidu.com/library/CityList/1.2/src/CityList_min.js" type="text/javascript"></script>

<script type="text/javascript">
    $('#content').editor('mini');

    var get_tag = <?php echo $get_tag;?> + 0;
    $(function(){
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

    // baiduMap
    $(function(){
        $('#mapswitch').bind('click', function(){
            var point = '',
                address = '',
                url = '?app=activity&controller=activity&action=map';
            var d = ct.formDialog({
                    title: '百度地图',
                    width: 600,
                    height: 420
                },
                url,
                function(){},
                function(){
                    baiduMap.load();
                },
                function(form, dialog, options){
                    baiduMap.beforeSubmit();
                    var zoom = dialog.find('input[name=zoom]').val();
                    var point = dialog.find('input[name=marker]').val();
                    point = JSON.parse(point);
                    $('#point').val(point.lng+','+point.lat+','+(zoom || 12));
                    d.dialog('close');
                    return false;
                }
            );
        });
    });
    //预览功能
var preview = function (contentid, catid, modelid) {
    $.post('?app=system&controller=content&action=preview', $('#activity_add').serialize(), function (json){
        if (json.state) {
            window.open(APP_URL+'?app=system&controller=content&action=preview&key='+json.key);
        } else {
            ct.error(json.error);
        }
    }, "json");
}
</script>
<?php $this->display('footer');
