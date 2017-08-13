$(function() {
    var title = $('#title');
    var description = $('[name=description]');
    var thumb = $('[name=thumb]');
    var image = $('[name=image]');

    var pickerResult = $('.picker-result');
    var pickerOverlay = $('.picker-overlay');

    var $map = pickerResult.find('[data-role=map]');
    var tplMap = $('#tpl-map').html();

    MobileContent.init();

    // 选取内容
    var rowReference = $('#row-reference');
    var referenceid = rowReference.find('[name=referenceid]');
    rowReference.find('a:eq(1)').click(function() {
        ct.assoc.open('?app=activity&controller=activity&action=edit&contentid=' + referenceid.val(), 'newtab');
        return false;
    });

    var dataActivity = $('#data-activity');

    function renderActivity(data) {
        var gender = parseInt(data.gender, 10);
        var maxpersons = parseInt(data.maxpersons, 10);

        pickerResult.find('[data-role=time-sign]').html(data.signstart && data.signend
            ? (data.signstart + ' ~ ' + data.signend)
            : (data.signstart ? '开始于 ' + data.signstart : '于 ' + data.signend + ' 结束')
        );
        pickerResult.find('[data-role=time-show]').html(data.starttime && data.endtime
            ? (data.starttime + ' ~ ' + data.endtime)
            : (data.starttime ? '开始于 ' + data.starttime : '于 ' + data.endtime + ' 结束')
        );
        pickerResult.find('[data-role=maxpersons]').html(maxpersons ? maxpersons + '人' : '不限制');
        gender = gender == 1 ? '男' : (gender == 2 ? '女' : '不限制');
        pickerResult.find('[data-role=gender]').html(gender);
        pickerResult.find('[data-role=description]').html(data.content || '');
        pickerResult.find('[data-role=address]').html(data.address || '');

        var point = (data.point || '').split(',');
        if (point.length > 1) {
            $map.html(ct.renderTemplate(tplMap, {
                lng: point[0],
                lat: point[1],
                zoom: point[2],
                address: data.address
            })).show();
        } else {
            $map.hide();
        }

        pickerOverlay.hide();
    }

    var btnPick = $('#btn-pick').click(function() {
        $.datapicker({
            url: '?app=system&controller=port&action=picker&modelid=7&catid='+(MobileContent.bindCatids || ''),
            picked: function(items, port){
                if (!port || port == 'cmstop') {
                    loadContent(items[0].contentid);
                } else {
                    ct.error('只支持从 CmsTop 数据源中选取活动');
                }
            },
            multiple: false
        });
        return false;
    });

    pickerOverlay.click(function() {
        btnPick.triggerHandler('click');
    });
    if (dataActivity.length) {
        renderActivity(JSON.parse(dataActivity.val()) || [])
    } else if (!window.pushModel) {
        pickerOverlay.click();
    }

    // form
    var lock = false;
    $('form').ajaxForm(function(json) {
        if (json && json.state) {
            lock = true;
            MobileContent.success(json);
        } else {
            lock = false;
            ct.error(json && json.error || '操作失败，请重试');
        }
    }, null, function(form) {
        if (lock) {
            ct.error('正在提交，请稍后');
            return false;
        }

        if (!referenceid.val()) {
            ct.error('请选取活动');
            return false;
        }

        var category = form.find('[name=catid]');
        if (!category.val()) {
            ct.error('请选择栏目');
            return false;
        }

        var thumb = $('[name="thumb"]').val();
        if (thumb && (['jpg','jpeg','png','bmp','gif'].indexOf(thumb.split('.').pop()) == -1)) {
            ct.warn('列表缩略图不合法');
            return false;
        }

        var thumbSlider = $('[name="thumb_slider"]').val();
        if (thumbSlider && (['jpg','jpeg','png','bmp','gif'].indexOf(thumbSlider.split('.').pop()) == -1)) {
            ct.warn('幻灯片缩略图不合法');
            return false;
        }

        lock = true;
        return true;
    });
    var loadContent =  function(contentid) {
        $.post('?app=activity&controller=activity&action=detail', {
            contentid: contentid
        }, function(json) {
            if (json && json.state) {
                title.val(json.data.title).trigger('change');
                thumb.val(json.data.thumb).trigger('change');
                description.val(json.data.description);
                referenceid.val(json.data.contentid);
                rowReference.find('a:eq(0)').attr('href', json.data.url).text(json.data.title);
                rowReference.show();
                renderActivity(json.data);
                MobileContent.formChanged = true;
            } else {
                ct.error('获取活动信息失败，请重试');
            }
        }, 'json');
    }
    window.loadContent = loadContent;
});