<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_form">
    <tbody>
        <tr>
            <th width="80"><span class="c_red">*</span> 标题：</th>
            <td>
                <input type="hidden" name="contentid" value="<?=$data['contentid']?>" />
                <span class="icon-title">
                    <?php if ($system_fields['icon']['checked']): ?>
                    <?=element::icon('icon', $data['icon']);?>
                    <input type="hidden" name="iconsrc" value="<?=$data['iconsrc']?>" />
                    <input type="text" name="title" class="inputtit_focus" size="42" style="width:262px;padding-left:20px;color:<?=$data['color']?>;" value="<?=htmlspecialchars($data['title'])?>"/>
                    <?php else: ?>
                    <input type="text" name="title" class="inputtit_focus" size="42" style="width:280px;color:<?=$data['color']?>;" value="<?=htmlspecialchars($data['title'])?>"/>
                    <?php endif; ?>
                </span>
                <img src="images/color.gif" alt="色板" height="16" width="16" style="vertical-align: middle;cursor:pointer;background-color:<?=$data['color']?>;"/>
                <input type="hidden" name="color" value="<?=$data['color']?>" />
                <?php if ($system_fields['subtitle']['checked']): ?>
                <label><input type="checkbox" class="checkbox_style" onclick="$(this).parents('tbody').next()[this.checked ? 'show' : 'hide']()"/>&nbsp;&nbsp;副题</label>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>链接：</th>
            <td><input type="text" name="url" size="50" value="<?=$data['url']?>"/></td>
        </tr>
    </tbody>
    <?php if ($system_fields['subtitle']['checked']): ?>
    <tbody style="display:<?=$data['subtitle'] ? '' : 'none'?>;">
        <tr>
            <th>副题：</th>
            <td>
                <input type="text" name="subtitle" class="inputtit_focus" size="50" value="<?=htmlspecialchars($data['subtitle'])?>"/>
            </td>
        </tr>
        <?php if ($system_fields['suburl']['checked']): ?>
        <tr>
            <th>副题链接：</th>
            <td><input type="text" name="suburl" size="50" value="<?=$data['suburl']?>"/></td>
        </tr>
        <?php endif; ?>
    </tbody>
    <?php endif; ?>
    <tbody>
        <?php if ($system_fields['thumb']['checked']): ?>
        <tr>
            <th>缩略图：</th>
            <td>
                <input type="text" name="thumb" size="30" value="<?=$data['thumb']?>"/>
            </td>
        </tr>
        <?php endif; ?>
        <?php if ($system_fields['description']['checked']): ?>
        <tr>
            <th>摘要：</th>
            <td><textarea style="width:333px;height:70px;resize:vertical;" rows="5" cols="64" name="description"><?=strip_tags(html_entity_decode($data['description']))?></textarea>
            </td>
        </tr>
        <?php endif; ?>
        <?php if ($system_fields['time']['checked']): ?>
        <tr>
            <th>时间：</th>
            <td>
                <input type="text" name="time" class="input_calendar" value="<?=date('Y-m-d H:i:s', $data['time'] ? $data['time'] : TIME)?>" onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});"/>
            </td>
        </tr>
        <?php endif; ?>
        <?php if (isset($custom_fields) && isset($custom_fields['text'])) foreach (array_filter($custom_fields['text']) as $index => $text): ?>
        <?php if (empty($custom_fields['name'][$index])) continue; ?>
        <tr>
            <th><?=$text?>：</th>
            <td>
                <input type="text" name="<?=$custom_fields['name'][$index]?>" size="50" value="<?=$data[$custom_fields['name'][$index]]?>"/>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<textarea data-role="validator-rules" style="display: none;"><?=$rules?></textarea>