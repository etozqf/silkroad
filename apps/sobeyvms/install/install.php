<?php
$menu = loader::model('admin/menu', 'system');
$local_path = str_replace('\\', '/', dirname(__FILE__)) . DS;

$menuids = array();
$menuid = $menu->add(array(
	'parentid'=>5,
	'name'=>'稿件同步',
	'url'=>'?app=sobeyvms&action=cate',
	'sort'=>100
));
$menuids[] = $menuid;
$menuids[] = $menu->add(array(
	'parentid'=>$menuid,
	'name'=>'稿件同步配置',
	'url'=>'?app=sobeyvms&action=setting',
	'sort'=>101
));
write_file($local_path.'install.log', implode(',', $menuids));

// 复制视频模板文件
$template = config('template','name');
$src = $local_path.'video_templates'.DS.'player';
$dest = ROOT_PATH.'templates'.DS.$template.DS.'video'.DS.'player';
if (!file_exists($dest.DS.'sobeyvms.html') && is_writable(dirname($dest))) {
	import('helper.folder');
	folder::copy($src, $dest);
}
