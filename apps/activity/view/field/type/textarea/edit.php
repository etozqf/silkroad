<tr<?=activityField::genValidateAttributes($field)?>>
    <th> <?php if($field['need']) {?><span class="c_red">*</span><?php } ?>&nbsp;&nbsp;<?=$field['label']?>：</th>
    <td><textarea name="<?=$field['fieldid']?>" id="<?=$field['fieldid']?>"  cols="37" rows="6"><?=$data?></textarea></td>
</tr>