;$(function() {
window.QRCode || (window.QRCode = {});
window.QRCode.initForm = function(form) {
    var PREVIEW = true,
        btnContent = form.find('#pick-content'),
        btnMobile = form.find('#pick-mobile'),
        btnCategory = form.find('#btn-add-category'),

        btnImageChange = form.find('#btn-image-change'),
        btnImageUpload = form.find('#btn-image-upload'),
        btnImageSave = form.find('#btn-image-save'),

        fieldStr = form.find('#str'),
        fieldNote = form.find('#note'),

        fieldType = form.find('[name=type]'),
        fieldContentid = form.find('[name=contentid]'),
        fieldModelid = form.find('[name=modelid]'),
        rowReference = $('#reference'),

        fieldImage = form.find('#image'),
        fieldImageHolder = form.find('#image-holder'),

        /*fieldSize = form.find('[name=size]'),
        sizeMin = 4,
        sizeMax = 31,
        size = parseInt(fieldSize.val()) || sizeMin,
        labelSlider = form.find('#slider-number'),*/

        previewImage = form.find('#preview-image'),
        downloadPanel = $('#download-panel');

    // 关联内容
    function emptyReference() {
        fieldType.val('content');
        fieldContentid.val('0');
        fieldModelid.val('0');
        rowReference.hide();
    }
    rowReference.find('a:eq(1)').click(emptyReference);

    // 选择移动端内容
    btnMobile.click(function(){
        var _dialog = ct.iframe({
            url:'?app=mobile&controller=content&action=picker&multiple=0&title='+encodeURIComponent('选取内容'),
            width:500
        }, {
            ok:function(item) {
                fieldStr.val(item.qrcode).trigger('change');
                fieldNote.val(item.title).trigger('change');

                fieldType.val('mobile');
                fieldContentid.val(item.contentid);
                fieldModelid.val(item.modelid);
                rowReference.find('a:eq(0)').attr('href', item.url).text(item.title);
                rowReference.show();

                _dialog.dialog('destory').remove();
            }
        });
        return false;
    });

    // 选择内容
    btnContent.click(function() {
        $.datapicker({
            picked: function(items){
                fieldStr.val(items[0].url).trigger('change');
                fieldNote.val(items[0].title).trigger('change');

                fieldType.val('content');
                fieldContentid.val(items[0].contentid);
                fieldModelid.val(items[0].modelid);
                rowReference.find('a:eq(0)').attr('href', items[0].url).text(items[0].title);
                rowReference.show();
            },
            multiple: false
        });
        return false;
    });

    // 添加投放类型
    btnCategory.click(function() {
        var placeholder = '投放类型',
            value = fieldNote[0].value,
            length = value.length,
            selection = fieldNote.get_selection(),
            text = selection.text,
            start = selection.start,
            end = selection.end,
            replace,
            pos;

        // 选中状态下重复点击
        if (value.charAt(start - 1) == '#' && value.charAt(end) == '#') {
            fieldNote.set_selection(start, end);
            return;
        }

        // 非选中状态下重复点击
        if (!text && ((pos = value.indexOf('#' + placeholder + '#')) != -1)) {
            fieldNote.set_selection(pos + 1, pos + 1 + placeholder.length);
            return;
        }

        // 没有未处理的情况，那么直接插入或包裹选中的关键词
        replace = '#' + (text || placeholder) + '#';
        fieldNote[0].value = value.substring(0, start) + replace + value.substring(end, length);

        // 没有选中内容的话，选中插入的关键词
        if (text === '') {
            fieldNote.set_selection(start + 1, start + 1 + placeholder.length);
        }
    });

    /*// 大小选择
    function updateSize() {
        fieldSize.val(size);
        labelSlider.text([
            '生成后的二维码大小为 ', size * 33 + 10, ' 像素，约 ', (size * 33 * 0.04).toFixed(1), ' 厘米'
        ].join(''));
    }
    var slider = form.find('#slider');
    slider.length && slider.slider({
        animate: 'fast',
        range: 'min',
        min: sizeMin,
        max: sizeMax,
        value: size,
        slide: function(ev, ui) {
            size = ui.value;
            updateSize();
        }
    });
    updateSize();*/

    // 图标
    fieldImageHolder.floatImg({
        url:UPLOAD_URL
    });
    btnImageChange.click(function() {
        btnImageChange.hide();
        btnImageChange.next('div').show();
        return false;
    });
    btnImageUpload.length && btnImageUpload.uploader({
        fileDesc:'图像',
        fileExt:'*.jpg;*.jpeg;*.gif;*.png',
        multi:false,
        script:'?app=system&controller=upload&action=image',
        jsonType : 1,
        complete:function(json){
            if (json && json.state) {
                fieldImageHolder.val(json.file).trigger('change');
            } else {
                ct.error('上传失败');
            }
        },
        error:function(error){
            ct.warn(error.file.name+'：上传失败，'+error.type+':'+error.info);
        }
    });
    btnImageSave.click(function() {
        var value = $.trim(fieldImageHolder.val());
        !value && form.find('[name=image_fill]').attr('checked', false);
        fieldImage.val(value);
        btnImageSave.parent().hide();
        btnImageChange.show();
        return false;
    });

    // 预览
    $('#btn-preview').click(function() {
        PREVIEW = true;
        downloadPanel.hide();
        previewImage.attr('src', '?app=system&controller=qrcode&action=preview&' + form.serialize() + '&_=' + Math.random());
        return false;
    });

    // 表单提交
    var submitLock = false;
    var submitForm = $('#qrcode-add').ajaxForm(function(json) {
        submitLock = false;
        if (json && json.state) {
            PREVIEW = false;

            downloadPanel.empty();
            $.each(json.sizes, function(name, size) {
                if (name == '中') {
                    previewImage.attr('src', size.url + '?_=' + Math.random() * 5);
                }
                $('<input class="button-primary" type="button" value="'+name+'" />').click(function() {
                    window.open('?app=system&controller=qrcode&action=download&qrcode=' + encodeURIComponent(size.url));
                    return false;
                }).appendTo(downloadPanel);
            });

            fieldStr.val('');
            fieldNote.val('');
            emptyReference();

            ct.ok('生成成功，请点击二维码下载');
        } else {
            ct.error(json && json.error || '生成失败，请重新尝试');
        }
    }, null, function() {
        if (submitLock) {
            ct.warn('正在生成，请稍后');
            return false;
        }
        submitLock = true;
        return true;
    });

    // 点击下载
    previewImage.parent().mousedown(function() {
        if (PREVIEW) {
            ct.warn('该二维码仅供预览，请点击生成并保存按钮后再进行下载');
        } else {
            downloadPanel.show();
        }
        return false;
    });
};
});