<table width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td width="45">
            <input type="button" class="button_style_1" id="section-select" value="选择" />
        </td>
        <td>
            <textarea style="display:none;" id="tpl-section">
                <?=script_template('<div class="list-item">
                    <div title="{pageName} &gt; {name}" class="item-title">{pageName} &gt; {name}</div>
                    <a class="remove" href="javascript:void(0);" title="删除"><img src="images/del.gif" width="16" height="16" /></a>
                </div>')?>
            </textarea>
            <div id="section-list" class="removeable-list"></div>
            <script src="apps/page/js/section.js" type="text/javascript"></script>
            <script type="text/javascript">
                $(function() {
                    pushToSection(<?=$sections ? $sections : '[]'?>);
                });
            </script>
        </td>
    </tr>
    </tbody>
</table>