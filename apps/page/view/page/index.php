<?php $this->display('header', 'system');?>
<!--tree table-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/treetable/style.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.treetable.js"></script>
<!--contextmenu-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.contextMenu.js"></script>

<script type="text/javascript" src="apps/page/js/index.js"></script>

<div class="bk_8"></div>
<div class="tag_1">
    <span style="float: right;">
        <?php if (priv::aca('page', 'page', 'publish')): ?>
        <button onclick="app.publishAllPages()" class="button_style_4 f_l" type="button">生成全部</button>
        <?php endif; ?>
        <?php if (priv::aca('page', 'page', 'logs')): ?>
        <button onclick="app.allPageLogs()" class="button_style_4 f_l" type="button">操作记录</button>
        <?php endif; ?>
    </span>
    <ul class="tag_list">
        <li class="active"><a href="javascript:void(0);">维护模式</a></li>
        <?php if (priv::aca('page', 'page', 'manage')): ?>
        <li><a href="?app=page&controller=page&action=manage">管理模式</a></li>
        <?php endif;?>
    </ul>
</div>

<div class="bk_5"></div>
<div class="mar_l_8 mar_r_8">
    <table width="100%" id="treeTable" class="table_list treeTable" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th class="t_c bdr_3">页面名称</th>
            <th class="t_c" width="120">管理操作</th>
            <th class="t_c" width="120">最近更新</th>
            <th class="t_c" width="120">下次更新</th>
            <th class="t_c" width="80">更新频率</th>
            <th class="t_c" width="80">页面大小</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<ul id="right_menu" class="contextMenu">
    <?php if (priv::aca('page', 'page', 'visualedit')): ?><li class="visualedit"><a href="#visualedit">可视化维护</a></li><?php endif;?>
    <li class="view"><a href="#viewPage">查看页面</a></li>
    <?php if (priv::aca('page', 'page', 'publish')): ?><li><a href="#publishPage">生成页面</a></li><?php endif;?>
    <?php if (priv::aca('page', 'page_priv')): ?><li class="visualedit"><a href="#priv">权限设置</a></li><?php endif;?>
    <?php if (priv::aca('page', 'page', 'logs')): ?><li class="visualedit"><a href="#logs">操作记录</a></li><?php endif;?>
</ul>

<script type="text/javascript">
    (function(){
        var rowTemplate = '\
<tr id="row_{pageid}">\
	<td><a class="edit" href="javascript:;">{name}</a></td>\
	<td class="t_c">\
        <?php if (priv::aca('page', 'page', 'visualedit')): ?><img class="hand" func="app.visualedit" height="16" width="16" src="images/visualedit.gif" title="可视化维护" alt="可视化维护" /><?php endif;?>\
		<a href="{url}" target="_blank" title="查看页面"><img class="hand" height="16" width="16" src="images/view.gif" alt="查看页面" /></a>\
		<?php if (priv::aca('page', 'page', 'publish')): ?><img class="hand" func="app.publishPage" width="16" height="16" src="images/refresh.gif" title="生成页面" alt="生成页面" /><?php endif;?>\
		<?php if (priv::aca('page', 'page_priv')): ?><img class="hand" func="app.priv" width="16" height="16" src="images/priv.png" title="权限设置" alt="权限设置" /><?php endif;?>\
		<?php if (priv::aca('page', 'page', 'logs')): ?><img class="hand" func="app.logs" width="16" height="16" src="images/log.gif" title="操作记录" alt="操作记录" /><?php endif;?>\
	</td>\
	<td class="t_r">{published}</td>\
	<td class="t_r">{nextpublish}</td>\
    <td class="t_r">{frequency}秒</td>\
	<td class="t_r">{size}</td>\
</tr>';
        app.init({
            rowTemplate: rowTemplate,
            edit: function(id, tr, json) {
                ct.assoc.open('?app=page&controller=page&action=view&pageid='+id, 'newtab', json.clickpath.split(','));
            }
        });
    })();
</script>

<?php $this->display('footer', 'system'); ?>