<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8" />
    <title><?php echo table('role', $roleid, 'name');?>的权限</title>
</head>
<style type="text/css">
body {margin-left: 8px;}
h1 { font-size: 18px;}
ul {
	margin-bottom: 10px;
}
li {
	line-height: 24px;
	padding-right: 12px;
	font-size: 14px;
	max-width: 600px;
}
span {
	display: inline-block;
}
</style>
<body>
	<h1><?php echo table('role', $roleid, 'name');?>的权限</h1>
	<article id="content"></article>
</body>
<script type="text/javascript">
(function(){
	var list = eval('(<?php echo json_encode($list, 1);?>)');
	var build = function() {

	};
	var parse = function(list, panel) {
		var key, ul = document.createElement('ul');
		panel.appendChild(ul);
		for (key in list) {
			var li = document.createElement('li');
			li.innerHTML = '<span>'+key+'</span>';
			ul.appendChild(li);
			if (typeof list[key] === 'object') {
				parse(list[key], li);
			} else {
				if (li.parentElement.parentElement.tagName.toLocaleLowerCase() != 'article') {
					li.style.display = 'inline';
				}
			}
		}
	};
	parse(list, document.getElementById('content'));
})();
</script>