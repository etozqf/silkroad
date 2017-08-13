$(function() {
    var title = $('#title');
    var content = $('#content');
    var description = $('[name=description]');
    var thumb = $('[name=thumb]');
    var source = $('[name=source]');
    var video = $('[name=video]');
    var playtime = $('[name=playtime]');

    MobileContent.init();

    // 选取内容
    var rowReference = $('#row-reference');
    var referenceid = rowReference.find('[name=referenceid]');
    rowReference.find('a:eq(1)').click(function() {
        ct.assoc.open('?app=video&controller=video&action=edit&contentid=' + referenceid.val(), 'newtab');
        return false;
    });
    rowReference.find('a:eq(2)').click(function() {
        referenceid.val('');
        rowReference.hide();
        MobileContent.formChanged = true;
    });
    $('#btn-pick').click(function() {
        var _dialog = ct.iframe({
            url:'?app=video&controller=video&action=picker&mobile=1&catid='+(MobileContent.bindCatids || ''),
            width:500
        }, {
            ok: function(item) {
                loadContent(item);
                _dialog.dialog('close');
            }
        });
        return false;
    });

    // 选取视频
    $('#btn-pick-vms').click(function() {
        var _dialog = ct.iframe({
            title:'?app=video&controller=vms&action=index&selector=1',
            width:600,
            height:519
        }, {
            ok:function(json) {
                if (title.val().length == 0) {
                    title.val(json.title).trigger('change');
                }
                video.val('[ctvideo]'+json.id+'[/ctvideo]');
                thumb.val(json.pic);
                playtime.val(json.playtime);

                referenceid.val('');
                rowReference.hide();

                _dialog.dialog('close');
                MobileContent.formChanged = true;
            }
        });
        return false;
    });

    // 搜索视频
    $('#btn-search').click(function() {
        var _dialog = ct.iframe({
            'url':'?app=video&controller=search',
            'width':800,
            'height':500
        }, {
            ok:function(json) {
                if (json.state) {
                    title.val(json.title);
                    video.val(json.content.url);
                    thumb.val(json.content.thumb);
                    playtime.val(json.time || '');

                    referenceid.val('');
                    rowReference.hide();
                }
                _dialog.dialog('close');
                MobileContent.formChanged = true;
            }
        });
        return false;
    });

    // 第三方视频
    var thirdPartyPanel = $('#video-third-party');
    thirdPartyPanel.length && $.getJSON('?app=video&controller=thirdparty&action=getlist', function(json) {
        if (json && json.data) {
            $.each(json.data, function(index, item) {
                $('<input type="button" class="button_style_1" value="'+item.title+'" />').appendTo(thirdPartyPanel).click(function() {
                    var _dialog = ct.iframe({
                        title:'?app=video&controller=thirdparty&action=selector&id='+item.id,
                        width:800,
                        height:469
                    }, {
                        ok:function(json){
                            title.val(json.title);
                            video.val(json.video);
                            playtime.val(json.time);
                            thumb.val(json.thumb).trigger('change');

                            referenceid.val('');
                            rowReference.hide();

                            _dialog.dialog('close');
                            MobileContent.formChanged = true;
                        }
                    });
                    return false;
                });
            });
        }
    });

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

    var loadContent = function(item) {
        var render = function(item) {
            if (title.val().length == 0) {
                title.val(item.title).trigger('change');
            }
            thumb.val(item.thumb).trigger('change');
            if (item.source_name || item.source) {
                source.val(item.source_name || item.source);
            }
            description.val(item.description);
            video.val(item.video);
            playtime.val(item.playtime);

            referenceid.val(item.contentid);
            rowReference.find('a:eq(0)').attr('href', item.url).text(item.title);
            rowReference.show();

            MobileContent.formChanged = true;
        }
        if (typeof item != 'object') {
            $.post('?app=video&controller=video&action=detail', {contentid:item}, function(json) {
                if (json && json.state) {
                    render(json.data);
                }
            }, 'json');
        } else {
            render(item);
        }
    }
    window.loadContent = loadContent;
});