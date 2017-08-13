<tr<?=activityField::genValidateAttributes($field)?>>
    <th width="80"><?php if($field['need']) {?><span class="c_red">*</span><?php } ?>&nbsp;&nbsp;<?=$field['label']?>：</th>
    <td>
        <?php $values = explode(',', $data); ?>
        <?php $limit = intval($field['options']['limit']); ?>
        <?php foreach ($field['options']['option'] as $index => $option): ?>
        <label><input name="<?=$field['fieldid']?><?php if ($limit > 1): ?>[]<?php endif; ?>" type="checkbox" value="<?php if (is_array($option)): ?><?=$option[1]?><?php else: ?><?=$option?><?php endif; ?>"<?php if (in_array(is_array($option) ? $option[1] : $option, $values)): ?> checked<?php endif; ?> /> <?php if (is_array($option)): ?><?=$option[0]?><?php else: ?><?=$option?><?php endif; ?></label>&nbsp;&nbsp;
        <?php endforeach; ?>
    </td>
</tr>