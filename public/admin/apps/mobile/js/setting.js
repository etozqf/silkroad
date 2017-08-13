$(function() {
    /* 表单相关 */
    $('#setting_index').ajaxForm(function(json) {
        if (json && json.state) {
            ct.tips('保存成功');
            var weiboEnabledInput = $('[name="config[weibo][enabled]"][value=1]').is(':checked');
            if (weiboEnabled && !weiboEnabledInput) {
                var tips = ct.alert('保存成功 请同时禁用微博应用<p /><a href="javascript:ct.assoc.open(\'?app=mobile&controller=setting&action=app\', \'newtab\');">点击此处设置</a>');
                tips.children('a:last').remove();
            }
            weiboEnabled = weiboEnabledInput;
        } else {
            ct.error(json && json.error || '保存失败');
        }
    });

    /* 城市选择 */
    var baseUrl = '?app=mobile&controller=weather_city&action=';
    $('#weatherid').nlist({
        message: {
            title: '选取城市'
        },
        initUrl: baseUrl + 'query',
        dataUrl: baseUrl + 'browse',
        paramValue: 'weather_id',
        maxWidth: 350
    });

    /* 微博相关 */
    var weiboContainer = $('#weibo-container');
    var weiboPlatforms = $('#weibo-platforms');
    var weiboAuth = $('#weibo-auth');
    var tplWeiboAuth = $('#tpl-weibo-auth').html();
    var checkWeibo = $('#weibo-check').find(':input').click(function() {
        var value = parseInt(checkWeibo.filter(':checked').val(), 10);
        if (value) {
            weiboContainer.show();
            if (!weiboAuth.children().length) {
                renderWeiboAuth();
            }
        } else {
            weiboContainer.hide();
        }
    });
    function renderWeiboAuth() {
        $.getJSON('?app=mobile&controller=weibo&action=info', function(json) {
            if (json && json.data) {
                weiboAuth.empty().append(ct.renderTemplate(tplWeiboAuth, json.data)).show();
                weiboPlatforms.hide();
                bindAuthEvents();
            } else {
                weiboAuth.hide();
                weiboPlatforms.show();
            }
        });
    }
    function bindAuthEvents() {
        weiboAuth.find('#btn-weibo-revoke').click(function() {
            ct.confirm('取消绑定后官方微博功能将失效，确定要取消绑定吗？', function() {
                $.getJSON('?app=mobile&controller=weibo&action=revoke', function(json) {
                    if (json && json.state) {
                        weiboAuth.empty().hide();
                        weiboPlatforms.show();
                    } else {
                        ct.error(json && json.error || '取消绑定失败');
                    }
                });
            });
            return false;
        });
        weiboAuth.find('#btn-weibo-auth').click(function() {
            bindWeibo($(this).attr('data-platform'));
            return false;
        });
    }
    function bindWeibo(platform) {
        var popwin = window.open('?app=mobile&controller=weibo&action=auth&platform=' + platform);
        var interval = setInterval(function() {
            if (!popwin || popwin.closed) {
                clearInterval(interval);
                renderWeiboAuth();
            }
        }, 200);
    }
    weiboContainer.find('.weibo-platform').click(function() {
        bindWeibo($(this).attr('data-platform'));
        return false;
    });
    if (parseInt(checkWeibo.filter(':checked').val(), 10)) {
        renderWeiboAuth();
    }
});