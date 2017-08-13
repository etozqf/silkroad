<?php $this->display('header', 'system'); ?>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<!--pagination-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.pagination.js"></script>
<script type="text/javascript">
    var action = 'index';
</script>

<div class="bk_8"></div>
<div class="mar_l_8" style="width:96%; overflow: hidden;">
    <button id="program-add" class="mar_l_8 f_l button_style_2">添加</button>
    <div class="search_icon search f_r">
        <form id="submit-form">
            <input type="text" name="title" value="" size="15" placeholder="标题">
            <a href="javascript:;">搜索</a>
        </form>
    </div>
</div>
<div id="proids">
    <div class="proids">
        <dl>
            <dt>频道：</dt>
            <dd>
                <a href="javascript:;" data-channel="0" class="checked">全部</a>
                <?php foreach (table('mobile_live_channel') as $item):?>
                <a href="javascript:;" data-channel="<?php echo $item['id'];?>"><?php echo $item['name'];?></a>
                <?php endforeach;?>
            </dd>
        </dl>
    </div>
</div>
<div class="bk_8"></div>
<table id="list-table" width="96%" cellpadding="0" cellspacing="0" class="table_list mar_l_8">
    <thead>
        <tr>
            <th width="30"></th>
            <th width="40">ID</th>
            <th width="40%">名称</th>
            <th width="40%">流地址</th>
            <th width="50">管理操作</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<div class="table_foot">
    <button id="program-list-refresh" class="button_style_1 f_l">刷新</button>
    <button id="program-list-batch-delete" class="button_style_1 f_l mar_l_8">批量删除</button>
    <div id="pagination" class="mar_l_8 pagination f_r"></div>
</div>

<script id="table-template" type="text/template">
    <tr id="row-{id}" data-id="{id}">
        <td class="t_c"><input type="checkbox" /></td>
        <td class="t_c">{id}</td>
        <td class="t_l">{title}</td>
        <td class="t_l">{url}</td>
        <td class="t_c">
            <a class="program-edit" href="javascript:;">
                <img src="images/edit.gif" alt="编辑" title="编辑" width="16" height="16" class="hand edit" />
            </a>
            &nbsp;
            <a class="program-delete" href="javascript:;">
                <img src="images/delete.gif" alt="删除" title="删除" width="16" height="16" class="hand delete" />
            </a>
        </td>
    </tr>
</script>
<script type="text/javascript" src="apps/mobile/js/live/program.js"></script>