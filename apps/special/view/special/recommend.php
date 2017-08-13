<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>推送到专题区块</title>
    <link rel="stylesheet" type="text/css" href="css/admin.css"/>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.template.js"></script>

    <link href="<?php echo IMG_URL?>js/lib/tree/style.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo IMG_URL?>js/lib/cmstop.tree.js" type="text/javascript"></script>

    <style type="text/css">
        .panel {
            overflow: hidden;
        }
        #keyword {
            float: left;
            margin-right: 5px;
        }
        .panel-left {
            float: left;
            width: 380px;
            border-right: solid 1px #CEE1E8;
            position: relative;
        }
        .spec-list {
            height: 350px;
            overflow-x: hidden;
            overflow-y: scroll;
        }
        #left-msg {
            position: absolute;
            width: 100%;
            height: 350px;
            line-height: 350px;
            left: 0;
            bottom: 0;
            text-align: center;
            display: none;
        }
        .spec-item {
            height: 30px;
            line-height: 30px;
            cursor: pointer;
            padding: 0 10px 0 5px;
            clear: both;
            white-space: nowrap;
            overflow: hidden;
            border-bottom: 1px dotted #D7E3F2;
        }
        .spec-item:hover {
            background: #FFFDDD;
        }
        .spec-item.selected {
            background-color: #E7F2F5;
            border-bottom-color: #FFFFFF;
        }
        .spec-item .time {
            float: right;
        }
        .spec-item .img {
            float: left;
            margin-right: 5px;
            padding-top: 3px;
        }
        .spec-item .item-title {
            float: left;
            width: 260px;
            overflow: hidden;
        }

        .panel-right {
            position: relative;
            overflow: hidden;
            zoom: 1;
        }
        .selected-list {
            height: 380px;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
        }
        #right-msg {
            position: absolute;
            width: 100%;
            height: 100%;
            line-height: 380px;
            text-align: center;
            right: 0;
            top: 0;
            display: none;
        }
        .selected-item {

        }
        .selected-item .item-title {
            background: #F0F7FD;
            color: #4C8DC2;
            height: 30px;
            line-height: 30px;
            padding: 0 10px;
            position: relative;
        }
        .selected-item .item-title span {
            float: left;
            width: 360px;
            white-space: nowrap;
            overflow: hidden;
        }
        .selected-item .item-title .remove {
            position: absolute;
            right: 10px;
            top: 3px;
            *top: 5px;
        }
        .selected-item .item-page {
            margin: 15px 10px;
            position: relative;
            border: solid 1px #AAA;
            padding: 15px 10px 5px;
        }
        .selected-item .item-page .page-title {
            position: absolute;
            top: -12px;
            left: 10px;
            display: block;
            padding: 5px 10px;
            color: #000;
            background: #FFF;
        }
        .selected-item .item-page .item-section {
            margin-right: 5px;
            display: inline-block;
            max-width: 350px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .selected-item .item-page .item-section label {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="panel">
    <div class="panel-left">
        <div class="operation_area">
            <form action="" id="form-query">
                <input id="keyword" type="text" value="" placeholder="搜索标题" size="30" name="keyword">
                <input id="catid" name="catid" width="150"
                       url="?app=system&controller=category&action=cate&dsnid=&catid=%s"
                       initUrl="?app=system&controller=category&action=name&catid=%s"
                       paramVal="catid"
                       paramTxt="name"
                       multiple="1"
                       value=""
                       alt="选择栏目" />
                <input type="submit" class="button_style_1" value="搜索" />
            </form>
        </div>
        <div class="spec-list"></div>
        <div id="left-msg">找不到符合条件且包含推送区块的专题</div>
    </div>
    <div class="panel-right">
        <div class="selected-list"></div>
        <div id="right-msg">请先选择左侧的专题</div>
    </div>
</div>
<div class="btn_area">
    <button type="button" action="ok" class="button_style_1">确定</button>
    <button type="button" action="cancel" class="button_style_1">取消</button>
</div>

<script type="text/template" id="tpl-spec">
    <div class="spec-item" contentid="{%contentid%}">
        <span class="time">{%published%}</span>
        <a class="img" target="_blank" href="{%url%}">
            <img width="16" height="16" alt="访问" src="images/view.gif" />
        </a>
        <span class="item-title" title="{%title%}">{%title%}</span>
    </div>
</script>

<script type="text/template" id="tpl-selected">
    <div class="selected-item" contentid="{%contentid%}">
        <div class="item-title">
            <a class="remove" href="javascript:void(0);" title="删除"><img src="images/del.gif" /></a>
            <span title="{%title%}"><a href="{%url%}" target="_blank">{%title%}</a></span>
        </div>
        <div class="page-wrap"></div>
    </div>
</script>

<script type="text/template" id="tpl-page">
    <div class="item-page">
        <span class="page-title">{%name%}</span>
        <div class="section-wrap"></div>
    </div>
</script>

<script type="text/template" id="tpl-section">
    <span class="item-section" title="{%name%}">
        <label><input type="checkbox" name="sectionid" value="{%placeid%}" />{%name%}</label>
    </span>
</script>

<script type="text/javascript">
(function() {
var checkedPlace = <?=$checkedPlace ? $checkedPlace : '{}'?>,
    checkedContent = <?=$checkedContent ? $checkedContent : '[]'?>,
    checkedTitle = {},

    specList = $('.spec-list'),
    selectedList = $('.selected-list'),
    keyword = $('#keyword'),
    catid = $('#catid').selectree(),
    form = $('#form-query'),

    tplSpec = new Template($('#tpl-spec').html()),
    tplSelected = new Template($('#tpl-selected').html()),
    tplPage = new Template($('#tpl-page').html()),
    tplSection = new Template($('#tpl-section').html()),

    lock, total = 0, page = 1, count = 0,
    pagesize = Math.ceil(specList.height() / 30 * 1.5),
    msgLeft = $('#left-msg'),
    msgRight = $('#right-msg');

function removeIds(contentid, ids) {
    var chkedids = checkedPlace[contentid];
    if (!chkedids || !chkedids.length) return;
    $.each(ids, function(i, id) {
        var index = chkedids.indexOf(id);
        if (index > -1) {
            chkedids.splice(index, 1);
            delete checkedTitle[id];
        }
    });
    if (!chkedids.length) delete checkedPlace[contentid];
}

function addId(contentid, id) {
    var chkedids = checkedPlace[contentid];
    if (!chkedids) chkedids = [];
    if (chkedids.indexOf(id) > -1) return;
    chkedids.push(id);
    checkedPlace[contentid] = chkedids;
}

function queryPlaces(contentid, success) {
    $.getJSON('?app=special&controller=special&action=getPlace&contentid='+contentid, function(json) {
        if (json) {
            success && success(json);
        } else {
            ct.error('获取推送位置出错');
        }
    });
}

function query(clean) {
    if (lock) return;
    lock = true;
    if (clean) {
        total = 0;
        page = 1;
        count = 0;
    }
    $.getJSON('?app=special&controller=special&action=search&hasplace=1', {
        keyword:keyword.val(),
        catid:catid.val(),
        page:page,
        pagesize:pagesize
    }, function(json) {
        lock = false;
        clean && specList.empty();
        if (json.state && json.data) {
            msgLeft.hide();
            page++;
            count += json.data.length;
            total = parseInt(json.total);
            $.each(json.data, function(i, item) {
                specList.append(renderSpec(item));
            });
        }
        !total && msgLeft.show();
    });
}

function renderSpec(spec) {
    var $spec = $(tplSpec.render(spec));
    if (selectedList.find('.selected-item[contentid='+spec.contentid+']').length) {
        $spec.addClass('selected');
    }
    $spec.click(function() {
        if ($spec.hasClass('selected')) return false;
        renderSelected(spec);
        $spec.addClass('selected');
    });
    return $spec;
}

function renderSelected(content) {
    var allPlaceids = [],
        contentid = content.contentid;
    queryPlaces(contentid, function(pages) {
        if (!pages.length) {
            ct.error(content.title + ' 下暂无推送区块');
            return;
        }
        msgRight.hide();
        var $selected = $(tplSelected.render(content)),
            pageWrap = $selected.find('.page-wrap'),
            chkedids = checkedPlace[contentid] || [];
        $selected.find('a.remove').click(function() {
            specList.find('.spec-item[contentid='+contentid+']').removeClass('selected');
            removeIds(contentid, allPlaceids);
            if (!$selected.siblings().length) msgRight.show();
            $selected.remove();
            return false;
        });
        $.each(pages, function(i, page) {
            var $page = $(tplPage.render(page)),
                placeWrap = $page.find('.section-wrap');
            $.each(page.places, function(n, place) {
                var $place = $(tplSection.render(place)),
                    placeid = place.placeid,
                    $checkbox = $place.find(':checkbox'),
                    title = {
                        contentid:contentid,
                        title:content.title,
                        pageName:page.name,
                        sectionName:place.name,
                        placeid:placeid
                    };
                $checkbox.click(function() {
                    if (this.checked) {
                        addId(contentid, placeid);
                        checkedTitle[placeid] = title;
                    } else {
                        removeIds(contentid, [placeid]);
                    }
                });
                if (chkedids.indexOf(placeid) > -1) {
                    $checkbox.attr('checked', true);
                    checkedTitle[placeid] = title;
                }
                placeWrap.append($place);
                allPlaceids.push(placeid);
            });
            pageWrap.append($page);
        });
        selectedList.append($selected);
    });
}

specList.scroll(function() {
    if (!lock && count < total
        && specList.scrollTop() + specList.height() > specList[0].scrollHeight - 20) {
        query();
    }
});
form.submit(function() {
    query(true);
    return false;
});
keyword.keyup(function(ev) {
    if (ev.keyCode == 13) {
        form.submit();
        return false;
    }
});

if (checkedContent.length) {
    $.each(checkedContent, function(i, content) {
        renderSelected(content);
    });
} else {
    msgRight.show();
}

$('.btn_area [action=ok]').click(function() {
    if (window.dialogCallback && dialogCallback.picked) dialogCallback.picked(checkedTitle);
    return false;
});
$('.btn_area [action=cancel]').click(function() {
    getDialog().dialog('close');
    return false;
});

query(true);
})();
</script>
</body>
</html>