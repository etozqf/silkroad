<tr>
    <th width="80"><?php if($field['need']) {?><span class="c_red">*</span><?php } ?>&nbsp;&nbsp;<?=$field['label']?>：</th>
    <td>
        <input id="file" class="" type="text" <?php if (!is_null($data)) {?>value="<?=table('attachment', $data, 'filepath')?><?=table('attachment', $data, 'filename')?>"<?php } else {?>value=""<?php }?> size="30" name="file">
        <input id="fileaid" class="" type="hidden" value="<?=$data?>" size="30" name="<?=$field['fieldid']?>">
        <span id="fileUp" style="height:23px;"></span>
</tr>