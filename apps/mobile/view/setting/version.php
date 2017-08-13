<?php $this->display('header', 'system'); ?>

<div class="mar_l_8 mar_t_8">
    <div id="tabs" class="tag_1">
        <ul class="tag_list" style="clear: both; overflow: hidden;">
            <li><a href="#iphone">iPhone</a></li>
            <li><a href="#android">Android</a></li>
            <li><a href="#ipad">iPad</a></li>
            <li><a href="#pad">Android Pad</a></li>
        </ul>
        <div id="iphone" class="mar_t_10">
            <form name="version" action="?app=mobile&controller=setting&action=version" method="POST" class="validator">
                <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
                    <caption>更新版本</caption>
                    <tr>
                        <th width="80"><span class="c_red">*</span> 版本代号：</th>
                        <td><input type="text" name="iphone_version" value="<?=$setting['iphone_version']?>" size="8" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top: 5px; vertical-align: top;">更新说明：</th>
                        <td>
                            <textarea name="iphone_version_description" cols="80" rows="5"><?=$setting['iphone_version_description']?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th> 下载地址：</th>
                        <td><input type="text" name="iphone_version_url" value="<?=$setting['iphone_version_url']?>" size="80" /></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class="bk_8"></div>
                            <input type="submit" value="保存" class="button_style_2"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="android" class="mar_t_10">
            <form name="version" action="?app=mobile&controller=setting&action=version" method="POST" class="validator">
                <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
                    <caption>更新版本</caption>
                    <tr>
                        <th width="80"><span class="c_red">*</span> 版本代号：</th>
                        <td><input type="text" name="android_version" value="<?=$setting['android_version']?>" size="8" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top: 5px; vertical-align: top;">更新说明：</th>
                        <td>
                            <textarea name="android_version_description" cols="80" rows="5"><?=$setting['android_version_description']?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th> 下载地址：</th>
                        <td><input type="text" name="android_version_url" value="<?=$setting['android_version_url']?>" size="80" /></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class="bk_8"></div>
                            <input type="submit" value="保存" class="button_style_2"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="ipad" class="mar_t_10">
            <form name="version" action="?app=mobile&controller=setting&action=version" method="POST" class="validator">
                <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
                    <caption>更新版本</caption>
                    <tr>
                        <th width="80"><span class="c_red">*</span> 版本代号：</th>
                        <td><input type="text" name="iPad_version" value="<?=$setting['iPad_version']?>" size="8" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top: 5px; vertical-align: top;">更新说明：</th>
                        <td>
                            <textarea name="iPad_version_description" cols="80" rows="5"><?=$setting['iPad_version_description']?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th> 下载地址：</th>
                        <td><input type="text" name="iPad__version_url" value="<?=$setting['iPad_version_url']?>" size="80" /></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class="bk_8"></div>
                            <input type="submit" value="保存" class="button_style_2"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="pad" class="mar_t_10">
            <form name="version" action="?app=mobile&controller=setting&action=version" method="POST" class="validator">
                <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
                    <caption>更新版本</caption>
                    <tr>
                        <th width="80"><span class="c_red">*</span> 版本代号：</th>
                        <td><input type="text" name="Pad_version" value="<?=$setting['Pad_version']?>" size="8" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top: 5px; vertical-align: top;">更新说明：</th>
                        <td>
                            <textarea name="Pad_version_description" cols="80" rows="5"><?=$setting['Pad_version_description']?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th> 下载地址：</th>
                        <td><input type="text" name="Pad_version_url" value="<?=$setting['Pad_version_url']?>" size="80" /></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class="bk_8"></div>
                            <input type="submit" value="保存" class="button_style_2"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="apps/mobile/js/lib/tab.js"></script>
<script type="text/javascript">
$(function() {
    $('#tabs').tab();
    $('form').ajaxForm(function(json) {
        if (json && json.state) {
            ct.tips('保存成功');
        } else {
            ct.error(json && json.error || '保存失败');
        }
    });
});
</script>