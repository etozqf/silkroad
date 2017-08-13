<div class="bk_8"></div>
<form method="POST" action="?app=addon&controller=engine&action=add">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th><span class="c_red">*</span> 挂件名称：</th>
            <td><input type="text" name="name" value="" size="20" /></td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 挂件描述：</th>
            <td><input type="text" name="description" value="" size="30" /></td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 默认位置：</th>
            <td>
                <?php if ($places): ?>
                <select name="place" style="width: 140px;">
                    <?php foreach ($places as $place_alias => $place_name): ?>
                    <option value="<?=$place_alias?>"><?=$place_name?></option>
                    <?php endforeach; ?>
                </select>
                <?php else: ?>
                暂无位置
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>启用状态：</th>
            <td>
                <label>
                    <input type="radio" name="disabled" value="0" checked="checked" /> 启用
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                    <input type="radio" name="disabled" value="1" /> 禁用
                </label>
            </td>
        </tr>
    </table>
</form>
<div class="bk_5"></div>