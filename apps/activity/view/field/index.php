<?php $this->display('header', 'system');?>

<link rel="stylesheet" type="text/css" href="apps/activity/css/field.css" />

<!-- list -->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.list.js"></script>

<!-- json -->
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/json2.js"></script>

<div class="bk_10"></div>
<div class="table_head">
    <button onclick="field.add()" class="button_style_4 f_l" type="button">新增字段</button>
</div>

<div class="bk_5"></div>
<table width="500" cellpadding="0" cellspacing="0" class="table_list" style="margin-left:6px;">
    <caption>字段管理</caption>
    <thead>
    <tr>
        <th width="50" class="bdr_3" style="cursor:move;" title="拖动以排序">排序</th>
        <th>字段名称</th>
        <th width="100">字段类型</th>
        <th width="80">是否启用</th>
        <th width="60">管理操作</th>
    </tr>
    </thead>
    <tbody>
        <?php if ($fields): foreach ($fields as $index => $field): ?>
        <tr class="row" data-fieldid="<?=$field['fieldid']?>">
            <td width="50" class="t_c" style="cursor:move;" title="拖动以排序"><?=($index + 1)?></td>
            <td><?=$field['label']?></td>
            <td width="100" class="t_c"><?=$field['type_name']?></td>
            <td width="80" class="t_c"><?=$field['disabled'] ? '<span class="c_red">禁用</span>' : '启用'?></td>
            <td width="60">
                <img width="16" height="16" class="hand edit" title="编辑" alt="编辑" src="images/edit.gif">
                <?php if (!$field['system']): ?>
                <img width="16" height="16" class="hand delete" title="删除" alt="删除" src="images/delete.gif">
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
            <td colspan="5">暂无字段</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<script type="text/javascript" src="apps/activity/js/field.js"></script>
<script type="text/javascript">
    $(function() {
        var table = $('.table_list');

        table.find('tr.row').each(function() {
            var row = $(this), fieldid = row.attr('data-fieldid');
            row.find('.edit').click(function() {
                field.edit(fieldid);
                return false;
            });
            row.find('.delete').click(function() {
                ct.confirm('此操作不可恢复，确定要删除该字段吗？', function() {
                    field.del(fieldid);
                });
                return false;
            });
        });

        var tbody = table.find('tbody:first');
        tbody.sortable({
            axis: 'y',
            handle: 'td:first',
            forceHelperSize: true,
            placeholder: 'tr-placeholder',
            opacity: 0.8,
            start: function(ev, ui) {
                $('<td colspan="' + ui.item.children().length + '">&nbsp;</td>').appendTo(ui.placeholder);
                ui.helper.find('td:eq(1)').width(ui.item.width() - ui.item.children().not(':eq(1)').innerWidth());
                ui.helper.css('background-color', '#FFF');
                ct.IE && ui.helper.css('margin-left', '0px');
            },
            stop: function(ev, ui) {
                var oldIndex = parseInt(ui.item.find('td:first').html()),
                    newIndex = tbody.find('>tr').index(ui.item.get(0)) + 1,
                    diff = oldIndex - newIndex,
                    fieldid = ui.item.attr('data-fieldid');
                if (diff) {
                    field.sort(fieldid, oldIndex, newIndex);
                }
                tbody.find('>tr').each(function(index, tr) {
                    $(tr).find('td:first').text(index + 1);
                });
            }
        });
    });
</script>

<?php $this->display('footer', 'system');