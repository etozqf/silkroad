<?php $this->display('header', 'system'); ?>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<!-- contextMenu -->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.contextMenu.js"></script>

<!-- treetable -->
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.treetable.js"></script>
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/treetable/style.css" />

<script type="text/javascript" src="apps/system/js/psn.js"></script>

<link rel="stylesheet" type="text/css" href="apps/page/css/page.css" />
<link rel="stylesheet" type="text/css" href="apps/page/css/fields.css" />
<script type="text/javascript" src="apps/page/js/admin.js"></script>
<?=$resources?>
<style type="text/css">
    html, body {
        height: 100%;
    }
    .section_table {
        margin: 5px 5px 10px 6px;
        position: relative;
    }
    .section_table .section_action {
        position: absolute;
        right: 0;
        top: -25px;
    }
    .section-item {
        display: block;
        height: 24px;
        line-height: 24px;
    }
</style>

<div class="bk_8"></div>
<div class="tag_1">
    <span style="float: right;">
        <?php if ($haspriv):?>
        <input type="button" value="设置" class="button_style_1" onclick="app.editPage(<?=$pageid?>);" />
        <input type="button" value="权限" class="button_style_1" onclick="app.pagePriv(<?=$pageid?>);" />
        <input type="button" value="模板编辑" class="button_style_1" style="width:70px" onclick="app.templateEdit(<?=$pageid?>); return false;" />
        <?php endif;?>
        <?php if (priv::aca('page', 'page', 'visualedit') && (priv::page($pageid) || priv::section_page($pageid))): ?>
        <input type="button" value="可视化维护" class="button_style_1" style="width:70px" onclick="app.visualEdit(<?=$pageid?>); return false;" />
        <?php endif; ?>
        <?php if ($haspriv):?>
        <input type="button" value="查看" class="button_style_1" onclick="app.viewPage(<?=$pageid?>);" />
        <input type="button" value="操作记录" class="button_style_1" onclick="app.logs(<?=$pageid?>); return false;" />
        <input type="button" value="立即生成页面" class="button_style_1" onclick="app.publishPage(<?=$pageid?>); return false;" />
        <?php endif;?>
    </span>
    <ul class="tag_list">
        <?php if (priv::aca('page', 'page', 'view')): ?>
        <li><a href="?app=page&controller=page&action=view&pageid=<?=$pageid?>&force=1">维护模式</a></li>
        <?php endif; ?>
        <li class="active"><a href="javascript:void(0);">管理模式</a></li>
    </ul>
    <?php if (!isset($status) || $status == 1): ?>
    <span class="search_icon search mar_l_8">
        <input type="text" name="searchSection" value="按ID搜索区块" id="searchSection" onblur=" this.value || (this.value = '按ID搜索区块')" onfocus="this.value == '按ID搜索区块' && (this.value = '')" />
        <a href="javascript:search();">搜索</a>
    </span>
    <?php endif; ?>
</div>

<form action="" id="section-filter">
    <dl class="proids" style="margin:0 8px; border:0;">
        <dt>状态：</dt>
        <dd data-role="status">
            <a<?php if (!isset($status) || $status == 1):?> class="checked"<?php endif; ?> href="?app=page&controller=page&action=admin&pageid=<?=$pageid?>&status=1">已发布</a>
            <a<?php if ($status == 0):?> class="checked"<?php endif; ?> href="?app=page&controller=page&action=admin&pageid=<?=$pageid?>&status=0">已删除</a>
        </dd>
    </dl>
    <dl class="proids" style="margin:0 8px; border:0;">
        <dt>类型：</dt>
        <dd data-role="type">
            <input type="hidden" name="type" value="" />
            <a class="checked" href="javascript:void(0);" type="">全部</a>
            <?php foreach (app_config('page', 'type') as $type => $type_name): ?>
            <a href="javascript:void(0);" type="<?=$type?>"><?=$type_name?></a>
            <?php endforeach; ?>
        </dd>
    </dl>
</form>

<?php $this->display("page/admin/$status"); ?>
<?php $this->display('footer', 'system');