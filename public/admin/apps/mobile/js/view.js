;$(function() {
var form = $('form'),
    defaultValues = form.serializeObject(),
    inited = false,
    editor;

var headerView = $('#header-view'),
    headerEdit = $('#header-edit'),
    contentView = $('#content-view'),
    contentEdit = $('#content-edit');

var model = ct.getParam('controller', location.href);
var contentid = ct.getParam('contentid', location.href);
var interval;

// hack for next actions
parent && parent.MobileContent && (parent.MobileContent.model = model);

var btnEdit = $('#btn-quickedit').click(function() {
    MobileContent.enterEdit();
});
var btnCancel = $('#btn-quickedit-quit').click(function() {
    MobileContent.quitEdit(true);
});

$('[data-maxlength]').maxLength({
    prevent: false,
    count: $.fn.maxLength.COUNT_LEFT,
    mode: $.fn.maxLength.MODE_CHINESE,
    display: MobileContent.maxLengthDisplay
});

// 动态改变大小
ResizeDelegate.start();
var contentDetail = $('.content-detail-wrap'),
    contentDetailTop = contentDetail.offset().top;
ResizeDelegate.add(contentDetail, function(ww, wh) {
    this.css('height', wh - contentDetailTop);
}, true);

function render(data) {
    headerView.find('h2 a').html(data.title);
    headerEdit.find('[name=title]').val(data.title);
    headerView.find('.content-desc').html(data.description);
    headerEdit.find('[name=description]').val(data.description);
    headerView.find('img').attr('src', data.thumb ? (data.thumb + '?_=' + new Date().getTime()) : (IMG_URL + 'images/nopic.gif'));
    headerEdit.find('[name=thumb]').val(data.thumb || '');
    if (data.content) {
        contentView.html(data.content);
        contentEdit.find('[name=content]').val(data.content);
        editor && editor.setCode(data.content);
    }
}

MobileContent.enterEdit = function() {
    if (parent && parent.MobileContent.islock(model, contentid)) {
        ct.warn('当前文档已被锁定，无法编辑');
        return false;
    }

    if (btnEdit.text() == '快速编辑') {
        parent && parent.MobileContent.lock(model, contentid);
        interval = setInterval(function() {
            parent && parent.MobileContent.lock(model, contentid);
        }, 10000);
        headerView.hide();
        headerEdit.show();
        contentView.hide();
        contentEdit.show();
        if (!inited) {
            // 缩略图
            setTimeout(function() {
                headerEdit.find('#btn-thumb').mobileUploader({
                    fileExt:'*.jpg;*.jpeg;*.gif;*.png'
                }, function(json) {
                    headerEdit.find('[name=thumb]').val(UPLOAD_URL + json.file);
                    headerEdit.find('img').attr('src', UPLOAD_URL + json.file + '?_=' + new Date().getTime());
                });
            }, 500);
            if (contentEdit.length) {
                editor = new $.Rte(contentEdit.find('textarea'), {
                    uploadUrl: '?app=system&controller=upload&action=image&format=cmstop.rte',
                    uploaderInput: 'Filedata'
                });
            }
            inited = true;
        }
        btnEdit.text('保存修改');
        btnCancel.show();
    } else {
        MobileContent.saveEdit(function() {
            MobileContent.quitEdit();
            parent && parent.itemlist && parent.itemlist.reload();
            interval && clearInterval(interval);
            parent && parent.MobileContent.unlock(model, contentid);
        });
    }
};
MobileContent.saveEdit = function(callback) {
    form.ajaxSubmit({
        dataType:'json',
        beforeSerialize:function() {
            if (editor && form[0].content) {
                form[0].content.value = editor.getCode();
            }
        },
        beforeSubmit:function(array, _form, options) {
            if (!form[0].title.value) {
                ct.error('标题不能为空');
                return false;
            }
            if (!form[0].description.value) {
                ct.error('描述不能为空');
                return false;
            }

            var controller = ct.getParam('controller');
            if (controller != 'article' && controller != 'link' && !form[0].thumb.value) {
                ct.error('缩略图不能为空');
                return false;
            }
            if (form[0].content && !form[0].content.value) {
                ct.error('内容不能为空');
                return false;
            }
            return true;
        },
        success:function(json) {
            if (json && json.state) {
                defaultValues = form.serializeObject();
                render(defaultValues);
                $.isFunction(callback) && callback(json);
            } else {
                ct.error(json && json.error || '保存失败');
            }
        }
    });
};
MobileContent.quitEdit = function(discard) {
    interval && clearInterval(interval);
    parent && parent.MobileContent.unlock(model, contentid);
    headerEdit.hide();
    headerView.show();
    contentEdit.hide();
    contentView.show();
    discard && render(defaultValues);
    btnEdit.text('快速编辑');
    btnCancel.hide();
};
});