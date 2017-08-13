<script type="text/javascript" src="apps/system/js/psn.js"></script>
<style type="text/css">
    .overlay {
        position:fixed;top:0;left:0;
        z-index:9998;
        height:100%;
        width:100%;
        background-color:black;
        opacity:0.5;
        filter:Alpha(Opacity=50);
    }
    .test-box {
        position:fixed;
        width:700px;
        height:100px;
        top:50%;
        left:50%;
        margin-left:-370px;
        margin-top:-80px;
        padding:20px;
        background-color:#fff;
        z-index:9999;
        border:1px solid #ccc;
        border-radius:5px;
        text-align:center;
        *filter:progid:DXImageTransform.Microsoft.Glow(color=#000000,strength=6);
        box-shadow:3px 3px 5px #000;
    }
    .test-box.haserror {
        height:250px;
        margin-top:-155px;
    }
    .test-box .progress-control:after {
        content: ".";
        display: block;
        height: 0;
        font-size: 0;
        clear: both;
        visibility: hidden;
    }
    .test-box .progress-control {
        width:100px;
        margin:0 auto;
    }
    .test-box .progress-control.wide {
        width:650px;
    }
    .test-box .control {
        cursor:pointer;
        font-size:16px; font-family:Arial, Verdana, "宋体";
        font-weight:bold;
        padding:14px;
        border:1px solid #C3E1F0;
        background-color:#F2F8FD;
        border-radius:5px;
        text-align:center;
        width:80px;
        height:30px;
        line-height:30px;
        float:right;
    }
    .test-box .control:hover {
        border-color:#FDBD77;
        background-color:#FFFDD7;
        color: #FF3300;
    }
    .test-box .current {
        margin:5px auto;
        text-align:left;
        font-size:14px; font-family:Arial, Verdana, "宋体";
        width:650px;
        height:20px;
        line-height:20px;
    }
    .test-box .output {
        width:640px;
        height:150px;
        margin:0 auto;
        overflow-y:auto;
        padding:5px;
        text-align:left;
        background:#000;
        display:none;
    }
    .test-box.haserror .output {
        display:block;
    }
    .test-box .output p {
        font-size:12px; font-family:Arial, Verdana, "宋体";
        color:#fff;
    }
    .test-box .output .err {
        color:red;
    }
    .test-box .output .ok {
        color:green;
    }
    .test-box .close {
        width:30px;
        height:20px;
        font-size:18px;
        line-height:20px;
        text-align:center;
        right:0;
        top:0;
        position:absolute;
        background-color:#ccc;
        cursor:pointer;
    }
    .test-box .progress {
        font-size:12px; font-family:Arial, Verdana, "宋体";
        padding:14px;
        border:1px solid #C3E1F0;
        background-color:#F2F8FD;
        border-radius:5px;
        text-align:center;
        width:500px;
        float:left;
    }
    .test-box .progress .bar {
        height:28px;
        border:1px solid #ccc;
        background:#fff;text-align:left;position:relative;
    }
    .test-box .progress .bar .percent {
        position:absolute; top:0; left:50%;
        width:80px;
        height:28px;
        margin-left:-40px;
        line-height:28px;text-align:center;
        font-family:Tahoma, Verdana, Arial; font-size:14px;
        font-weight:bold; color:#f60;
    }
    .test-box .progress .bar .indicator {
        background-color:#DDEEF1;
        height:28px;width:10%;
        border-right:1px solid #9FCCE9;
    }
</style>

<div class="bk_8"></div>
<div class="tag_1">
    <span style="float: right;">
        <?php if (priv::aca('page', 'page', 'add')): ?><button onclick="app.addRootPage()" class="button_style_4 f_l" type="button">添加页面</button><?php endif; ?>
        <?php if (priv::aca('page', 'page', 'test')): ?><button onclick="app.test()" class="button_style_4 f_l" type="button">模版检测</button><?php endif; ?>
        <?php if (priv::aca('page', 'page', 'publish')): ?><button onclick="app.publishAllPages()" class="button_style_4 f_l" type="button">生成全部</button><?php endif; ?>
        <?php if (priv::aca('page', 'page', 'logs')): ?><button onclick="app.allPageLogs()" class="button_style_4 f_l" type="button">操作记录</button><?php endif; ?>
    </span>
    <ul class="tag_list">
        <?php if (priv::aca('page', 'page', 'index')): ?>
        <li><a href="?app=page&controller=page&action=index">维护模式</a></li>
        <?php endif; ?>
        <li class="active"><a href="javascript:void(0);">管理模式</a></li>
    </ul>
    <span class="search_icon search mar_l_8">
        <input type="text" name="searchSection" value="按ID搜索区块" id="searchSection" onblur=" this.value || (this.value = '按ID搜索区块')" onfocus="this.value == '按ID搜索区块' && (this.value = '')" />
        <a href="javascript:search();">搜索</a>
    </span>
</div>

<dl class="proids" style="margin:0 8px; border:0;">
    <dt>状态：</dt>
    <dd>
        <a class="checked" href="?app=page&controller=page&action=manage&status=1">已发布</a>
        <a href="?app=page&controller=page&action=manage&status=0">已删除</a>
    </dd>
</dl>

<div class="bk_5"></div>
<div class="mar_l_8 mar_r_8">
    <table width="100%" id="treeTable" class="table_list treeTable" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th class="t_c bdr_3">页面名称</th>
            <th class="t_c" width="180">管理操作</th>
            <th class="t_c" width="120">最近更新</th>
            <th class="t_c" width="120">下次更新</th>
            <th class="t_c" width="60">更新频率</th>
            <th class="t_c" width="60">页面大小</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<ul id="right_menu" class="contextMenu">
    <li class="view"><a href="#viewPage">查看页面</a></li>
    <?php if (priv::aca('page', 'page_priv')): ?><li class="priv"><a href="#priv">权限设置</a></li><?php endif; ?>
    <?php if (priv::aca('page', 'page', 'add')): ?><li class="new"><a href="#addPage">添加子页面</a></li><?php endif; ?>
    <?php if (priv::aca('page', 'page', 'visualedit')): ?><li class="visualedit"><a href="#visualedit">可视化维护</a></li><?php endif; ?>
    <?php if (priv::aca('page', 'page', 'publish')): ?><li><a href="#publishPage">生成页面</a></li><?php endif; ?>
    <?php if (priv::aca('page', 'page', 'property')): ?><li class="edit"><a href="#pageProperty">修改页面</a></li><?php endif; ?>
    <?php if (priv::aca('page', 'page', 'remove')): ?><li class="delete"><a href="#remove">删除页面</a></li><?php endif; ?>
</ul>

<script type="text/javascript">
var searchSection	= $('#searchSection');
$(function() {
    searchSection.bind('keydown',function(event) {
        if (event.keyCode == 13) {
            search();
        }
    });
});
function search(){
    var id	= searchSection.val();
    if (!id) return false;
    $.getJSON('?app=page&controller=section&action=view&sectionid='+id, null, function(json) {
        if (json.state && json.pageid) {
            ct.assoc.open('?app=page&controller=page&action=admin&pageid='+json.pageid+'#sectionid='+id, 'newtab');
        } else {
            ct.error(json.error || '区块不存在');
        }
    });
}
(function(){
var rowTemplate = '\
<tr id="row_{pageid}">\
<td><a class="edit" href="javascript:;">{name}</a></td>\
<td class="t_c">\
<a href="{url}" target="_blank" title="查看"><img class="hand" height="16" width="16" src="images/view.gif" alt="查看页面" /></a>\
<?php if (priv::aca('page', 'page_priv')): ?><img class="hand" func="app.priv" height="16" width="16" src="images/priv.png" title="权限设置" alt="权限设置" /><?php endif; ?>\
<?php if (priv::aca('page', 'page', 'add')): ?><img class="hand" func="app.addPage" height="16" width="16" src="images/add_1.gif" title="添加子页" alt="添加子页" /><?php endif; ?>\
<?php if (priv::aca('page', 'page', 'visualedit')): ?><img class="hand" func="app.visualedit" height="16" width="16" src="images/visualedit.gif" title="可视化维护" alt="可视化维护" /><?php endif; ?>\
<?php if (priv::aca('page', 'page', 'publish')): ?><img class="hand" func="app.publishPage" width="16" height="16" src="images/refresh.gif" title="生成页面" alt="生成页面" /><?php endif; ?>\
<?php if (priv::aca('page', 'page', 'property')): ?><img class="hand" func="app.pageProperty" height="16" width="16" src="images/edit.gif" title="修改页面" alt="修改页面" /><?php endif; ?>\
<?php if (priv::aca('page', 'page', 'copy')): ?><img class="hand" func="app.pageCopy" height="16" width="16" src="images/pagecopy.png" title="克隆页面" alt="克隆页面" /><?php endif; ?>\
<?php if (priv::aca('page', 'page', 'remove')): ?><img class="hand" func="app.remove" height="16" width="16" src="images/delete.gif" title="删除页面" alt="删除页面" /><?php endif; ?>\
</td>\
<td class="t_r">{published}</td>\
<td class="t_r">{nextpublish}</td>\
<td class="t_r">{frequency}秒</td>\
<td class="t_r">{size}</td>\
</tr>';
app.init({
    baseUrl: '?app=page&controller=page&action=tree&status=1',
    rowTemplate: rowTemplate,
    rowReady: function(id, tr, json) {
    },
    edit: function(id, tr, json) {
        app.admin(id);
    }
});
})();
</script>