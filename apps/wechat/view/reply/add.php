<div class="bk_8"></div>
<form id="rule-edit-form" action="?app=wechat&controller=reply&action=add" method="post">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="80">规则名：</th>
            <td><input type="text" class="bdr inputtit_focus" name="name" size="80" /></td>
        </tr>
        <tr>
            <th><?php echo element::tips('输入词组后以回车确认增加，关键词最多5个');?>关键词：</th>
            <td><input id="tags" class="vali_pass" type="text" size="60" name="tags" autocomplete="0" /></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th></th>
            <td>
                <div class="wechat-route-button-panel">
                    <div class="wechat-route-post-button">
                        <a id="reply-text" class="wechat-route-button-text" href="javascript:;" title="文字"></a>
                        <?php if (value($_GET, 'type') === 'service'):?>
                        <a id="reply-picture" class="wechat-route-button-picture" href="javascript:;" title="图片"></a>
                        <a id="reply-voice" class="wechat-route-button-voice" href="javascript:;" title="语音"></a>
                        <a id="reply-video" class="wechat-route-button-video" href="javascript:;" title="视频"></a>
                        <?php endif;?>
                        <a id="reply-list" class="wechat-route-button-list" href="javascript:;" title="图文"></a>
                    </div>
                    <div class="wechat-route-post-reply-outter">
                        <div id="route-post-content" class="wechat-route-post-reply"></div>
                    </div>
                </div>
                <input type="hidden" name="content" />
            </td>
        </tr>
    </table>
</form>