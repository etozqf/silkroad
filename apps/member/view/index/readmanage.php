<?php $this->display('header', 'system');?>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<div class="bk_10"></div>
<div class="tag_1">
    <ul class="tag_list">
        <li><a href="?app=member&controller=index&action=profile&userid=<?=$member['userid']?>">用户资料</a></li>
        <li class="s_3"><a href="?app=member&controller=index&action=readmanage&userid=<?=$member['userid']?>">阅读管理</a></li>
        <?php if($member['groupid'] == 1) { ?>
            <li><a href="?app=system&controller=administrator&action=stat&userid=<?=$member['userid']?>">工作报表</a></li>
            <li><a href="?app=system&controller=score&action=view&userid=<?=$member['userid']?>">评分记录</a></li>
            <li><a href="?app=system&controller=administrator&action=priv&userid=<?=$member['userid']?>">权限</a></li>
        <?php } ?>
    </ul>
    <input type="button" value="修改资料" class="button_style_1" onclick="member.edit(<?=$member['userid']?>)"/>
    <input type="button" value="修改密码" class="button_style_1" onclick="member.password(<?=$member['userid']?>)"/>
    <input type="button" value="修改头像" class="button_style_1" onclick="member.avatar(<?=$member['userid']?>)"/>
    <input type="button" value="删除" class="button_style_1" onclick="member.del(<?=$member['userid']?>);"/>
    <input name="back" type="button" value="返回" class="button_style_1" onclick="javascript:history.go(-1);"/>
</div>
<div>
    <dl class="proids">
        <dt>类型 ：</dt>
        <dd>
            <?php foreach($typenames as $id_type=>$typename):?>
            <a href="/?app=member&controller=index&action=readmanage&idtype=<?=$id_type?>&userid=<?=$member['userid']?>" <?=$checked[$idtype]?> ><?=$typename?></a>
            <?php endforeach;?>
        </dd>
        <dd style="float:right; margin-right:20px;">
            <?php foreach($typenames as $id_type=>$typename):?>
            <a href="javascript:;" onclick="readmanage.add('<?=$id_type?>');">添加<?=$typename?></a>
            <?php endforeach;?>
        </dd>
    </dl>
</div>
<table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list mar_l_8">
    <thead>
    <tr>
        <th width="30" class="t_l bdr_3"><input type="checkbox" onclick="readmanage.checkall(this);" class="checkbox_style"></th>
        <th width="100">ID</th>
        <th>标题</th>
        <th width="100">类型</th>
        <th width="100">管理操作</th>
        <th width="120">创建时间</th>
    </tr>
    </thead>
    <tbody id="list_body">
    <?php if($list):?>
    <?php foreach ($list as $k=>$v):?>
        <tr class="row_chked">
            <td><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_<?=$v['mid']?>" value="<?=$v['mid']?>"></td>
            <td><?=$v['id']?></td>
            <td><a href="javascript:;" class="title_list"><?=$v['title']?></a></td>
            <td class="t_c"><?=$typenames[$v['idtype']]?></td>
            <td class="t_c"><img src="images/delete.gif" onclick="readmanage.delete('<?=$v['mid']?>');" alt="删除" title="删除" width="16" height="16" class="hand delete"></td>
            <td class="t_c"><?=date('Y-m-d H:i', $v['dateline'])?></td>
        </tr>
    <?php endforeach;?>
    <?php else: ?>
    <tr class="row_chked"><td colspan="6" class="t_c">暂无数据</td></tr>
    <?php endif; ?>
    </tbody>
</table>
<div class="table_foot">
    <div id="pagination" class="pagination f_r"></div>
    <div class="f_r">
        共&nbsp;<span id="pagetotal" class="c_red"><?=$total?></span>&nbsp;条
        <input type="text" name="pagesize" value="<?=$pagesize?>" size="2" maxlength="2" style="width:15px;" id="pagesize"> 条/页&nbsp;&nbsp;
    </div>
    <p class="f_l">
        <input type="button" onclick="readmanage.delete();" id="delete_btn" value="删除" class="button_style_1" title="删除记录">
    </p>
</div>

<script type="text/javascript">

var userid = '<?=$member['userid']?>';
$('#pagination').pagination(<?=$total?>, {
    items_per_page : <?=$pagesize?>,
    current_page : <?=$page-1?>,
    prev_text : '上一页',
    next_text : '下一页',
    prev_show_always : false,
    next_show_always : false,
    callback : function(page) {
        page++;
        window.location.href = '/?app=member&controller=index&action=readmanage&userid='+userid+'&idtype=<?=$idtype?>&page='+page;
    }
});

</script>
<script type="text/javascript" src="apps/member/js/member.js"></script>
<script type="text/javascript" src="apps/member/js/readmanage.js"></script>
<?php $this->display('footer','system');?>