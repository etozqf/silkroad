$(function() {
    var title = $('#title');
    var description = $('[name=description]');
    var thumb = $('[name=thumb]');
    var source = $('[name=source]');

    var picturePanel = $('.picker-result');
    var pictureEmptyPanel = $('.picker-overlay');
    var tplPicture = $('#tpl-picture').html();

    MobileContent.init();

    // 选取内容
    var rowReference = $('#row-reference');
    var referenceid = rowReference.find('[name=referenceid]');
    rowReference.find('a:eq(1)').click(function() {
        ct.assoc.open('?app=picture&controller=picture&action=edit&contentid=' + referenceid.val(), 'newtab');
        return false;
    });
    var dataPictures = $('#data-pictures');
    function renderGallery(pictures) {
        var photos = [];
        $.each(pictures, function(i, row) {
            var image = row.image.indexOf('://') > -1 ? row.image : UPLOAD_URL + row.image;
            photos.push({
                big:image,
                thumb:image,
                note:row.note || ''
            });
        });
        pictureEmptyPanel.hide();
        picturePanel.empty().show();
        $(tplPicture).appendTo(picturePanel).gallery({
            photos: photos,
            maxWidth: 738,
            minHeight: 0,
            preload: 1,
            scrollIntoView: false,
            hashPageParam: '',
            thumbWidth: 80,
            thumbHeight: 60
        });
    };
    function vaildThumb(src, success, failed) {
        var img = new Image();
        img.onload = function() {
            (img.height >= img.width) ? success() : failed();
        }
        img.onerror = function () {
            ct.error('缩略图不存在');
        }
        img.src = src.indexOf('://') > -1 ? src : UPLOAD_URL + src;
    };
    var btnPick = $('#btn-pick').click(function() {
        $.datapicker({
            url: '?app=system&controller=port&action=picker&modelid=2&catid='+(MobileContent.bindCatids || ''),
            picked: function(items, port){
                if (!port || port == 'cmstop') {
                    loadContent(items[0].contentid);
                } else {
                    ct.error('只支持从 CmsTop 数据源中选取组图');
                }
            },
            multiple: false
        });
        return false;
    });
    pictureEmptyPanel.click(function() {
        btnPick.triggerHandler('click');
    });
    if (dataPictures.length) {
        renderGallery(JSON.parse(dataPictures.val()) || [])
    } else if (!window.pushModel) {
        pictureEmptyPanel.click();
    }

    // form
    var lock = false,
    thumbigVaild = false;
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
            ct.error('请选取组图');
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
        var thumbig = form.find('[name=thumbig]').val();
        if (thumbig && !thumbigVaild) {
            vaildThumb(thumbig, function() {
                thumbigVaild = true;
                lock = false;
                form.submit()
            }, function () {
                ct.error('图片高度必须大于宽度');
                lock = false;
            });
            return false;
        }
        return true;
    });
    var loadContent = function(contentid) {
        $.post('?app=picture&controller=picture&action=detail', {contentid:contentid}, function(json) {
            if (json && json.state) {
                title.val(json.data.title).trigger('change');
                thumb.val(json.data.thumb).trigger('change');
                if (json.data.source_name || json.data.source) {
                    source.val(json.data.source_name || json.data.source);
                }
                description.val(json.data.description);
                referenceid.val(json.data.contentid);
                rowReference.find('a:eq(0)').attr('href', json.data.url).text(json.data.title);
                rowReference.show();
                renderGallery(json.data.pictures);
                MobileContent.formChanged = true;
            } else {
                ct.error('获取组图信息失败，请重试');
            }
        }, 'json');
    };
    window.loadContent = loadContent;
});