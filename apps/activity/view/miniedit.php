<?php $this->display('header');?>
<link rel="stylesheet" type="text/css" href="apps/system/css/toolbox.css" />
<style type="text/css">
    .over th{background-color:#fffddd;}
</style>
<form name="activity_edit" id="activity_edit" method="POST" action="?app=activity&controller=activity&action=edit">
<div class="mini-main">
<input type="hidden" name="modelid" id="modelid" value="<?=$modelid?>" />
<input type="hidden" name="contentid" id="contentid" value="<?=$contentid?>" />
<? if($status == 6): ?>
<input type="hidden" name="url" id="url" value="<?=$url?>" />
    <? endif; ?>
<table width="98%" border="0" cellspacing="0" cellpediting="0" class="table_form mar_l_8">
    <tr>
        <th width="80"><span class="c_red">*</span> 栏目：</th>
        <td><?=element::category('catid', 'catid', $catid)?></td>
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
        <th>Tags：</th>
        <td><?=element::tag('tags', $tags)?></td>
    </tr>
    <tr>
        <th>活动时间：</th>
        <td> <input type="text" name="starttime" id="srarttime" size="20" class="input_calendar" value="<?=$starttime?>">&nbsp;~&nbsp;<input type="text" name="endtime" id="endtime" class="input_calendar" size="20" value="<?=$endtime?>"></td>
    </tr>
    <tr>
        <th>报名时间：</th>
        <td><input type="text" name="signstart" id="signstart" class="input_calendar" size="20" value="<?=$signstart?>">&nbsp;~&nbsp;<input type="text" name="signend" id="signend" class="input_calendar" size="20" value="<?=$signend?>"></td>
    </tr>
    <tr>
        <th>人数限制：</th>
        <td>
            <input type="text" name="maxpersons" id="maxpersons" size="10" value="<?=$maxpersons?>">
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
        <th>活动类型：</th>
        <td><input type="text" name="type" id="type"  value="<?=$type?>" size="10"/></td>
    </tr>
    <tr>
        <th>活动地址：</th>
        <td><input id="point" name="point" type="hidden" value="<?=$point?>" /><input type="text" name="address" id="address"  value="<?=$address?>" size="80"/><a href="javascript:;" id="mapswitch" style="margin-left:10px;text-decoration:underline">使用地图</a></td>
    </tr>
    <tr>
        <th></th>
        <td><div style="width:628px;height:340px;border:1px solid #CCC;display:none" id="map"></div></td>
    </tr>
    <tr>
        <th>活动介绍：</th>
        <td><textarea name="content" id="content" style="visibility:hidden;height:300px;width:632px"><?=$content?></textarea></td>
    </tr>
    <tr>
        <th>摘要：</th>
        <td><textarea name="description" id="description" maxLength="255" style="width:627px;height:40px;" class="bdr"><?=$description?></textarea></td>
    </tr>
    <tr>
        <th>缩略图：</th>
        <td><?=element::image('thumb',$thumb,45);?></td>
    </tr>
    <tr>
        <th>接收人：</th>
        <td><input type="text" name="mailto" id="mailto"  value="<?=$mailto?>" size="20"/></td>
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
    <tr>
        <th><?=element::tips('推送至页面')?> 页面：</th>
        <td><?=element::section()?></td>
    </tr>
    <tr>
        <th><?=element::tips('推送至专题')?> 专题：</th>
        <td><input type="hidden" value="<?=$placeid?>" class="push-to-place" name="placeid" /></td>
    </tr>
</table>

<div class="mar_l_8 hand title" id="activity-fields" title="点击收起">
    <a href="javascript:void(0);" id="manage_field" style="float:right;margin-right:10px;">字段管理</a>
    <span class="span_open">表单选择</span>
</div>
<div id="inputs"><?php $this->display('field/render'); ?></div>
<script type="text/javascript" src="apps/activity/js/activityField.js" ></script>

<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
    <tr>
        <th width="80"><?=element::tips('活动定时发布时间')?> 上线：</th>
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
</div>
<div class="cmstop-message-box-bd-fd mini-footer">
    <a href="javascript:_close('取消发布');" title="" class="btn-cancel">取消</a>
    <input type="submit" name="" class="btn-ok" value="保存" />
</div>
</form>
<link href="<?=IMG_URL?>js/lib/colorInput/style.css" rel="stylesheet" type="text/css" />
<script src="<?=IMG_URL?>js/lib/cmstop.colorInput.js" type="text/javascript"></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="tiny_mce/editor.js"></script>
<script type="text/javascript" src="apps/system/js/psn.js"></script>
<script type="text/javascript" src="js/related.js"></script>
<script type="text/javascript" src="apps/page/js/section.js"></script>
<script type="text/javascript" src="apps/special/js/push.js"></script>
<?php if($baidumapkey): ?>
<script type="text/javascript" src="http://api.map.baidu.com/api?key=<?=$baidumapkey?>&v=1.3&services=true" ></script>
<script type="text/javascript" src="apps/system/js/field.js" ></script>
<script type="text/javascript">
    var get_tag = <?php echo $get_tag;?> + 0;
    var isInit = false;
    $('#mapswitch').click(function(){
        var mapobj = $('#map'), t=this;
        mapobj.slideToggle('slow',function(){
            if(!isInit){
                function addMarker(point)
                {
                    var marker = new BMap.Marker(point);
                    map.addOverlay(marker);
                }
                var map = new BMap.Map("map"),
                    pointval = $('#point').val(),
                    point = new BMap.Point(116.404, 39.915);
                if(pointval)
                {
                    var a = pointval.split(',');
                    point = new BMap.Point(parseFloat(a[0]),parseFloat(a[1]));
                }
                map.enableScrollWheelZoom();
                map.addControl(new BMap.NavigationControl());
                map.centerAndZoom(point, 12);
                addMarker(point);
                map.addEventListener("click", function(e){
                    $('#point').val(e.point.lng+','+e.point.lat);
                    map.clearOverlays();
                    addMarker(e.point);
                });
                isInit = true;
                var address = $('#address').val();
                if(address.length > 1){
                    // 创建地址解析器实例
                    var myGeo = new BMap.Geocoder();
                    // 将地址解析结果显示在地图上，并调整地图视野
                    myGeo.getPoint(address, function(point){
                        if (point) {
                            $('#point').val(point.lng+','+point.lat);
                            map.centerAndZoom(point, 16);
                            map.addOverlay(new BMap.Marker(point));
                        }
                    }, '\u5168\u56fd');	// 全国
                }
            }
            t.innerHTML = mapobj.is(':visible')?'关闭地图':'使用地图';
        });
    });
</script>
<?php else: ?>
<script type="text/javascript">
    var tip = ct.tips('您还未设置百度地图', 'warning', 'center', 5);
    tip.append('&nbsp;<a style="color:#336633;margin-left:10px;text-decoration:underline;" class="close" href="javascript:ct.assoc.open(\'?app=system&controller=setting&action=api\');">点击设置</a>');
</script>
<?php endif ?>
<script type="text/javascript">
    $('#content').editor('mini');
    $(function(){
        var miniMainHeight = $(window).height()-40;
        $('.mini-main').height(miniMainHeight);
        $('#activity_edit').ajaxForm(function(json) {
            if (json.state){
                var text;
                text    = '恭喜,编辑成功.';
                ct.confirm(text, function() {
                    _close('编辑成功');
                },function() {
                    _close('编辑成功');
                });
            }else{
                content.error(json);
            }
        }, null, null);
        $('.input_calendar').DatePicker({'format':'yyyy-MM-dd HH:mm:ss'});
    });

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

    function _close(s) {
        if (location.hash == "#ie") {
            // 兼容旧版IE右键转载
            window.close();
        }
        location.href = IMG_URL+'apps/system/close.html?s='+s;
    }
</script>
<?php $this->display('footer');
