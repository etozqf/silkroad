$(function() {
    var tableDays = $('#table-days'),
        tableWhole = $('#table-whole'),
        tabContainer = $('#tab-trends'),
        tabs = tabContainer.find('li'),
        contents = tabContainer.nextAll('.ui-diagram-tab-content');

    // 加载今日概况
    tableDays.loading();
    query('client_today', null, function(json) {
        $.each(json, function(day, stat) {
            var row = tableDays.find('[data-day="'+day+'"]');
            $.each(stat, function(key, value) {
                row.find('[data-role="'+key+'"]').text(value || 0);
            });
        });
        tableDays.loading('hide');
    });

    // 加载整体数据
    tableWhole.loading();
    query('client_overview', null, function(json) {
        $.each(json, function(key, value) {
            tableWhole.find('[data-role="'+key+'"]').text(value || 0);
        });
        tableWhole.loading('hide');
    });

    function query(action, gettype, callback) {
        action || (action = 'client_trends');
        $.post('?app=mobile&controller=stat&action=' + action, gettype ? {
            gettype: gettype
        } : {}, function(json) {
            callback(json && json.state && json.data || []);
        }, 'json');
    }

    function render(to, name, data) {
        var categories = [], series = [];
        $.each(data, function(key, value) {
            categories.push(key);
            series.push(value);
        });
        return new Highcharts.Chart({
            chart: {
                renderTo: to,
                type: 'line'
            },
            title: {
                text: null
            },
            subtitle: {
                text: null
            },
            xAxis: {
                categories: categories,
                labels:{
                    step: parseInt(categories.length / 7, 10),
                    formatter: function() {
                        var arr = (this.value + '').split('-');
                        if (arr.length > 2) {
                            arr.splice(0, 1);
                        }
                        return arr.join('-');
                    }
                }
            },
            yAxis: {
                title: {
                    text: null
                },
                showFirstLabel: false
            },
            tooltip: {
                formatter: function() {
                    return this.x + ' ' + this.series.name + ' ' + this.y;
                },
                crosshairs: true
            },
            series: [{
                name: name,
                data: series
            }],
            credits: {
                enabled: false
            }
        });
    }

    // 初始化图表
    tabs.each(function(index, tab) {
        var content = contents.eq(index),
            diagram,
            renderTo = content.find('[data-diagram]'),
            gettype = renderTo.attr('data-diagram'),
            seriesName;
        tab = $(tab);
        seriesName = tab.text();
        tab.click(function() {
            tabs.removeClass('active');
            tab.addClass('active');
            contents.hide();
            content.show();
            if (!diagram) {
                content.loading();
                query('client_trends', gettype, function(json) {
                    diagram = render(renderTo[0], seriesName, json);
                    content.loading('hide');
                });
            }
        });
    }).eq(0).click();
});