<?php $this->display('header', 'system');?>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<!--contextmenu-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.contextMenu.js"></script>

<!-- list -->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.list.js"></script>

<!--<div class="bk_10"></div>
<div class="table_head">
    <button onclick="engine.add()" class="button_style_4 f_l" type="button">新增挂件</button>
</div>-->

<div class="bk_5"></div>
<table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="table_list" style="margin-left:6px;">
    <caption>挂件设置</caption>
    <thead>
    <tr>
        <th width="50" class="bdr_3">排序</th>
        <th width="100">挂件种类</th>
        <th width="80">挂件描述</th>
        <th>默认位置</th>
        <th width="70">启用状态</th>
        <th width="50">操作</th>
    </tr>
    </thead>
    <tbody id="list_body">
    </tbody>
</table>

<!--右键菜单-->
<ul id="right_menu" class="contextMenu">
    <li class="edit"><a href="#engine.edit">编辑</a></li>
</ul>

<script type="text/javascript">
    function formReady(form, dialog) {
        form.find('[name=place]').selectlist();
    }
    var engine = {
        add:function(){
            ct.formDialog({title:'新增内容挂件',width:300}, '?app=addon&controller=engine&action=add', function (json){
                if (json.state) {
                    tableApp.addRow(json.data);
                    return true;
                } else {
                    return false;
                }
            }, formReady);
        },
        edit: function(engineid){
            ct.formDialog({title:'编辑内容挂件',width:300}, '?app=addon&controller=engine&action=edit&engineid='+engineid, function(json){
                if (json.state)
                {
                    tableApp.updateRow(engineid, json.data);
                    return true;
                }
                else
                {
                    return false;
                }
            }, formReady);
        }
    };
    var row_template = '<tr id="row_{engineid}" engineid="{engineid}">\
 	<td width="50" class="t_c" style="cursor: move;">{sort}</td>\
	<td width="100" class="t_l">{name}</td>\
	<td width="80" class="t_l">{description}</td>\
	<td class="t_l">{place_name}</td>\
    <td width="70" class="t_c">{disabled_name}</td>\
	<td width="50" class="t_c">\
        <img src="images/edit.gif" alt="编辑" width="16" height="16" class="hand edit"/> &nbsp;\
    </td>\
</tr>';
    var tableApp = new ct.table('#item_list', {
        rowIdPrefix : 'row_',
        rowCallback : function(id, tr){
            tr.find('img.edit').click(function(){
                engine.edit(id);
            });
        },
        dblclickHandler : engine.edit,
        template : row_template,
        baseUrl  : '?app=addon&controller=engine&action=page'
    });
    tableApp.load();
    $('#item_list').bind('update', function() {
        var tbody = $(this).find('tbody:first');
        tbody.sortable({
            axis: 'y',
            handle: 'td:first',
            forceHelperSize: true,
            placeholder: 'tr-placeholder',
            opacity: 0.8,
            start: function(ev, ui) {
                $('<td colspan="' + ui.item.children().length + '">&nbsp;</td>').appendTo(ui.placeholder);
                ui.helper.find('td:eq(3)').width(ui.item.width() - ui.item.children().not(':eq(3)').innerWidth());
                ui.helper.css('background-color', '#FFF');
                ct.IE && ui.helper.css('margin-left', '0px');
            },
            stop: function(ev, ui) {
                var oldIndex = parseInt(ui.item.find('td:first').html()),
                    newIndex = tbody.find('>tr').index(ui.item.get(0)) + 1,
                    diff = oldIndex - newIndex,
                    engineid = ui.item.attr('engineid');
                if (diff) {
                    $.post("?app=addon&controller=engine&action=updown", {
                        engineid: engineid,
                        old: oldIndex,
                        now: newIndex
                    });
                }
                tbody.find('>tr').each(function(index, tr) {
                    $(tr).find('td:first').text(index + 1);
                });
            }
        });
    });
</script>
<?php $this->display('footer', 'system');