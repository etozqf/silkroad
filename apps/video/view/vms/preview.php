<!DOCTYPE HTML>
<html>
<head>
    <title><?=$head['title']?></title>
    <style>
        body,div{margin:0;padding:0;border:0;}
        #ctvideo{width:530px;height:450px;overflow:hidden;}
        #ctvideo img {max-width: 100%;max-height: 100%;}
    </style>
</head>
<body>
<?php if (!empty($vid)):?>
<div id="ctvideo"><script type="text/javascript" src="<?=$playerurl?>?w=530&h=450&vid=<?=$vid?>"></script></div>
<?php elseif (!empty($id)):?>
<div id="ctvideo"><script type="text/javascript" src="<?=$playerurl?>?w=600&amp;h=480&amp;id=<?=$id?>&&amp;manual=0&amp;autoStart=false"></script></div>
<?php endif;?>
</body>
</html>