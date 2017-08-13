<style type="text/css">
    .change_type label {
        margin-right: 5px !important;
    }
</style>
<div style="margin:10px 5px 0;">请选择 <b class="c_red"><?=$section['name']?></b> 转换类型：</div>
<div style="margin:15px auto 10px;" class="change_type">
    <form method="POST" action="?app=page&controller=section&action=change_type">
        <table width="98%" class="mar_l_8" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td>
                        <input type="hidden" name="sectionid" value="<?=$sectionid?>" />
                        <?php foreach (app_config('page', 'type') as $type_alias => $type_name): ?>
                        <label class="<?=$type_alias?>"><input<?php if ($section['type'] == $type_alias): ?> checked="checked"<?php endif; ?> type="radio" id="type_<?=$type_alias?>" name="type" class="radio_style" value="<?=$type_alias?>" /><span><?=$type_name?></span></label>
                        <?php endforeach; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>