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
    <button id="channel-add" class="mar_l_8 f_l button_style_2">添加</button>
    <div class="search_icon search f_r">
        <form id="submit-form">
            <input type="text" name="name" value="" size="15" placeholder="标题">
            <a href="javascript:;">搜索</a>
        </form>
    </div>
</div>
<div class="bk_8"></div>
<div class="list-outter" style="position: relative;">
    <table id="list-table" width="96%" cellpadding="0" cellspacing="0" class="table_list mar_l_8">
        <thead>
            <tr>
                <th width="30">
                    <div class="move_cursor"></div>
                </th>
                <th width="30%">频道名称</th>
                <th width="30%">创建时间</th>
                <th>管理操作</th>
            </tr>
        </thead>
        <tbody class="ui-sortable"></tbody>
    </table>
    <div class="table_foot">
        <button id="channel-list-refresh" class="button_style_1 f_l">刷新</button>
        <div id="pagination" class="mar_l_8 pagination f_r"></div>
    </div>
</div>


<script id="table-template" type="text/template">
    <tr id="row-{id}" data-id="{id}" data-sort="{sorttime}">
        <td width="30" class="t_c" style="cursor: move;">{id}</td>
        <td width="30%" class="t_c">{name}</td>
        <td width="30%" class="channel-created t_c">{created}</td>
        <td width="30%" class="t_c">
            <a class="channel-edit" href="javascript:;">编辑</a>
            &nbsp;
            <a class="channel-delete" href="javascript:;">删除</a>
        </td>
    </tr>
</script>
<script type="text/javascript" src="apps/mobile/js/live/channel.js"></script>