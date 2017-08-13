<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/picker.css" />

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/json2.js"></script>

<div class="picker-panel">
    <div class="operation_area">
        <form action="" id="form-query">
            <input style="padding: 2px; vertical-align: middle;" id="keyword" type="text" value="<?=$keywords?>" placeholder="搜索标题" size="30" name="keyword">
            <?php if ($modelid): ?>
            <input type="hidden" name="modelid" value="<?=$modelid?>" />
            <?php else: ?>
            <select name="modelid">
                <option value="">选择模型</option>
                <?php
                  $modelids = value($_GET, 'modelids', '1,2,3,4,5,6,7,8,9,10');
                  $modelids = array_map('intval', explode(',', $modelids));
                ?>
                <?php foreach (mobile_model() as $_modelid => $_model): ?>
                <?php if (!in_array($_modelid, $modelids)) continue;?>
                <option value="<?=$_modelid?>"><?=$_model['name']?></option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
            <input id="catid" name="catid" width="150"
                   url="?app=mobile&controller=setting&action=category_tree&disabled=0&catid=%s"
                   initUrl="?app=mobile&controller=setting&action=category_name&catid=%s"
                   paramVal="catid"
                   paramTxt="catname"
                   multiple="1"
                   value=""
                   alt="选择频道" />
            <input type="submit" class="button_style_1" style="margin-right: 0;" value="搜索" />
        </form>
    </div>
    <div class="picker-panel-left">
        <div class="picker-panel-title">待选(<span data-role="loaded">0</span>/<span data-role="total">0</span>)</div>
        <div class="picker-content-list"></div>
        <div id="message">暂无内容</div>
    </div>
</div>

<div class="btn_area">
    <button type="button" class="button_style_1">确定</button>
</div>
<script type="text/javascript">
  var modelids = '<?php echo $modelids;?>';
</script>
<script type="text/javascript" src="apps/mobile/js/picker.js"></script>
<script type="text/javascript">MobilePicker.init(<?=$multiple?>)</script>