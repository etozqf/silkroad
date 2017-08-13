<div class="bk_8"></div>
<style type="text/css">
    .table_form th, .table_form td {
        line-height: 24px;
    }
</style>
<form method="POST" action="?app=activity&controller=field&action=edit">
    <input type="hidden" name="fieldid" value="<?=$field['fieldid']?>" />
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="80"><span class="c_red">*</span> 字段名称：</th>
            <td>
                <?php if ($field['system']): ?>
                <?=$field['label']?>
                <?php else: ?>
                <input type="text" name="label" value="<?=$field['label']?>" size="30" />
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 字段标识：</th>
            <td>
                <?=$field['fieldid']?>
            </td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 字段类型：</th>
            <td>
                <?php if ($types): ?>
                <select name="type" style="width: 140px;">
                    <?php foreach ($types as $type_name => $type_label): ?>
                    <?php if ($field['system'] && $type_name != $field['type']) continue; ?>
                    <option value="<?=$type_name?>"<?php if ($field['type'] == $type_name): ?> selected="selected"<?php endif; ?>><?=$type_label?></option>
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
        <?php if (!$field['system']): ?>
        <tr>
            <th>是否启用：</th>
            <td>
                <label>
                    <input type="radio" name="disabled" value="0"<?php if (!$field['disabled']): ?> checked="checked"<?php endif; ?> /> 启用
                </label>
                &nbsp;&nbsp;
                <label>
                    <input type="radio" name="disabled" value="1"<?php if ($field['disabled']): ?> checked="checked"<?php endif; ?> /> 禁用
                </label>
            </td>
        </tr>
        <tr>
            <th>默认勾选：</th>
            <td>
                <label>
                    <input type="radio" name="default" value="1"<?php if ($field['default']): ?> checked="checked"<?php endif; ?> /> 是
                </label>
                &nbsp;&nbsp;
                <label>
                    <input type="radio" name="default" value="0"<?php if (!$field['default']): ?> checked="checked"<?php endif; ?> /> 否
                </label>
            </td>
        </tr>
        <?php endif; ?>
    </table>
</form>
<div class="bk_5"></div>