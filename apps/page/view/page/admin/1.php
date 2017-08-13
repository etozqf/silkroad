<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="tiny_mce/editor.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/editplus/style.css" />
<script src="<?=IMG_URL?>js/lib/cmstop.editplus.js" type="text/javascript"></script>
<script src="js/cmstop.editplus_plugin.js" type="text/javascript"></script>

<style type="text/css">
    .section-item-output {
        background: url(css/images/bg.gif) no-repeat scroll transparent;
        width: 16px;
        display: inline-block;
        zoom: 1;
        height: 24px;
        line-height: 24px;
    }
    .section-item-output.json {
        background-position: -136px -1271px;
    }
    .section-item-output.xml {
        background-position: -136px -1328px;
    }
</style>

<div class="section_table">
    <div class="section_action">
        <?php if ($haspriv && priv::aca('page', 'section', 'add')): ?>
        <button class="button_style_4" onclick="app.addSection(<?=$pageid?>);">添加区块</button>
        <?php endif; ?>
        <?php if ($haspriv && priv::aca('page', 'section', 'publish')): ?>
        <button class="button_style_4" onclick="app.publishAllSections(<?=$pageid?>)">生成全部</button>
        <?php endif; ?>
    </div>
    <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="table_list">
        <thead>
        <tr>
            <th width="30" class="t_l bdr_3">
                <input type="checkbox" id="check_all" class="checkbox_style"/>
            </th>
            <th width="30">ID</th>
            <th>区块名称</th>
            <th width="150">管理操作</th>
            <th width="100">调用代码</th>
            <th width="120">区块编辑</th>
            <th width="120">最近更新</th>
            <th width="120">下次更新</th>
        </tr>
        </thead>
        <tbody id="list_body">
        </tbody>
    </table>
    <div class="table_foot">
        <p class="f_l">
            <?php if ($haspriv && priv::aca('page', 'section', 'fill_data')): ?>
            <input type="button" onclick="app.fillSectionData()" value="填充测试数据" class="button_style_1" title="填充测试数据（仅支持手动和推送区块）"/>
            <?php endif; ?>
        </p>
    </div>
</div>

<ul id="menu-popset" class="contextMenu">
    <?php if ($haspriv && priv::aca('page', 'section', 'publish')): ?><li class="publish"><a href="#app.publishSection">发布</a></li><?php endif; ?>
    <?php if ($haspriv && priv::aca('page', 'section_priv')): ?><li class="priv"><a href="#app.sectionPriv">权限</a></li><?php endif; ?>
    <?php if ($haspriv && priv::aca('page', 'section', 'property')): ?><li class="edit"><a href="#app.sectionProperty">编辑</a></li><?php endif; ?>
    <?php if ($haspriv && priv::aca('page', 'section', 'preview')): ?><li class="preview"><a href="#app.jumpToPreviewSection">预览</a></li><?php endif; ?>
    <?php if ($haspriv && priv::aca('page', 'page', 'view')): ?><li class="setting"><a href="#app.jumpToEditSection">维护</a></li><?php endif; ?>
    <?php if ($haspriv && priv::aca('page', 'section', 'copy')): ?><li class="copy"><a href="#app.copySection">克隆</a></li><?php endif; ?>
    <?php if ($haspriv && priv::aca('page', 'section', 'move')): ?><li class="move"><a href="#app.moveSection">移动</a></li><?php endif; ?>
    <?php if ($haspriv && priv::aca('page', 'section', 'change_type')): ?><li class="change_type"><a href="#app.changeSectionType">类型转换</a></li><?php endif; ?>
    <?php if ($haspriv && priv::aca('page', 'section', 'remove')): ?><li class="delete"><a href="#app.removeSection">删除</a></li><?php endif; ?>
    <?php if ($haspriv && priv::aca('page', 'section', 'fill_data')): ?><li class="fill_data"><a href="#app.fillSectionData">填充数据</a></li><?php endif; ?>
</ul>

<script type="text/javascript">
var searchSection	= $('#searchSection');
$(document).ready(function() {
    searchSection.bind('keydown',function(event) {
        if (event.keyCode == 13) {
            search();
        }
    });
});
function search(){
    var sectionList	= $('#list_body');
    var id	= searchSection.val();
    var state = false;
    $.each(sectionList.find('>tr'),function(k, v) {
        if (id == $(v).attr('sectionid')) {
            state = true;
            app.highlightSection(id);
            return false;
        }
    });
    if (state) return;
    $.getJSON('?app=page&controller=section&action=view&sectionid='+id, null, function(json) {
        if (json.state) {
            ct.assoc.open('?app=page&controller=page&action=admin&pageid='+json.pageid+'#sectionid='+id, 'newtab');
        } else {
            ct.error(json.error);
        }
    });
}

var tableApp;
(function() {
var pageid = <?=intval($pageid)?>;
var pageurl = '<?=$page['url']?>';

var matchedSectionid;
if (location.hash && location.hash.indexOf('sectionid=') > -1) {
    var hashes = location.hash.substr(1).split('&');
    $.each(hashes, function(i, h) {
        h = h.split('=');
        if (h[0] == 'sectionid' && (h[1] = parseInt(h[1]))) {
            matchedSectionid = h[1];
            return false;
        }
    });
}

var row_template = '<tr id="row_{sectionid}" sectionid="{sectionid}" type="{type}">\
<td><input type="checkbox" class="checkbox_style" id="chk_row_{sectionid}" value="{sectionid}" /></td>\
<td>{sectionid}</td>\
<td><span class="section-item {type} f_l">{name}</span><span class="f_l" style="margin-left:10px;">{output_type_links}</span></td>\
<td class="t_c">\
    <?php if ($haspriv && priv::aca('page', 'section', 'publish')): ?><img src="images/refresh.gif" func="app.publishSection" title="发布" alt="发布" width="16" height="16" class="hand"/> &nbsp;<?php endif; ?>\
    <?php if ($haspriv && priv::aca('page', 'section_priv')): ?><img src="images/priv.png" func="app.sectionPriv" title="权限" alt="权限" width="16" height="16" class="hand"/> &nbsp;<?php endif; ?>\
    <?php if ($haspriv && priv::aca('page', 'section', 'property')): ?><img src="images/edit.gif" func="app.sectionProperty" title="编辑" alt="编辑" width="16" height="16" class="hand"/> &nbsp;<?php endif; ?>\
    <?php if ($haspriv && priv::aca('page', 'section', 'remove')): ?><img src="images/delete.gif" func="app.removeSection" title="删除" alt="删除" width="16" height="16" class="hand"/> &nbsp;<?php endif; ?>\
    <img src="images/popset.gif" title="更多操作" alt="更多操作" width="22" height="16" class="hand popset"/> &nbsp;\
</td>\
<td><input type="text" value="{ssi_code}" readonly size="15" style="width:90px;" /></td>\
<td>{editors}</td>\
<td>{published}</td>\
<td>{nextupdate}</td>\
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

var appInited;
tableApp = new ct.table('#item_list', {
    rowIdPrefix : 'row_',
    rowCallback : function(id, tr){
        tr.find('[func]').each(function(i, item) {
            var self = $(item),
                func = ct.func(self.attr('func'));
            self.click(function() {
                func && func(id, tr);
                return false;
            });
        });
        tr.find('.popset').contextMenu('#menu-popset', function(action) {
            (ct.func(action) || function() {})(id, tr);
        }).click(function(ev) {
            $(this).trigger('contextMenu', [ev]);
        });
        tr.find(':text').mouseover(function() {
            this.select();
        });
        if (matchedSectionid) {
            app.highlightSection(matchedSectionid);
            matchedSectionid = null;
        }
    },
    jsonLoaded : function(json) {
        if (!appInited) {
            var allSections = {}, allSectionIds = [];
            $.each(json.data, function(i, n) {
                allSections[n.sectionid] = n.name;
                allSectionIds.push(n.sectionid);
            });
            app.init({
                pageid:pageid,
                pageurl:pageurl,
                allSections:allSections,
                allSectionIds:allSectionIds
            });
        }
    },
    dblclickHandler : app.sectionProperty,
    template : row_template,
    baseUrl  : '?app=page&controller=page&action=sections&pageid=<?=$pageid?>&status=<?=$status?>'
});
tableApp.load();
})();
</script>