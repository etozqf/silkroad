<div class="bk_8"></div>
<form method="POST" action="?app=page&controller=page&action=pagecopy">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
  <tr>
    <th>克隆：</th>
    <td><span><?=$page['name']?></span><td>
  </tr>
  <tr>
    <th><span class="c_red">*</span> 名称：</th>
    <td><input type="text" name="name" id="name" value="<?=$name?>" size="20"/></td>
  </tr>
  <tr>
    <th><span class="c_red">*</span> 模板：</th>
    <td>
    <?=element::template('template', 'template', '', 24);?>
    </td>
  </tr>
  <tr>
    <th><span class="c_red">*</span> 网址：</th>
    <td>
    	<?=element::psn('path', 'path', '', $size = 24, $type = 'file')?>
    </td>
  </tr>
  <tr>
    <th>克隆到：</th>
    <td>
      <select name="parentid">
          <option value="0" selected>页面</option>
          <?php foreach ($parent as $value) {?>
              <option value="<?=$value[pageid]?>"><?=$value[name]?></option>
          <?php }?>
      </select>
    </td>
  </tr>
  <tr>
  	<th>更新频率：</th>
    <td>
    	<input type="text" name="frequency" value="3600" size="5" /> 秒 (0表示手动)
    </td>
  </tr>
</table>
</form>