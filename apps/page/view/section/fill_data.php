<style type="text/css">
    #fill-data th,
    #fill-data td {
        line-height: 22px;
    }
</style>
<form method="POST" action="?app=page&controller=section&action=fill_data" id="fill-data">
    <input type="hidden" name="sectionid" value="<?=$sectionid?>" />
    <div class="bk_8"></div>
    <table width="95%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tbody>
            <tr>
                <th width="80">默认条数：</th>
                <td>
                    <input type="text" name="rows" value="<?=$rows ? $rows : ''?>" size="5" title="手动区块会自动使用区块设置中的条数" />
                </td>
            </tr>
            <tr>
                <th>缩略图：</th>
                <td>
                    <label><input type="radio" name="thumb" value="" checked="checked" /> 不限</label>
                    <label><input type="radio" name="thumb" value="1" /> 有图</label>
                </td>
            </tr>
            <tr>
                <th>摘要：</th>
                <td>
                    <label><input type="checkbox" name="description" value="1" /> 生成</label>
                </td>
            </tr>
            <tr>
                <th>短标题：</th>
                <td>
                    <label><input type="checkbox" name="subtitle" value="1" /> 生成</label>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="bk_5"></div>
</form>