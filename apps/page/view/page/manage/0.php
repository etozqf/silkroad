<style type="text/css">
    tr.row_highlight td {
        background-color: #FFFDDD !important;
    }
</style>

<div class="bk_8"></div>
<div class="tag_1">
    <ul class="tag_list">
        <?php if (priv::aca('page', 'page', 'index')): ?>
        <li><a href="?app=page&controller=page&action=index">维护模式</a></li>
        <?php endif; ?>
        <li class="active"><a href="javascript:void(0);">管理模式</a></li>
    </ul>
</div>

<dl class="proids" style="margin:0 8px; border:0;">
    <dt>状态：</dt>
    <dd>
        <a href="?app=page&controller=page&action=manage&status=1">已发布</a>
        <a class="checked" href="?app=page&controller=page&action=manage&status=0">已删除</a>
        <span class="c_red">已删除的页面会被高亮并可进行管理操作</span>
    </dd>
</dl>

<div class="bk_5"></div>
<div class="mar_l_8 mar_r_8">
    <table width="100%" id="treeTable" class="table_list treeTable" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th class="t_c bdr_3">页面名称</th>
            <th class="t_c" width="70">管理操作</th>
            <th class="t_c" width="130">最近更新</th>
            <th class="t_c" width="130">下次更新</th>
            <th class="t_c" width="60">更新频率</th>
            <th class="t_c" width="60">页面大小</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    (function(){
        var rowTemplate = '\
<tr id="row_{pageid}">\
<td><a class="edit" href="javascript:;">{name}</a></td>\
<td class="t_c">\
    <?php if (priv::aca('page', 'page', 'restore')): ?><img class="hand" func="app.restore" height="16" width="16" src="images/recover.gif" title="还原页面" alt="还原页面" /><?php endif; ?>\
    <?php if (priv::aca('page', 'page', 'delete')): ?><img class="hand" func="app.del" height="16" width="16" src="images/delete.gif" title="彻底删除" alt="彻底删除" /><?php endif; ?>\
</td>\
<td class="t_r">{published}</td>\
<td class="t_r">{nextpublish}</td>\
<td class="t_r">{frequency}秒</td>\
<td class="t_r">{size}</td>\
</tr>';
        app.init({
            initTree:true,
            baseUrl: '?app=page&controller=page&action=tree',
            rowTemplate: rowTemplate,
            rowReady: function(id, tr, json) {
                if (json.status == 0) {
                    tr.addClass('row_highlight');
                } else {
                    tr.find('img').remove();
                }
            },
            edit: function(id, tr, json) {
            }
        });
    })();
</script>