<tr<?=activityField::genValidateAttributes($field)?>>
    <th width="80"><?php if($field['need']) {?><span class="c_red">*</span><?php } ?>&nbsp;&nbsp;<?=$field['label']?>：</th>
    <td>
        <?php foreach ($field['options']['option'] as $index => $option): ?>
        <label><input name="<?=$field['fieldid']?>" type="radio" value="<?php if (is_array($option)): ?><?=$option[1]?><?php else: ?><?=$option?><?php endif; ?>"<?php if ($data == is_array($option) ? $option[1] : $option): ?> checked<?php endif; ?> /> <?php if (is_array($option)): ?><?=$option[0]?><?php else: ?><?=$option?><?php endif; ?></label>&nbsp;&nbsp;
        <?php endforeach; ?>
    </td>
</tr>