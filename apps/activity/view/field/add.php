<div class="bk_8"></div>
<style type="text/css">
    .table_form th, .table_form td {
        line-height: 24px;
    }
</style>
<form method="POST" action="?app=activity&controller=field&action=add">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="80"><span class="c_red">*</span> 字段名称：</th>
            <td><input type="text" name="label" value="" size="30" /></td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 字段标识：</th>
            <td>
                <input type="text" name="fieldid" value="" size="20" />
                &nbsp;&nbsp;<span class="c_gray">以字母开头，只能包含字母、数字和下划线</span>
            </td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 字段类型：</th>
            <td>
                <?php if ($types): ?>
                <select name="type" style="width: 140px;">
                    <?php foreach ($types as $type_name => $type_label): ?>
                    <option value="<?=$type_name?>"><?=$type_label?></option>
                    <?php endforeach; ?>
                </select>
                <?php else: ?>
                暂无可用的字段类型
                <?php endif; ?>
            </td>
        </tr>
        <tr data-role="placeholder" style="display:none;">
            <td>
                <input type="hidden" name="options" />
            </td>
            <td data-role="options" class="field-type-options"></td>
        </tr>
        <tr>
            <th>是否启用：</th>
            <td>
                <label>
                    <input type="radio" name="disabled" value="0" checked="checked" /> 启用
                </label>
                &nbsp;&nbsp;
                <label>
                    <input type="radio" name="disabled" value="1" /> 禁用
                </label>
            </td>
        </tr>
        <tr>
            <th>默认勾选：</th>
            <td>
                <label>
                    <input type="radio" name="default" value="1" /> 是
                </label>
                &nbsp;&nbsp;
                <label>
                    <input type="radio" name="default" value="0" checked="checked" /> 否
                </label>
            </td>
        </tr>
    </table>
</form>
<div class="bk_5"></div>