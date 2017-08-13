<form name="payview_category_edit" id="payview_category_edit" method="POST" class="validator" action="?app=payview&controller=payview_category&action=edit&pvcid=<?=$pvcid?>">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<input type="hidden" name="pvcid" id="pvcid" value="<?=$pvcid?>"/>
	<tr>
		<th width="120"><span class="c_red">*</span> 栏目组名称：</th>
		<td><input type="text" name="title" id="title" value="<?=$data['title']?>" size="50"/></td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 订阅栏目：</th>
		<td>  
			
			<input type='text' width="150" class="selectree" id="catid" name="catid" value="<?=$data['catid']?>"
				url="?app=payview&controller=payview_category&action=cate&catid=%s&parentid=<?=$setting['catid']?>"
				initUrl="?app=system&controller=category&action=name&catid=%s"
				paramVal="catid"
				paramTxt="name"
				multiple="multiple"
			/>
		</td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 栏目组LOGO：</th>
		<td><?=element::image('logo', $data['logo'], 50)?></td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 订阅期限：</th>
		<td><input type="text" name="timetype" id="timetype" value="<?=$data['timetype']?>" size="10"/> 个月</td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 订阅费用：</th>
		<td><input type="text" name="fee" id="fee" value="<?=$data['fee']?>" size="20"/> 元</td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 订阅类型：</th>
		<td>
			<label><input type="radio" name="type" id="type0" value="0" <?php if($data['type']==0){echo 'checked="checked"';} ?> /> 线上订阅</label>
			<label><input type="radio" name="type" id="type1" value="1" <?php if($data['type']==1){echo 'checked="checked"';} ?> /> 线下订阅</label>
		</td>
	</tr>
	<tr>
		<th>栏目组说明：</th>
		<td><textarea rows="3" cols="50" name="description"><?=htmlspecialchars($data['description'])?></textarea></td>
	</tr>
</table>
</form>