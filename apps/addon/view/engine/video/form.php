<form action="">
    <input type="hidden" name="tabs" value="0" />
    <div class="mod-tabs">
        <div class="tabs-trigger" data-tabs="triggers">
            <ul>
                <li class="tabs-trigger-item" data-tabs="trigger-item">添加</li>
                <li class="tabs-trigger-item" data-tabs="trigger-item">选择</li>
            </ul>
        </div>
        <div class="tabs-content" data-tabs="contents">
            <div class="tabs-content-item" data-tabs="content-item">
                <div class="mod-content-form">
                    <table class="table_form" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>地址：</th>
                            <td>
                                <input type="text" name="video" value="" size="40" style="width: 220px;" /> &nbsp;
                                <button class="button_style_4" data-role="pick-file" data-type="<?=setting('video', 'openserver') ? 'vms' : 'file'?>">选择/上传</button>
                                <div id="thirdparty" style="padding: 5px 0;"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="tabs-content-item" data-tabs="content-item" id="item-placeholder">
                <div class="mod-content-empty">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <button class="button_style_4" data-role="pick-content">选择视频</button>
                                <button class="button_style_4" data-role="add">发布视频</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="addon-param">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <label>视频宽度：<input type="text" name="width" value="630" size="5" /></label>
                </td>
                <td>
                    <label>视频高度：<input type="text" name="height" value="420" size="5" /></label>
                </td>
            </tr>
        </table>
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
            <button class="button_style_4" data-role="pick-content">重新选择</button>
        </div>
    </div>
</script>