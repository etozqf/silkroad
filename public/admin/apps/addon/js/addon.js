/**
 * plugin tabs
 */
(function($, undefined) {
var OPTIONS = {
    event: 'click',
    active: undefined,                       // on active callback
    activeIndex: undefined,                  // startup active index
    activeClass: 'tabs-trigger-item-active'  // active class name
};
$.fn.tabs = function(options) {
    return this.each(function() {
        options = $.extend({}, OPTIONS, options || {});
        var container = $(this),
            triggers = container.find('[data-tabs=triggers]'),
            triggerItems = triggers.find('[data-tabs=trigger-item]'),
            contents = container.find('[data-tabs=contents]'),
            contentItems = contents.find('[data-tabs=content-item]');
        triggerItems.each(function(index, item) {
            var $item = $(item),
                $content = contentItems.eq(index);
            $item.bind(options.event, function() {
                if ($item.hasClass(options.activeClass)) {
                    return false;
                }
                triggerItems.not(item).removeClass(options.activeClass);
                contentItems.not($content[0]).hide().removeClass(options.activeClass);
                $item.addClass(options.activeClass);
                $content.show().addClass(options.activeClass);
                $.isFunction(options.active) && options.active.apply(this, [index, $content]);
            });
        }).eq(options.activeIndex || 0).trigger(options.event);
    });
};
})(jQuery);

/**
 * main logic
 */
(function($) {
var actionLock = false, region = {}, storage = {};
function genPlaces(data, callback) {
    $.post('?app=addon&controller=addon&action=genPlaces', data, function(json) {
        if (json && json.state) {
            callback(json);
        } else {
            ct.error(json && json.error || '获取位置信息失败');
        }
    }, 'json');
}
function renderPlaces(json, callback) {
    var html = '<select name="place">';
    $.each(json.places, function(alias, name) {
        html += '<option value="' + alias + '"' + (json.selected == alias ? ' selected' : '') + '>' + name + '</option>';
    });
    html += '</select>';
    callback(html);
}
var Addon = {
    engines: {},
    contentid: undefined,
    render: function(container, contentid) {
        var self = this;
        container = container && container.jquery ? container : $(container);
        if (! container || ! container.length) return;
        contentid && (this.contentid = contentid);
        container.load('?app=addon&controller=addon&action=render' + (contentid ? ('&contentid=' + contentid ): ''), function() {
            fet('lib.list lib.json2', function() {
                self.initialize();
            });
        });
        this.container = container;
    },
    initialize: function() {
        var self = this;

        this.storageField = this.container.find('textarea[name=addons]');
        this.parseAddon(this.storageField.val());

        // 限制对话框区域
        region = this.container.offset();
        region.width = this.container.width();

        this.container.find('.addon-engine').click(function() {
            var $engine = $(this),
                engine = $engine.attr('engine');
            if (Addon.readAddon(engine)) {
                self.editAddon(engine, $engine.attr('addonid'), $engine);
            } else {
                self.addAddon(engine, $engine);
            }
            return false;
        }).filter('[addonid]').addClass('filled');
    },
    registerEngine: function(engine, options) {
        this.engines[engine] = options;
    },

    addAddon: function(engine, trigger) {
        var addonEngine = Addon.engines[engine], form;
        if (actionLock || ! addonEngine) {
            return false;
        }
        actionLock = true;
        trigger.dropdialog({
            content: '?app=addon&controller=addon&action=addAddon&engine=' + engine + '&catid=' + $('#catid').val(),
            width: addonEngine.dialogWidth || 350,
            region: region,
            closeOnBlur: false,
            buttons: {
                '确定': function() {
                    form && form.length && form.submit();
                },
                '取消': function() {
                    this.close();
                }
            },
            afterRender: function(dialog) {
                var dropdialog = this;
                addonEngine.afterRender && addonEngine.afterRender(dialog);

                form = dialog.find('form:first');
                if (! form.length) return;
                addonEngine.addFormReady && addonEngine.addFormReady(form, dialog);

                form[0].onsubmit = null;
                form.unbind('submit').submit(function() {
                    if (addonEngine.beforeSubmit && addonEngine.beforeSubmit(form, dialog) === false) {
                        return false;
                    }
                    var data = addonEngine.genData ? addonEngine.genData(form, dialog) : form.serializeObject();
                    Addon.saveAddon(engine, {
                        contentid: data.contentid || 0,
                        place: dialog.find('[name=place]').val(),
                        data: data
                    }, trigger);
                    if (addonEngine.afterSubmit && addonEngine.afterSubmit(form, dialog) === false) {
                        return false;
                    }
                    dropdialog.close();
                    return false;
                });

                genPlaces({
                    engine: engine
                }, function(json) {
                    renderPlaces(json, function(select) {
                        $(select).appendTo(dropdialog.extra).selectlist({
                            direction:'up'
                        });
                    });
                });
            },
            beforeClose: function(dialog) {
                try {
                    dialog.find('select').each(function() {
                        $(this).selectlist('destroy');
                    });
                } catch (ex) {}
            },
            afterClose: function() {
                actionLock = false;
            }
        });
    },
    editAddon: function(engine, addonid, trigger) {
        var addonEngine = Addon.engines[engine], form;
        if (actionLock || ! addonEngine) {
            return false;
        }
        actionLock = true;
        trigger.dropdialog({
            content: '?app=addon&controller=addon&action=editAddon&engine=' + engine + (addonid ? ('&addonid=' + addonid) : '') + '&catid=' + $('#catid').val(),
            width: addonEngine.dialogWidth || 350,
            region: region,
            closeOnBlur: false,
            buttons: {
                '确定': function() {
                    form && form.length && form.submit();
                },
                '取消': function() {
                    this.close();
                }
            },
            afterRender: function(dialog) {
                var dropdialog = this;
                addonEngine.afterRender && addonEngine.afterRender(dialog);

                form = dialog.find('form:first');
                if (! form.length) return;
                addonEngine.editFormReady && addonEngine.editFormReady(form, dialog);

                form[0].onsubmit = null;
                form.unbind('submit').submit(function() {
                    if (addonEngine.beforeSubmit && addonEngine.beforeSubmit(form, dialog) === false) {
                        return false;
                    }
                    var data = addonEngine.genData ? addonEngine.genData(form, dialog) : form.serializeObject();
                    Addon.saveAddon(engine, {
                        addonid: addonid,
                        contentid: data.contentid || 0,
                        place: dialog.find('[name=place]').val(),
                        data: data
                    }, trigger);
                    if (addonEngine.afterSubmit && addonEngine.afterSubmit(form, dialog) === false) {
                        return false;
                    }
                    dropdialog.close();
                    return false;
                });

                $('<a class="addon-delete" href="javascript:void(0);" title="删除">删除</a>').appendTo(dropdialog.extra).click(function() {
                    Addon.removeAddon(engine, trigger);
                    dropdialog.close();
                    return false;
                });

                genPlaces({
                    contentid: Addon.contentid,
                    engine: engine,
                    addonid: addonid
                }, function(json) {
                    Addon.readAddon(engine) && (json.selected = Addon.readAddon(engine).place);
                    renderPlaces(json, function(select) {
                        $(select).appendTo(dropdialog.extra).selectlist({
                            direction:'up'
                        });
                    });
                });
            },
            beforeClose: function(dialog) {
                try {
                    dialog.find('select').each(function() {
                        $(this).selectlist('destroy');
                    });
                } catch (ex) {}
            },
            afterClose: function() {
                actionLock = false;
            }
        });
    },
    initAddon: function(data) {
        $.each(data, function(engine, recorder) {
            Addon.saveAddon(engine, {
                addonid: recorder.addonid,
                contentid: recorder.contentid || 0,
                place: recorder.place,
                data: recorder.data
            });
        });
    },
    parseAddon: function(data) {
        if (data) {
            data = JSON.parse(data);
            this.initAddon(data);
        }
    },
    readAddon: function(engine) {
        return storage[engine] || undefined;
    },
    saveAddon: function(engine, data, trigger) {
        storage[engine] = data;
        this.storageField.val(JSON.stringify(storage));
        trigger && trigger.addClass('filled');
    },
    removeAddon: function(engine, trigger) {
        delete storage[engine];
        this.storageField.val(JSON.stringify(storage));
        trigger && trigger.removeClass('filled');
    }
};
window.Addon = Addon;
})(jQuery);