<style type="text/css">
.weibo-content-empty td {
    line-height: 200px;
    text-align: center;
}
.weibo-content-action td {
    padding: 10px 10px 0 10px;
}
.weibo-content-action td .button_style_4 {
    margin: 0;
}
.weibo-content-area td {
    padding: 0;
}
.weibo-item-list {
    height: 200px;
    overflow-y: scroll;
    margin-top: 10px;
    padding: 10px;
    border: solid #CCC;
    border-width: 1px 0 1px;
}
.mod-weibo .weibo-btn-remove {
    position: absolute;
    right: 5px;
    top: 50%;
    margin-top: -8px;
    display: block;
    width: 16px;
    height: 16px;
    cursor: pointer;
}
</style>
<form action="">
    <div class="mod-tabs">
        <div class="tabs-trigger" data-tabs="triggers">
            <ul>
                <li class="tabs-trigger-item" data-tabs="trigger-item"><?php if ($edit): ?>编辑<?php else: ?>选择<?php endif; ?></li>
            </ul>
        </div>
        <div class="tabs-content" data-tabs="contents">
            <div class="tabs-content-item" data-tabs="content-item" id="item-placeholder" style="padding:0;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr class="weibo-content-action">
                        <td>
                            <button class="button_style_4" data-role="pick">选取微博</button>
                        </td>
                    </tr>
                    <tr class="weibo-content-area">
                        <td>
                            <div class="weibo-item-list">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="weibo-content">
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
<script type="text/template" id="tpl-weibo-empty">
    <tr class="weibo-content-empty">
        <td>
            暂无微博
        </td>
    </tr>
</script>
<script type="text/template" id="tpl-weibo-item">
    <tr>
        <td>
            <div class="mod-weibo">
                <input type="hidden" name="index" value="{%index%}" />
                <input type="hidden" name="platform" value="{%platform%}" />
                <input type="hidden" name="profile" value="{%profile%}" />
                <input type="hidden" name="avatar" value="{%avatar%}" />
                <input type="hidden" name="username" value="{%username%}" />
                <input type="hidden" name="vtype" value="{%vtype%}" />
                <textarea name="text" style="display:none;">{%text%}</textarea>
                <textarea name="image" style="display:none;">{%JSON.stringify(image)%}</textarea>
                <input type="hidden" name="url" value="{%url%}" />
                <input type="hidden" name="time" value="{%time%}" />
                <span title="删除" class="weibo-btn-remove" data-role="remove"><img src="<?=ADMIN_URL?>images/del.gif" alt=""></span>
                <div class="weibo-avatar">
                    <a class="weibo-icon weibo-icon-{%platform%}" href="{%profile%}" target="_blank"></a>
                    <a class="weibo-head" href="{%profile%}" target="_blank">
                        <img src="{%avatar%}" />
                    </a>
                </div>
                <div class="weibo-message">
                        <span class="weibo-user">
                            <a href="{%profile%}" target="_blank">{%username%}</a>
                            {%if vtype%}<a href="{%profile%}" target="_blank" class="weibo-icon weibo-icon-{%vtype%}"></a>{%endif%}
                            :
                        </span>
                    <span class="weibo-content">{%text%}</span>
                    {%if image%}
                    <div class="weibo-media">
                        <div class="weibo-image">
                            <a href="{%image[1]%}" target="_blank"><img src="{%image[0]%}" alt=""></a>
                        </div>
                    </div>
                    {%endif%}
                    <span class="weibo-info">
                        <a class="weibo-time"{%if url%} href="{%url%}" target="_blank"{%else%} href="javascript:void(0);"{%endif%}>{%time%}</a>
                    </span>
                </div>
            </div>
        </td>
    </tr>
</script>