$(function() {
    var tableContainer = $('#table-user'),
        table = tableContainer.find('table'),
        tbody = table.find('tbody'),
        diagramUser = $('#diagram-user'),
        filterDate = $('.ui-filter a'),
        dateMin = $('[name=starttime]'),
        dateMax = $('[name=endtime]'),
        filterCategory = $('.ui-label a'),
        selectedSeries = filterCategory.filter('.active'),
        title = '',
        diagram,
        CATEGORIES,
        SERIES,
        btnExport = $('#btn-export'),
        formExport = $('#form-export'),
        tplRow = $('#tpl-row').html();

    // 日期范围名称
    filterDate.click(function() {
        var _this = $(this);
        filterDate.removeClass('active');
        _this.addClass('active');
        dateMin.val(_this.attr('data-min'));
        dateMax.val(_this.attr('data-max'));
        query();
    });

    // 初始化日期选择器
    dateMin.DatePicker({
        format:'yyyy-MM-dd',
        change:function(date) {
            filterDate.removeClass('active').filter('[data-min="'+date+'"]').filter('[data-max="'+dateMax.val()+'"]').addClass('active');
            query();
        }
    });
    dateMax.DatePicker({
        format:'yyyy-MM-dd',
        change:function(date) {
            filterDate.removeClass('active').filter('[data-min="'+dateMin.val()+'"]').filter('[data-max="'+date+'"]').addClass('active');
            query();
        }
    });

    // 切换 diagram 显示的 category
    filterCategory.click(function() {
        if (diagram) {
            var _this = $(this);
            filterCategory.removeClass('active');
            _this.addClass('active');
            selectedSeries = _this;
            renderChart();
        }
        return false;
    });

    // 导出
    btnExport.click(function() {
        var data = { header: [], rows: [] };
        table.find('thead th').each(function() {
            data.header.push($(this).text());
        });
        tbody.children().each(function() {
            var row = [];
            $(this).children().each(function() {
                row.push($(this).text());
            });
            data.rows.push(row);
        });
        data.title = title.split(/\s+/);
        data.title.pop();
        data.title = data.title.join(' ');
        formExport[0].data.value = JSON.stringify(data);
        formExport.submit();
        return false;
    });

    function query() {
        diagramUser.parent().loading();
        tableContainer.loading();
        $.post('?app=mobile&controller=stat&action=client_user', {
            starttime: dateMin.val(),
            endtime: dateMax.val()
        }, function(json) {
            render(json && json.state && json.data || []);
            diagramUser.parent().loading('hide');
            tableContainer.loading('hide');
        }, 'json');
    }

    function render(data) {
        var categories = [],
            series = {},
            hourMode = (dateMin.val() == dateMax.val());
        tbody.empty();
        var keys = [], key;
        for (key in data) {
            if (data.hasOwnProperty(key)) {
                keys.push(key);
            }
        }
        if (hourMode) {
            table.find('thead th:eq(0)').text('时间');
        } else {
            table.find('thead th:eq(0)').text('日期');
        }
        $.each(keys, function(i, key) {
            // 渲染表格
            var row = data[key], next;
            if (hourMode) {
                next = parseInt(key, 10) + 1;
                (next == 24) && (next = '0');
                row.datetime = parseInt(key, 10) + '点 ~ ' + next + '点';
            } else {
                row.datetime = key;
            }
            tbody.append(ct.renderTemplate(tplRow, row));
            delete row.datetime;

            // 渲染图表
            categories.push(key);
            row.activepercent = parseFloat(row.activepercent);
            $.each(row, function(row_key, row_value) {
                if (!series[row_key]) series[row_key] = [];
                series[row_key].push(row_value);
            });
        });
        CATEGORIES = categories;
        SERIES = series;
        renderChart();
    }

    function renderChart() {
        var dayMin = dateMin.val(),
            dayMax = dateMax.val(),
            start = CATEGORIES[0],
            end = CATEGORIES[CATEGORIES.length - 1],
            selectedDate = filterDate.filter('.active'),
            seriesData = SERIES[selectedSeries.attr('data-series')];

        title = selectedDate.length
            ? selectedDate.text()
            : ((dayMin && dayMax && dayMin == dayMax)
                ? dayMax
                : (start
                    ? (start == end ? start : (start + ' ~ ' + end))
                    : (dayMin == dayMax ? dayMin : (dayMin + ' ~' + dayMax))
                )
            );
        title += ' ' + selectedSeries.text();

        if (diagram) diagram.destroy();
        diagram = new Highcharts.Chart({
            chart: {
                renderTo: 'diagram-user',
                type: 'line'
            },
            title: {
                text: title
            },
            subtitle: {
                text: null
            },
            xAxis: {
                categories: CATEGORIES,
                labels:{
                    step: Math.ceil(CATEGORIES.length / 10),
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
                    var name = selectedSeries.text();
                    var suffix = name == '用户活跃率' ? '%' : '';
                    if (dateMin.val() == dateMax.val()) {
                        var next = parseInt(this.x, 10) + 1;
                        return parseInt(this.x, 10) + '点 ~ ' + (next == 24 ? '0' : next) + '点 ' + name + ' ' + this.y + suffix;
                    }
                    return this.x + ' ' + name + ' ' + this.y + suffix;
                },
                crosshairs: true
            },
            series: [{
                data: seriesData
            }],
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            }
        });
    }

    // 查询
    query();
});