<form name="<?=uniqid('section-push-')?>" method="POST" action="?app=page&controller=section&action=recommendItem">
    <input type="hidden" name="sectionid" value="<?=$sectionid?>"/>
    <input type="hidden" name="contentid" value="<?=$contentid?>"/>
    <?=section_fields_form(decodeData($section['fields']), $data)?>
</form>