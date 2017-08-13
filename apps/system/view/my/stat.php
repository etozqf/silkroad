<?php $this->display('header', 'system');?>
<?php
function admin_list($userid, $year, $month = null)
{
	$time = $year;
	if ($month)
	{
		$time .= '-'.$month;
	}
	$db = factory::db();
	$date = new date($time.'-01');
	$mintime = strtotime($date->starttime_of_month());
	$maxtime = strtotime($date->endtime_of_month());
	$daystart = intval($date->firstday_of_month()->day);
	$dayend = intval($date->lastday_of_month()->day);
	
	$result = array_fill($daystart, $dayend, array('posts'=>0, 'comments'=>0, 'pv'=>0));
	
	$data = $db->select("SELECT comments,pv,score,published FROM #table_content WHERE published>:mintime AND published<:maxtime AND createdby=$userid", array('mintime'=>$mintime,'maxtime'=>$maxtime));
	foreach ($data as $v)
	{
		$day = intval(date('j',$v['published']));
		$result[$day]['posts'] ++;
		$result[$day]['comments'] += $v['comments'];
		$result[$day]['pv'] += $v['pv'];
		$result[$day]['score'] += $v['score'];
	}
	return $result;
}

if (!isset($year) || !$year) $year = date('Y');
if (!isset($month) || !$month) $month = date('m');

$months = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
?>
<div class="bk_10"></div>
<div id="proids">
    <dl class="proids"><dt>年份：</dt><dd>
<?php
$i = 0;
for ($y = date('Y'); ; $y--)
{
	$i++;
	$selected = $y == $year ? 'class="checked"' : '';
	echo '<a href="?app=system&controller=my&action=stat&userid='.$userid.'&year='.$y.'&month='.$month.'" '.$selected.'>'.$y.'年</a>';
	if ($i >= 10) break;
}
?>
	</dd></dl>
	<dl class="proids"><dt>月份：</dt><dd>
	<?php
foreach ($months as $m)
{
	$selected = $m == $month ? 'class="checked"' : '';
	echo '<a href="?app=system&controller=my&action=stat&userid='.$userid.'&year='.$year.'&month='.$m.'" '.$selected.'>'.(substr($m, 0, 1) == 0 ? substr($m, 1) : $m).'月</a>';
}
?></dd></dl>   
 </div>

<table width="500px" border="0" cellpadding="0" cellspacing="0" class="table_list" style="margin:10px">
	<tbody>
	<tr>
	  <th width="100" class="bdr_3">日期</th>
	  <th width="133">内容</th>
	  <th width="133">评论</th>
	  <th width="133">PV</th>
	  <th width="133">评分</th>
	</tr>
<?php 
$posts = $comments = $pv = 0;
$data = admin_list($_userid, $year, $month);
foreach ($data as $k=>$r)
{
$posts += $r['posts'];
$comments += $r['comments'];
$pv += $r['pv'];
$score += $r['score'];
?>
	<tr onmouseover="this.style.backgroundColor='#FFFDDD'" onmouseout="this.style.backgroundColor=''">
	  <td class="t_c"><?=$year."-".$month."-".sprintf("%02s",$k)?></td>
	  <td><?=$r['posts']?></td>
	  <td><?=$r['comments']?></td>
	  <td><?=$r['pv']?></td>
	  <td><?=empty($r['score'])?0:$r['score']?></td>
	</tr>     
<?php
}
?> 
   <tr>
	  <td class="t_r">合计：</td>
	  <td><?=$posts?></td>
	  <td><?=$comments?></td>
	  <td><?=$pv?></td>
	  <td><?=$score?></td>
	</tr>
	</tbody>
</table>
<?php $this->display('footer', 'system');?>