;$(function() {
    var day_min = $('[name=day_min]');
    var day_max = $('[name=day_max]');
    var filterCategory = $('#filter-category').find('a').click(function() {
        if ($(this).attr('data-category')) {
            filterViewtype.removeClass('checked').filter('[data-viewtype=date]').addClass('checked');
        }
        highlightAndQuery.call(this);
    });
    var filterModelid = $('#filter-model').find('a').click(highlightAndQuery);
    var filterViewtype = $('#filter-viewtype').find('a').click(function() {
        if (filterCategory.filter('.checked').attr('data-category') && $(this).attr('data-viewtype') == 'category') {
            return false;
        }
        highlightAndQuery.call(this);
    });
    var containerDate = $('#stat-diagram-date'), chartDate;
    var containerCategory = $('#stat-diagram-category'), chartCategory;
    var table = $('#stat-table'), thead = table.find('thead'), tbody = table.find('tbody');

    day_min.DatePicker({
        format:'yyyy-MM-dd',
        change:query
    });
    day_max.DatePicker({
        format:'yyyy-MM-dd',
        change:query
    });

    function highlightAndQuery() {
        $(this).addClass('checked').siblings().removeClass('checked');
        query();
    }

    function query() {
        var checkedCatetory = filterCategory.filter('.checked');
        var checkedModel = filterModelid.filter('.checked');
        var params = {
            day_min: day_min.val(),
            day_max: day_max.val(),
            catid: checkedCatetory.attr('data-category') || '',
            modelid: checkedModel.attr('data-modelid') || '',
            viewtype: filterViewtype.filter('.checked').attr('data-viewtype') || ''
        };
        $.post('?app=mobile&controller=stat&action=content_query', params, function (json) {
            var options = {
                title: [
                    json.start,
                    ' ~ ',
                    json.end,
                    ' 移动端内容统计报表'
                ].join(''),
                subtitle: [
                    '[', checkedCatetory.text(), '频道] ', '[', checkedModel.text(), '模型]'
                ].join(''),
                posts: [],
                pv: [],
                comments: []
            };

            var totalPosts = json.posts || 0,
                totalPV = json.pvs || 0,
                totalComments = json.comments || 0;
            var posts = 0, pv = 0, comments = 0;

            tbody.empty();

            if (params.viewtype == 'category') {
                containerCategory.show();
                containerDate.hide();
                thead.find('th:first').text('频道');
                options.xAxis = {
                    categories: []
                };
                for (var category in json.rows) {
                    posts = parseInt(json.rows[category].posts, 10) || 0;
                    pv = parseInt(json.rows[category].pv, 10) || 0;
                    comments = parseInt(json.rows[category].comments, 10) || 0;

                    // chart
                    options.xAxis.categories.push(category);
                    options.posts.push(posts);
                    options.pv.push(pv);
                    options.comments.push(comments);

                    // table
                    tbody.append([
                        '<tr>',
                        '<td class="t_c">', category, '</td>',
                        '<td class="t_c">', posts, '</td>',
                        '<td class="t_c">', pv, '</td>',
                        '<td class="t_c">', comments, '</td>',
                        '</tr>'
                    ].join(''));
                }

                chartCategory && chartCategory.destroy();
                chartCategory = createChart({
                    posts:options.posts,
                    pv:options.pv,
                    comments:options.comments,
                    chart: {
                        renderTo: 'stat-diagram-category',
                        type: 'column'
                    },
                    title: options.title,
                    subtitle: options.subtitle,
                    xAxis: options.xAxis,
                    tooltip: {
                        formatter: function() {
                            return '' + this.x + '频道 ' + this.series.name + ' ' + this.y;
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    }
                });
            } else {
                containerDate.show();
                containerCategory.hide();
                thead.find('th:first').text('日期');

                var categories = [];
                for (var day in json.rows) {
                    categories.push(day);
                    posts = parseInt(json.rows[day].posts, 10) || 0;
                    pv = parseInt(json.rows[day].pv, 10) || 0;
                    comments = parseInt(json.rows[day].comments, 10) || 0;

                    // chart
                    options.posts.push(posts);
                    options.pv.push(pv);
                    options.comments.push(comments);

                    // table
                    tbody.append([
                        '<tr>',
                            '<td class="t_c">', day, '</td>',
                            '<td class="t_c">', posts || 0, '</td>',
                            '<td class="t_c">', pv || 0, '</td>',
                            '<td class="t_c">', comments || 0, '</td>',
                        '</tr>'
                    ].join(''));
                }

                chartDate && chartDate.destroy();
                chartDate = createChart({
                    posts:options.posts,
                    pv:options.pv,
                    comments:options.comments,
                    chart: {
                        renderTo: 'stat-diagram-date',
                        type: 'spline',
                        spacingRight: 35,
                        zoomType: 'x'
                    },
                    title: options.title,
                    subtitle: options.subtitle,
                    xAxis: {
                        categories: categories,
                        labels: {
                            step: Math.ceil(categories.length / 10),
                            formatter: function() {
                                var arr = (this.value + '').split('-');
                                arr.splice(0, 1);
                                return arr.join('-');
                            }
                        }
                    },
                    tooltip: {
                        formatter: function() {
                            return '' + this.x + ' ' + this.series.name + ' ' + this.y;
                        },
                        crosshairs: true
                    }
                });
            }

            $('#total_posts').text(totalPosts);
            $('#total_pv').text(totalPV);
            $('#total_comments').text(totalComments);
        }, 'json');

        return false;
    }

    function createChart(options) {
        return new Highcharts.Chart({
            chart: options.chart,
            title: {
                text: options.title
            },
            subtitle: {
                text: options.subtitle
            },
            xAxis: options.xAxis,
            yAxis: {
                title: null,
                allowDecimals: false,
                min: 0,
                showFirstLabel: false
            },
            legend: {
                margin: 25
            },
            tooltip: options.tooltip,
            plotOptions: options.plotOptions,
            series: [{
                name: '发稿量',
                data: options.posts
            }, {
                name: '访问量',
                data: options.pv
            }, {
                name: '评论量',
                data: options.comments
            }],
            navigation: {
                menuItemStyle: {
                    fontSize: '12px'
                }
            },
            credits: {
                enabled: true,
                text: 'CmsTop Stat',
                href: 'http://www.cmstop.com/',
                position: {
                    align: 'right',
                    x: -20,
                    verticalAlign: 'top',
                    y: 20
                },
                style: {
                    cursor: 'pointer',
                    color: '#909090',
                    fontSize: '10px'
                }
            }
        });
    }

    query();
});