<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<title><?=$head['title']?></title>
<?=$resources?>
<style type="text/css">
body {
    overflow:hidden;
}
.inline li {
    margin:0 3px;
}
.table_info img {
    margin:0 4px;
}
.section-item-output {
    background: url(css/images/bg.gif) no-repeat scroll transparent;
    width: 16px;
    display: inline-block;
    zoom: 1;
    height: 24px;
    line-height: 24px;
}
.section-item-output.json {
    background-position: -136px -1271px;
}
.section-item-output.xml {
    background-position: -136px -1328px;
}
</style>
<script type="text/javascript">
$(ct.listenAjax);
</script>
</head>
<body scroll="no">
<!--右侧开始-->
<div class="bk_8"></div>
<div class="tag_1" style="margin-bottom:0;">
    <span style="float: right;">
        <?php if (priv::aca('page', 'page', 'visualedit') && (priv::page($page['pageid']) || priv::section_page($page['pageid']))): ?>
        <input type="button" value="可视化维护" class="button_style_1" style="width:70px" onclick="page.visualEdit(<?=$page['pageid']?>); return false;" />
        <?php endif; ?>
        <?php if ($haspriv):?>
        <input type="button" value="查看" class="button_style_1" onclick="window.open('<?=$page['url']?>','_blank'); return false;" />
        <input type="button" value="操作记录" class="button_style_1" onclick="page.logs(<?=$page['pageid']?>); return false;" />
        <input type="button" value="立即生成页面" class="button_style_1" onclick="page.publishPage(<?=$page['pageid']?>); return false;" />
        <?php endif;?>
        <?php if (priv::aca('page', 'page_priv') && (priv::page($page['pageid']))): ?>
        <input type="button" value="页面权限" class="button_style_1" style="width:70px" onclick="page.pagePriv(<?=$page['pageid']?>); return false;" />
        <?php endif; ?>
    </span>
    <ul class="tag_list">
        <li class="active"><a href="javascript:void(0);">维护模式</a></li>
        <?php if (priv::aca('page', 'page', 'admin')): ?>
        <li><a href="?app=page&controller=page&action=admin&pageid=<?=intval($page['pageid'])?>&force=1">管理模式</a></li>
        <?php endif;?>
    </ul>
</div>

<div class="bk_10"></div>
<div id="bodyContainer">
	<div id="overlay">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td class="message"></td>
                </tr>
            </tbody>
        </table>
	</div>
	<div class="f_r" id="viewBox"></div>
	<div id="sectionPanel" class="f_l">
		<h3>
    		<a class="search_11 f_r" style="margin-top:4px" onfocus="this.blur()" onclick="page.searchSection(this);" href="javascript:;">
				<img height="16" width="16" alt="搜索" src="images/space.gif"/>
			</a>
			<span class="f_l">区块列表<span id="sectionCount"></span></span>
		</h3>
		<div id="sectionBox" style="clear:both;">
			<div class="bk_5"></div>
			<ul id="sectionList"></ul>
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="clear"></div>
<script type="text/javascript">
page.init(<?=$_GET['pageid']?>);
</script>
<?php $this->display('footer', 'system');