<?php $this->display('header', 'system');?>
<!--tree table-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/treetable/style.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.treetable.js"></script>
<!--contextmenu-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.contextMenu.js"></script>

<script type="text/javascript" src="apps/page/js/index.js"></script>
<style type="text/css">
    .table_list img {
        margin: 0 3px;
    }
</style>

<?php $this->display("page/manage/$status"); ?>
</body>
</html>