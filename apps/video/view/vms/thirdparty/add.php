<div class="bk_10"></div>

<form id="<?=$app?>_<?=$controller?>_<?=$action?>" method="POST" class="validator" action="?app=<?=$app?>&controller=<?=$controller?>&action=<?=$action?>">
<input type="hidden" name="id" id="id" value="<?=$id?>"/>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tr>
		<th width="80">接口名称：</th>
		<td><input type="text" name="title" id="title" value="<?=$title?>" style="width:300px" /></td>
	</tr>
    <tr>
        <th>接口地址：</th>
        <td><input type="text" name="apiurl" id="apiurl" value="<?=$apiurl?>" style="width:300px" /></td>
    </tr>
    <tr>
        <th>认证令牌：</th>
        <td><input type="text" name="authkey" id="listname" value="<?=$authkey?>" style="width:300px" /></td>
    </tr>
    <tr>
        <th>厂商标识：</th>
        <td>
            <select id="apitype" name="apitype">
                <?php
                foreach($partnerlist as $key=>$name)
                {
                    $selected = '';
                    $apitype = isset($apitype) ? $apitype : '';
                    if($key == $apitype) $selected = ' selected';
                    echo '<option value="'.$key.'"'.$selected.'>'.$name.'</option>';
                }
                ?>
            </select>
        </td>
    </tr>
	<tr>
		<th>状态：</th>
		<td>
            <input type="radio" name="status" value="0" />禁用&nbsp;&nbsp;
            <input type="radio" name="status" value="1" />启用
        </td>
	</tr>
</table>
</form>

<script language="javascript">
    var status = <?php echo $status?> + 0;
    $(function(){
        $("input[name=status]").eq(status).attr('checked',true);
        $('#apitype').selectlist();
    });
</script>
