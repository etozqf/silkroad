<div class="bk_20"></div>
<form id="html_ls" method="POST" action="?app=system&controller=html&action=show">
<input name="catid" type="hidden" value="<?=$catid?>"/>
<table border="0" cellspacing="0" cellpadding="0" class="table_form" width="100%">
  <tr>
    <th width="50%">生成模型：</th>
    <td>
      <select multiple="multiple" name="modelids">
        <?php
          foreach (table('model') as $model):
            if ($model['modelid'] == 3) continue;
        ?>
        <option value="<?php echo $model['modelid'];?>"><?php echo $model['name'];?></option>
        <?php endforeach;?>
      </select>
    </td>
  </tr>
  <tr>
    <th>发布时间范围：</th>
    <td><select name="published_start">
    <option value="" selected>全部</option>
    <option value="<?=date('Y-m-d', TIME)?>">今日</option>
    <option value="<?=date('Y-m-d', strtotime('yesterday'))?>&<?=date('Y-m-d', strtotime('today'))?>">昨日</option>
    <option value="<?=date('Y-m-d', date::this_week(true))?>">本周</option>
    <option value="<?=date('Y-m-01', strtotime('this month'))?>">本月</option>
    </select></td>
  </tr>
  <tr>
    <th>每轮生成：</th>
    <td><input type="text" name="limit" id="limit" value="<?php if($limit) echo $limit; else echo '100'?>" size="5"/> 条</td>
  </tr>
</table>
</form>