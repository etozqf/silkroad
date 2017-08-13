(function() {
    var activity = window.activity = window.activity || {};

    function strlen(val) {
        return val ? Math.ceil(val.replace(/[^\x00-\xff]/gm, '__').length / 2) : 0;
    }

    function formatRegExp(regex) {
        return regex.replace(/^\//, '').replace(/\/[usgim]*$/i, '');
    }

    $.extend(activity, {
        formReady:function(contentid, form) {
            if (!contentid || !form[0]) return;

            form[0].reset();

            if ($.cookie(COOKIE_PRE+'auth')) {
                activity.loadStoredData(form);
            }

            form.submit(activity.validateForm);
        },
        loadStoredData:function(form) {
            var fields = {}, names = [];
            form.find(':input').each(function() {
                if (this.type
                    && this.name != 'seccode'
                    && this.type != 'file'
                    && this.type != 'submit'
                    && this.type != 'hidden'
                    && this.type != 'button') {
                    names.push(this.name);
                    fields[this.name] = this;
                }
            });
            if (!names.length) return;
            $.getJSON(APP_URL+'?app=activity&controller=sign&action=load&jsoncallback=?', {
                names:names.join('$$')
            }, function(json) {
                if (!json || !json.state) return;
                $.each(json.fields, function(name, value) {
                    if (value && fields[name]) {
                        switch (fields[name].type) {
                            case 'text':case 'password':case 'textarea':
                            fields[name].value = value;
                            break;
                            case 'radio':case 'checkbox':
                            form.find('[name="'+name+'"]').filter('[value='+value+']').attr('checked', true);
                            break;
                            case 'select':
                                form.find('[name="'+name+'"]').val(value);
                                break;
                        }
                    }
                });
            });
        },
        validateForm:function() {
            var form = $(this), error = false;
            form.find('[data-validate-name]').each(function() {
                var row = $(this),
                    name = row.attr('data-validate-name'),
                    field = row.attr('name') == name ? row : row.find('[name="'+name+'"]'),
                    label = row.attr('data-validate-label'),
                    require = parseInt(row.attr('data-validate-require')),
                    limit = parseInt(row.attr('data-validate-limit')),
                    regex = row.attr('data-validate-regex'),
                    value, item, selectCount = 0;
                switch (field[0].nodeName.toLocaleLowerCase()) {
                    case 'input':
                        item = ($.isFunction(field[0].attr) ? field[0] : field);
                        if (item.attr('type') == 'radio' || item.attr('type') == 'checkbox') {
                            value = field.filter(':checked').val();
                            selectCount = field.filter(':checked').length;
                        } else {
                            value = field.val();
                        }
                        break;
                    case 'select':
                        value = field.val();
                        selectCount = field.find(':selected').length;
                        break;
                    default:
                        value = field.val();
                        break;
                }
                if (value != '' && value !== undefined) {
                    if (limit) {
                        if (selectCount) {
                            if (selectCount > limit) {
                                error = true;
                                alert(label + '只能选择' + limit + '项');
                                field.focus();
                                return false;
                            }
                        } else {
                            if (strlen(value) > limit) {
                                error = true;
                                alert(label + '长度不能超过' + limit + '个字');
                                field.focus();
                                return false;
                            }
                        }
                    }
                    if (regex && !(new RegExp(formatRegExp(regex))).exec(value)) {
                        error = true;
                        alert(label + '格式不正确');
                        field.focus();
                        return false;
                    }
                } else {
                    if (require) {
                        error = true;
                        alert(label + '不能为空');
                        field.focus();
                        return false;
                    }
                }
            });
            return !error;
        }
    });
})();

// 动态加载报名人员
(function(){
    var _buildSigned = function(element, json) {
        var html = '';
        $.each(fields, function(fieldName, fieldItem) {
            if (fieldItem.display) {
                html += '<a>' + _fiter(fieldName, json[fieldName]) + '</a>';
            }
        });
        element.append(html);
    };

    var _fiter = function(key, value) {
        value = (typeof(value) === 'undefined') ? '' : value.toString();
        if (key === 'sex' && value.length > 0) {
            value = (value === '1') ? '男' : '女';
        }
        if (key === 'photo') {
            value = '<a href="'+UPLOAD_URL+value+'" target="_blank">查看</a>';
        }
        if (key === 'aid') {
            value = '<a href="'+UPLOAD_URL+value+'" target="_blank">下载</a>';
        }
        return value;
    };

    $(function() {
        $.getJSON(APP_URL + '?app=activity&controller=sign&action=get_signed&jsoncallback=?', {contentid:contentid}, function(res) {
            if (res.state && res.data && res.data.length) {
                var nameList = $('#name_list');
                $.each(res.data, function(i, k) {
                    _buildSigned(nameList, k);
                });
            }
        });
    });
})();