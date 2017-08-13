<div class="bk_8"></div>
<form method="POST" name="section_recommend_editLog" action="?app=page&controller=section_recommend&action=editLog">
    <input type="hidden" name="sectionid" value="<?=$sectionid?>"/>
    <input type="hidden" name="recommendid" value="<?=$recommendid?>"/>
    <?=section_fields_form(decodeData($section['fields']), $item['data'])?>
</form>