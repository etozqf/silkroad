<div class="bk_8"></div>
<form id="magazine_add" name="magazine_add" method="POST" class="validator" action="?app=magazine&controller=magazine&action=save">
<input type="hidden" name="mid" value="<?=$magazine['mid']?>"/>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form" id="attachment_panel">
	<tr class="abc">
		<th width="110"><span class="c_red">*</span> 中英文刊物名称：</th>
		<td><input type="text" name="name" value="<?=$magazine['name']?>" size="40"/></td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 别名：</th>
		<td><input type="text" name="alias" value="<?=$magazine['alias']?>" size="40"/></td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 列表模板：</th>
		<td><?=element::template("template_list","template_list",$magazine['template_list'],28);?></td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 内页模板：</th>
		<td><?=element::template("template_content","template_content",$magazine['template_content'],28);?></td>
	</tr>
	<?php $logo = json_decode($magazine['logo'], true); 
		if (!$logo) {
			?>
			<tr class="attachment-img">
				<th>缩略图：</th>
				<td><?=element::image("logo[0]", $magazine['logo'], 28)?>
					<a style="cursor:pointer" half="javascript:;" class="del" onclick="del(this)">删除</a>
				</td>
			</tr>
			<?php
		} else {
		foreach ($logo as $k => $v) { ?>
	<tr class="attachment-img">
		<th>缩略图：</th>
		<td><?=element::image("logo[$k]", $v, 28)?>
			<a style="cursor:pointer" half="javascript:;" class="del" onclick="del(this)">删除</a>
		</td>
	</tr>

	<?php }}?>
	
</table>

<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">

	<tr>
		<th width="110"></th>
		<td style="padding-left:300px"><a id="upload_pic" class="upload_pic" href="javascript:;" onclick="return false;" title="最多支持6张图片" 
                                   style="width: 60px;">添加图片</a></td>
	</tr>
	<tr>
		<th> 网址：</th>
		<td><input type="text" name="url" value="<?=$magazine['url']?>" size="40"/></td>
	</tr>
	<tr>
		<th> 简介：</th>
		<td>
			<textarea name="memo" style="width: 336px;height:90px;"><?=$magazine['memo']?></textarea>
		</td>
	</tr>
</table>
</form>

<script id="attachment_pic_template" type="text/template">
    <tr class="attachment-img">
    	<th>缩略图：</th>
        <img src="{src}" width="100" height="100" style=""/>
        <th style="text-align: left;"><input type="text" name="logo[{aid}]" value="{value}" size="40"/>&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;<a style="cursor:pointer" half="javascript:;" class="delete">删除</a>
        <th>
    </tr>
</script>
<script type="text/javascript">
    $(function () {
        var maxUpload = 6;
    	var iii = $(".attachment-img").length;

        var b, e, c, f, d, g, h;
        b = $("#attachment_pic_template").html();
        new Uploader(document.getElementById("upload_pic"), {
            script: "?app=magazine&controller=magazine&action=upload",
            fileExt: "*.png;*.jpg;*.jpeg;*.gif",
            fileDesc: "png|jpg|gif",
            fieldName: "Filedata",
            sizeLimit: 5120000,
            multi: true,
            jsonType: 1,
            uploadComplete: function (a) {
                if (a) {
                    if ($("#upload_info").empty(),
                                    a = $(b.replace("{src}", UPLOAD_URL + a.data.file).replace("{value}", a.data.file).replace(/{aid}/g, a.data.aid)), $("#attachment_panel").append(a), a.find(".delete").bind("click", function (a) {
                                $(a.currentTarget).parent().parent(".attachment-img").remove();
                                return ;
                            }), 0 < maxUpload && $(".attachment-img").length >= maxUpload) return f.hide()
                } else return $("#upload_info").html("\u56fe\u7247\u4e0a\u4f20\u5931\u8d25")
            }
        });
    	f = $("#upload_pic");
    	if ( 0 < maxUpload && iii >= maxUpload) {f.hide()}
	});

    function del (el) {
    	$(el).parent().parent().remove();
    }
</script>