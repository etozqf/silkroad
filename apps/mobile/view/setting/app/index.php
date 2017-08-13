<?php $this->display('header', 'system'); ?>

<style type="text/css">
    .row-menu {
        background: #FFEEEF;
    }
    img.hand {
        margin-right: 5px;
    }
</style>

<link rel="stylesheet" href="apps/mobile/css/base.css" />
<link rel="stylesheet" href="apps/mobile/css/app.css" />

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/dropdialog/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.dropdialog.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/loading/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.loading.js"></script>

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="apps/mobile/js/lib/uploader.js"></script>

<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<script type="text/javascript">
    var group = <?php echo json_encode($group);?>;
</script>

<?php $app_config = app_config('mobile', 'app'); ?>

<div class="bk_8"></div>
<div class="tag_1">
    <ul id="tag-list" class="tag_list">
        <?php foreach($group as $item):?>
        <li data-type="<?php echo $item['type'];?>" data-version="<?php echo $item['version'];?>">
            <a href="javascript:;"><?php echo $item['type'] . $item['version'];?></a>
        </li>
        <?php endforeach;?>
    </ul>
</div>

<div class="mar_l_8 mar_r_8" style="width: 900px;">
    <div class="app-row">
        <div class="app-row-title">主导航应用：</div>
        <div id="list-menu" class="app-row-list"></div>
    </div>
    <div class="app-row">
        <div class="app-row-title">应用广场：</div>
        <div id="list-square" class="app-row-list"></div>
    </div>
    <div class="app-row">
        <div class="app-row-title">已禁用：</div>
        <div id="list-disable" class="app-row-list"></div>
    </div>
</div>

<script type="text/template" id="tpl-item">
    <div class="app-item" data-appid={appid}>
        <input type="hidden" name="appid" value="{appid}" />
        <div class="app-item-thumb">
            <img src="{iconurl_src}" alt="{name}" onerror="this.src = '<?=MOBILE_URL?>templates/<?=TEMPLATE?>/app/images/app_default_ico.png'" />
        </div>
        <div class="app-item-label" title="{name}">{name_short}</div>
    </div>
</script>

<script type="text/javascript" src="apps/mobile/js/app.js"></script>
<script type="text/javascript">
    MobileApp.init({
        iconurl: {
            width: '<?=$app_config['icon']['width']?>',
            height: '<?=$app_config['icon']['height']?>'
        },
        iconurl_ipad: {
            width: '<?=$app_config['icon_ipad']['width']?>',
            height: '<?=$app_config['icon_ipad']['height']?>'
        }
    });
</script>