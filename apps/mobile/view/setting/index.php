<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/base.css" />

<link rel="stylesheet" href="<?=IMG_URL?>js/lib/nlist/style.css" />
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.nlist.js"></script>

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="apps/mobile/js/lib/uploader.js"></script>

<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>

<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<script type="text/javascript">
    var weiboEnabled = <?php echo $setting['weibo']['enabled'] ? 'true' : 'false'?>;
</script>

<div class="bk_8"></div>
<form id="setting_index" action="?app=mobile&controller=setting&action=index" method="POST" class="validator">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption style="margin-top:0;margin-bottom:0;">基本设置</caption>
        <tr>
            <th width="160">默认城市：</th>
            <td style="line-height: 24px;"><input type="text" id="weatherid" name="config[weatherid]" value="<?=$setting['weatherid']?>" /></td>
        </tr>
        <tr>
            <th>城市版本：</th>
            <td><input type="text" name="config[weather_version]" value="<?=$setting['weather_version']?>" /></td>
        </tr>
        <?php foreach((array)app_config('mobile', 'background.weather') as $item):?>
        <tr>
            <th>天气背景图(<?php echo $item;?>)：</th>
            <td><?php echo element::image("config[weather_background][$item]", $setting['weather_background'][$item], 20, 1);?></td>
        </tr>
        <?php endforeach;?>
    </table>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>功能设置</caption>
        <tr>
            <th width="160">内容标题最大长度：</th>
            <td><input type="text" name="config[content_title_length]" size="5" value="<?=$setting['content_title_length']?>" /> 字</td>
        </tr>
        <tr>
            <th>内容摘要最大长度：</th>
            <td><input type="text" name="config[content_description_length]" size="5" value="<?=$setting['content_description_length']?>" /> 字</td>
        </tr>
        <tr>
            <th>默认幻灯片数量：</th>
            <td>
                <input type="text" name="config[slider_default_num]" size="5" value="<?=$setting['slider_default_num']?>" />
            </td>
        </tr>
        <tr>
            <th>开启评论功能：</th>
            <td>
                <label><input type="radio" name="config[comment][open]" value="1"<?=form_element::checked($setting['comment']['open'])?> /> 开启</label>
                <label><input type="radio" name="config[comment][open]" value="0"<?=form_element::checked(!$setting['comment']['open'])?> /> 关闭</label>
            </td>
        </tr>
        <tr>
            <th>匿名评论：</th>
            <td>
                <label><input type="radio" name="config[comment][islogin]" value="0"<?=form_element::checked(!$setting['comment']['islogin'])?> /> 开启</label>
                <label><input type="radio" name="config[comment][islogin]" value="1"<?=form_element::checked($setting['comment']['islogin'])?> /> 关闭</label>
            </td>
        </tr>
        <tr>
            <th>匿名报料：</th>
            <td>
                <label><input type="radio" name="config[baoliao][islogin]" value="0"<?=form_element::checked(!$setting['baoliao']['islogin'])?> /> 开启</label>
                <label><input type="radio" name="config[baoliao][islogin]" value="1"<?=form_element::checked($setting['baoliao']['islogin'])?> /> 关闭</label>
            </td>
        </tr>
        <tr>
            <th>报料最大上传图片大小：</th>
            <td><input type="text" name="config[baoliao][max_picsize]" value="<?=$setting['baoliao']['max_picsize']?>" size="5" /> MB</td>
        </tr>
        <tr>
            <th>报料最大上传视频大小：</th>
            <td><input type="text" name="config[baoliao][max_videosize]" value="<?=$setting['baoliao']['max_videosize']?>" size="5" /> MB</td>
        </tr>
        <tr>
            <th>启动页时长：</th>
            <td><input type="text" name="config[screen_duration]" value="<?=$setting['screen_duration']?>" size="5" /> 秒</td>
        </tr>
        <?php loader::import('lib.weiboEngine', app_dir('weibo')); ?>
        <?php $weibo_platforms = weiboEngine::platforms(); if (!empty($weibo_platforms)) : ?>
        <tr>
            <th>开启官方微博：</th>
            <td id="weibo-check">
                <label><input type="radio" name="config[weibo][enabled]" value="1"<?=form_element::checked($setting['weibo']['enabled'])?> /> 开启</label>
                <label><input type="radio" name="config[weibo][enabled]" value="0"<?=form_element::checked(!$setting['weibo']['enabled'])?> /> 关闭</label>
                <span class="ui-shortips-yellow" style="float:none;margin-left:5px;">
                    开启该功能需要配置微博API信息，<a href="javascript:void(0);" onclick="ct.assoc.open('?app=system&controller=setting&action=api', 'newtab')">点击此处设置</a>
                </span>
            </td>
        </tr>
        <tr id="weibo-container"<?php if (!$setting['weibo']['enabled']): ?> style="display:none;"<?php endif; ?>>
            <th>官方微博绑定：</th>
            <td>
                <div id="weibo-platforms"<?php if (!empty($setting['weibo']['auth'])): ?> style="display:none;"<?php endif; ?>>
                <?php foreach ($weibo_platforms as $weibo_platform): ?>
                    <a data-platform="<?=$weibo_platform['alias']?>" class="weibo-platform" style="background-image:url(<?=$weibo_platform['button']?>);">绑定<?=$weibo_platform['name']?></a>
                <?php endforeach; ?>
                </div>
                <div id="weibo-auth"<?php if (empty($setting['weibo']['auth'])): ?> style="display:none;"<?php endif; ?>></div>
                <script type="text/template" id="tpl-weibo-auth">
                    已绑定{platform_name} <a href="{profile_url}" target="_blank">@{nickname}</a>（<a href="javascript:void(0);" id="btn-weibo-revoke">取消绑定</a>）&nbsp;&nbsp;
                    授权过期时间：<span class="c_red">{expiration}</span>（<a data-platform="{platform}" href="javascript:void(0);" id="btn-weibo-auth">重新授权</a>）
                </script>
            </td>
        </tr>
        <?php else: ?>
        <input type="hidden" name="config[weibo][enabled]" value="0" />
        <?php endif; ?>
    </table>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>优化设置</caption>
        <tr>
            <th width="160">列表缓存时长：</th>
            <td><input type="text" name="config[cache_list]" size="5" value="<?=$setting['cache_list']?>" /> 秒</td>
        </tr>
        <tr>
            <th width="160">内容缓存时长：</th>
            <td><input type="text" name="config[cache_content]" size="5" value="<?=$setting['cache_content']?>" /> 秒</td>
        </tr>
    </table>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <tr>
            <th width="160">&nbsp;</th>
            <td>
                <div class="bk_8"></div>
                <input type="submit" id="submit" value="保存" class="button_style_2"/>
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript" src="apps/mobile/js/setting.js"></script>