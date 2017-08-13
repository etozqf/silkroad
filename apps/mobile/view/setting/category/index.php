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

<?php $category_config = app_config('mobile', 'category'); ?>

<div class="bk_8"></div>
<div class="mar_l_8 mar_r_8" style="width: 900px;">
    <div class="list-header">
        <span class="ui-shortips">当前频道版本：<?=setting('mobile', 'category_version') ? setting('mobile', 'category_version') : 1?></span>
        <input type="button" class="button_style_4" value="添加" onclick="MobileCategory.add();" />
    </div>
    <div class="bk_5"></div>
    <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list">
        <thead>
        <tr>
            <th width="30" class="t_l bdr_3"><input type="checkbox" id="check_all" class="checkbox_style"/></th>
            <th width="50">排序</th>
            <th>频道名称</th>
            <th width="100">图标</th>
            <th width="70">头条频道</th>
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
            <input type="button" onclick="MobileCategory.del();" value="删除" class="button_style_1"/>
        </p>
    </div>
</div>

<!--右键菜单-->
<ul id="right_menu_0" class="contextMenu">
    <li class="edit"><a href="#MobileCategory.edit">编辑</a></li>
    <li class="edit"><a href="#MobileCategory.priv">权限</a></li>
    <li class="disable"><a href="#MobileCategory.disable">禁用</a></li>
    <li class="delete separator"><a href="#MobileCategory.del">删除</a></li>
</ul>
<ul id="right_menu_1" class="contextMenu">
    <li class="edit"><a href="#MobileCategory.edit">编辑</a></li>
    <li class="edit"><a href="#MobileCategory.priv">权限</a></li>
    <li class="disable"><a href="#MobileCategory.enable">启用</a></li>
    <li class="delete separator"><a href="#MobileCategory.del">删除</a></li>
</ul>

<script type="text/javascript" src="apps/mobile/js/lib/sortable.js"></script>
<script type="text/javascript">
$(function() {
    function formReady(form, dialog) {
        var iconurl = form.find('[name=iconurl]').floatImg({
            url: UPLOAD_URL,
            width: '<?=$category_config['icon']['width']?>',
            heihgt: '<?=$category_config['icon']['height']?>'
        });
        dialog.find('#upload').mobileUploader(function(json) {
            iconurl.val(json.file);
        });
        dialog.find('[name=catid_bind]').selectree();
    }
    window.MobileCategory = {
        add:function() {
            ct.formDialog({
                title: '添加频道',
                width: 500
            }, '?app=mobile&controller=setting&action=category_add', function(json) {
                if (json && json.state) {
                    tableApp.load();
                    return true;
                }
            }, formReady);
            return false;
        },
        edit:function(catid) {
            ct.formDialog({
                title: '编辑频道',
                width: 500
            }, '?app=mobile&controller=setting&action=category_edit&catid=' + catid, function(json) {
                if (json && json.state) {
                    tableApp.reload();
                    return true;
                }
            }, formReady);
            return false;
        },
        priv:function(catid) {
            ct.iframe({
                url: '?app=mobile&controller=setting&action=category_priv&catid='+catid,
                width: 350,
                height: 300
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
                $.getJSON('?app=mobile&controller=setting&action=category_delete&catid=' + id, function(json) {
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
            $.getJSON('?app=mobile&controller=setting&action=category_enable&catid=' + id, function(json) {
                if (json && json.state) {
                    ct.ok('启用成功');
                    tableApp.reload();
                } else {
                    ct.error(json && json.error || '启用失败');
                }
            });
        },
        disable:function(id) {
            $.getJSON('?app=mobile&controller=setting&action=category_disable&catid=' + id, function(json) {
                if (json && json.state) {
                    ct.ok('禁用成功');
                    tableApp.reload();
                } else {
                    ct.error(json && json.error || '禁用失败');
                }
            });
        },
        sort:function(oldIndex, newIndex) {
            $.post("?app=mobile&controller=setting&action=category_updown", {
                catid: this.attr('data-catid'),
                old: oldIndex,
                now: newIndex
            });
        }
    };

    var row_template = [
        '<tr id="row_{catid}" right_menu_id="right_menu_{disabled}" data-catid="{catid}" data-disabled="{disabled}">',
            '<td width="30"><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_{catid}" value="{catid}" /></td>',
            '<td width="50" class="t_c" style="cursor: move;" title="拖动以排序">{sort}</td>',
            '<td>{catname}</td>',
            '<td width="100" class="t_c">{iconurl_src}</td>',
            '<td width="70" class="t_c" data-headline="{headline}">{headline_name}</td>',
/*          '<td width="120" class="t_c">{default_display_name}</td>', */
            '<td width="60" class="t_c">{disabled_name}</td>',
            '<td width="120" class="t_c">',
                '<img src="images/edit.gif" alt="编辑" title="编辑" width="16" height="16" class="hand edit"/> &nbsp;',
                '<img src="images/priv.png" alt="权限" title="权限" width="16" height="16" class="hand priv"/> &nbsp;',
                '<img src="images/disable.png" alt="禁用" title="禁用" width="16" height="16" class="hand disable"/><img src="images/enable.png" alt="启用" title="启用" width="16" height="16" class="hand enable"/> &nbsp;',
                '<img src="images/delete.gif" alt="删除" title="删除" width="16" height="16" class="hand delete"/> &nbsp;',
            '</td>',
        '</tr>'
    ].join('');
    window.tableApp = new ct.table('#item_list', {
        baseUrl: '?app=mobile&controller=setting&action=category_page',
        rowIdPrefix: 'row_',
        pageField: 'page',
        pageSize: 15,
        dblclickHandler: MobileCategory.edit,
        rowCallback: function(id, tr) {
            var td = tr.find('td:eq(3)'), src = td.text(), img;
            if (src) {
                img = $('<a href="javascript:;" data-image="<img src=\''+src+'\' />">查看</a>').appendTo(td.empty());
                img.attrTips('data-image');
            } else {
                td.text('');
            }

            tr.find('img.edit').click(function() {
                MobileCategory.edit(id);
                return false;
            });
            tr.find('img.priv').click(function() {
                MobileCategory.priv(id);
                return false;
            });
            tr.find('img.delete').click(function() {
                MobileCategory.del(id);
                return false;
            });

            var disabled = parseInt(tr.attr('data-disabled'));
            if (disabled) {
                tr.find('img.disable').hide();
                tr.find('img.enable').show().click(function() {
                    MobileCategory.enable(id);
                    return false;
                });
            } else {
                tr.find('img.enable').hide();
                tr.find('img.disable').show().click(function() {
                    MobileCategory.disable(id);
                    return false;
                });
            }
            var headlineTd = tr.find('[data-headline]');
            if (!(headlineTd.attr('data-headline')>>0)) {
                var a = $('<a href="javascript:;" title="设置为头条">(设为)</a>');
                a.bind('click', {
                    id: id
                }, function(event) {
                    $.post('?app=mobile&controller=setting&action=category_set_headline', {
                        catid: event.data.id
                    }, function(req) {
                        if (!req.state) {
                            return ct.error(req.error || '设置失败');
                        }
                        tableApp.reload();
                    }, 'json');
                }).appendTo(headlineTd);
            }
        },
        jsonLoaded: function (json) {
            $('#pagetotal').html(json.total);
        },
        template: row_template
    });
    tableApp.load();
    TableSortable($('#item_list'), 1, 1, 2, MobileCategory.sort);
});
</script>