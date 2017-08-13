<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/base.css" />

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/tablesorter/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.tablesorter.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/contextMenu/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.contextMenu.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/pagination/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="apps/mobile/js/lib/uploader.js"></script>

<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<style type="text/css">
    .table_list td {
        padding: 5px;
        line-height: 22px;
        vertical-align: top;
    }
</style>

<?php $app_config = app_config('mobile', 'app'); ?>

<div class="bk_8"></div>
<div class="mar_l_8 mar_r_8" style="width: 900px;">
    <div class="list-header">
        <input type="button" class="button_style_4" value="添加" onclick="MobileMoreApp.add();" />
    </div>
    <div class="bk_5"></div>
    <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list">
        <thead>
        <tr>
            <th width="30" class="t_l bdr_3"><input type="checkbox" id="check_all" class="checkbox_style"/></th>
            <th width="50">排序</th>
            <th width="150">应用名称</th>
            <th width="100">应用图标</th>
            <th>应用简介</th>
            <th width="80" class="t_c">管理</th>
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
            <input type="button" onclick="MobileMoreApp.del();" value="删除" class="button_style_1"/>
        </p>
    </div>
</div>

<!--右键菜单-->
<ul id="right_menu" class="contextMenu">
    <li class="edit"><a href="#MobileMoreApp.edit">编辑</a></li>
    <li class="delete separator"><a href="#MobileMoreApp.del">删除</a></li>
</ul>

<script type="text/javascript" src="apps/mobile/js/lib/sortable.js"></script>
<script type="text/javascript">
    $(function() {
        function formReady(form, dialog) {
            var iconurl = form.find('[name=iconurl]').floatImg({
                url: UPLOAD_URL,
                width: '<?=$app_config['icon']['width']?>',
                heihgt: '<?=$app_config['icon']['height']?>'
            });
            dialog.find('#upload').mobileUploader(function(json) {
                iconurl.val(json.file);
            });
        }

        window.MobileMoreApp = {
            add:function() {
                ct.formDialog({
                    title: '添加应用',
                    width: 460
                }, '?app=mobile&controller=setting&action=moreapp_add', function(json) {
                    if (json && json.state) {
                        tableApp.load();
                        return true;
                    }
                }, formReady);
                return false;
            },
            edit:function(id) {
                ct.formDialog({
                    title: '添加应用',
                    width: 460
                }, '?app=mobile&controller=setting&action=moreapp_edit&appid=' + id, function(json) {
                    if (json && json.state) {
                        tableApp.load();
                        return true;
                    }
                }, formReady);
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
                    $.getJSON('?app=mobile&controller=setting&action=moreapp_delete&appid=' + id, function(json) {
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
            sort:function(oldIndex, newIndex) {
                $.post("?app=mobile&controller=setting&action=app_updown", {
                    appid: this.attr('data-appid'),
                    old: oldIndex,
                    now: newIndex
                });
            }
        };

        var row_template = [
            '<tr id="row_{appid}" data-appid="{appid}">',
                '<td width="30"><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_{appid}" value="{appid}" /></td>',
                '<td width="50" class="t_c" style="cursor: move;" title="拖动以排序">{sort}</td>',
                '<td width="150">{name}</td>',
                '<td width="100" class="t_c">{iconurl_src}</td>',
                '<td>{description_short}</td>',
                '<td width="60" class="t_c">',
                    '<img src="images/edit.gif" alt="编辑" title="编辑" width="16" height="16" class="hand edit"/> &nbsp;',
                    '<img src="images/delete.gif" alt="删除" title="删除" width="16" height="16" class="hand delete"/> &nbsp;',
                '</td>',
            '</tr>'
        ].join('');
        window.tableApp = new ct.table('#item_list', {
            baseUrl: '?app=mobile&controller=setting&action=moreapp_page',
            rowIdPrefix: 'row_',
            pageField: 'page',
            pageSize: 15,
            dblclickHandler: 'MobileMoreApp.edit',
            rowCallback: function(id, tr) {
                var td = tr.find('td:eq(3)'), src = td.text(), img;
                if (src) {
                    img = $('<a href="javascript:;" data-image="<img src=\''+src+'\' />">查看</a>').appendTo(td.empty());
                    img.attrTips('data-image');
                } else {
                    td.text('');
                }

                tr.find('img.edit').click(function() {
                    MobileMoreApp.edit(id);
                    return false;
                });
                tr.find('img.delete').click(function() {
                    MobileMoreApp.del(id);
                    return false;
                });
            },
            jsonLoaded: function (json) {
                $('#pagetotal').html(json.total);
            },
            template: row_template
        });
        tableApp.load();
        TableSortable($('#item_list'), 1, 1, 4, MobileMoreApp.sort);
    });
</script>