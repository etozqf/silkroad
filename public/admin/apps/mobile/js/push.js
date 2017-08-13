;$(function() {
    var title = $('[name=title]'),
        content = $('[name=message]'),
        $platforms = $('.push-platform-body'),
        form = $('form'),
        rowReference = $('#row-reference'),
        rowReferenceRemove = rowReference.find('a:eq(2)'),
        fieldContentid = $('[name=contentid]'),
        fieldModelid = $('[name=modelid]');

    $.getJSON('?app=mobile&controller=push&action=platform', function(json) {
        $platforms.empty();

        if (json.state && json.data) {
            var platforms = json.data.platform;
            if (platforms.length) {
                $.each(platforms, function(index, platform) {
                    $platforms.append(ct.renderTemplate([
                        '<div class="push-platform">',
                        '<label><input type="checkbox" name="platform[]" value="{name}" />{label}</label>',
                        '</div>'
                    ].join(''), {
                        name:platform[0],
                        label:platform[1]
                    }));
                });
            } else {
                ct.warn('没有可用的推送目标');
            }
        } else {
            ct.error(json.error || '获取推送目标失败，请检查推送服务器配置', 'center', 99);
        }
    });

    title.maxLength({
        update: function() {
            var tipsWidth = this.next('span').outerWidth(true) + 5;
            this.css({
                'padding-right': tipsWidth,
                'width': 350 - tipsWidth + 3
            });
        }
    });
    
    title.next('span').height(title.height() - 2);

    content.maxLength();

    rowReferenceRemove.click(function() {
        fieldContentid.val('');
        fieldModelid.val('');
        rowReference.hide();
    });
    $('#pick-content').click(function() {
        var _dialog = ct.iframe({
            url:'?app=mobile&controller=content&action=picker&title='+encodeURIComponent('选取内容'),
            width:500
        }, {
            ok:function(item) {
                title.val(item.title).trigger('change').trigger('keyup');
                content.val(item.description).trigger('change').trigger('keyup');
                fieldContentid.val(item.contentid);
                fieldModelid.val(item.modelid);
                rowReference.find('a:eq(0)').attr('href', item.url).text(item.title);
                rowReference.find('a:eq(1)').click(function() {
                    ct.assoc.open('?app=mobile&controller=' + item.model + '&action=edit&contentid=' + item.contentid, 'newtab');
                    return false;
                });
                rowReference.show();
                _dialog.dialog('destory').remove();
            }
        });
        return false;
    });

    $('input[type=submit]').click(function() {
        if (!form[0].message.value.length) {
            if(form.find('[name="platform[]"]').filter(':checked').eq(0).val() === "android") {
				ct.error('推送至Android端时，标题必填');
				return false;
	    	}
            ct.error('请输入推送内容');
            return false;
        }

        if (!form.find('[name="platform[]"]').filter(':checked').length) {
            ct.error('请选择推送目标');
            return false;
        }

        form.ajaxSubmit({
            dataType: 'json',
            success: function(json) {
                if (json && json.state) {
                    ct.ok('推送成功');
                    form[0].reset();
                    title.trigger('change').trigger('keyup');
                    content.trigger('change').trigger('keyup');
                    rowReferenceRemove.click();
                } else {
                    ct.error(json && json.error || '推送失败，请重新尝试');
                }
            }
        });

        return false;
    });
});