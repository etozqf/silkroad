<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>视频预览</title>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>
    <style>
        body,div{border:0;padding:0;margin:0;font-size: 12px;}
        body{background: #333333;}
        .playerCode {
        <?php
            if($width) echo 'width:'.$width.'px;overflow-x:hidden;';
            if($height) echo 'height:'.$height.'px;overflow-y:hidden;';
        ?>
        }
    </style>
    <script type="text/javascript">
        if(typeof contentid == 'undefined'){
            var contentid = 0;
        }
    </script>
</head>
<body>
<div class="playerCode"><?php echo $code; ?></div>
</body>
</html>