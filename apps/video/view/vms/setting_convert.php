<?php $this->display('header'); ?>
<div class="bk_8"></div>
<div class="tag_1" style="margin-bottom:0;">
    <ul class="tag_list" id="pagetab">
        <li><a href="?app=video&controller=vms&action=setting">接口参数配置</a></li>
        <li><a href="?app=video&controller=thirdparty&action=index">第三方接口配置</a></li>
        <li><a href="?app=video&controller=vms&action=setting_video&type=convert" class="s_3">转码参数配置</a></li>
        <li><a href="?app=video&controller=vms&action=setting_video&type=player">播放器参数配置</a></li>
    </ul>
</div>

<div class="bk_10"></div>
<form name="video_vms_setting_convert" id="video_vms_setting_convert" action="?app=video&controller=vms&action=set_setting" method="POST">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>视频水印</caption>
        <tr>
            <th width="120">状态：</th>
            <td>
                <input type="radio" name="watermark[open]" value="1" />开启 &nbsp;
                <input type="radio" name="watermark[open]" value="0" />关闭
            </td>
        </tr>
        <tr>
            <th>水印图片：</th>
            <td><input type="text" name="watermark[tmpfile]" value="" size="60"/> <span class="button upload_btn">上传</span></td>
        </tr>
        <tr>
            <th>水印位置：</th>
            <td>
                <select id="watermark_pos" name="watermark[pos]">
                    <option value="0">左上角</option>
                    <option value="1">右上角</option>
                </select>
            </td>
        </tr>
        <tr>
            <th></th>
            <td valign="middle"><br/>
                <input type="submit" value="保存" class="button_style_2"/>
            </td>
        </tr>
    </table>
</form>

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>
<script type="text/javascript">
    function submit_ok(data) {
        if (data.state) ct.tips("保存成功");
        else ct.error(data.error);
    }
    $(function(){
        $('#video_vms_setting_convert').ajaxForm('submit_ok');
        $('.upload_btn').each(function(i,v){
            var input = $(this).prev();
            $(this).uploader({
                script : '?app=system&controller=upload&action=upload',
                fileExt : '*.png',
                jsonType:1,
                complete:function(json, data) {
                    if (json.state) {
                        input.val(UPLOAD_URL + json.file);
                    } else {
                        ct.error(json.error);
                    }
                },
                error:function(data) {
                    ct.error(data.type+':'+data.info);
                }
            });
        });
        $.getJSON('?app=video&controller=vms&action=get_setting&type=watermark', function(json){
            if(json.state && json.data){
                $('#video_vms_setting_convert').find(':input').each(function(i,v){
                    var name = $(this).attr('name');
                    if(!name) return false;
                    var k1,k2;
                    k1 = name.substring(0,name.indexOf('['));
                    k2 = name.substring(k1.length + 1, name.indexOf(']'));
                    if($(this).attr('type') == 'radio'){
                        if($(this).val() == json.data[k1][k2]){
                            $(this).attr('checked', true);
                        }
                    }else if($(this).attr('type') == 'text'){
                        $(this).val(json.data[k1][k2]);
                    }else if($(this).attr('type') == 'select-one'){
                        $(this).val(json.data[k1][k2]);
                    }
                });
                $('#watermark_pos').selectlist();
            }else{
                ct.error(json.error ? json.error : '数据异常');
            }
        });
    });
</script>
<?php $this->display('footer'); ?>