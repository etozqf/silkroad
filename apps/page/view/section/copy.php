<div class="bk_5"></div>
<form method="POST" action="?app=page&controller=section&action=copy">
    <input type="hidden" name="sectionid" value="<?=$sectionid?>" />
    <table width="100%" class="mar_l_8" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <th width="80">区块名称：</th>
            <td>
                <input style="width: 245px;" type="text" name="name" value="<?=$section['name']?>(拷贝)" size="45" />
            </td>
        </tr>
        <tr>
            <th>拷贝数量：</th>
            <td>
                <input type="text" name="quantity" value="1" size="3" />
            </td>
        </tr>
        <tr>
            <th valign="top">选择页面：</th>
            <td>
                <table width="100%" id="pagetreetable" class="table_list treeTable mar_l_8" cellpadding="0" cellspacing="0">
                    <tbody></tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
    var template = '<tr id="m_{pageid}"><td><label><input type="radio" name="pageid" value="{pageid}"/>{name}</label></td></tr>';
    var t = new ct.treeTable('#pagetreetable',{
        'idField'		: 'pageid',
        'treeCellIndex'	: 0,
        'template'		: template,
        'rowIdPrefix'	: 'm_',
        'collapsed'		: 1,
        'parentField'	: 'parentid',
        'baseUrl'		: '?app=page&controller=page&action=tree&status=1',
        'rowReady'		: function(id,tr,json) {
        },
        'rowsPrepared'  : function(tbody) {
            tbody.find('#m_<?=$section['pageid']?>').click().find('input[name=pageid]').eq(0).click();
        }
    });
    t.load();
</script>
<div class="bk_5"></div>