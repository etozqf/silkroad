<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2017/4/14
 * Time: 10:53
 */
header("Content-type:text/html;charset=utf-8");
require "db.php";

//写个小工具用来update content_propery
//录入文件名，proname
$title = file_get_contents("title.php");
$proname = file_get_contents("proname.php");
$title = explode("\r\n",$title);
$proname = explode("\r\n",$proname);

//var_dump($title);
//var_dump($proname);


if (count($title) != count($proname))
{
    die("两组数据不一致，请核对，我的哥");
}


$db = db('cmstop');
$res = $db->select("select contentid,title from cmstop_content limit 1");
var_dump($res);
//查出对应的contentid，proid存入对应的contentid，proid数组
for ($i=0;$i<count($title);$i++)
{
    $sql = "select contentid,title from cmstop_content where title='{$title[$i]}'";
    $id = $db->get($sql);
    if ($id)
    {
        $contentid[$i] = $id['contentid'];
    }
    else
    {
        echo 'content不存在的数据：'."$title[$i] </br>";
    }

}

for ($j=0;$j<count($proname);$j++)
{
    $sql = "select proid,name from cmstop_property where name='{$proname[$j]}'";
    $id = $db->get($sql);
    if ($id)
    {
        $proid[$j] = $id['proid'];
    }
    else
    {
        echo 'property不存在的数据：'."$proname[$j] </br>";
    }
}
echo "<pre>";
//var_dump($proid);
//var_dump($contentid);
echo "</pre>";


//开始拼sql
if ($proname && $contentid)
{
    $sql = "insert into cmstop_content_property(contentid,proid) VALUES ";
    for ($k=0;$k<count($contentid);$k++)
    {
        $sql .='('."$contentid[$k]".','."$proid[$k]".')';
        if ($k != count($contentid)-1)
        {
            $sql .=",";
        }
    }
    $sql .=';';
    var_dump($sql);
 
    $db->query("SET FOREIGN_KEY_CHECKS = 0");
    $res = $db->insert($sql);
    if ($res)
    {
        echo "<h1>插入成功 $res 条数据</h1>";
    }
    $db->query("SET FOREIGN_KEY_CHECKS = 1");
}

//273887

function db($dbname)
{
    $db_mysql =  new db(array(
        'driver' => 'mysql',
        'host' => 'mysql.db.silkroad.news.cn',
        'port' => 3306,
        'username' => 'cmstop',
        'password' => 'wEAegnZk9XVnzlie3oyuPw',
        'dbname' => 'cmstop',
        'prefix' => 'cmstop_',
        'pconnect' => 0,
        'charset' => 'utf8'
    ));
    return $db_mysql;
}

