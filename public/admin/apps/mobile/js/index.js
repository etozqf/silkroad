;$(function() {
window.CURRENT_CONTENTID = null;

function buildQuery(baseUrl, obj, ignoreEmpty) {
    var query = [];
    for (var key in obj) {
        if (obj.hasOwnProperty(key)) {
            if (!ignoreEmpty || (typeof obj[key] != 'undefiend' && obj[key] !== null)) {
                query.push(key + '=' + obj[key])
            }
        }
    }
    query = query.join('&');
    query && (query = ((baseUrl.indexOf('?') > -1) ? '&' : '?') + query);
    return baseUrl + query;
}
function inStack(needle, stack) {
    needle = needle + '';
    return $.inArray(needle, stack.split(/\s*,\s*/)) > -1;
}

var catid = ct.getParam('catid') || '';

var btnView = $('#btn-view'),
    tabHeight = $('.tag_1').outerHeight(true),
    contentContainer = $('.content-detail-container'),
    iframe = contentContainer.find('iframe');

MobileContent.status = ct.getParam('status') || 6;

MobileContent.lockRow = function(id, row) {
    var btnLock, btnEdit = row.find('img.edit');
    if (!row) row = itemlist.getRow(id);
    if (!row || !row.length) return;
    btnLock = row.find('img.lock');
    if (!btnLock.length) {
        btnLock = $('<img class="hand lock" src="images/lock.gif" title="文档已被锁定" />').insertAfter(btnEdit);
    }
    btnLock.show();
    btnEdit.hide();
};
MobileContent.unlockRow = function(id, row) {
    if (!row) row = itemlist.getRow(id);
    if (!row || !row.length) return;
    row.find('img.lock').hide();
    row.find('img.edit').show();
};

// 重写 view 方法为在 iframe 中打开
MobileContent.view = function(id, model) {
    model && typeof model === 'string' || (model = MobileContent.getModel(id));
    itemlist.uncheck(id);
    CURRENT_CONTENTID = id;
    btnView.click();
    model && (iframe[0].src = '?app=mobile&controller='+model+'&action=view&contentid='+id);
};

// F5 刷新时 iframe 也被刷新但却不可见的问题
$(window).bind('unload', function() {
    iframe[0].src = 'about:blank';
    iframe.remove();
    iframe = null;
});

MobileContent.search = function(catid, modelid, status) {
    var _dialog = ct.formDialog({
        title:'高级搜索'
    }, buildQuery('?app=mobile&controller=content&action=search', {
        catid:catid,
        modelid:modelid,
        status:status
    }, true), function() {}, function(form, dialog) {
        form.find('[name=keyword]').val(inputKeyword.val());
        form.find('input.input_calendar').DatePicker({'format':'yyyy-MM-dd HH:mm:ss'});
        form.find('select').selectlist();
    }, function(form, dialog) {
        var params = form.serializeObject();
        for (var key in params) {
            if (params.hasOwnProperty(key) && params[key] === '') {
                delete(params[key]);
            }
        }
        filterSlide.attr('checked', false);

        MobileContent.status = status = params.status;
        filterStatus.children('[data-status='+status+']').addClass('active').siblings().removeClass('active');
        modelid = params.modelid || '';
        filterModel.children('[data-modelid='+modelid+']').addClass('active').siblings().removeClass('active');

        itemlist.load(params);
        inputKeyword.val(form.find('[name=keyword]').val());
        _dialog.dialog('destory').remove();
        return false;
    });
    return false;
};

// 下拉菜单
var dropmenu = $('div.dropmenu').dd({width: 65});
var dropmenuItems = dropmenu.find('li').click(function() {
    var model = $(this).attr('model'),
        url = '?app=mobile&controller='+model+'&action=add';
    ct.assoc.open(buildQuery(url, {catid:catid}, true), 'newtab');
});
dropmenu.find('h3').click(function() {
    dropmenuItems.eq(0).triggerHandler('click');
});

// 筛选条件
function reloadItemList() {
    var where, keyword;
    filterSlide.attr('checked', false);
    where = {
        modelid:modelid,
        status:status
    };
    keyword = $('#input-keyword').val();
    if (keyword) {
        where.keyword = keyword;
    }
    itemlist.load(where);
}
var filterModel = $('#filter-model'),
    modelid = filterModel.children().click(function() {
        var _this = $(this);
        modelid = _this.attr('data-modelid');
        _this.addClass('active').siblings().removeClass('active');
        reloadItemList();
        return false;
    }).filter('.active').attr('data-modelid');
var filterStatus = $('#filter-status'),
    status = filterStatus.children().click(function() {
        var _this = $(this);
        MobileContent.status = status = $(this).attr('data-status');
        _this.addClass('active').siblings().removeClass('active');
        reloadItemList();
        return false;
    }).filter('.active').attr('data-status');

// 列表区域高度自适应
ResizeDelegate.start();
var itemlistBody = $('.ui-itemlist-body'),
    itemlistBodyTop = itemlistBody.offset().top,
    itemlistFooterHeight = $('.ui-itemlist-footer').outerHeight(true);
function itemlistHeight(wh) {
    wh || (wh = $(window).height());
    return wh - itemlistBodyTop - itemlistFooterHeight;
}
ResizeDelegate.add(itemlistBody, function(ww, wh) {
    this.css('height', itemlistHeight(wh));
}, true);

// 列表相关
var inputKeyword = $('#input-keyword').keyup(function(ev) {
    if (ev.keyCode == 13) {
        btnSearch.click();
        return false;
    }
});
var btnSearch = $('#btn-search').click(function() {
    var where;
    filterSlide.attr('checked', false);
    where = {keyword:inputKeyword.val()};
    modelid && (where.modelid = modelid);
    status && (where.status = status);
    itemlist.load(where);
    return false;
});

var batchActions = $('.batch-actions :button');
var filterSlide = $('#filter-slide').click(function() {
    if (this.checked) {
        itemlist.setData('inslider', 1);
    } else {
        itemlist.removeData('inslider');
    }
});
var btnOrderby = $('#btn-orderby').contextMenu('#menu-orderby', function(orderby) {
    itemlist.orderby(orderby);
}, null, null, function(_btn, menu) {
    menu.children('[data-status]').hide().filter(function() {
        return inStack(MobileContent.status, $(this).attr('data-status'));
    }).show();
}).click(function(ev) {
    btnOrderby.trigger('contextMenu', [ev]);
});
window.itemlist = new ItemList($('#itemlist'), {
    rowIdPrefix:'row_',
    rowIdKey:'contentid',
    baseUrl:buildQuery('?app=mobile&controller=content&action=page', {
        catid:catid,
        modelid:modelid,
        status:status
    }, true),
    rowTemplate:$('#tpl-item-row').html(),
    checkOnClick:false,
    pagesize:Math.floor(itemlistHeight() / 58),
    rowReady:function(id, row, data) {
        // 手动绑定菜单
        row.contextMenu('#menu-item', function(action) {
            if ((action = ct.func(action))) {
                action(id, row, data);
            }
        }, null, null, function(row, menu) {
            menu.children().each(function() {
                var _this = $(this),
                    _status = _this.attr('data-status'),
                    _model = _this.attr('data-modelid'),
                    _negative = false,
                    _inslider = _this.attr('data-inslider'),
                    _stick = _this.attr('data-stick');
                if (_model && _model[0] == '!') {
                    _negative = true;
                    _model = _model.substr(1);
                }
                if ((_status !== undefined && !inStack(MobileContent.status, _status))
                    || (_model !== undefined && (_negative ? inStack(data.modelid, _model) : !inStack(data.modelid, _model)))
                    || (_inslider !== undefined && data.inslider != _inslider)
                    || (_stick !== undefined && data.stick != _stick)) {
                    _this.hide();
                } else {
                    _this.show();
                }
            });
        });

        catid && data.inslider && row.addClass('slider-tips');

        // 提示信息
        row.find('[data-tips]').tipsy({
            gravity:$.fn.tipsy.autoNS,
            title:'data-tips',
            html:true,
            delayIn:500,
            opacity:1
        });
        row.bind('beforeRemove', function() {
            row.tipsy('hide');
        });

        // 编辑和删除
        row.find('img.edit').click(function() {
            MobileContent.edit(id);
            return false;
        });

        var btnRemove = row.find('img.remove');
        btnRemove.click(function() {
            MobileContent.remove(id);
            return false;
        });
        var btnDelete = row.find('img.delete');
        btnDelete.click(function() {
            MobileContent.del(id);
            return false;
        });
        if (data.status == 0) {
            btnRemove.hide();
        } else {
            btnDelete.hide();
        }
        if (!catid) {
            btnDelete.hide();
        }

        // 锁定判断
        if (data.locked) {
            MobileContent.lockRow(id, row);
        } else {
            MobileContent.unlockRow(id, row);
        }

        // 下拉菜单
        row.find('.popset').click(function(ev) {
            row.trigger('contextMenu', [ev]);
            return false;
        });

        if (hotVideo.indexOf(id>>>0) > -1) {
            row.addClass('hot-video')
        }
    },
    check:function() {
        batchActions.hide().filter(function() {
            return inStack(MobileContent.status, $(this).attr('data-status'));
        }).show();
    },
    uncheck:function() {
        if (!itemlist.checkedRows().length) {
            batchActions.hide();
        }
    },
    click:function(id) {
        MobileContent.view(id);
    },
    dblclick:MobileContent.edit
});
itemlist.load();

// 内容区域
ResizeDelegate.add(contentContainer, function(ww, wh) {
    this.height(wh - tabHeight);
});
ResizeDelegate.add($('.content-stat-container'), function(ww, wh) {
    this.height(wh - tabHeight);
}, true);

$('.content-viewer').tab({
    trigger:'.tag_1 li',
    content:'.ui-tab-content > div',
    active:1,
    activeClass:'active',
    beforeActive:function(index) {
        if (index == 0 && !CURRENT_CONTENTID) {
            ct.warn('请从列表中选择要查看的内容');
            return false;
        }
    },
    afterActive:function(index) {
        ResizeDelegate.trigger();
    }
});

$.getJSON('?app=mobile&controller=stat&action=content_overview&catid='+catid, function(json) {
    var overlay = $('.content-stat-overlay');
    var rank = json.rank;
    var stat = json.stat;

    delete json.rank;
    delete json.stat;
    $.each(json, function(key, value) {
        $('#stat_' + key).text(value);
    });

    // 图表
    var pvs = [];
    var categories = [];
    for (var key in stat.pv) {
        categories.push(key);
        pvs.push(stat.pv[key].pv);
    }
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'content-stat-diagram',
            type: 'spline',
            zoomType: 'x'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: categories,
            labels: {
                step: parseInt(categories.length / 7, 10),
                formatter: function() {
                    var arr = this.value.split('-');
                    arr.splice(0, 1);
                    return arr.join('-');
                }
            }
        },
        yAxis: {
            title: null,
            allowDecimals: false,
            min: 0,
            showFirstLabel: false
        },
        legend: {
            enabled: false
        },
        tooltip: {
            formatter: function() {
                return '' + this.x + ' ' + this.series.name + ' ' + this.y;
            },
            crosshairs: true
        },
        series: [{
            name: '访问量',
            data: pvs
        }],
        navigation: {
            menuItemStyle: {
                fontSize: '12px'
            }
        },
        credits: {
            enabled: false
        }
    });

    var template = $('#tpl-rank-row').html();
    $.each(rank, function(key, rows) {
        var ul = $('#rank_' + key);
        $.each(rows, function(i, row) {
            ul.append(ct.renderTemplate(template, row));
        });
    });

    overlay.hide();
    overlay.parent().css('overflow-y', 'auto');
 });

});
