;(function($) {
var selected = [],
    form,
    message = $('#message'),
    lock,
    contentsPanel = $('.picker-content-list'),
    contentsPanelLoaded = $('span[data-role=loaded]'),
    contentsPanelTotal = $('span[data-role=total]'),
    lastFocusedRow,
    total = 0,
    loaded = 0,
    page = 0,
    pagesize = 13,
    multiple = false;

function query(params, clear) {
    if (lock) return;
    lock = true;
    if (clear) {
        page = 1;
        selected = [];
    } else {
        page += 1;
    }
    params.page = page;
    params.pagesize = pagesize;
    $.post('?app=vote&controller=vote&action=picker', params, function(json) {
        if (json && json.data) {
            message.hide();
            total = json.total;
            if (clear) {
                contentsPanel.empty();
                loaded = json.data.length;
            } else {
                loaded = loaded + json.data.length;
            }
            $.each(json.data, function(index, item) {
                var input,
                    row = $([
                        '<div tips="', item.tips, '" id="picker-content-'+item.contentid+'" class="picker-panel-item">',
                            '<span class="time">'+item.time+'</span>',
                            '<input type="'+(multiple ? 'checkbox' : 'radio')+'" name="item[]" />',
                            '<a href="'+item.url+'" target="_blank" class="item-title">'+item.title+'</a>',
                        '</div>'
                    ].join('')).appendTo(contentsPanel);
                row.tipsy({
                    gravity:$.fn.tipsy.autoNS,
                    title:'tips',
                    html:true,
                    delayIn:500,
                    opacity:1
                });
                row.bind('check', function() {
                    input.attr('checked', true);
                    if (multiple) {
                        addSelected(item);
                        row.addClass('selected');
                    } else {
                        lastFocusedRow && lastFocusedRow.removeClass('selected');
                        selected[0] = item;
                        lastFocusedRow = row.addClass('selected');
                    }
                });
                row.bind('uncheck', function() {
                    if (multiple) {
                        input.attr('checked', false);
                        removeSelected(item);
                        row.removeClass('selected');
                    }
                });
                input = row.find(':input').click(function(ev) {
                    if (input[0].checked) {
                        row.trigger('check');
                    } else {
                        row.trigger('uncheck');
                    }
                    ev.stopPropagation();
                });
                row.click(function(ev) {
                    if (input[0].checked) {
                        row.trigger('uncheck');
                    } else {
                        row.trigger('check');
                    }
                    ev.stopPropagation();
                });
            });
        } else {
            contentsPanel.empty();
            message.show();
            total = 0;
            loaded = 0;
        }
        contentsPanelTotal.text(total);
        contentsPanelLoaded.text(loaded);
        lock = false;
    }, 'json');
}

function addSelected(item) {
    var inStack = false;
    $.each(selected, function(index, json) {
        if (item.contentid == json.contentid) {
            inStack = true;
            return false;
        }
    });
    !inStack && selected.push(item);
}

function removeSelected(item) {
    $.each(selected, function(index, json) {
        if (item.contentid == json.contentid) {
            selected.splice(index, 1);
            return false;
        }
    });
}

window.VotePicker = {
    init:function(_multiple) {
        multiple = _multiple;

        $('select[name=modelid]').selectlist();
        $('[name=catid]').selectree();

        form = $('#form-query').submit(function() {
            query(form.serializeObject(), true);
            return false;
        });
        contentsPanel.scroll(function() {
            if (!lock && loaded < total
                && contentsPanel.scrollTop() + contentsPanel.height() > contentsPanel[0].scrollHeight - 20) {
                query(form.serializeObject());
            }
        });

        query(form.serializeObject(), true);

        $('.btn_area button').click(function() {
            var callback = window.dialogCallback;
            if (selected.length) {
                callback.ok && callback.ok(multiple ? selected : selected[0]);
            } else {
                ct.error('没有选中的内容');
            }
            return false;
        });
    }
};
})(jQuery);