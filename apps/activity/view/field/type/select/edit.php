<tr<?=activityField::genValidateAttributes($field)?>>
    <th width="80"><?php if($field['need']): ?><span class="c_red">*</span><?php endif; ?>&nbsp;&nbsp;<?=$field['label']?>：</th>
    <td>
        <?php if ($field['options']['limit'] > 1): ?>
        <?php $values = explode(',', $data); ?>
        <select name="<?=$field['fieldid']?>[]" style="width:60%;height:80px;" multiple="multiple" size="<?=intval($field['options']['limit'])?>">
            <option value="">请选择</option>
            <?php foreach ($field['options']['option'] as $index => $option): ?>
            <?php if (is_array($option)): ?>
            <option<?php if(in_array($option[1], $values)): ?> selected<?php endif; ?> value="<?=$option[1]?>"><?=$option[0]?></option>
            <?php else: ?>
            <option<?php if(in_array($option, $values)): ?> selected<?php endif; ?> value="<?=$option?>"><?=$option?></option>
            <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <?php else: ?>
        <select name="<?=$field['fieldid']?>" style="width:60%;">
            <option value="">请选择</option>
            <?php foreach ($field['options']['option'] as $index => $option): ?>
            <?php if (is_array($option)): ?>
            <option<?php if($data == $option[1]): ?> selected<?php endif; ?> value="<?=$option[1]?>"><?=$option[0]?></option>
            <?php else: ?>
            <option<?php if($data == $option): ?> selected<?php endif; ?> value="<?=$option?>"><?=$option?></option>
            <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>
    </td>
</tr>