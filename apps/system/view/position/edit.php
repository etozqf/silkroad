<div class="bk_8"></div>
<form name="<?=$app?>_<?=$controller?>_<?=$action?>" id="<?=$app?>_<?=$controller?>_<?=$action?>" method="post" class="validator" action="?app=<?=$app?>&controller=<?=$controller?>&action=edit">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tr>
		<th width="80"><span class="c_red">*</span> 园区名称：</th>
		<td><input name="name" type="text" value="<?=$name?>" size="16" maxlength="255"  /><input name="positionid" type="hidden" value="<?=$positionid?>" /></td>
	</tr>
	
	<tr>
		<th width="80"><span class="c_red">*</span> 链接地址：</th>
		<td><input name="url" type="text" value="<?=$url?>" size="16" maxlength="255"  /></td>
	</tr>
	<tr>
		<th width="80"><span class="c_red">*</span> 所属省份(或国家)：</th>
		<td><input name="province" type="text" value="<?=$province?>" size="16" maxlength="255"  /><span style="color:#ccc">(例如河北省,北京市,新疆自治区)</span></td>
	</tr>
	<tr>
		<th width="80"><span class="c_red">*</span> 位置坐标：</th>
		<td><input name="point" type="text" value="<?=$point?>" size="16" maxlength="255"  /><span style="color:#ccc">(例如111,222)</span></td>
	</tr>
	<tr>
		<th width="80"><span class="c_red">*</span> 园区类别：</th>
		<td>
			<select id="status" name="value">
				<option value="">请选择</option>
			<?php 
				$db=factory::db();
				$res = $db->select("select * from cmstop_yq_category order by sort");
				foreach($res as $key=>$val){
			?>
				<option <?php if($val['value']==$value){echo 'selected';}?> value="<?php echo $val['cateid'];?>"><?php echo $val['value'];?></option>
				
			<?php 
			}
			?>
			</select>
		</td>
	</tr>
</table>
</form>