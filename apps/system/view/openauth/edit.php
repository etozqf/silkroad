<div class="tabs">
	<ul>
		<li index="0"><a href="javascript:;">基本信息</a></li>
        <li index="1"><a href="javascript:;">接口权限</a></li>
        <li index="2"><a href="javascript:;">栏目权限</a></li>
		<li index="3"><a href="javascript:;">页面权限</a></li>
	</ul>
</div>
<form name="<?=$controller?>_<?=$action?>" id="<?=$controller?>_<?=$action?>" method="POST" action="?app=system&controller=openauth&action=<?=$action?>&userid=<?=$userid?>">
<div class="part">
	<table width="93%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	  <tr>
	    <th width="80"><?=element::tips('用于内容访问和添加的授权后台帐号')?>授权用户：</th>
	    <td><input type="text" name="username" value="<?=$username?>" id="username" maxlength="25" style="width:150px;" readonly /></td>
      </tr>
        <tr><th>授权公钥：</th><td><input type="text" name="auth_key" value="<?=$auth_key?>" maxlength="32" style="width:260px;" /></td></tr>
        <tr><th>授权私钥：</th><td><input type="text" name="auth_secret" value="<?=$auth_secret?>" maxlength="32" style="width:260px;" /></td></tr>
        <tr><th>备注：</th><td><textarea name="remarks"  style="width:260px;height:60px;"><?=$remarks?></textarea></tr>
        <tr><th>状态：</th><td>
            <select name="disabled">
                <?php $disabled = 0; ?>
                <option value="1" <?php if($disabled) echo 'selected="selected"'; ?>>禁用</option>
                <option value="0" <?php if(!$disabled) echo 'selected="selected"'; ?>>启用</option>
            </select>
        </td></tr>
       <!--  <tr><th><?=element::tips('去重只会跟会标题去重，重复文章会自动保存为草稿')?>是否去重：</th><td>
           <input class="checkbox_style" type="checkbox" <?php if($isrepeat) echo 'checked';?> name="isrepeat" value="1">
       </td></tr> -->
	</table>
</div>
<div class="part">
    <table width="93%" class="table_list mar_l_8 acaTreeTable" cellpadding="0" cellspacing="0">
        <tbody>
        </tbody>
    </table>
</div>
<div class="part">
    <input class="placetree" name="catid" value="<?=$catid?>"
           url="?app=system&controller=category&action=cate&catid=%s"
           initUrl="?app=system&controller=category&action=name&catid=%s"
           paramVal="catid"
           paramTxt="name"
           multiple="multiple" />
</div>
<div class="part">
    <table width="93%" class="table_list mar_l_8 pageTreeTable" cellpadding="0" cellspacing="0">
        <tbody>
        </tbody>
    </table>
</div>
</form>