<style type="text/css">
.main { padding: 6px 16px 0;}
.textarea { width: 220px; height: 110px; min-height: 110px; border: 1px solid #aaa; resize: vertical;}
.toolbar { display: none!important;}
</style>
<div class="main">
	<div class="title">请填写退稿理由：</div>
	<div>
		<form action="?app=contribution&controller=index&action=reject" method="post">
			<input type="hidden" name="contributionid" value="" />
			<textarea id="annotation" name="annotation" class="textarea"></textarea>
		</form>
	</div>
</div>
<script type="text/javascript">
	var rejectFormReady = function(){
		cmstop.fet(IMG_URL + 'js/lib/rte/style.css');
		cmstop.fet(IMG_URL + 'js/lib/cmstop.rte.js', function() {
			window.editor = new $.Rte($('#annotation'), {
				disable: ['italic', 'fontsize', 'fontname', 'forecolor', 'insertunorderedlist', 'insertorderedlist', 'justify', 'createlink', 'insertimage', 'html', 'bold', 'under']
			});
		});
	};
</script>