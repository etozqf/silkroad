(function($) {
    var options, optionsFields,
        FIELDID;

    function formReady(form, dialog) {
        var type = form.find('[name=type]'),
            placeholder = form.find('[data-role=placeholder]');

        options = placeholder.find('[data-role=options]');

        function showOptionsDetail(elem) {
            elem.parent().nextAll('[data-rule=advanced]').show();
        }
        function hideOptionsDetail(elem) {
            elem.parent().nextAll('[data-rule=advanced]').hide();
        }

        function loadDesignHTML(type) {
            options.empty();
            options.load('?app=activity&controller=field&action=design&type='+type+(FIELDID ? '&fieldid='+FIELDID : ''), function() {
                if (options.children().length) {
                    placeholder.show();
                    var rules = options.find('select').selectlist().bind('changed', function(ev, list) {
                        if (list.checked[0] == '__advanced__') {
                            showOptionsDetail(rules);
                        } else {
                            hideOptionsDetail(rules);
                        }
                    });
                    if (rules.val() == '__advanced__') {
                        showOptionsDetail(rules);
                    } else {
                        hideOptionsDetail(rules);
                    }
                } else {
                    placeholder.hide();
                }
            });
        }

        type.selectlist().bind('changed', function(ev, list) {
            loadDesignHTML(list.checked[0]);
        });
        if (type.val()) {
            loadDesignHTML(type.val());
        }
    }

    function beforeSerialize(form) {
        optionsFields = options.find(':input');
        form[0].options.value = JSON.stringify(optionsFields.serializeObject() || '');
        optionsFields.attr('disabled', true);
    }

    var field = window.field = {
        add:function() {
            FIELDID = null;
            ct.formDialog({
                title:'添加字段',
                width:500
            }, '?app=activity&controller=field&action=add', function(json) {
                if (json && json.state) {
                    field.refreshForm(function() {
                        window.location.reload();
                    });
                    return true;
                } else {
                    optionsFields.removeAttr('disabled');
                }
            }, formReady, null, beforeSerialize);
        },
        edit:function(fieldid) {
            FIELDID = fieldid;
            ct.formDialog({
                title:'编辑字段',
                width:500
            }, '?app=activity&controller=field&action=edit&fieldid='+fieldid, function(json) {
                if (json && json.state) {
                    field.refreshForm(function() {
                        window.location.reload();
                    });
                    return true;
                } else {
                    optionsFields.removeAttr('disabled');
                }
            }, formReady, null, beforeSerialize);
        },
        del:function(fieldid) {
            $.getJSON('?app=activity&controller=field&action=del&fieldid='+fieldid, function(json) {
                if (json && json.state) {
                    field.refreshForm(function() {
                        window.location.reload();
                    });
                    return true;
                } else {
                    ct.error(json && json.error || '删除失败，请重试');
                }
            });
        },
        sort:function(fieldid, oldIndex, newIndex) {
            $.post("?app=activity&controller=field&action=updown", {
                fieldid: fieldid,
                old: oldIndex,
                now: newIndex
            }, function() {
                field.refreshForm();
            });
        },
        refreshForm:function(callback) {
            $('#frame_container iframe', parent.document).filter(function(){
                return /\?app=activity&controller=activity&action=(add|edit)/i.test(this.src);
            }).each(function(){
                var contentid = /contentid=([\d]+)/.exec(this.src);
                contentid = contentid ? parseInt(contentid[1]) : 0;
                this.contentWindow.activityField && this.contentWindow.activityField.render(contentid);
            });
            $.isFunction(callback) && callback();
        }
    };
})(jQuery);