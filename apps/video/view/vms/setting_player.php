<?php $this->display('header'); ?>

<style>
    .skinlist{}
    .skinlist li{float:left;margin:5px;}
    .skinlist li img{border:1px solid #ccc; padding:2px;width:120px;height:80px;cursor:pointer;}
    .skinlist .checked{border:2px solid #077AC7;padding:1px;}
</style>

<div class="bk_8"></div>
<div class="tag_1" style="margin-bottom:0;">
    <ul class="tag_list" id="pagetab">
        <li><a href="?app=video&controller=vms&action=setting">接口参数配置</a></li>
        <li><a href="?app=video&controller=thirdparty&action=index">第三方接口配置</a></li>
        <li><a href="?app=video&controller=vms&action=setting_video&type=convert">转码参数配置</a></li>
        <li><a href="?app=video&controller=vms&action=setting_video&type=player" class="s_3">播放器参数配置</a></li>
    </ul>
</div>

<div class="bk_10"></div>
<form name="video_vms_setting_player" id="video_vms_setting_player" action="?app=video&controller=vms&action=set_setting" method="POST">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>播放器</caption>
        <tr>
            <th width="120">默认皮肤：</th>
            <td>
                <input type="hidden" id="player_skinNum" name="player[skinNum]" value="1" />
                <ul class="skinlist"></ul>
            </td>
        </tr>
        <tr>
            <th>播放器LOGO文件：</th>
            <td>
                <input type="text" name="player[logoImgPath]" value="" size="60"/> <span id="up_start" class="button upload_btn">上传</span>
            </td>
        </tr>
        <tr>
            <th>播放器LOGO链接：</th>
            <td>
                <input type="text" name="player[logoClickUrl]" value="" size="60"/>
            </td>
        </tr>
        <tr>
            <th>视频接口：</th>
            <td>
                <input type="text" name="player[videoInfoPath]" value="" size="60"/>
            </td>
        </tr>
        <tr>
            <th>广告接口：</th>
            <td>
                <input type="text" name="player[adInfoPath]" value="" size="60"/>
            </td>
        </tr>
        <tr>
            <th>统计接口：</th>
            <td>
                <input type="text" name="player[statUrl]" value="" size="60"/>
            </td>
        </tr>
        <tr>
            <th>推荐接口：</th>
            <td>
                <input type="text" name="player[recommendInfoPath]" value="" size="60"/>
            </td>
        </tr>
        <tr>
            <th>开启推荐：</th>
            <td>
                <input type="radio" name="player[isShowRecommend]" value="1" />开启 &nbsp;
                <input type="radio" name="player[isShowRecommend]" value="0" />关闭
            </td>
        </tr>
        <tr>
            <th>显示广告：</th>
            <td>
                <input type="radio" name="player[isShowAd]" value="1" />开启 &nbsp;
                <input type="radio" name="player[isShowAd]" value="0" />关闭
            </td>
        </tr>
        <tr>
            <th>自动播放：</th>
            <td>
                <input type="radio" name="player[autoStart]" value="1" />开启 &nbsp;
                <input type="radio" name="player[autoStart]" value="0" />关闭
            </td>
        </tr>
        <tr>
            <th>记忆播放：</th>
            <td>
                <input type="radio" name="player[isBreakpoint]" value="1" />开启 &nbsp;
                <input type="radio" name="player[isBreakpoint]" value="0" />关闭
            </td>
        </tr>
        <tr>
            <th>默认清晰度：</th>
            <td>
                <input type="radio" name="player[definition]" value="1" />标清 &nbsp;
                <input type="radio" name="player[definition]" value="2" />高清 &nbsp;
                <input type="radio" name="player[definition]" value="3" />超清
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
<script type="text/javascript">
    function submit_ok(data) {
        if (data.state) ct.tips("保存成功");
        else ct.error(data.error);
    }
    $(function(){
        $('#video_vms_setting_player').ajaxForm('submit_ok');
        $('.upload_btn').each(function(i,v){
            var input = $(this).prev();
            $(this).uploader({
                script : '?app=system&controller=upload&action=upload',
                fileExt : '*.jpg;*.png;',
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
        $.getJSON('?app=video&controller=vms&action=get_setting&type=player', function(json){
            if(json.state && json.data){
                $('#video_vms_setting_player').find(':input').each(function(i,v){
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
                    }
                });
                var skinlist = $('.skinlist');
                for(var i=0;i<json.data.player.skins.length;i++){
                    var img = '<li><img src="'+json.data.player.skins[i]+'" alt="点击选择" title="点击选择" /></li>';
                    skinlist.append(img);
                }
                $('#player_skinNum').val(json.data.player.skinNum);
                skinlist.find('li:eq('+(json.data.player.skinNum-1)+')').find('img').addClass('checked');
                skinlist.find('li').each(function(i,v){
                    $(this).bind('click', function(){
                        $('#player_skinNum').val(i+1);
                        $(this).find('img').addClass('checked');
                        $(this).siblings().find('img').removeClass('checked');
                    });
                });
            }else{
                ct.error(json.error ? json.error : '数据异常');
            }
        });
    });
</script>
<?php $this->display('footer'); ?>