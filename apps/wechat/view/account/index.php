<div class="bk_10"></div>

<div class="suggest w_650 mar_l_8">
    <h2>友情提示</h2>
    <p>
        1. 确保公众号已启用开发模式
        <br />
        2. 最多只能添加两个公众号
    </p>
</div>

<div class="bk_10"></div>

<div class="tag_1">
    <ul id="account-type" class="tag_list">
        <li data-type="subscribe"><a href="javascript:;">订阅号</a></li>
        <li data-type="service"><a href="javascript:;">服务号</a></li>
    </ul>
</div>

<form id="account-add-form" action="?app=wechat&controller=account" method="post">
    <input type="hidden" name="account[type]" value="" />
    <table class="table_form mar_l_8" border="0" cellspacing=​"0" cellpadding=​"0">​
        <tr>
            <th width="70"><label for="account-name">用户名：</label></th>
            <td width="180"><input id="account-name" class="wechat-input-text" type="text" name="account[name]" value="" title="" required /></td>
        </tr>
        <tr>
            <th><label for="account-token">Token：</label></th>
            <td><input id="account-token" class="wechat-input-text" type="text" name="account[token]" value="" title="" required /></td>
        </tr>
        <tr data-role="service-only">
            <th><label for="account-appid">AppID：</label></th>
            <td><input id="account-appid" class="wechat-input-text" type="text" name="account[appid]" value="" title="" /></td>
        </tr>
        <tr data-role="service-only">
            <th><label for="account-secret">AppSecret：</label></th>
            <td><input id="account-secret" class="wechat-input-text" type="text" name="account[secret]" value="" title="" /></td>
        </tr>
        <tr>
            <th></th>
            <td><button class="button_style_2">添加</button></td>
        </tr>
    </table>
</form>

<div class="bk_10"></div>

<table id="account-list" class="table_list mar_l_8" cellspacing="0" cellpadding="0" border="0">
    <thead>
        <tr>
            <th width="180" class="bdr_3">微信名称</th>
            <th width="130">状态</th>
            <th width="200">操作</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<div id="account-success" class="ct_tips success success-msg" style="display:none;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td colspan="2" class="t_c"><strong>恭喜，公众号添加成功</strong></td>
            </tr>
            <tr>
                <th width="75">URL：</th>
                <td><a href="javascript:;"><?php echo API_URL;?>/wechat/<span data-id></span></a></td>
            </tr>
            <tr>
                <td colspan="2" class="t_c">
                    <button data-btn="edit" class="button_style_4">继续修改</button>
                    <button data-btn="close" class="button_style_4">关闭</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script id="account-list-template" type="text/template">
    <tr id="row_{id}" data-type="{type}">
        <td class="t_c">
            <a href="javascript:;" class="enter">{name}</a>
        </td>
        <td class="state t_c" data-state="{state}"></td>
        <td class="ctrl t_c">
            <a href="javascript:;" class="edit"><img src="images/edit.gif" /></a>
            <a href="javascript:;" class="delete"><img src="images/delete.gif" /></a>
        </td>
    </tr>
</script>