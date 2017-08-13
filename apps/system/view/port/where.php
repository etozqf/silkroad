<form>
	<input type="text" name="keywords" />
	<?php $model = table('model');
    if (empty($_GET['modelid'])):?>
	<select name="modelid" class="modelset" multiple alt="选择模型">
		<?php foreach($model as $v):?>
		<option value="<?=$v['modelid']?>" ico="<?=$v['alias']?>"><?=$v['name']?></option>
		<?php endforeach;?>
	</select>
	<?php else:
    $modelids = explode(',', $_GET['modelid']);
    if(count($modelids) > 1):?>
    <select name="modelid" class="modelset" multiple alt="选择模型">
        <?php foreach($model as $v):?>
        <?php if (!in_array($v['modelid'], $modelids)) continue;?>
        <option value="<?=$v['modelid']?>" ico="<?=$v['alias']?>"><?=$v['name']?></option>
        <?php endforeach;?>
    </select>
    <?php else:?>
    <input name="modelid" value="<?=$_GET['modelid']?>" type="hidden" />
    <?php endif;?>
	<?php endif;?>
    <?php $catid = id_format(value($_GET, 'catid')); ?>
    <input width="150" class="selectree" name="catid" value="<?php echo $catid ? implode_ids($catid) : '';?>"
           url="?app=system&controller=category&action=cate&catid=%s"
           initUrl="?app=system&controller=category&action=name&catid=%s"
           paramVal="catid"
           paramTxt="name"
           multiple="1"
           alt="选择栏目"
        />
</form>