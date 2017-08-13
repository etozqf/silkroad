<?php $this->display('header', 'system'); ?>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.template.js"></script>
<script type="text/javascript" src="apps/page/js/section.js"></script>

<form name="page_priv" id="page_priv" method="POST" action="?app=page&controller=page_priv&action=add">
    <input type="hidden" name="pageid" value="<?=$pageid?>"/>
    <table style="margin:8px">
        <tr>
            <td>用户名：<input type="text" name="username" size="20"/></td>
            <td><input type="submit" name="submit" value="添加" class="button_style_1"/></td>
        </tr>
    </table>
</form>
<table id="item_list" width="330" cellpadding="0" cellspacing="0" class="table_list mar_l_8">
    <thead>
    <tr>
        <th class="bdr_3">用户名</th>
        <th>角色</th>
        <th width="40">删除</th>
    </tr>
    </thead>
    <tbody id="list_body">
    </tbody>
</table>

<textarea style="display:none;" id="tpl-row">
    <?=script_template('<tr id="row_{%userid%}">
        <td><a href="javascript: url.member({%userid%});">{%username%}</a></td>
        <td>{%rolename%}</td>
        <td class="t_c"><img src="images/delete.gif" alt="删除" width="16" height="16" class="hand delete" /></td>
    </tr>')?>
</textarea>

<script type="text/javascript">
    (function () {
        var form = $('#page_priv'),
            input = $('input[name=username]'),
            tbody = $('#list_body'),
            rowTpl = new Template($('#tpl-row').val());

        function add(json) {
            var row = $(rowTpl.render(json));
            row.find('.delete').click(function() {
                del(json.pageid, json.userid);
            });
            tbody.append(row);
        }

        function del(pageid, userid) {
            $.getJSON('?app=page&controller=page_priv&action=delete&pageid=' + pageid + '&userid=' + userid, function(json) {
                if (json.state) {
                    $('#row_' + userid).remove();
                } else {
                    ct.error(json.error || '设置失败');
                }
            });
        }

        form.submit(function () {
            form.ajaxSubmit({
                success: function (json) {
                    if (json.state) {
                        $('input[name="username"]').val('');
                        add(json);
                    } else {
                        ct.error(json.error || '添加失败');
                    }
                },
                dataType: 'json'
            });
            return false;
        });

        $.each(<?=json_encode((array) $data)?>, function(i, item) {
            add(item);
        });
    })();
</script>
<?php $this->display('footer', 'system'); ?>