<tr<?=activityField::genValidateAttributes($field)?>>
    <th width="80"><?php if($field['need']) {?><span class="c_red">*</span><?php } ?>&nbsp;&nbsp;<?=$field['label']?>：</th>
    <td><input type="text" name="<?=$field['fieldid']?>" id="<?=$field['fieldid']?>" value="<?=$data?>" size="40"/></td>
</tr>