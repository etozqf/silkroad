<?php
define('CMSTOP_START_TIME', microtime(true));
define('RUN_CMSTOP', true);
define('IN_ADMIN', 1);
require '../../cmstop.php';

$db=factory::db();
$list=$db->select("select contentid from cmstop_content where catid=286 and status=6");






foreach($list as $key=>$value){

        $arr[]=$value['contentid'];

}


$str=implode(",",$arr);

file_put_contents("/tmp/2017",$str);



