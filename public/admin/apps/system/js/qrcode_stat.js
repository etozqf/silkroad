$(function() {
var chart;
window.QRCode || (window.QRCode = {});
$.extend(window.QRCode, {
    view: function(id) {
        ct.ajaxDialog({
            title: '查看详细',
            width: 500
        }, '?app=system&controller=qrcode&action=view&qrcodeid=' + id, function(dialog) {
            var pieOverlay = $('#qrcode-pie-overlay'),
                pie = $('#qrcode-pie');
            $.getJSON('?app=system&controller=qrcode&action=query&qrcodeid=' + id, function(json) {
                pieOverlay.show();
                chart && chart.destroy();
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'qrcode-pie',
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    },
                    title: {
                        text: ''
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage}% ({point.y})</b>',
                        percentageDecimals: 1
                    },
                    legend: {
                        align: 'right',
                        verticalAlign: 'top',
                        x: -10,
                        y: 50,
                        floating: true,
                        layout: 'vertical'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true,

                            center: [130, 130],
                            size: '90%'
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: '访问量',
                        data: json
                    }],
                    credits: {
                        enabled: false
                    }
                });
                pieOverlay.hide();
            });
        }, function(json) {
            return true;
        });
    },
    edit: function(id) {
        ct.formDialog({
            title: '编辑二维码',
            width: 500
        }, '?app=system&controller=qrcode&action=edit&qrcodeid=' + id, function(json) {
            if (json && json.state) {
                ct.ok('编辑成功');
                tableApp.reload();
                return true;
            }
        }, function(form, dialog) {
            QRCode.initForm(form);
        });
    },
    del: function(id) {
        if (!id) {
            id = tableApp.checkedIds() + '';
        }
        if (!id || !id.length) {
            ct.warn('请选择要删除的记录！');
            return false;
        }
        ct.confirm('操作不可恢复，确定要删除吗？', function() {
            $.getJSON('?app=system&controller=qrcode&action=delete&qrcodeid='+id, function(json) {
                if (json && json.state) {
                    ct.ok('删除成功');
                    tableApp.reload();
                } else {
                    ct.error(json && json.error || '删除失败');
                }
            });
        })
    }
});
});