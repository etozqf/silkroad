<div class="bk_8"></div>
<form method="POST" action="?app=system&controller=port&action=<?=$_GET['action']?>">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
  <tr>
    <th><span class="c_red">*</span> 标识：</th>
    <td>
    <input type="text" name="port" value="<?=$port?>" size="20" />
    <?php if (!empty($portid)): ?>
    <input type="hidden" name="portid" value="<?=$portid?>" />
    <?php endif;?>
    </td>
  </tr>
  <tr>
    <th><span class="c_red">*</span> 系统名称：</th>
    <td><input type="text" name="name" value="<?=$name?>" size="20" /></td>
  </tr>
  <tr>
    <th><span class="c_red">*</span> 接口地址：</th>
    <td><input type="text" name="url" value="<?=$url?>" size="40"/></td>
  </tr>
  <tr>
    <th>接口密钥：</th>
    <td><input type="text" name="authkey" value="<?=$authkey?>" size="40"/></td>
  </tr>
  <tr>
    <th>服务状态：</th>
    <td>
    	<label>
    		<input type="radio" name="disabled" value="0" <?=empty($disabled)?'checked="checked"':''?> /> 开启
    	</label>
    	&nbsp;&nbsp;&nbsp;&nbsp;
    	<label>
    		<input type="radio" name="disabled" value="1" <?=!empty($disabled)?'checked="checked"':''?> /> 关闭
    	</label>
    </td>
  </tr>
</table>
</form>
<div class="bk_5"></div>