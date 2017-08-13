<?php
$setting = new setting();
$setting->set_array('baoliao', array(
	'admin' => '管理员',
	'item' => '["name","email"]'
));

$menu = loader::model('admin/menu', 'system');

$menuids = array();
$menuid = $menu->add(array(
	'parentid'=>5,
	'name'=>'报料',
	'url'=>'?app=baoliao&controller=baoliao&action=index',
	'sort'=>''
));
$menuids[] = $menuid;
$menuids[] = $menu->add(array(
	'parentid'=>$menuid,
	'name'=>'报料设置',
	'url'=>'?app=baoliao&controller=setting&action=index',
	'sort'=>''
));
$installlog = str_replace('\\', '/', dirname(__FILE__)).'/install.log';
write_file($installlog, implode(',', $menuids));