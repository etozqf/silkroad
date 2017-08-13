<div class="bk_8"></div>
<form method="POST" action="?app=page&controller=section&action=add">
    <table width="94%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <tr>
            <th width="72">类型：</th>
            <td>
                <?php foreach (app_config('page', 'type') as $type_alias => $type_name): ?>
                <label class="<?=$type_alias?>"><input<?php if ($type == $type_alias): ?> checked="checked"<?php endif; ?> type="radio" id="type_<?=$type_alias?>" name="type" class="radio_style" value="<?=$type_alias?>" /><span><?=$type_name?></span></label>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 名称：</th>
            <td>
                <input type="text" name="name" value="" maxlength="30" size="20" />
                <input type="hidden" name="pageid" value="<?=$pageid?>" />
            </td>
        </tr>
        <tr>
            <th>初始代码：</th>
            <td>
                <textarea name="data" class="code" wrap="off" style="width:370px;"><?=$data?></textarea>
            </td>
        </tr>
        <tr>
            <th>备注：</th>
            <td><textarea placeholder="备注用于在编辑维护区块时提示区块所需数据的规格参数，如缩略图大小，标题长度等" name="description" style="width:370px;height:35px;padding:0;margin:0;"></textarea></td>
        </tr>
    </table>
</form>
<div class="bk_5"></div>