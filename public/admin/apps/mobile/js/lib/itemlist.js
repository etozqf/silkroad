/**
 * 非表格的内容列表，常用于某些需要滚动条的列表
 *
 * 表格列表推荐 cmstop.table.js
 *
 * @depends jquery 1.3.2+, cmstop.contextMenu.js, jquery.pagination.js
 */
;(function($) {
var DOT = '.';
var PREFIX = 'ui-itemlist-';
var OPTIONS = {
    baseUrl:'',
    rowIdPrefix:'',
    rowIdKey:'',
    rowTemplate:'',
    rightMenuId:'right_menu',
    method:'get',
    checkOnClick:true,

    messages:{
        loading:'数据加载中',
        empty:'暂无数据'
    },

    pagesize:10,
    pageOptions:{
        varname:'page',
        sizename:'pagesize',
        prevText:'上一页',
        nextText:'下一页'
    },

    orderName:'orderby',

    jsonLoaded:null,
    rowReady:null,
    update:null,
    check:null,
    uncheck:null,
    click:null,
    dblclick:null
};
var CLASSES = {
    LOADING:PREFIX+'loading',
    MESSAGE:PREFIX+'message',
    HEADER:PREFIX+'header',
    BODY:PREFIX+'body',
    FOOTER:PREFIX+'footer',

    ITEM:PREFIX+'item',
    HOVER:PREFIX+'item-hover',
    CHECK:PREFIX+'item-check',
    HIGHLIGHT:PREFIX+'item-highlight'
};

function template(tpl, data) {
    return tpl.replace(/\{([^\}]+?)\}/gm, function(match, key) {
        var parts, part, result = data, type, defaultValue;
        key = key.split('||');
        parts = key[0].split('.');
        defaultValue = key[1] || '';
        while (part = parts.shift()) {
            if (!(result = result[part])) break;
        }
        type = typeof result;
        return (type != 'undefined' && result !== null) ? result : defaultValue;
    });
}

function formatIds(ids) {
    return (ids + '').split(',');
}

function func(func, context) {
    if (typeof func == 'function') {
        return func;
    }
    if (typeof func == 'string') {
        func = func.split('.');
        var o = (context || window)[func[0]], w = null;
        if (!o) return null;
        for (var i = 1, l; l = func[i++];) {
            if (!o[l]) {
                return null;
            }
            w = o;
            o = o[l];
        }
        return o && (function () {
            return o.apply(w, arguments);
        });
    }
    return null;
}

function noop() {}

function ItemList(elem, options) {
    var _this = this, opt;
    _this.events = {};
    opt = _this.options = $.extend(true, {}, OPTIONS, options);

    if (!opt.baseUrl) throw new Error('baseUrl must be specialed.');
    if (!opt.rowIdPrefix) throw new Error('rowIdPrefix must be specialed.');
    if (!opt.rowIdKey) throw new Error('rowIdKey must be specialed.');
    if (!opt.rowTemplate) throw new Error('rowTemplate must be specialed.');

    _this.container = elem.jquery ? elem : $(elem);
    _this.header = _this.find('header');
    _this.btnAll = _this.header.length && _this.header.find(':checkbox:first');
    _this.body = _this.find('body').html('<div class="'+CLASSES.LOADING+'">'+opt.messages.loading+'</div>');
    _this.footer = _this.find('footer');
    _this.pager = _this.find('pager', _this.footer);

    _this.oldData = {};
    _this.events = {};
    _this.inited = false;
    _this.rowStack = {};
    _this.lastCheckedRow = null;
}
ItemList.prototype = {
    bind:function(event, func) {
        event in this.events || (this.events[event] = []);
        this.events[event].push(func);
        return this;
    },
    trigger:function(event, args) {
        if (event in this.events) {
            for (var index = 0, func; (func = this.events[event][index++]) && (func.apply(this, args || []) !== false); ) {}
        }
    },
    find:function(name, context) {
        return (context || this.container).find(DOT+PREFIX+name);
    },
    load:function(params) {
        var _this = this;
        _this.inited = false;
        if (params && Object.prototype.toString.call(params) == '[object Object]' && 'isPrototypeOf' in params) {
            _this.oldData = params;
        } else {
            _this.oldData = params = {};
        }
        _this.loadPage();
    },
    reload:function() {
        this.inited = false;
        this.loadData(this.oldData);
    },
    loadPage:function(index) {
        var opt = this.options.pageOptions;
        this.oldData[opt.varname] = (index || 0) + 1;
        this.oldData[opt.sizename] = this.options.pagesize;
        this.loadData(this.oldData);
    },
    loadData:function(param) {
        var _this = this, opt = _this.options;
        $.ajax({
            url:_this.options.baseUrl,
            data:param,
            type:opt.method || 'get',
            dataType:'json',
            success:function(json) {
                if (json) {
                    json.data || (json.data = []);
                    json.total || (json.total = 0);
                    func(opt.jsonLoaded || noop)(json);
                    _this.render(json.data, json.total);
                } else {
                    func(opt.error || noop)(json);
                }
            }
        });
    },
    setData:function(key, value) {
        this.oldData[key] = value;
        this.load(this.oldData);
    },
    removeData:function(key) {
        delete this.oldData[key];
        this.load(this.oldData);
    },
    orderby:function(orderby) {
        this.oldData[this.options.orderName] = orderby;
        this.reload();
    },
    render:function(data, total) {
        var _this = this;

        _this.btnAll.length && _this.btnAll.attr('checked', false);
        _this.clear();
        _this.rowStack = {};

        if (data && data.length) {
            $.each(data, function(index, row) {
                _this.addRow(row);
            });
        } else {
            _this.body.html('<div class="'+CLASSES.MESSAGE+'">'+_this.options.messages.empty+'</div>');
        }

        if (!_this.inited) {
            if (_this.btnAll.length) {
                _this.btnAll.unbind('click');
                _this.btnAll.click(function() {
                    if (this.checked) {
                        _this.checkAll();
                    } else {
                        _this.uncheckAll();
                    }
                });
            }
            _this.pager.length && _this.renderPager(total);
            _this.inited = true;
        }

        func(_this.options.update || noop)(_this.container);
    },
    renderRow:function(data) {
        var opt = this.options,
            row = $(template(opt.rowTemplate, data)),
            rowId = data[opt.rowIdKey];
        if (!rowId) {
            throw new Error('row data id key not exists');
        }
        row.attr('id', opt.rowIdPrefix + rowId);
        row.addClass(CLASSES.ITEM);
        this.bindRowEvent(row, data);
        return row;
    },
    bindRowEvent:function(row, data) {
        var _this = this,
            opt = _this.options,
            input = row.find(':input'),
            multiple = input.is(':checkbox'),
            rowId = data[opt.rowIdKey];
        input.attr('name', opt.rowIdKey + (multiple ? '[]' : ''));
        row.hover(function() {
            row.addClass(CLASSES.HOVER);
        }, function() {
            row.removeClass(CLASSES.HOVER);
        });
        row.bind('check', function() {
            input.attr('checked', true);
            row.addClass(CLASSES.CHECK);
            if (!multiple && _this.lastCheckedRow && _this.lastCheckedRow != row) {
                _this.lastCheckedRow.trigger('uncheck');
            }
            _this.lastCheckedRow = row;
            func(opt.check || noop)(rowId, row, data);
        });
        row.bind('uncheck', function() {
            input.attr('checked', false);
            row.removeClass(CLASSES.CHECK);
            _this.btnAll.length && _this.btnAll.attr('checked', false);
            func(opt.uncheck || noop)(rowId, row, data);
        });
        row.click(function(ev) {
            if (opt.checkOnClick) {
                if (input[0].checked) {
                    multiple && row.trigger('uncheck');
                } else {
                    row.trigger('check');
                }
            }
            func(opt.click || noop)(rowId, row, data);
            ev.stopPropagation();
        });
        row.dblclick(function() {
            func(opt.dblclick || noop)(rowId, row, data);
        });
        input.click(function(ev) {
            if (input[0].checked) {
                row.trigger('check');
            } else {
                multiple && row.trigger('uncheck');
            }
            ev.stopPropagation();
        });
        if ($.fn.contextMenu) {
            row.bind('contextMenu', function() {
                for (var id in _this.rowStack) {
                    if (_this.rowStack.hasOwnProperty(id)) {
                        _this.rowStack[id] && _this.rowStack[id].trigger('uncheck');
                    }
                }
                row.trigger('check');
            });
            row.contextMenu('#' + (row.attr('data-context-menu') || opt.rightMenuId), function(action) {
                if ((action = func(action))) {
                    action(rowId, row, data);
                }
            });
        }
        row.data('item', data);
        return row;
    },
    renderPager:function(total) {
        var _this = this,
            pagesize = _this.options.pagesize,
            opt = _this.options.pageOptions;
        $.fn.pagination && _this.pager.pagination(total, {
            callback: function() {
                _this.loadPage.apply(_this, Array.prototype.slice.call(arguments));
            },
            items_per_page: pagesize,
            current_page: Math.max(parseInt(_this.oldData[opt.varname], 10) - 1, 0),
            num_display_entries: opt.numDisplayEntries || 2,
            num_edge_entries: opt.numEdgeEntries || 1,
            prev_text:opt.prevText,
            next_text:opt.nextText,
            prev_show_always:false,
            next_show_always:false
        });
    },
    getRow:function(id) {
        return this.rowStack[id];
    },
    addRow:function(data, prepend) {
        var row = this.renderRow(data)[prepend ? 'prependTo' : 'appendTo'](this.body),
            rowId = data[this.options.rowIdKey];
        func(this.options.rowReady || noop)(rowId, row, data);
        func(this.options.update || noop)(this.container);
        return this.rowStack[rowId] = row;
    },
    updateRow:function(id, data) {
        var oldRow = this.getRow(id),
            oldRowIsChecked = this.isRowChecked(id, oldRow),
            newRow,
            rowId = data[this.options.rowIdKey];
        if (oldRow.length) {
            newRow = this.renderRow(data);
            oldRow.trigger('beforeRemove');
            oldRow.replaceWith(newRow);
            oldRowIsChecked && newRow.trigger('check');
        }
        func(this.options.rowReady || noop)(rowId, newRow, data);
        func(this.options.update || noop)(this.container);
        return this.rowStack[data[this.options.rowIdKey]] = newRow;
    },
    deleteRow:function(ids, reload) {
        var row;
        ids = formatIds(ids);
        for (var index in ids) {
            if (ids.hasOwnProperty(index)) {
                row = this.getRow(ids[index]);
                if (row && row.length) {
                    if (this.isRowChecked(ids[index], row)) {
                        this.uncheck(ids[index]);
                    }
                    row.trigger('beforeRemove');
                    row.remove();
                }
                delete this.rowStack[index];
            }
        }
        func(this.options.update || noop)(this.container);
    },
    clear:function() {
        var ids = [];
        for (var id in this.rowStack) {
            if (this.rowStack.hasOwnProperty(id)) {
                ids.push(id);
            }
        }
        this.deleteRow(ids);
        this.body.empty();
    },
    check:function(ids) {
        var row;
        ids = formatIds(ids);
        for (var index in ids) {
            if (ids.hasOwnProperty(index)) {
                row = this.getRow(ids[index]);
                row && row.length && row.trigger('check');
            }
        }
    },
    uncheck:function(ids) {
        var row;
        ids = formatIds(ids);
        for (var index in ids) {
            if (ids.hasOwnProperty(index)) {
                row = this.getRow(ids[index]);
                row && row.length && row.trigger('uncheck');
            }
        }
    },
    checkAll:function() {
        for (var id in this.rowStack) {
            if (this.rowStack.hasOwnProperty(id)) {
                this.check(id);
            }
        }
    },
    uncheckAll:function() {
        for (var id in this.rowStack) {
            if (this.rowStack.hasOwnProperty(id)) {
                this.uncheck(id);
            }
        }
    },
    isRowChecked:function(id, row) {
        row || (row = this.getRow(id));
        if (!row.length) {
            return false;
        }
        return row.hasClass(CLASSES.CHECK);
    },
    checkedRows:function() {
        var result = [];
        for (var id in this.rowStack) {
            if (this.rowStack.hasOwnProperty(id)) {
                if (this.isRowChecked(id, this.rowStack[id])) {
                    result.push(this.rowStack[id]);
                }
            }
        }
        return result;
    },
    checkedIds:function() {
        var result = [],
            id = null,
            rows = this.checkedRows();
        for (var index in rows) {
            if (rows.hasOwnProperty(index)) {
                id = rows[index].data('item')[this.options.rowIdKey];
                id && result.push(id);
            }
        }
        return result;
    },
    setPagesize:function(pagesize) {
        if (!(pagesize = parseInt(pagesize, 10))) {
            throw new TypeError('invalid pagesize');
        }
        this.options.pagesize = pagesize;
        this.load(this.oldData);
    },
    getPagesize:function() {
        return this.options.pagesize;
    }
};
window.ItemList = ItemList;
})(jQuery);