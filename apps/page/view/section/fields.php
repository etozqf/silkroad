<table cellpadding="0" cellspacing="0" width="100%" class="section-fields">
    <tr class="fields-row">
        <td width="150">
            <input type="hidden" name="system_fields[contentid][checked]" value="1" />
            <input type="hidden" name="system_fields[contentid][func]" value="intval" />
            <label><input type="checkbox" disabled="disabled" checked="checked" /> 标题(title)</label>
            <input type="hidden" name="system_fields[title][checked]" value="1" />
            <input type="hidden" name="system_fields[title][required]" value="1" />
            <input type="hidden" name="system_fields[url][checked]" value="1" />
            <input type="hidden" name="system_fields[color][checked]" value="1" />
        </td>
        <td class="fields-rule">
            字符长度：
            <input type="text" name="system_fields[title][min_length]" value="<?=form_element::intval($system_fields['title']['min_length'])?>" size="3" /> ~
            <input type="text" name="system_fields[title][max_length]" value="<?=form_element::intval($system_fields['title']['max_length'])?>" size="3" />
        </td>
    </tr>
    <tr class="fields-row">
        <td>
            <label><input type="checkbox" name="system_fields[icon][checked]" value="1"<?=form_element::checked($system_fields['icon']['checked'])?>/> 图标(icon)</label>
            <input type="hidden" name="system_fields[iconsrc][checked]" value="1" />
        </td>
        <td class="fields-rule">
            请将图标文件放在 ./public/img/icon/ 目录下
        </td>
    </tr>
    <tr class="fields-row">
        <td>
            <label><input type="checkbox" name="system_fields[subtitle][checked]" value="1"<?=form_element::checked($system_fields['subtitle']['checked'])?>/> 副题(subtitle)</label>
        </td>
        <td class="fields-rule">
            字符长度：
            <input type="text" name="system_fields[subtitle][min_length]" value="<?=form_element::intval($system_fields['subtitle']['min_length'])?>" size="3" /> ~
            <input type="text" name="system_fields[subtitle][max_length]" value="<?=form_element::intval($system_fields['subtitle']['max_length'])?>" size="3" />
        </td>
    </tr>
    <tr class="fields-row">
        <td colspan="2">
            <label><input type="checkbox" name="system_fields[suburl][checked]" value="1"<?=form_element::checked($system_fields['suburl']['checked'])?>/> 副题链接(suburl)</label>
        </td>
    </tr>
    <tr class="fields-row">
        <td>
            <label><input type="checkbox" name="system_fields[thumb][checked]" value="1"<?=form_element::checked($system_fields['thumb']['checked'])?>/> 缩略图(thumb)</label>
        </td>
        <td class="fields-rule">
            <span class="f_l">
                <label><input type="checkbox" name="system_fields[thumb][required]" value="1"<?=form_element::checked($system_fields['thumb']['required'])?>/> 必选</label>
            </span>
            <span class="f_l" style="margin-left:5px;">
                宽 <input type="text" name="system_fields[thumb][width]" value="<?=form_element::intval($system_fields['thumb']['width'])?>" size="3" /> px
            高 <input type="text" name="system_fields[thumb][height]" value="<?=form_element::intval($system_fields['thumb']['height'])?>" size="3" /> px
            </span>
            <span class="f_l" style="margin-left:5px;">
                <label title="勾选限制后，编辑上传的图片必须符合指定的规格"><input type="checkbox" name="system_fields[thumb][limit]" value="1"<?=form_element::checked($system_fields['thumb']['limit'])?>/> 限制</label>
            </span>
        </td>
    </tr>
    <tr class="fields-row">
        <td>
            <label><input type="checkbox" name="system_fields[description][checked]" value="1"<?=form_element::checked($system_fields['description']['checked'])?>/> 描述(description)</label>
        </td>
        <td class="fields-rule">
            字符长度：
            <input type="text" name="system_fields[description][min_length]" value="<?=form_element::intval($system_fields['description']['min_length'])?>" size="3" /> ~
            <input type="text" name="system_fields[description][max_length]" value="<?=form_element::intval($system_fields['description']['max_length'])?>" size="3" />
        </td>
    </tr>
    <tr class="fields-row">
        <td colspan="2">
            <label><input type="checkbox" name="system_fields[time][checked]" value="1"<?=form_element::checked($system_fields['time']['checked'])?>/> 发布时间(time)</label>
            <input type="hidden" name="system_fields[time][func]" value="section_fields_strtotime" />
        </td>
    </tr>
    <?php if (isset($custom_fields) && isset($custom_fields['text'])) foreach (array_filter($custom_fields['text']) as $index => $text): ?>
    <?php if (empty($custom_fields['name'][$index])) continue; ?>
    <tr class="fields-row fields-row-custom">
        <td colspan="2">
            <input style="width:122px;" class="fields-text" type="text" name="custom_fields[text][<?=$index?>]" value="<?=$text?>" size="20" placeholder="字段中文说明" />
            <input style="width:175px;" class="fields-text" type="text" name="custom_fields[name][<?=$index?>]" value="<?=$custom_fields['name'][$index]?>" size="30" placeholder="字段英文标识" />
            <a class="fields-delete" href="javascript:void(0);" title="删除"></a>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr class="fields-row fields-row-custom">
        <td colspan="2">
            <input style="width:122px;" class="fields-text" type="text" name="custom_fields[text][]" value="" size="20" placeholder="字段中文说明" />
            <input style="width:175px;" class="fields-text" type="text" name="custom_fields[name][]" value="" size="30" placeholder="字段英文标识" />
            <a class="fields-delete" href="javascript:void(0);" title="删除"></a>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <a class="fields-add" href="javascript:void(0);" title="增加字段">增加字段</a>
        </td>
    </tr>
</table>