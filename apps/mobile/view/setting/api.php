<?php $this->display('header', 'system'); ?>

<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="bk_8"></div>
<form action="?app=mobile&controller=setting&action=api" method="POST" class="validator">
    <?php $auth_config = app_config('system', 'auth'); ?>
    <?php foreach (app_config('mobile', 'share') as $share): if ($share['hidden']) continue; ?>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption><?=$share['text']?></caption>
        <tr>
            <th width="160">是否启用：</th>
            <td>
                <label><input type="radio" name="config[api][<?=$share['alias']?>][open]" value="1"<?=form_element::checked($setting['api'][$share['alias']]['open'])?> /> 开启</label>
                <label><input type="radio" name="config[api][<?=$share['alias']?>][open]" value="0"<?=form_element::checked(!$setting['api'][$share['alias']]['open'])?> /> 关闭</label>
            </td>
        </tr>
        <tr>
            <th>App ID：</th>
            <td>
                <input type="text" name="config[api][<?=$share['alias']?>][appid]" size="40" value="<?=$setting['api'][$share['alias']]['appid']?>" />
                &nbsp;&nbsp;<a href="<?=$share['apply_url']?>" target="_blank">立即申请</a>
            </td>
        </tr>
        <tr>
            <th>App Key：</th>
            <td>
                <input type="text" name="config[api][<?=$share['alias']?>][appkey]" size="40" value="<?=$setting['api'][$share['alias']]['appkey']?>" />
            </td>
        </tr>
        <tr>
            <th>App Secret：</th>
            <td><input type="text" name="config[api][<?=$share['alias']?>][appsecret]" size="40" value="<?=$setting['api'][$share['alias']]['appsecret']?>" /></td>
        </tr>
        <?php if ($share['alias'] == 'qqweibo'): ?>
        <tr>
            <th>应用网址：</th>
            <td><input type="text" readonly="readonly" value="<?=$auth_config['tencent_weibo']['authorize_callback_url']?>" size="40" title="只读，复制到应用申请处使用" /></td>
        </tr>
        <tr>
            <th></th>
            <td><em style="color:red">* 此处应用地址必须和腾讯应用申请的应用地址完全一致</em></td>
        </tr>
        <?php elseif ($share['alias'] == 'sinaweibo_ipad'): ?>
        <tr>
            <th>授权回调页：</th>
            <td><input type="text" readonly="readonly" value="<?=$auth_config['sina_weibo']['authorize_callback_url']?>" size="40" title="只读，复制到应用申请处使用" /></td>
        </tr>
        <tr>
            <th></th>
            <td><em style="color:red">* 此处授权回调页地址必须和新浪微博应用申请的授权回调页地址完全一致</em></td>
        </tr>
        <?php elseif ($share['alias'] == 'sinaweibo_pad'): ?>
        <tr>
            <th>授权回调页：</th>
            <td><input type="text" readonly="readonly" value="<?=$auth_config['sina_weibo']['authorize_callback_url']?>" size="40" title="只读，复制到应用申请处使用" /></td>
        </tr>
        <tr>
            <th></th>
            <td><em style="color:red">* 此处授权回调页地址必须和新浪微博应用申请的授权回调页地址完全一致</em></td>
        </tr>
        <?php elseif ($share['alias'] == 'sinaweibo_iphone'): ?>
        <tr>
            <th>授权回调页：</th>
            <td><input type="text" readonly="readonly" value="<?=$auth_config['sina_weibo']['authorize_callback_url']?>" size="40" title="只读，复制到应用申请处使用" /></td>
        </tr>
        <tr>
            <th></th>
            <td><em style="color:red">* 此处授权回调页地址必须和新浪微博应用申请的授权回调页地址完全一致</em></td>
        </tr>
        <?php elseif ($share['alias'] == 'sinaweibo_android'): ?>
        <tr>
            <th>授权回调页：</th>
            <td><input type="text" readonly="readonly" value="<?=$auth_config['sina_weibo']['authorize_callback_url']?>" size="40" title="只读，复制到应用申请处使用" /></td>
        </tr>
        <tr>
            <th></th>
            <td><em style="color:red">* 此处授权回调页地址必须和新浪微博应用申请的授权回调页地址完全一致</em></td>
        </tr>
        <?php else: ?>
        <tr>
            <th>Redirect Uri：</th>
            <td><input type="text" name="config[api][<?=$share['alias']?>][redirect_uri]" size="40" value="<?=$setting['api'][$share['alias']]['redirect_uri']?>" /></td>
        </tr>
        <?php endif; ?>
    </table>
    <?php endforeach; ?>

    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <tr>
            <th width="160">&nbsp;</th>
            <td>
                <div class="bk_8"></div>
                <input type="submit" value="保存" class="button_style_2"/>
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
    $(function() {
        $('form').ajaxForm(function(json) {
            if (json && json.state) {
                ct.tips('保存成功');
            } else {
                ct.error(json && json.error || '保存失败');
            }
        });
    });
</script>
