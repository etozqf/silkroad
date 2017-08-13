<div class="bk_8"></div>
<form>
    <table width="60%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <?php foreach ($data as $item):?>
        <tr height="32">
            <td><input type="radio" id="account-change-<?php echo $item['id'];?>" name="id" value="<?php echo $item['id'];?>" <?php if ($id == $item['id']):?>checked="checked" <?php endif;?>/></td>
            <td><label for="account-change-<?php echo $item['id'];?>"><?php echo $item['name'];?></label></td>
        </tr>
        <?php endforeach;?>
    </table>
</form>