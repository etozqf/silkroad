<div class="bk_10"></div>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
    <?php foreach ($fields as $field): ?>
    <tr>
        <th width="65"><?=$field['label']?>：</th>
        <td><?=str_replace('{'.$field['fieldid'].'}', $data[$field['fieldid']], activityField::renderTemplate($field['type'], $field))?></td>
    </tr>
    <?php endforeach; ?>
</table>