<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/base.css" />

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/tablesorter/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.tablesorter.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/contextMenu/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.contextMenu.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/pagination/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>

<!--selectree-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>

<script src="<?=IMG_URL?>js/lib/cmstop.tree.js" type="text/javascript"></script>
<link href="<?=IMG_URL?>js/lib/tree/style.css" rel="stylesheet" type="text/css"/>

<script src="<?=IMG_URL?>js/lib/cmstop.suggest.js" type="text/javascript"></script>
<link href="<?=IMG_URL?>js/lib/suggest/style.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="apps/mobile/js/lib/uploader.js"></script>

<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="bk_8"></div>
<div class="mar_l_8 mar_r_8" style="width: 900px;">
    <div class="list-header">
        <input type="button" class="button_style_4" value="添加" onclick="MobileClassIfy.add();" />
    </div>
    <div class="bk_5"></div>
    <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list">
        <thead>
        <tr>
            <th width="30" class="t_l bdr_3"><input type="checkbox" id="check_all" class="checkbox_style"/></th>
            <th width="50">排序</th>
            <th>分类名称</th>
            <th width="70">所属模型</th>
            <th width="60">状态</th>
            <th width="120" class="t_c">管理操作</th>
        </tr>
        </thead>
        <tbody id="list_body"></tbody>
    </table>
    <div class="table_foot">
        <div id="pagination" class="pagination f_r"></div>
        <div class="f_r">
            共有 <span id="pagetotal">0</span> 条记录&nbsp;&nbsp;
        </div>
        <p class="f_l">
            <input type="button" onclick="tableApp.reload()" value="刷新" class="button_style_1"/>
            <input type="button" onclick="MobileClassIfy.del();" value="删除" class="button_style_1"/>
        </p>
    </div>
</div>

<!--右键菜单-->
<ul id="right_menu_0" class="contextMenu">
    <li class="edit"><a href="#MobileClassIfy.edit">编辑</a></li>
    <li class="disable"><a href="#MobileClassIfy.disable">禁用</a></li>
    <li class="delete separator"><a href="#MobileClassIfy.del">删除</a></li>
</ul>
<ul id="right_menu_1" class="contextMenu">
    <li class="edit"><a href="#MobileClassIfy.edit">编辑</a></li>
    <li class="disable"><a href="#MobileClassIfy.enable">启用</a></li>
    <li class="delete separator"><a href="#MobileClassIfy.del">删除</a></li>
</ul>

<script type="text/javascript">
    $(function() {
        //Classify object
        window.MobileClassIfy = {
            add:function() {
                ct.formDialog({
                    title: '添加分类',
                    width: 500
                }, '?app=mobile&controller=setting&action=classify_add', function(json) {
                    if (json && json.state) {
                        tableApp.load();
                        return true;
                    }
                    ct.error(json.error);
                });
                return false;
            },
            edit:function(classifyid) {
                ct.formDialog({
                    title: '编辑分类',
                    width: 500
                }, '?app=mobile&controller=setting&action=classify_edit&classifyid=' + classifyid, function(json) {
                    if (json && json.state) {
                        tableApp.reload();
                        return true;
                    }
                    ct.error(json.error);
                });
                return false;
            },
            del:function(id) {
                var length = 1;
                if (!id) {
                    id = tableApp.checkedIds();
                    length = id.length;
                    id = id.join(',');
                }
                if (!id) {
                    ct.error('请选择要删除的记录');
                    return false;
                }
                ct.confirm('操作不可撤销，确定要删除选择的 <span style="color:red;">' + length + '</span> 条记录吗？', function() {
                    $.getJSON('?app=mobile&controller=setting&action=classify_delete&classifyid=' + id, function(json) {
                        if (json && json.state) {
                            ct.ok('删除成功');
                            tableApp.reload();
                        } else {
                            ct.error(json && json.error || '删除失败');
                        }
                    });
                });
                return false;
            },
            enable:function(id) {
                $.getJSON('?app=mobile&controller=setting&action=classify_enable&classifyid=' + id, function(json) {
                    if (json && json.state) {
                        ct.ok('启用成功');
                        tableApp.reload();
                    } else {
                        ct.error(json && json.error || '启用失败');
                    }
                });
            },
            disable:function(id) {
                $.getJSON('?app=mobile&controller=setting&action=classify_disable&classifyid=' + id, function(json) {
                    if (json && json.state) {
                        ct.ok('禁用成功');
                        tableApp.reload();
                    } else {
                        ct.error(json && json.error || '禁用失败');
                    }
                });
            }
        };

        var row_template = [
            '<tr id="row_{classifyid}" right_menu_id="right_menu_{disabled}" data-catid="{classifyid}" data-disabled="{disabled}">',
            '<td width="30"><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_{classifyid}" value="{classifyid}" /></td>',
            '<td width="50" class="t_c" title="">{sort}</td>',
            '<td>{classname}</td>',
            '<td width="70" class="t_c" data-headline="{modelid}">{model}</td>',
            '<td width="60" class="t_c">{disabled_name}</td>',
            '<td width="120" class="t_c">',
            '<img src="images/edit.gif" alt="编辑" title="编辑" width="16" height="16" class="hand edit"/> &nbsp;',
            '<img src="images/disable.png" alt="禁用" title="禁用" width="16" height="16" class="hand disable"/><img src="images/enable.png" alt="启用" title="启用" width="16" height="16" class="hand enable"/> &nbsp;',
            '<img src="images/delete.gif" alt="删除" title="删除" width="16" height="16" class="hand delete"/> &nbsp;',
            '</td>',
            '</tr>'
        ].join('');

        window.tableApp = new ct.table('#item_list', {
            baseUrl: '?app=mobile&controller=setting&action=classify_page',
            rowIdPrefix: 'row_',
            pageField: 'page',
            pageSize: 15,
            dblclickHandler: MobileClassIfy.edit,
            rowCallback: function(id, tr) {

                tr.find('img.edit').click(function() {
                    MobileClassIfy.edit(id);
                    return false;
                });
                tr.find('img.delete').click(function() {
                    MobileClassIfy.del(id);
                    return false;
                });

                var disabled = parseInt(tr.attr('data-disabled'));
                if (disabled) {
                    tr.find('img.disable').hide();
                    tr.find('img.enable').show().click(function() {
                        MobileClassIfy.enable(id);
                        return false;
                    });
                } else {
                    tr.find('img.enable').hide();
                    tr.find('img.disable').show().click(function() {
                        MobileClassIfy.disable(id);
                        return false;
                    });
                }
            },
            jsonLoaded: function (json) {
                $('#pagetotal').html(json.total);
            },
            template: row_template
        });
        tableApp.load();
    });
</script>
