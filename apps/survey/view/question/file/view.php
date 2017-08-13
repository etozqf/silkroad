<dl class="list_2">
<dt class="c_blue b f_14"><span><?=$sort?></span>. <?=$subject?></dt>
<dd><?=$description?></dd>
<dd>
	<?php if(in_array(substr($record[$questionid], -4), array('.jpg', 'jpeg', '.gif', '.bmp', '.png'))):?>
	<img src="<?php echo UPLOAD_URL . $record[$questionid]?>" />
	<script type="text/javascript">
	(function() {
		var img = $('img[src="<?php echo UPLOAD_URL . $record[$questionid]?>"]'),
		width = img.width();
		if (width > 300) {
			img.attr('width', 300);
		}
	})();
	</script>
	<?php elseif($record[$questionid]):?>
	<a href="<?php echo UPLOAD_URL.$record[$questionid];?>" target="_blank">下载 </a>
	<?php else:?>
	无
	<?php endif;?>
</dd>
</dl>