
function initWidget(widget, step) {
    widget.jquery || (widget = $(widget));
    if (!step) {
        var engine = widget.attr('engine'), engines = DIY.getWidgetEngines(),
            hooks = (engine in engines) ? engines[engine] : {};
        hooks.widgetid = widget[0].id;
        hooks.content = widget.find('.diy-content');
        hooks.afterRender && hooks.afterRender.call(hooks, widget);
    }
    if (!step || step == 1) {
        pushGuid(widget[0].id);
        widget.addClass('diy-placement');
        widget.find('.diy-content').html(spinning());
        widget.append('<div class="diy-masker"></div>');
    }
    if (!step || step == 2) {
        new dragMent(widget, placeMentDragOptions);
        bindOver(widget);
        bindMove(widget);
        widget.dblclick(function(){
            DIY.editWidget(widget);
        });
    }
}
(function(){
var WIDGET_ENGINES = {};
$.extend(DIY, {
    getWidgetEngines:function(){
        return WIDGET_ENGINES;
    },
    registerEngine:function(engine, o){
        WIDGET_ENGINES[engine] = o;
    },
    addWidget:function(engine){
        var widget = Gen.renderWidget(engine), content = widget.find('.diy-content'),
            hooks = (engine in WIDGET_ENGINES) ? WIDGET_ENGINES[engine] : {},
            url = '?app=special&controller=online&action=addWidget&engine='+encodeURIComponent(engine);
        hooks.widgetid = widget[0].id;
        hooks.content = content;
        initWidget(widget, 1);
        LANG.BUTTON_OK = '完成';
        LANG.BUTTON_NEXTSTEP = '下一步';
        LANG.BUTTON_PREVIEW = '预览';
        ajaxForm({
            width:hooks.dialogWidth || 450,
            title:'添加模块'
        }, url, function(json, flag) {
            content.html(json.html||'');
            hooks.afterRender && hooks.afterRender.call(hooks, widget);
            if (flag != 'preview') {
                initWidget(widget, 2);
                widget.attr('widgetid', json.widgetid);
                widget.attr('engine', engine);
                blink(widget);
                History.log('add', [widget, widget[0].parentNode, widget.next()[0]]);
                if (flag == 'nextstep') {
                    LANG.BUTTON_OK = '完成';
                    LANG.BUTTON_NEXTSTEP = '下一步';
                    LANG.BUTTON_CANCEL = null;
                    DIY.setTitle(widget);
                }
            }
        }, hooks.addFormReady, hooks.beforeSubmit, hooks.afterSubmit,
        function(){
            placementPanel.appendTo($fragments);
            widget.remove();
        }, null, hooks);
        return widget;
    },
    useWidget:function(engine, widgetid){
        var widget = Gen.renderWidget(engine), content = widget.find('.diy-content'),
            hooks = (engine in WIDGET_ENGINES) ? WIDGET_ENGINES[engine] : {},
            url = '?app=special&controller=online&action=useWidget&widgetid='+widgetid;
        hooks.widgetid = widget[0].id;
        hooks.content = content;
        initWidget(widget, 1);
        LANG.BUTTON_OK = '完成';
        LANG.BUTTON_NEXTSTEP = '下一步';
        LANG.BUTTON_PREVIEW = '预览';
        var hasSkin = 0;
        ajaxForm({
            title:'使用模块',
            width:300,
            height:110
        }, url, function(json, flag){
            if (! json.state) {
                body.message('fail', json.error);
                return;
            }
            content.html(json.html||'');
            if (!hasSkin) {
                Gen.useSkin(widget, engine, json.skin);
                hasSkin = 1;
            }
            hooks.afterRender && hooks.afterRender.call(hooks, widget);
            if (flag != 'preview') {
                initWidget(widget, 2);
                widget.attr('widgetid', json.widgetid);
                widget.attr('engine', engine);
                json.modified || widget.removeClass('diy-modified');
                blink(widget);
                History.log('add', [widget, widget[0].parentNode, widget.next()[0]]);
                if (flag == 'nextstep') {
                    LANG.BUTTON_OK = '完成';
                    LANG.BUTTON_NEXTSTEP = '下一步';
                    LANG.BUTTON_CANCEL = null;
                    DIY.setTitle(widget);
                }
            }
        }, null, null, null, function(){
            placementPanel.appendTo($fragments);
            widget.remove();
        }, null, hooks);
        return widget;
    },
    shareWidget:function(widget){
        var widgetid = widget.attr('widgetid'),
            url = '?app=special&controller=online&action=shareWidget&widgetid='+widgetid;
        ajaxForm('共享模块', url, function(json){
            if (json.state) {
                body.message('info', json.info);
                DIY.trigger('query-widget', ['', json.widget.engine, json.widget.folder]);
            } else {
                body.message('fail', json.error);
            }
        }, function(form, dialog) {
            dialog.find('select').selectlist();
            dialog.find('[name="thumb"]').imageInput(1);
        }, null, null, null, function(data){
            data.push({
                name:'skin',
                value:Gen.genWidget(widget[0])
            });
        });
    },
    editWidget:function(widget){
        var engine = widget.attr('engine'), content = widget.find('.diy-content'),
            hooks = (engine in WIDGET_ENGINES) ? WIDGET_ENGINES[engine] : {},
            widgetid = widget.attr('widgetid'),
            url = '?app=special&controller=online&action=editWidget&engine='+encodeURIComponent(engine)+'&widgetid='+widgetid,
            fg = $(document.createElement('div')),
            bak = null, bakd = 0;
        hooks.widgetid = widgetid;
        hooks.content = content;
        LANG.BUTTON_OK = '保存';
        LANG.BUTTON_PREVIEW = '预览';
        ajaxForm({
            width:hooks.dialogWidth || 450,
            title:'编辑模块'
        }, url, function(json, flag){
            var tobak = !bakd && flag == 'preview';
            tobak && (bakd = 1);
            if (flag == 'preview') {
                tobak && (bak = {
                    0:moveChilren(content, fg),
                    1:widget.hasClass('diy-modified')
                });
                content.html(json.html);
                hooks.afterRender && hooks.afterRender.call(hooks, widget);
                widget.addClass('diy-modified');
            } else {
                bakd = 0;
                $('div.diy-widget[widgetid="'+widgetid+'"]').each(function(){
                    var w = $(this), c = w.find('.diy-content');
                    c.html(json.html);
                    hooks.afterRender && hooks.afterRender(w);
                    w.addClass('diy-modified');
                });
            }
        }, hooks.editFormReady, hooks.beforeSubmit, hooks.afterSubmit,
        function(){
            if (bakd) {
                content.html(bak[0]);
                bak[1] || widget.removeClass('diy-modified');
                bak = null;
            }
            fg.remove();
        }, null, hooks);
    },
    pubWidget:function(widget){
        var widgetid = widget.attr('widgetid'),
            url = '?app=special&controller=online&action=pubWidget&widgetid='+widgetid;
        json(url, function(json){
            if (json.state) {
                $('div.diy-widget[widgetid="'+widgetid+'"]').removeClass('diy-modified');
                body.message('info', json.info);
            } else {
                body.message('fail', json.error);
            }
        });
    }
});

DIY.registerInit(function(){
    var inload = 0, stack = [], ival = setTimeout(prepare, 5000);
    function dequeue(){
        var w;
        while (inload < 3 && (w = stack.shift())) {
            loadWidget(w);
            inload++;
        }
    }
    function loadWidget(widget){
        var url = envUrl('?app=special&controller=online&action=renderWidget&widgetid='+widget.attr('widgetid')),
            hooks = WIDGET_ENGINES[widget.attr('engine')];
        widget.find('.diy-content').load(url, function(){
            inload--;
            dequeue();
            hooks.afterRender && hooks.afterRender.call(hooks, widget);
        });
    }
    function prepare(){
        if (DIY.isReady()) return;
        clearTimeout(ival);
        ival = null;
        $('.diy-widget').each(function(){
            var w = $(this);
            stack.push(w);
            initWidget(w);
        });
        dequeue();
        DIY.trigger('ready');
    }
    $(window).bind('load', prepare);
});
})();
/**
 * CmsTop 专题组件（widget）助手文件
 *
 * 提供专题 widget 的常见操作，如拖动排序，上移下移等操作
 *
 * @depends jquery.js cmstop.js config.js
 */

window.cmstop || (cmstop = {});
cmstop.widget || (cmstop.widget = {});

/**
 * 专题列表组件拖动排序
 *
 * 仅使用于如下的结构：
 * @code
 * <div class="list-area">
 *     <div class="list-sepr">...</div>
 *     <div class="list-item">...</div>
 *     <div class="list-sepr">...</div>
 *     <div class="list-item">...</div>
 *     ...
 * </div>
 * @endcode
 *
 * @depends jquery.js, jquery.ui.js
 */
(function($, ct) {
$.extend(ct.widget, {
    dragSort: function() {
        var OPTIONS = {
                'handle': '.list-ctrl',
                'number': 'span.num',
                'child-class': 'list-item',
                'sepr-class': 'list-sepr'
            },
            DOT = '.',
            HELPER = 'drag-sort-helper',
            inited = false,
            seprPrev = undefined,
            from = undefined,
            to = undefined;
        return function(elem, options) {
            var o = $.extend({}, OPTIONS, options || {}),
                childClass = DOT + o['child-class'],
                seprClass = DOT + o['sepr-class'];
            if (inited) {
                elem.sortable('refresh');
            } else {
                elem.sortable({
                    'axis': 'y',
                    'handle': o['handle'],
                    'items': childClass,
                    'cancel': seprClass,
                    'helper': 'clone',
                    'placeholder': o['child-class'] + ' ' + HELPER,
                    'opacity': 0.6,
                    create: function() {
                        inited = true;
                    },
                    start: function(ev, ui) {
                        from = ui.item.parent().children(childClass).index(ui.item[0]);
                        seprPrev = ui.item.prev(seprClass).hide();
                        elem.find(DOT + HELPER).css('height', ui.item.height());
                    },
                    stop: function(ev, ui) {
                        var nextIsSepr = ui.item.next(':first').is(seprClass),
                            prevIsSepr = ui.item.prev(':first').is(seprClass),
                            items = ui.item.parent().children(childClass);
                        to = items.index(ui.item[0]);
                        if (prevIsSepr || from > to && ! nextIsSepr) {
                            seprPrev.insertAfter(ui.item);
                        }
                        if (nextIsSepr || from < to && ! prevIsSepr) {
                            seprPrev.insertBefore(ui.item);
                        }
                        seprPrev.show();
                        seprPrev = undefined;

                        items.each(function(index) {
                            $(this).find(o.number).text(index + 1);
                        });
                    }
                });
            }
        };
    }()
});
})(jQuery, cmstop);