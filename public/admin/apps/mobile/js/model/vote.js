$(function() {
    var title = $('#title');
    var description = $('[name=description]');
    var thumb = $('[name=thumb]');

    var pickerResult = $('.picker-result');
    var pickerOverlay = $('.picker-overlay');

    var voteResult = $('#vote-result');
    var tplVoteResult = $('#tpl-vote-result').html();

    MobileContent.init();

    // 选取内容
    var rowReference = $('#row-reference');
    var referenceid = rowReference.find('[name=referenceid]');
    rowReference.find('a:eq(1)').click(function() {
        ct.assoc.open('?app=vote&controller=vote&action=edit&contentid=' + referenceid.val(), 'newtab');
        return false;
    });

    var dataVote = $('#data-vote');

    function renderVote(data) {
        pickerResult.find('[data-role=view-log]').unbind().click(function() {
            vote.vote_log(data.contentid);
            return false;
        });

        pickerResult.find('[data-role=display]').html(data.display == 'list' ? '列表模式' : '评选模式');
        pickerResult.find('[data-role=total]').html(parseInt(data.total, 10) || 0);
        pickerResult.find('[data-role=starttime]').html(data.starttime || '未设置');
        pickerResult.find('[data-role=endtime]').html(data.endtime || '未设置');
        pickerResult.find('[data-role=mininterval]').html(parseInt(data.mininterval, 10) ? '同IP ' + parseInt(data.mininterval, 10) + ' 小时内不得重复投票' : '不限');
        pickerResult.find('[data-role=description]').html(data.description);

        voteResult.empty();
        $.each(data.option, function(index, option) {
            option.index = index + 1;
            option.width = 5 * option.percent || 1;
            voteResult.append(ct.renderTemplate(tplVoteResult, option));
        });

        pickerOverlay.hide();
    }

    var btnPick = $('#btn-pick').click(function() {
        $.datapicker({
            url: '?app=system&controller=port&action=picker&modelid=8&catid='+(MobileContent.bindCatids || ''),
            picked: function(items, port){
                if (!port || port == 'cmstop') {
                    loadContent(items[0].contentid);
                } else {
                    ct.error('只支持从 CmsTop 数据源中选取投票');
                }
            },
            multiple: false
        });
        return false;
    });

    pickerOverlay.click(function() {
        btnPick.triggerHandler('click');
    });
    if (dataVote.length) {
        renderVote(JSON.parse(dataVote.val() || '[]') || []);
    } else if (!window.pushModel){
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
        $.post('?app=vote&controller=vote&action=detail', {contentid:contentid}, function(json) {
            if (json && json.state) {
                title.val(json.data.title).trigger('change');
                thumb.val(json.data.thumb).trigger('change');
                description.val(json.data.description);
                referenceid.val(json.data.contentid);
                rowReference.find('a:eq(0)').attr('href', json.data.url).text(json.data.title);
                rowReference.show();
                renderVote(json.data);
                MobileContent.formChanged = true;
            } else {
                ct.error('获取投票信息失败，请重试');
            }
        }, 'json');
    }
    window.loadContent = loadContent;
});