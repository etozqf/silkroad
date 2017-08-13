<div class="section_table">
    <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="table_list">
        <thead>
        <tr>
            <th width="30" class="t_l bdr_3">
                <input type="checkbox" id="check_all" class="checkbox_style"/>
            </th>
            <th width="30">ID</th>
            <th>区块名称</th>
            <th width="80">管理操作</th>
        </tr>
        </thead>
        <tbody id="list_body">
        </tbody>
    </table>
</div>

<script type="text/javascript">
    var tableApp;
    (function() {
        var pageid = <?=intval($pageid)?>;
        var pageurl = '<?=$page['url']?>';

        var row_template = '<tr id="row_{sectionid}" sectionid="{sectionid}">\
    <td width="30"><input type="checkbox" class="checkbox_style" id="chk_row_{sectionid}" value="{sectionid}" /></td>\
 	<td width="30">{sectionid}</td>\
	<td><span class="section-item {type}">{name}</span></td>\
	<td width="80" class="t_c">\
	    <?php if ($haspriv && priv::aca('page', 'section', 'restore')): ?><img src="images/recover.gif" func="app.restoreSection" title="恢复" alt="恢复" width="16" height="16" class="hand restore"/> &nbsp;<?php endif; ?>\
        <?php if ($haspriv && priv::aca('page', 'section', 'delete')): ?><img src="images/delete.gif" func="app.deleteSection" title="彻底删除" alt="彻底删除" width="16" height="16" class="hand"/> &nbsp;<?php endif; ?>\
    </td>\
</tr>';

        var filterForm = $('#section-filter'),
            filterTypes = filterForm.find('[data-role=type] a'),
            filterType = $(filterForm[0].type);
        filterTypes.click(function() {
            var self = $(this);
            filterTypes.removeClass('checked');
            self.addClass('checked');
            filterType.val(self.attr('type'));
            tableApp.load(filterForm);
            return false;
        });

        tableApp = new ct.table('#item_list', {
            rowIdPrefix : 'row_',
            rowCallback : function(id, tr){
                tr.find('[func]').each(function(i, item) {
                    var self = $(item),
                        func = ct.func(self.attr('func'));
                    self.click(function() {
                        func && func(id);
                        return false;
                    });
                });
            },
            template : row_template,
            baseUrl  : '?app=page&controller=page&action=sections&pageid=<?=$pageid?>&status=<?=$status?>'
        });
        tableApp.load();

        app.init({
            pageid:pageid,
            pageurl:pageurl
        });
    })();
</script>