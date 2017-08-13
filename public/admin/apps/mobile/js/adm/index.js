$(function() {
    /* 表单相关 */
    $('#form').ajaxForm(function(json) {
        if (json && json.state) {
            ct.tips('保存成功');
        } else {
            ct.error(json && json.error || '保存失败');
        }
    });

    /* 上传相关 */
    function saveLogo(elem, success, error) {
        $.post('?app=mobile&controller=ad&action=index', elem.find(':input').serialize(), function(json) {
            if (json && json.state) {
                $.isFunction(success) && success(json);
            } else {
                $.isFunction(error) && success(error);
            }
        }, 'json');
    }
    function appendReuploadBtn(elem) {
        var btn = $('<span class="f_r"><img src="images/refresh.gif" alt="重新上传"></span>').appendTo(elem.find('.ui-bootstrap-logo-item-action'));
        btn.mobileUploader(function(json) {
            elem.find('input').val(json.file);
            saveLogo(elem, function() {
                elem.find('.ui-bootstrap-logo-item-img img').replaceWith('<img src="'+UPLOAD_URL+json.file+'" />');
            }, function() {
                ct.error('保存失败');
            });
        });
    }
    $('.ui-bootstrap-logo-item').each(function() {
        var logo = $(this);
        var addImg = logo.find('.ui-bootstrap-logo-item-img-empty').mobileUploader(function(json) {
            logo.find('input').val(json.file);
            saveLogo(logo, function() {
                addImg.replaceWith('<img src="'+UPLOAD_URL+json.file+'" />');
                appendReuploadBtn(logo);
            }, function() {
                ct.error('保存失败');
            });
        });
        if (!addImg.length) {
            appendReuploadBtn(logo);
        }
    });
});