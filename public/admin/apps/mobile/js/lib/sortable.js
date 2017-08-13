;(function($) {
window.TableSortable = function(table, handlerIndex, sortTextIndex, nonWidthIndex, onChange) {
    table.bind('update', function() {
        var tbody = $(this).find('tbody:first'),
            firstRow = tbody.find('>tr'),
            cellWidth = firstRow.find('td:eq('+nonWidthIndex+')').width();
        tbody.sortable({
            axis: 'y',
            handle: 'td:eq('+handlerIndex+')',
            forceHelperSize: true,
            placeholder: 'tr-placeholder',
            opacity: 0.8,
            start: function(ev, ui) {
                $('<td colspan="' + ui.item.children().length + '">&nbsp;</td>').appendTo(ui.placeholder);
                ui.helper.find('td:eq('+nonWidthIndex+')').width(cellWidth);
                ui.helper.css('background-color', '#FFF');
                ct.IE && ui.helper.css('margin-left', '0px');
            },
            stop: function(ev, ui) {
                var oldIndex = parseInt(ui.item.find('td:eq('+sortTextIndex+')').html(), 10),
                    newIndex = tbody.find('>tr').index(ui.item.get(0)) + 1,
                    diff = oldIndex - newIndex;
                if (diff) {
                    $.isFunction(onChange) && onChange.apply(ui.item, [oldIndex, newIndex]);
                }
                tbody.find('>tr').each(function(index, tr) {
                    $(tr).find('td:eq('+sortTextIndex+')').text(index + 1);
                });
            }
        });
    });
};
})(jQuery);