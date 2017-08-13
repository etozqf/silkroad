<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/base.css" />
<link rel="stylesheet" href="apps/mobile/css/ad.css" />

<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="apps/mobile/js/lib/uploader.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="bk_8"></div>

<?php $this->display('ad/menu');?>
<?php $image_config = app_config('mobile', 'ad.image');?>

<div class="ad-content">
    <h3>内容页广告</h3>
    <form id="form" name="adm_content" action="?app=mobile&controller=ad&action=content" method="POST" class="validator">
        <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mobile_adm">
            <caption>手机版</caption>
            <tbody>
                <tr>
                    <th width="60">广告类型：</th>
                    <td>
                        <span class="ui-label"><label onclick="$('.mobile_adm > tbody[data-role=link]').show().siblings('.mobile_adm > tbody[data-role]').hide();"><input<?=form_element::checked($data['ad']['mobile']['type'] == 'link' || !$data['ad']['mobile']['type'])?> type="radio" name="data[ad][mobile][type]" value="link" />内置</label></span>
                        <span class="ui-label"><label onclick="$('.mobile_adm > tbody[data-role=html]').show().siblings('.mobile_adm > tbody[data-role]').hide();"><input<?=form_element::checked($data['ad']['mobile']['type'] == 'html')?> type="radio" name="data[ad][mobile][type]" value="html" />第三方广告代码</label></span>
                    </td>
                </tr>
            </tbody>
            <tbody data-role="link"<?php if ($data['ad']['mobile']['type'] == 'html'): ?> style="display:none;"<?php endif; ?>>
                <tr>
                    <th>广告图片：</th>
                    <td>
                        <?php $attr = 'data-validator-tips="'.mobile_element::width_height('图片规格：', $image_config['mobile']['width'], $image_config['mobile']['height']).'" style="width:239px;"'; ?>
                        <?=element::image('data[ad][mobile][src]', $data['ad']['mobile']['src'], 40, 1, $attr)?>
                    </td>
                </tr>
                <tr>
                    <th>广告链接：</th>
                    <td><input type="text" size="62" name="data[ad][mobile][link]" value="<?=$data['ad']['mobile']['link']?>" style="width:400px;" /></td>
                </tr>
            </tbody>
            <tbody data-role="html"<?php if (!$data['ad']['mobile']['type'] || $data['ad']['mobile']['type'] == 'link'): ?> style="display:none;"<?php endif; ?>>
                <tr>
                    <th valign="top" style="padding-top:5px;">广告代码：</th>
                    <td>
                        <textarea name="data[ad][mobile][code]" cols="59" rows="10" style="width:400px;"><?=$data['ad']['mobile']['code']?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><span class="ui-shortips" title="仅对 android 客户端有效" style="float:none;"><span style="color:#df0000;">*</span> 因安卓客户端底层安全策略，第三方广告代码仅支持单个外部js链接</span></td>
                </tr>
            </tbody>
        </table>
        <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form pad_adm">
            <caption>pad版</caption>
            <tbody>
                <tr>
                    <th width="60">广告类型：</th>
                    <td>
                        <span class="ui-label"><label onclick="$('.pad_adm > tbody[data-role=link]').show().siblings('.pad_adm > tbody[data-role]').hide();"><input<?=form_element::checked($data['ad']['pad']['type'] == 'link' || !$data['ad']['pad']['type'])?> type="radio" name="data[ad][pad][type]" value="link" />内置</label></span>
                        <span class="ui-label"><label onclick="$('.pad_adm > tbody[data-role=html]').show().siblings('.pad_adm > tbody[data-role]').hide();"><input<?=form_element::checked($data['ad']['pad']['type'] == 'html')?> type="radio" name="data[ad][pad][type]" value="html" />第三方广告代码</label></span>
                    </td>
                </tr>
            </tbody>
            <tbody data-role="link"<?php if ($data['ad']['pad']['type'] == 'html'): ?> style="display:none;"<?php endif; ?>>
                <tr>
                    <th>广告图片：</th>
                    <td>
                        <?php $attr = 'data-validator-tips="'.mobile_element::width_height('图片规格：', $image_config['pad']['width'], $image_config['pad']['height']).'" style="width:239px;"'; ?>
                        <?=element::image('data[ad][pad][src]', $data['ad']['pad']['src'], 40, 1, $attr)?>
                    </td>
                </tr>
                <tr>
                    <th>广告链接：</th>
                    <td><input type="text" size="62" name="data[ad][pad][link]" value="<?=$data['ad']['pad']['link']?>" style="width:400px;" /></td>
                </tr>
            </tbody>
            <tbody data-role="html"<?php if (!$data['ad']['pad']['type'] || $data['ad']['pad']['type'] == 'link'): ?> style="display:none;"<?php endif; ?>>
                <tr>
                    <th valign="top" style="padding-top:5px;">广告代码：</th>
                    <td>
                        <textarea name="data[ad][pad][code]" cols="59" rows="10" style="width:400px;"><?=$data['ad']['pad']['code']?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><span class="ui-shortips" title="仅对 android 客户端有效" style="float:none;"><span style="color:#df0000;">*</span> 因安卓客户端底层安全策略，第三方广告代码仅支持单个外部js链接</span></td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <th>&nbsp;</th>
                    <td><input type="submit" id="submit" value="保存" class="button_style_2"/></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<script type="text/javascript" src="apps/mobile/js/adm/content.js"></script>