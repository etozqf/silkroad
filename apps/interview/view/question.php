<?php $this->display('header');?>
<?php $workflowid = table('category', $catid, 'workflowid');?>
  <div class="bk_8"></div>
  <div class="tag_1">
   <?php $this->display('view_header');?>
  </div>
  <div>
    <div class="f_l w_80 tag_list_2" style="height:400px;">
      <ul>
        <li><a href="?app=interview&controller=question&action=index&contentid=<?=$contentid?>&state=1" <?php if ($state == 1) { ?>class="s_6"<?php }?> >待审</a></li>
        <li><a href="?app=interview&controller=question&action=index&contentid=<?=$contentid?>&state=2" <?php if ($state == 2) { ?>class="s_6"<?php }?> >已审</a></li>
        <li><a href="?app=interview&controller=question&action=index&contentid=<?=$contentid?>&state=3" <?php if ($state == 3) { ?>class="s_6"<?php }?> >推荐</a></li>
        <li><a href="?app=interview&controller=question&action=index&contentid=<?=$contentid?>&state=0" <?php if ($state == 0) { ?>class="s_6"<?php }?> >已删</a></li>
      </ul>
    </div>
    <div style="margin-left:100px;">
  <table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="table_list">
    <thead>
      <tr>
        <th width="30" class="t_l bdr_3"><input type="checkbox" id="check_all" /></th>
        <th>内容</th>
        <th width="100">操作</th>
        <th width="100">昵称</th>
        <th width="100">发布时间</th>
        <th width="150">IP</th>
      </tr>
    </thead>
    <tbody id="list_body">
    </tbody>
  </table>
  
<?php $this->display("state/$state");?>

</div>
<div class="clear"></div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css"/>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.contextMenu.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<script type="text/javascript" src="apps/interview/js/question.js"></script>
<script type="text/javascript">
var state = <?=$state?>;
var contentid = <?=$contentid?>;

var tableApp = new ct.table('#item_list', {
    rowIdPrefix : 'row_',
    rightMenuId : 'right_menu',
    pageField : 'page',
    pageSize : 15,
    dblclickHandler : state == 1 ? 'question.pass' : null,
    rowCallback     : 'init_row_event',
    template : row_template,
    baseUrl  : '?app=interview&controller=question&action=page&contentid='+contentid+'&state='+state
});

tableApp.load();
</script>
<?php $this->display('footer');
