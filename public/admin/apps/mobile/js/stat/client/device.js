$(function() {
    var dateMin = $('[name=starttime]'),
        dateMax = $('[name=endtime]'),

        tabDeviceContainer = $('#tab-device'),
        tabDevice = tabDeviceContainer.find('li'),
        tabDeviceContents = tabDeviceContainer.nextAll('.ui-diagram-tab-content'),

        tabOSContainer = $('#tab-os'),
        tabOS = tabOSContainer.find('li'),
        tabOSContents = tabOSContainer.nextAll('.ui-diagram-tab-content'),

        tplTable = $('#tpl-table').html(),
        tplRow = $('#tpl-row').html(),

        TABLES = {},
        viewTabDevice,
        viewTabOS;

    // 初始化日期选择器
    dateMin.DatePicker({
        format:'yyyy-MM-dd',
        change:refresh
    });
    dateMax.DatePicker({
        format:'yyyy-MM-dd',
        change:refresh
    });

    function refresh() {
        TABLES = {};
        viewTabDevice.find('a').click();
        viewTabOS.find('a').click();
    }

    function query(gettype, datatype, callback) {
        $.post('?app=mobile&controller=stat&action=client_device', {
            gettype: gettype,
            datatype: datatype,
            starttime: dateMin.val(),
            endtime: dateMax.val()
        }, function(json) {
            callback(json && json.state && json.data || []);
        }, 'json');
    }

    function render(to, name, data) {
        var table = $(tplTable),
            tbody = table.find('tbody');
        to.empty();
        table.appendTo(to);
        $.each(data, function(key, row) {
            row.name = key;
            $(ct.renderTemplate(tplRow, row)).appendTo(tbody);
        });
        return table;
    }

    function preapre(type, tabs, contents, index, tab) {
        var content = contents.eq(index),
            renderTo = content.find('[data-table]'),
            table = renderTo.attr('data-table'),
            datatype = table.split('-')[0],
            gettype = table.split('-')[1],
            seriesName;
        tab = $(tab);
        seriesName = tab.text();
        tab.find('a').click(function() {
            tabs.removeClass('active');
            tab.addClass('active');
            contents.hide();
            content.show();
            if (type == 'device') {
                viewTabDevice = tab;
            } else {
                viewTabOS = tab;
            }
            if (!TABLES[table]) {
                content.loading();
                query(gettype, datatype, function(json) {
                    TABLES[table] = render(renderTo, seriesName, json);
                    content.loading('hide');
                    // 设置对应 tab content 的最小高度，以防止切换时闪动
                    contents.css('min-height', content.height());
                });
            }
        });
    }

    // 初始化选项卡
    viewTabDevice = tabDevice.each(function(index, tab) {
        preapre('device', tabDevice, tabDeviceContents, index, tab);
    });
    viewTabDevice.eq(0).find('a').click();
    viewTabOS = tabOS.each(function(index, tab) {
        preapre('os', tabOS, tabOSContents, index, tab);
    });
    viewTabOS.eq(0).find('a').click();
});