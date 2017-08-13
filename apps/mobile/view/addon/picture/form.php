<form action="">
    <input type="hidden" name="tabs" value="0" />
    <div class="mod-tabs">
        <div class="tabs-trigger" data-tabs="triggers">
            <ul>
                <li class="tabs-trigger-item" data-tabs="trigger-item"><?php if ($edit): ?>编辑<?php else: ?>选择<?php endif; ?></li>
            </ul>
        </div>
        <div class="tabs-content" data-tabs="contents">
            <div class="tabs-content-item" data-tabs="content-item">
                <div id="item-placeholder">
                    <div class="mod-content-empty">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <button class="button_style_4" data-role="pick">选择组图</button>
                                    <button class="button_style_4" data-role="add">发布组图</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/template" id="item-template">
    <div class="mod-content">
        <input type="hidden" name="contentid" value="{%contentid%}" />
        <input type="hidden" name="thumb" value="{%thumb%}" />
        <input type="hidden" name="url" value="{%url%}" />
        <input type="hidden" name="title" value="{%title%}" />
        <input type="hidden" name="date" value="{%date%}" />
        <input type="hidden" name="catid" value="{%catid%}" />
        <textarea name="catid_html" style="display:none;">{%catid_html%}</textarea>
        <div class="content-thumb">
            <a data-role="url" href="{%url%}" target="_blank"><img src="{%thumb ? thumb : (IMG_URL + 'images/nopic.gif')%}" alt="" /></a>
        </div>
        <div class="content-box">
            <h4 title="{%title%}"><a data-role="url" href="{%url%}" target="_blank">{%title%}</a></h4>
            <p class="date">{%date%}</p>
            <div class="category">
                {%decodeURIComponent(catid_html)%}
            </div>
        </div>
        <div class="content-action">
            <button class="button_style_4" data-role="pick">重新选择</button>
        </div>
    </div>
</script>