$(function() {
    var title = $('#title');
    var description = $('[name=description]');
    var thumb = $('[name=thumb]');

    var pickerResult = $('.picker-result');
    var pickerOverlay = $('.picker-overlay');

    MobileContent.init();

    // 选取内容
    var rowReference = $('#row-reference');
    var referenceid = rowReference.find('[name=referenceid]');
    rowReference.find('a:eq(1)').click(function() {
        ct.assoc.open('?app=survey&controller=survey&action=edit&contentid=' + referenceid.val(), 'newtab');
        return false;
    });

    var dataSurvey = $('#data-survey');

    function renderSurvey(data) {
        pickerResult.find('[data-role=design-questions]').unbind().click(function() {
            ct.assoc.open('?app=survey&controller=question&action=index&contentid=' + data.contentid, 'newtab');
            return false;
        });

        pickerResult.find('[data-role=view-log]').unbind().click(function() {
            ct.assoc.open('?app=survey&controller=report&action=answer&contentid=' + data.contentid, 'newtab');
            return false;
        });

        pickerResult.find('[data-role=questions]').html(parseInt(data.questions, 10) || 0);
        pickerResult.find('[data-role=answers]').html(parseInt(data.answers, 10) || 0);
        pickerResult.find('[data-role=time]').html((data.starttime || '未设置') + ' ~ ' + (data.endtime || '未设置'));
        pickerResult.find('[data-role=maxanswers]').html(parseInt(data.maxanswers, 10) ? parseInt(data.maxanswers, 10) + ' 人' : '不限');
        pickerResult.find('[data-role=minhours]').html(parseInt(data.minhours, 10) ? '同IP ' + parseInt(data.minhours, 10) + ' 小时内不得重复提交' : '不限');
        pickerResult.find('[data-role=description]').html(data.description);

        pickerOverlay.hide();
    }

    var btnPick = $('#btn-pick').click(function() {
        $.datapicker({
            url: '?app=system&controller=port&action=picker&modelid=9&catid='+(MobileContent.bindCatids || ''),
            picked: function(items, port){
                if (!port || port == 'cmstop') {
                    loadContent(items[0].contentid);
                } else {
                    ct.error('只支持从 CmsTop 数据源中选取调查');
                }
            },
            multiple: false
        });
        return false;
    });

    pickerOverlay.click(function() {
        btnPick.triggerHandler('click');
    });
    if (dataSurvey.length) {
        renderSurvey(JSON.parse(dataSurvey.html()) || []);
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

        var thumbig = $('[name="thumbig"]').val();
        if (thumbig && (['jpg','jpeg','png','bmp','gif'].indexOf(thumbig.split('.').pop()) == -1)) {
            ct.warn('内容缩略图不合法');
            return false;
        }

        lock = true;
        return true;
    });
    var loadContent = function(contentid) {
        $.post('?app=survey&controller=survey&action=detail', {contentid:contentid}, function(json) {
            if (json && json.state) {
                title.val(json.data.title).trigger('change');
                thumb.val(json.data.thumb).trigger('change');
                description.val(json.data.description);
                referenceid.val(json.data.contentid);
                rowReference.find('a:eq(0)').attr('href', json.data.url).text(json.data.title);
                rowReference.show();
                renderSurvey(json.data);
                MobileContent.formChanged = true;
            } else {
                ct.error('获取调查信息失败，请重试');
            }
        }, 'json');
    };
    window.loadContent = loadContent;
});