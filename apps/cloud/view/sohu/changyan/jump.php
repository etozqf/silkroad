<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<iframe id="ifm" src="<?php echo $url;?>" style="visibility:hidden;"></iframe>
</body>
<script type="text/javascript">
	var ifm = document.getElementById('ifm');
	ifm.onload = function() {
		location.href = 'http://changyan.sohu.com/audit/comments/TOAUDIT/1';
	}
</script>