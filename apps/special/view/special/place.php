<table width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td width="45">
            <input type="button" class="button_style_1" id="place-select" value="选择" />
        </td>
        <td>
            <script type="text/template" id="tpl-place">
                <div class="list-item">
                    <div title="{title} &gt; {pageName} &gt; {sectionName}" class="item-title">{title} &gt; {pageName} &gt; {sectionName}</div>
                    <a class="remove" href="javascript:void(0);" title="删除"><img src="images/del.gif" width="16" height="16" /></a>
                </div>
            </script>
            <div id="place-list" class="removeable-list"></div>
            <script src="apps/special/js/push.js" type="text/javascript"></script>
            <script type="text/javascript">
                $(function() {
                    pushToPlace(<?=$places ? $places : '[]'?>);
                });
            </script>
        </td>
    </tr>
    </tbody>
</table>