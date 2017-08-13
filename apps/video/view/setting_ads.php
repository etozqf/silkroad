<?php $this->display('header'); ?>

<div class="bk_10"></div>
<form name="video_setting_ads" id="video_setting_ads" action="?app=video&controller=video&action=setting_ads" method="POST">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>片头广告</caption>
        <tr>
            <th width="120">状态：</th>
            <td>
                <input type="radio" name="ads[begin][open]" value="1" />开启 &nbsp;
                <input type="radio" name="ads[begin][open]" value="0" />关闭
            </td>
        </tr>
        <tr>
            <th>资源地址：</th>
            <td><input type="text" name="ads[begin][file]" value="" size="60"/> <span id="up_start" class="button upload_btn">上传</span></td>
        </tr>
        <tr>
            <th>广告链接：</th>
            <td><input type="text" name="ads[begin][link]" value="" size="60"/></td>
        </tr>
        <tr>
            <th>广告时长：</th>
            <td><input type="text" name="ads[begin][time]" value="" size="4"/> 秒</td>
        </tr>
    </table>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>暂停广告</caption>
        <tr>
            <th width="120">状态：</th>
            <td>
                <input type="radio" name="ads[pause][open]" value="1" />开启 &nbsp;
                <input type="radio" name="ads[pause][open]" value="0" />关闭
            </td>
        </tr>
        <tr>
            <th>资源地址：</th>
            <td><input type="text" name="ads[pause][file]" value="" size="60"/> <span id="up_pause" class="button upload_btn">上传</span></td>
        </tr>
        <tr>
            <th>广告链接：</th>
            <td><input type="text" name="ads[pause][link]" value="" size="60"/></td>
        </tr>
    </table>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>片尾广告</caption>
        <tr>
            <th width="120">状态：</th>
            <td>
                <input type="radio" name="ads[end][open]" value="1" />开启 &nbsp;
                <input type="radio" name="ads[end][open]" value="0" />关闭
            </td>
        </tr>
        <tr>
            <th>资源地址：</th>
            <td><input type="text" name="ads[end][file]" value="" size="60"/> <span id="up_end" class="button upload_btn">上传</span></td>
        </tr>
        <tr>
            <th>广告链接：</th>
            <td><input type="text" name="ads[end][link]" value="" size="60"/></td>
        </tr>
        <tr>
            <th>广告时长：</th>
            <td><input type="text" name="ads[end][time]" value="" size="4"/> 秒</td>
        </tr>
        <tr>
            <th></th>
            <td valign="middle"><br/>
                <input type="submit" value="保存" class="button_style_2"/>
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    var ads = <?php echo ($ads ? json_encode(array('ads'=>$ads)) : "''"); ?>;
    var json = {};
    function submit_ok(data) {
        if (data.state) ct.tips("保存成功");
        else ct.error(data.error);
    }
    $(function(){
        $('#video_setting_ads').ajaxForm('submit_ok');
        $('.upload_btn').each(function(i,v){
            var input = $(this).prev();
            var fileext = '*.jpg;*.png;*.swf;*.flv;*.mp4';
            if($(this).attr('id') == 'up_pause'){
                fileext = '*.jpg;*.png;*.swf';
            }
            $(this).uploader({
                script : '?app=system&controller=upload&action=upload',
                fileExt : fileext,
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
        if(ads){
            json.data = ads;
            $('#video_setting_ads').find('input').each(function(i,v){
                var name = $(this).attr('name');
                if(!name) return false;
                var k1,k2,k3;
                k1 = name.substring(0,name.indexOf('['));
                k2 = name.substring(k1.length + 1, name.indexOf(']'));
                k3 = name.substring(k1.length + k2.length + 3, name.lastIndexOf(']'));
                if($(this).attr('type') == 'radio'){
                    if($(this).val() == json.data[k1][k2][k3]){
                        $(this).attr('checked', true);
                    }
                }else if($(this).attr('type') == 'text'){
                    $(this).val(json.data[k1][k2][k3]);
                }
            });
        }
    });
</script>
<?php $this->display('footer'); ?>