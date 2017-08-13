<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>推送到页面区块</title>
    <?=$resources?>

    <style type="text/css">
        .panel {
            height: 405px;
            overflow: hidden;
        }
        .panel-pages {
            float: left;
            width: 150px;
            height: 100%;
            overflow-x: auto;
            overflow-y: scroll;
            border-right: 1px solid #CBE2E9;
        }
        .panel-sections {
            float: left;
            width: 200px;
            height: 100%;
            overflow: hidden;
            border-right: 1px solid #CBE2E9;
            position: relative;
        }
        .panel-selected {
            height: 100%;
            overflow: hidden;
            zoom: 1;
            position: relative;
        }
        .section-search {
            height: 25px;
            line-height: 25px;
            background: #EFF7FD;
            color: #74A7D1;
        }
        .section-search input {
            width: 192px;
            background: #EFF7FD;
            color: #74A7D1;
            border-color: transparent;
            outline: none;
            margin: 0;
            height: 16px;
            line-height: 16px;
        }
        .section-search .search-icon {
            position: absolute;
            top: 5px;
            right: 5px;
            background: url(css/images/bg.gif) no-repeat -48px -569px;
            width: 16px;
            height: 16px;
            overflow: hidden;
        }
        .section-list {
            overflow-x: hidden;
            overflow-y: auto;
            height: 380px;
        }
        .list-item {
            height: 25px;
            line-height: 25px;
            padding: 0 10px;
            color: #000;
            cursor: pointer;
        }
        .list-item:hover {
            background: #FFFDDD;
        }
        .list-item.selected {
            background-color: #E7F2F5;
            border-bottom-color: #FFFFFF;
        }
        .list-item .item-name {
            display: block;
            width: 180px;
            white-space: nowrap;
            overflow: hidden;
        }

        .selected-list {
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
        }
        .form-item {
            overflow: hidden;
            margin-bottom: 10px;
        }
        .form-item .form-content-wrap {
            position: relative;
        }
        .form-item .form-content {
            padding: 5px;
        }
        .form-item .form-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            color: #74A7D1;
            text-align: center;
            background: #FFF;
            background:rgba(255, 255, 255, 0.8);
            filter:progid:DXImageTransform.Microsoft.Gradient(GradientType=0, StartColorStr='#CCFFFFFF', EndColorStr='#CCFFFFFF');
        }
        .form-item .form-overlay table {
            height: 100%;
        }
        .form-item .form-overlay table td {
            color: red;
        }
        :root .form-item .form-overlay {
            filter:progid:DXImageTransform.Microsoft.Gradient(GradientType=0, StartColorStr='#00FFFFFF', EndColorStr='#00FFFFFF');
        }
        .form-title {
            height: 25px;
            line-height: 25px;
            padding: 0 10px;
            background: #EFF7FD;
            color: #74A7D1;
        }
        .form-title .remove {
            float: right;
            margin: 3px 0 0 0;
        }

        #section-msg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 380px;
            line-height: 380px;
            text-align: center;
            background: #FFF;
            display: none;
        }
        #selected-msg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            line-height: 380px;
            text-align: center;
            background: #FFF;
            display: none;
        }

        .icon-title {
            position: relative;
            display: inline-block;
        }
        .icon-title .iconlist {
            position: absolute;
            border: none;
            left: 1px;
            top: 1px;
            background: transparent;
            margin: 0;
            padding: 2px;
        }
    </style>
</head>
<body>
<div class="panel">
    <div class="panel-pages">
        <div class="tree" id="tree" idv="3">

        </div>
    </div>
    <div class="panel-sections">
        <div class="section-search">
            <a href="javascript:void(0);" title="搜索" class="search-icon"></a>
            <input id="keyword" type="text" name="" value="区块名称" size="20" />
        </div>
        <div class="section-list"></div>
        <div id="section-msg">该页面下无可推送区块</div>
    </div>
    <div class="panel-selected">
        <div class="selected-list"></div>
        <div id="selected-msg">请在左侧选择要推送的区块</div>
    </div>
</div>
<div class="btn_area">
    <button type="button" action="ok" class="button_style_1">确定</button>
    <button type="button" action="cancel" class="button_style_1">取消</button>
</div>

<textarea style="display:none;" id="tpl-section">
    <?=script_template('<div class="list-item" sectionid="{%sectionid%}">
        <span class="section-item {%type%} item-name">{%name%}</span>
    </div>')?>
</textarea>

<textarea style="display:none;" id="tpl-form">
    <?=script_template('<div class="form-item">
        <div class="form-title">
            <a class="remove" title="删除" href="javascript:void(0);">
                <img src="images/del.gif" alt="" width="16" height="16" />
            </a>
            <span>{%pageName%} &gt; {%sectionName%}</span>
        </div>
        <div class="form-content-wrap">
            <div class="form-content">{%form%}</div>
            <div class="form-overlay">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td data-role="msg"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>')?>
</textarea>

<script type="text/javascript">
$(function() {
var lock,
    submitLock,
    tree,
    currentPage,
    contentid = <?=$contentid?>,
    chkedSections = <?=$checkedSections?>,
    chkedSectionIds = <?=$checkedSectionIds?>,
    removedSections = [],
    sectionList = $('.section-list'),
    selectedList = $('.selected-list'),
    tplSection = new Template($('#tpl-section').val()),
    tplForm = new Template($('#tpl-form').val()),
    keyword = $('#keyword'),
    keywordBtn = keyword.prev('a'),
    msgSection = $('#section-msg'),
    msgSelected = $('#selected-msg'),
    firstPagePath;

function loadSections(pageid, callback) {
    if (lock) return;
    lock = true;
    $.getJSON('?app=page&controller=page&action=sections', {
        pageid:pageid,
        type:'hand,push',
        keyword:(keyword[0].value != keyword[0].defaultValue ? keyword[0].value : '')
    }, function(json) {
        lock = false;
        if (json && json.data) {
            callback && callback(json.data);
        }
    });
}
function searchSection(page, callback) {
    msgSection.hide();
    loadSections(page.pageid, function(sections) {
        sectionList.empty();
        if (sections.length) {
            renderSections(page, sections);
        } else {
            msgSection.show();
        }
        callback && callback();
    });
}
function renderSections(page, sections) {
    $.each(sections, function(i, section) {
        var row = $(tplSection.render(section));
        row.click(function() {
            if (!row.hasClass('selected')) {
                loadForm(page, section, function() {
                    row.addClass('selected');
                });
            }
            return false;
        });
        if (chkedSectionIds.indexOf(section.sectionid) > -1) row.addClass('selected');
        sectionList.append(row);
    });
}
function loadForm(page, section, callback) {
    $.getJSON('?app=page&controller=section&action=recommendItem', {
        sectionid:section.sectionid,
        contentid:contentid
    }, function(json) {
        if (json) {
            msgSelected.hide();
            var form = renderForm(page, section, json);
            selectedList.append(form);
            //form.find(':input').trigger('change').trigger('keyup');
            selectedList.scrollTop(selectedList[0].scrollHeight);
            callback && callback(json);
        } else {
            ct.error('加载失败');
        }
    });
}
function renderForm(page, section, data) {
    var form = $(tplForm.render({
        pageName:page.name,
        sectionName:section.name,
        form:data.html
    }));
    var id = section.sectionid, index;

    // 如果该区块被加入到了已删除区块中，将其从已删除区块中移除
    if ((index = removedSections.indexOf(id)) > -1) removedSections.splice(index, 1);
    // 维护已选择区块ID
    if ((index = chkedSectionIds.indexOf(id)) == -1) chkedSectionIds.push(id);
    // 维护已选择条目
    chkedSections[id] = {
        sectionid:id,
        name:section.name,
        pageName:page.name,
        pageid:page.pageid
    };

    form.find('.remove').click(function() {
        // 将该区块加入到已删除区块中
        var index = removedSections.indexOf(id);
        if (index == -1) removedSections.push(id);
        // 维护已选择区块ID
        if ((index = chkedSectionIds.indexOf(id)) > -1) chkedSectionIds.splice(index, 1);
        // 维护已选择条目
        delete chkedSections[id];

        sectionList.find('.list-item[sectionid='+id+']').removeClass('selected');
        !form.siblings().length && msgSelected.show();
        form.remove();

        // 解除锁定
        if (section.type == 'push' && !section.check) {
            $.post('?app=page&controller=section&action=unlock', 'sectionid='+id);
        }
    });

    prepareForm(form);

    var overlay = form.find('.form-overlay');
    if (data.error) {
        overlay.find('[data-role=msg]').html(data.error);
        overlay.show();
        form.addClass('locked');
    } else {
        overlay.hide();
    }
    return form;
}
function prepareForm(form) {
    form = form.find('form:first');
    var title = form.find('input[name=title]').maxLength();
    var colorPicker = title.nextAll('img');
    colorPicker.titleColorPicker(title, colorPicker.next('input'));
    form.find('input[name=thumb]').data('editor-options', {
        width:750,
        height:420
    }).imageInput();

    var icon = form.find('[name=icon]').iconlist({
        container:form[0].title
    }).bind('changed',function(e, t, o){
                var ctrl = icon.data('listObj').ctrl;
                if (!ctrl) return;
                if (form[0].icon) {
                    form[0].iconsrc && (form[0].iconsrc.value = (form[0].icon.value ? o.find('img').attr('src') : ''));
                }
                $(form[0].title).css('padding-left', ctrl.outerWidth() + 2);
            });

    page.validate(form);
    form.validate({
        submitHandler:submit
    });
}

tree = $('#tree').tree({
    url:'?app=system&controller=index&action=menu&root=%s',
    paramId:'id',
    paramHaschild:'hasChildren',
    itemReady:function(li, ul, item){
        if (!firstPagePath) {
            firstPagePath = item.id;
        }
    },
    prepared:function(){
        this.open(firstPagePath, true);
    },
    click:function(div, id, item){
        var pageid = parseInt(id.replace(/^0*/, ''));
        if (currentPage && pageid == currentPage.pageid) return;
        keyword.val('').blur();
        var page = {
            pageid:pageid,
            name:item.text
        };
        searchSection(page, function() {
            currentPage = page;
        });
    }
});

keywordBtn.click(function() {
    keyword.focus();
    if (keyword.val()) searchSection(currentPage);
    return false;
});
keyword.focus(function() {
    if (this.value == this.defaultValue) this.value = '';
});
keyword.blur(function() {
    if (this.value == '') this.value = this.defaultValue;
});
keyword.keyup(function(ev) {
    if (ev.keyCode == 13) {
        searchSection(currentPage);
        return false;
    }
});

function removeRecommend(callback) {
    if (removedSections.length) {
        $.getJSON('?app=page&controller=section&action=removeRecommend', {
            contentid:contentid,
            sectionid:removedSections.join(',')
        }, function() {
            removedSections = [];
            callback && callback();
        });
    } else {
        callback && callback();
    }
}

function submit(form) {
    var container = form.closest('.form-item');
    submitLock = true;
    selectedList.scrollTop(container.scrollTop());
    removeRecommend(function() {
        form.ajaxSubmit({
            success:function(json) {
                var title = container.find('.form-title span').text();
                if (json && json.state) {
                    ct.ok('推送到 ' + title + ' 成功');
                    container.fadeOut('fast', function() {
                        container.remove();
                        setTimeout(submitNext, 800);
                    });
                } else {
                    submitLock = false;
                    ct.error('推送到 ' + title + ' 失败' + (json.error ? ' ' + json.error : ''));
                    selectedList.scrollTop(container.scrollTop());
                }
            },
            dataType:'json'
        });
    });
}

function submitNext() {
    var forms = selectedList.find('.form-item').not('.locked');
    if (forms.length) {
        var fm = forms.eq(0).find('form');
        fm.trigger('submit');
        var rl = fm.attr('val_result');
        if(rl == "false") submitLock = false;
    } else {
        if (window.dialogCallback && dialogCallback.picked) dialogCallback.picked(chkedSections);
    }
}

msgSelected.show();

$('.btn_area [action=ok]').click(function() {
    if (!submitLock) {
        submitNext();
    }
    return false;
});
$('.btn_area [action=cancel]').click(function() {
    getDialog().dialog('close');
    return false;
});

// 初始化已选择
if (chkedSections) {
    $.each(chkedSections, function(i, section) {
        loadForm({
            name:section.pageName,
            pageid:section.pageid
        }, {
            name:section.name,
            sectionid:section.sectionid
        });
    });
}
});
</script>
</body>
</html>