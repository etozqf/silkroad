(function() {
var DEFAULTS = {
    url: null,
    trigger: null,
    container: null,
    template: null,
    page: 1,
    pageVar: 'page',
    pagesize: 10,
    pagesizeVar: 'pagesize',
    sorttime: 0,
    sorttimeVar: 'time',
    loading: '加载中…'
};

function template(tpl, data) {
    if ($.isPlainObject(data)) {
        tpl = tpl.replace(/\{([^\}]+?)\}/gm, function(match, key) {
            var parts = key.split('.'), part, result = data;
            while (part = parts.shift()) {
                if (!(result = result[part])) break;
            }
            return (typeof result == 'undefined' || result === null) ? '' : result;
        });
    }
    return tpl;
}

function ScrollList(opt) {
    opt = this.options = $.extend({}, DEFAULTS, opt);

    this.trigger = opt.trigger;
    if (!this.trigger || !this.trigger.length) return;
    this.savedTriggerText = this.trigger.text();

    this.container = opt.container;
    if (!this.container || !this.container.length) return;

    this.template = window.Template ? new Template(opt.template) : opt.template;
    this.page = opt.page;
    this.pagesize = opt.pagesize;
    this.sorttime = opt.sorttime;
    this.ajaxOptions = $.isPlainObject(opt.ajaxOptions) ? opt.ajaxOptions : {};

    this.init();
}

ScrollList.prototype = {
    init: function() {
        var _this = this;
        _this.trigger.click(function() {
            _this.trigger.text(_this.options.loading);
            _this.query();
            return false;
        });
    },
    query: function() {
        var _this = this,
            opt = _this.options,
            params = _this.ajaxOptions;

        if (_this.queryLock) return;
        _this.queryLock = true;

        params[opt.pageVar] = _this.page;
        params[opt.pagesizeVar] = _this.pagesize;
        _this.sorttime && (params[opt.sorttimeVar] = _this.sorttime);

        $.post(opt.url, params, function(json) {
            var more;
            if (json && json.data) {
                _this.render(json.data);
                more = _this.page * _this.pagesize < parseInt(json.total);
                more && (_this.page += 1);
            } else {
                more = false;
            }
            more || (_this.trigger.hide());
            _this.queryLock = false;
        }, 'json');
    },
    render:function(rows) {
        var _this = this,
            fragment = document.createDocumentFragment();
        $.each(rows, function(index, row) {
            var cell = _this.template.render ? _this.template.render(row) : template(_this.template, row);
            fragment.appendChild($(cell)[0]);
        });
        _this.container.append(fragment);
        _this.trigger.text(_this.savedTriggerText);
        fragment = null;
    }
};

window.ScrollList = ScrollList;
})();