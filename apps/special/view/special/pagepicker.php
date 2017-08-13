<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>选择专题页面</title>
    <link rel="stylesheet" type="text/css" href="css/admin.css"/>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.template.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.list.js"></script>
    <style type="text/css">
        body {
            width: 650px;
            height: 370px;
            overflow: hidden;
        }
        .page-picker {
            margin: 0;
        }
        .page-picker .title {
            margin: 0;
            padding: 0 5px;
            border: none;
            height: 26px;
            background: url(css/images/bg_x.gif) repeat-x 0 -171px;
        }
        .page-picker li {
            height: 18px;
            line-height: 18px;
            overflow: hidden;
            padding: 5px;
            list-style: none;
            border-bottom: 1px dotted #D7E3F2;
        }
        .page-picker li.selected {
            background-color: #E7F2F5;
            border-bottom-color: #FFFFFF;
        }
        .page-picker li:hover {
            background: #FFFDDD;
        }
        .page-picker li a.img {
            float: right;
        }
        .page-picker li.selected a.img,
        .page-picker li:hover a.img {
            display: inline;
        }
        .page-picker .message {
            display: none;
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 274px;
            line-height: 274px;
            background: #FFF;
            color: #666;
            text-align: center;
            cursor: default;
        }
        .spec-list {
            width: 310px;
            height: 300px;
            float: left;
            border-right: solid 1px #D5DFE1;
            overflow: hidden;
            position: relative;
        }
        .spec-list .spec-items {
            position: relative;
            height: 274px;
            overflow-x: hidden;
            overflow-y: scroll;
        }
        .spec-list li {
            cursor: pointer;
            white-space: nowrap;
        }
        .spec-list li span {
            float: left;
            overflow: hidden;
            width: 260px;
        }
        .page-list {
            width: 310px;
            height: 300px;
            float: right;
            border-left: solid 1px #D5DFE1;
            overflow: hidden;
            position: relative;
        }
        .page-list .page-items {
            position: relative;
            height: 274px;
            overflow-x: hidden;
            overflow-y: scroll;
        }
        .page-items li {
            white-space: nowrap;
        }
        .page-items li label {
            float: left;
            overflow: hidden;
            width: 260px;
        }
        .page-list input {
            padding-top: 0;
            float: left;
        }
        .operation_area input,
        .operation_area .selectlist {
            float: left;
            margin-right: 5px;
        }
        .arrow {
            width: 28px;
            height: 300px;
            float: left;
            position: relative;
        }
        .arrow img {
            position: absolute;
            top: 50%;
            margin-top: -8px;
        }
    </style>
</head>
<body>
<div class="page-picker">
    <div class="operation_area">
        <form action="" id="form-query">
            <input type="text" value="" placeholder="专题名称" size="30" name="keyword">
            <select name="pubrange" id="range">
                <option value="">全部时间</option>
                <option value="today">今天</option>
                <option value="yesterday">昨天</option>
                <option value="week">本周</option>
                <option value="month">本月</option>
                <option value="year">今年</option>
            </select>
            <input type="submit" class="button_style_1" value="搜索" />
        </form>
    </div>
    <div class="page-list">
        <div class="title">专题页面</div>
        <ul class="page-items" id="page-items"></ul>
        <div class="message" id="page-msg">请在左侧选择专题</div>
    </div>
    <div class="spec-list">
        <div class="title">专题(<span id="special-loaded">...</span> / <span id="special-total">...</span>)</div>
        <ul class="spec-items" id="special-items"></ul>
        <div class="message" id="spec-msg">暂无专题</div>
    </div>
    <div class="arrow">
        <img src="images/move_left.gif" alt="" />
    </div>
    <div class="clear"></div>
</div>
<div class="btn_area">
    <button type="button" action="ok" class="button_style_1">确定</button>
    <button type="button" action="cancel" class="button_style_1">取消</button>
</div>
<script type="text/template" id="tpl-special-item">
    <li>
        <a class="img" target="_blank" href="{%url%}">
            <img width="16" height="16" alt="访问" src="images/view.gif" />
        </a>
        <span title="{%title%}">{%title%}</span>
    </li>
</script>
<script type="text/template" id="tpl-page-item">
    <li>
        <a class="img" target="_blank" href="{%page_url%}">
            <img width="16" height="16" alt="访问" src="images/view.gif" />
        </a>
        <label><input type="radio" name="pageid" value="{%pageid%}" />{%page_name%}</label>
    </li>
</script>
<script type="text/javascript">
$(function() {
var form = $('#form-query'),
    tplSpecial = new Template($('#tpl-special-item').html()),
    tplPage = new Template($('#tpl-page-item').html()),
    specialItems = $('#special-items'),
    pageItems = $('#page-items'),
    specialMsg = $('#spec-msg').show(),
    pageMsg = $('#page-msg').show(),
    specialLoaded = $('#special-loaded'),
    specialTotal = $('#special-total'),
    classSelected = 'selected',

    page = 1,
    loaded,
    total,
    queryLock = false,

    lastFocusedSpec,
    lastFocusedSpecItem,
    lastFocusedPage,
    lastFocusedPageItem;

function querySpecials(append) {
    if (queryLock) return;
    queryLock = true;
    if (append) {
        ++page;
    } else {
        specialItems.empty();
        page = 1;
    }
    $.post('?app=special&controller=special&action=pagePicker', form.serialize() + '&page=' + page, function(json) {
        queryLock = false;
        renderSpecials(json, append);
    }, 'json');
}
function renderSpecials(json, append) {
    if (! json || ! json.data || ! json.data.length) {
        specialMsg.show();
        return;
    }

    loaded = append ? loaded + json.data.length : json.data.length;
    specialLoaded.html(loaded);
    total = json.total;
    specialTotal.html(total);

    $.each(json.data, function(index, item) {
        var spec = $(tplSpecial.render(item)).appendTo(specialItems).click(function() {
            if (lastFocusedSpec) {
                if (lastFocusedSpec[0] == this) return false;
                lastFocusedSpec.removeClass(classSelected);
            }
            lastFocusedSpec = spec.addClass(classSelected);
            if (item.thumb && !item.thumb.match(/^https?:\/\//i)) {
                item.thumb = UPLOAD_URL + item.thumb;
            }
            lastFocusedSpecItem = item;
            queryPages(item.contentid);
        });
        if (! lastFocusedSpec) {
            lastFocusedSpec = spec.trigger('click');
        }
    });
    specialMsg.hide();
}
function queryPages(specialid) {
    if (queryLock) return;
    queryLock = true;
    lastFocusedPageItem = null;
    $.post('?app=special&controller=online&action=getPages', {
        published: 1,
        contentid: specialid
    }, function(json) {
        queryLock = false;
        renderPages(json);
    }, 'json');
}
function renderPages(json) {
    if (! json || ! json.length) {
        pageMsg.html(json && json.error || '专题不存在或该专题下暂无已发布页面').show();
        return;
    }
    pageItems.empty();
    $.each(json, function(index, item) {
        item.page_name = item.name;
        item.page_url = item.url;
        $(tplPage.render(item)).appendTo(pageItems).click(function(ev) {
            lastFocusedPage && (lastFocusedPage.removeClass(classSelected));
            lastFocusedPage = $(this).addClass(classSelected);
            lastFocusedPage.find('input').attr('checked', true);
            lastFocusedPageItem = item;
        });
    });
    pageMsg.hide();
}
function callback(func, args) {
    return window.dialogCallback
        && $.isFunction(window.dialogCallback[func])
        && window.dialogCallback[func].apply(null, args || []);
}
specialItems.scroll(function(ev) {
    if (! queryLock
        && loaded < total
        && specialItems.scrollTop() + specialItems.height() > specialItems[0].scrollHeight - 20) {
        querySpecials(true);
    }
});
$('#range').selectlist().bind('changed', function(e, t) {
    querySpecials();
});
form.submit(function() {
    querySpecials();
    return false;
});
$('button[action=ok]').click(function() {
    if (! lastFocusedSpecItem || ! lastFocusedPageItem) {
        ct.error('请选择要使用的专题页面');
        return false;
    }
    callback('ok', [$.extend({}, lastFocusedSpecItem, lastFocusedPageItem)]);
});
$('button[action=cancel]').click(function() {
    callback('close');
});
querySpecials();
});
</script>
</body>
</html>