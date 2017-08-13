<?php $this->display('header', 'system');?>
<!--tree-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tree/style.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.tree.js"></script>
<div class="bk_10"></div>
<div class="suggest w_650 mar_l_8">
	<h2>友情提示</h2>
	<p>《互联网新闻开放协议》是百度新闻搜索制定的搜索引擎新闻源收录标准，网站可将发布的新闻内容制作成遵循此开放协议的XML格式的网页（独立于原有的新闻发布形式）供搜索引擎索引。
本功能将能根据您的实际需要，生成符合百度新闻源要求的XML文件。<a class="c_red" href="http://news.baidu.com/newsop.html" target="_blank">点击查看</a>如果您的网站尚未成为百度新闻源，请先<a class="c_red" href="http://news.baidu.com/newsop.html#km" target="_blank">申请收录</a>。</p>
</div>
<div class="bk_8"></div>
<form id="baidunews" action="?app=system&controller=baidunews&action=index" method="POST">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<caption>输出XML设置</caption>  
	<tr>
		<th width="120">状态：</th>
		<td>
			<input type="radio" id="l1" name="setting[open]" value="1" class="radio" <?php if ($setting['open']) echo 'checked';?>/> 开启
			<input type="radio" id="l2" name="setting[open]" value="0" class="radio" <?php if (!$setting['open']) echo 'checked';?>> 关闭
		</td>
	</tr>  
	<tr>
		<th>Xml地址：</th>
		<td>
			<?=element::psn('baidunewsurl','setting[url]',$setting['url'], 45, 'file')?>
				<input type="button" onclick="makeBaiduNews()" value=" 立刻生成 " class="button_style_1"/>
		</td>
	</tr>  
	<tr>
		<th>Xml链接：</th>
		<td><span class="baiduurl">
		<a href="<?php echo $setting['Realurl']?>" target="_blank"  ><?php echo $setting['Realurl']?></a></span>
		</td>  
	</tr>
	<tr>
        <th>包含频道：</th>
        <td valign="middle">
            <input type="hidden" id="category" name="setting[category]" value="<?=$categorys?>"
                   class="placetree"
                   url="?app=system&controller=category&action=cate&catid=%s"
                   initUrl="?app=system&controller=category&action=name&catid=%s"
                   paramVal="catid"
                   paramTxt="name"
                   multiple="multiple" />
        </td>
	</tr>
	<tr>
		<th>包含内容：</th>
		<td >
		<input type="checkbox" id="homepage"name="setting[article]" value="1" <?php if ($setting['article']) echo 'checked';?> class="checkbox" />
			文章&nbsp;&nbsp;       
		<input type="checkbox"id="ishomepage" name="setting[picture]" value="1" <?php if ($setting['picture']) echo 'checked';?> />
		   组图     
		</td>
	</tr> 
	<tr>
		<th>输出条数：</th>
		<td ><input type="text" name="setting[number]" value="<?=$setting['number']?>" size="10"/>   条</td>
	</tr>  
	<tr>
		<th>更新频率：</th>
		<td ><input type="text" name="setting[frequency]" value="<?=$setting['frequency']?>" size="10"/> 分钟   </td>
	</tr>
</table>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<tbody align="left">
		<caption>百度新闻源基本设置</caption>   
	<tr>
		<th width="120">网站地址：</th>
		<td><input type="text" name="setting[webname]" value="<?=$setting['webname']?>" size="30"/></td>
	</tr>
	<tr>
		<th>负责人员的Email：</th>
		<td ><input type="text" name="setting[adminemail]" value="<?=$setting['adminemail']?>" size="30"/></td>
	</tr>
	<tr>
		<th>更新周期：</th>
		<td ><input type="text" name="setting[updatetime]" value="<?=$setting['updatetime']?>" size="6"/> 分钟</td>
	</tr>
	<tr>
		<th></th>
		<td valign="middle"><input type="submit" id="submit" value="保存" class="button_style_2"/></td>
	</tr>
	</tbody>
</table>
<br/>
</form>
<script type="text/javascript" src="apps/system/js/psn.js"></script>
<script type="text/javascript" src="apps/system/js/treeview_selector.js"></script>
<link href="<?=IMG_URL?>js/lib/treeview/treeview.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/jquery.treeview.js"></script>
<script type="text/javascript">
$(function() {
    $('#category').placetree();
    $('#baidunews').ajaxForm('submit_ok');
});
function submit_ok(data) {
	if(data.state) {
		ct.tips(data.message || '生成成功');
	} else {
		ct.error(data.error || '生成失败，请重试');
	}
}
function makeBaiduNews() {
	$.getJSON(
		"?app=system&controller=baidunews&action=xml&make=1",
		submit_ok
	);
}
</script>
<?php $this->display('footer', 'system');?>
