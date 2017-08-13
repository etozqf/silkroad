<div class="bk_8"></div>
<form method="POST" action="?app=spider&controller=manager&action=editTask">
<input type="hidden" name="taskid" value="<?=$taskid?>" />
<table width="390" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
  <tr>
    <th><span class="c_red">*</span> 名称：</th>
    <td><input type="text" name="title" value="<?=$title?>" size="40"/></td>
  </tr>
  <tr>
    <th><span class="c_red">*</span> 目标：</th>
    <td><input type="text" name="url" value="<?=$url?>" size="40"/></td>
  </tr>
  <tr>
    <th><span class="c_red">*</span> 规则：</th>
    <td><?=$rule_dropdown?>&nbsp;&nbsp;<a href="javascript:ct.assoc.open('?app=spider&controller=manager&action=addrule','newtab')">添加规则</a></td>
  </tr>
  <tr>
    <th>默认栏目：</th>
    <td><?=element::category('catid','catid',$catid)?></td>
  </tr>
  <tr>
    <th>更新频率：</th>
    <td><input type="text" name="frequency" value="<?=$frequency?>" size="20"/> 秒</td>
  </tr>
  <tr height="22">
    <th>过滤重复标题：</th>
    <td><input type="checkbox" name="titlecheck" value="1"<?php if($titlecheck):?> checked="checked"<?php endif;?> /></td>
  </tr>
  <tr height="22">
    <th>标题包含关键词：</th>
    <td><input type="text" name="titletags" value="<?=$titletags?>" /><span style="color:red;">多个以半角逗号分割</span></td>
  </tr>
  <tr height="22">
    <th>标题过滤关键词：</th>
    <td><input type="text" name="nottitletags" value="<?=$nottitletags?>" /><span style="color:red;">多个以半角逗号分割</span></td>
  </tr>
  <tr height="22">
    <th>添加定时采集任务：</th>
    <td>
      <input id="cron" type="checkbox" value="1"<?php if($cron=='1'):?> checked="checked"<?php endif;?> />
      <input type="hidden" name="cron" value="<?php echo $cron;?>" />
    </td>
  </tr>
  <tbody id="cron_details" style="display:none;">
    <tr>
      <th>采集间隔：</th>
      <td><input type="text" name="cron_frequency" value="<?php echo $cron_frequency;?>" />秒</td>
    </tr>
    <tr>
      <th>采集条数：</th>
      <td><input type="text" name="cron_count" value="<?php echo $cron_count;?>" />(0为不限制)</td>
    </tr>
    <tr>
      <th>下次采集时间：</th>
      <td><input type="text" class="datepicker" name="cron_next" value="<?php echo $cron_next ? date('Y-m-d H:i:s', $cron_next) : date('Y-m-d H:i:s');?>" onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});" /></td>
    </tr>
    <tr>
      <th>默认状态：</th>
      <td>
        <label><input type="radio" name="cron_status" value="1"<?php if($cron_status==1):?> checked="checked"<?php endif;?>>草稿</label>&nbsp;
        <label><input type="radio" name="cron_status" value="3"<?php if($cron_status==3):?> checked="checked"<?php endif;?>>待审</label>&nbsp;
        <label><input type="radio" name="cron_status" value="6"<?php if($cron_status==6):?> checked="checked"<?php endif;?>>已发</label>&nbsp;
    </tr>
  </tbody>
</table>
</form>
<script type="text/javascript">
(function() {
  var cron = $('#cron'),
  cronDetails = $('#cron_details'),
  cronField = $('[name="cron"]');
  cron.bind('click', function() {
    if (cron.is(':checked')) {
      cronDetails.show();
      cronField.val('1');
    } else {
      cronDetails.hide();
      cronField.val('0');
    }
  });
  cron.is(':checked') ? cronDetails.show() : cronDetails.hide();
})();
</script>