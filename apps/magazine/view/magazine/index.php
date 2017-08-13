<?php $this->display('header'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/dropdown/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.dropdown.js"></script>
<script type="text/javascript" src="apps/magazine/js/magazine.js"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<div class="mar_l_8">
	<div class="mar_t_10">
		<input type="button" id="add" value="创建刊物" class="button_style_4"/>
		<input type="button" id="access" value="查看前台" class="button_style_4"/>
	</div>
	<div id="magazineDiv" class="clearfix"><!-- magazine容器 -->
	
	</div>	
</div>
<?php $this->display('footer','system');?>
<script type="text/javascript">
var tpl = '\
		<div rel="{mid}" class="magazineItem">\
			<ul class="magazine">\
				<li class="magazine_logo">\
					<div><a href="{url}" target="_blank"><img alt="{name}" src="{logo}"/></a></div>\
				</li>\
				<li><span>　　　刊物名：</span>{name}</li>\
				<li class="memo"><span>　　　简　介：</span>{memo}</li>\
				<li>\
					<input type="button" class="manage button_style_1" value="管理"/>\
					<input value="新建" type="button" class="newEdition button_style_1" />\
					<input type="button" class="edit button_style_1" value="属性"/>\
					<input type="button" class="delete button_style_1" value="删除"/>\
				</li>\
			</ul>\
		</div>';
$(function () {
	$('#add').click(magazine.save);
	$('#access').click(function (){
		window.open('<?=$SETTING['www_root']?>');
	});
	magazine.load();
	ct.nav([
		{text: '扩展'},
		{text: '中英文刊物'}
	]);
});
</script>