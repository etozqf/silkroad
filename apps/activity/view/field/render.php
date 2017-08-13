<table  border="0" cellspacing="0" cellpediting="0" class="table_form mar_l_8" id="inputss">
    <tr>
        <th width="80" class="t_r"><b>项目</b></th>
        <td width="10"></td>
        <th width="30"><b>启用</b></th>
        <th width="30"><b>必填</b></th>
        <th width="55"><b>前端显示</b></th>
    </tr>
    <?php if ($all_fields): foreach ($all_fields as $field): ?>
        <tr>
            <th><?=$field['label']?></th>
            <td></td>
            <?php if ($field['system']): ?>
                <td>
                    <input type="hidden" name="fields[<?=$field['fieldid']?>][have]" value="1" />
                    <input type="checkbox" checked disabled />
                </td>
                <td>
                    <input type="hidden" name="fields[<?=$field['fieldid']?>][need]" value="1" />
                    <input type="checkbox" checked disabled />
                </td>
            <?php else: ?>
                <td><input type="checkbox" name="fields[<?=$field['fieldid']?>][have]" <?php if($fields[$field['fieldid']]['have']) echo 'checked ="checked"' ; ?> value="1" /></td>
                <td><input type="checkbox" name="fields[<?=$field['fieldid']?>][need]" <?php if($fields[$field['fieldid']]['need']) echo 'checked ="checked"' ; ?> value="1" /></td>
            <?php endif; ?>
            <?php if ($field['fieldid'] == 'name'):?>
                <td>
                    <input type="hidden" name="fields[<?=$field['fieldid']?>][display]" value="1" />
                    <input type="checkbox" checked disabled />
                </td>
            <?php else:?>
                <td><input type="checkbox" name="fields[<?=$field['fieldid']?>][display]" <?php if($fields[$field['fieldid']]['display']) echo 'checked ="checked"' ; ?> value="1" /></td>
            <?php endif;?>
        </tr>
    <?php endforeach; endif; ?>
</table>