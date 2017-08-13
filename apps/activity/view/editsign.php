<div class="bk_8"></div>
<form method="POST" action="?app=<?=$app?>&controller=sign&action=edit">
    <input type="hidden" name="signid" value="<?=$signid?>"/>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
    <?php foreach ($fields as $field): ?>
        <?=activityField::edit($field['type'], $field, $data[$field['fieldid']])?>
        <?php if($field['type'] == 'file'){ $aidValue = $field['fieldid']; ?>
            <input type="hidden" name="<?=$field['fieldid']?>" id="<?=$field['fieldid']?>" value="<?=$data[$aidValue]?>" size="40"/>
        <?php }?>
    <?php endforeach; ?>
    </table>
</form>