<?php
set_time_limit(0);
define('RUN_CMSTOP',true);
define('IN_ADMIN', 1);
define('CURRENT_TIMESTAMP',time());
define('CMSTOP_START_TIME', microtime(true));
if(file_exists('../../../cmstop.php'))
{
    include('../../../cmstop.php'); 
    require_once(ROOT_PATH.'version.php');
}else
{
    echo "\nPlease convert program placed the correct directory\n",
         "CMSTOP Media \n",
         "cmstop/public/admin\n";
    exit();   
}
// 项目定制文件加载器
include_once('./custom.php');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT);
ini_set('display_errors', 'On');
// 静态页面生成器类文件
include_once('./html.php');
$html = new html();

/**
 * $_SERVER['argv'][1] 生成HTML类型 
 * show        代表内容最终页
 * show_article代表文章最终页
 * show_picture代表组图最终页
 * show_video  代表视频最终页
 * $_SERVER['argv'][1] = show
 * 					   $_SERVER['argv'][2] 生成内容页栏目ID，如不限制栏目请设置为0
 * 					   $_SERVER['argv'][3] 生成内容页时SQL中limit中pagesize值，以实分页生成
 * $_SERVER['argv'][1] = show_article || show_picture || show_video
 * 					   $_SERVER['argv'][2] 生成列表页栏目ID，如不限制栏目请设置为0
 * 					   $_SERVER['argv'][3] 生成列表页时SQL中limit中offset值，以实现断点
 * 					   $_SERVER['argv'][4] 生成列表页时SQL中limit中pagesize值，以实分页生成
 * 注意：$_SERVER['argv'][1] 为空时，将会根据config.php的配置进行生成静态页面
 */

if(empty($_SERVER['argv'][1])){
	$html->create();
	exit();
}
if(!empty($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'show'){
	if(!empty($_SERVER['argv'][2])){
		$catid = $_SERVER['argv'][2];
	}else{
		$catid = 0;
	}
	if(!empty($_SERVER['argv'][3])){
		$pagesize = $_SERVER['argv'][3];
	}else{
		$pagesize = 50;
	}
	$html->show($catid, $pagesize);
	exit();
}
if(!empty($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'show_list'){
	if(!empty($_SERVER['argv'][2])){
		$catid = $_SERVER['argv'][2];
	}else{
		$catid = 0;
	}
	if(!empty($_SERVER['argv'][3])){
		$maxpage = $_SERVER['argv'][3];
	}else{
		$maxpage = null;
	}
	$html->show_list($catid, $maxpage);
	exit();
}

if(!empty($_SERVER['argv'][1])){
	$type = $_SERVER['argv'][1];
}else{
	$type = null;
}
if(!empty($_SERVER['argv'][2])){
	$catid = $_SERVER['argv'][2];
}else{
	$catid = null;
}
if(!empty($_SERVER['argv'][3])){
	$offset = $_SERVER['argv'][3];
}else{
	$offset = null;
}
if(!empty($_SERVER['argv'][4])){
	$pagesize = $_SERVER['argv'][4];
}else{
	$pagesize = null;
}
$types = array('show_article', 'show_picture', 'show_video');
if(in_array($type, $types)){
	$html->$type($catid, $offset, $pagesize);
}else{
	echo "\t".'OMG!Sorry!Yours Action Do Not Supprot!',"\n\t",
	'You Can Use Any of "show",show_article","show_picture","show_video"!',
	"\n\t",'Thank You!'."\n";
}
