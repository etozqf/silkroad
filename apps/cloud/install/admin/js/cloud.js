var thirdLoginTable;

$(document).ready(function() {
	window.ctswitch('.switch');
	$('.switch').click(function(obj) {
		$.get('?app=cloud&controller=cloud&action=ajax_switch', {name:$(obj.currentTarget).attr('name'), value:$(obj.currentTarget).attr('checked')*1}, function(json) {
			if (!json.state) {
				ct.error('操作失败');
				$(obj.currentTarget).attr('checked', !$(obj.currentTarget).attr('checked'));
			}
		}, 'json');
	});
	$('.cloud-banner-setting').click(function(event) {
		var parent = $(event.currentTarget).parent().parent('.cloud-box');

        var callback =  function(){};
        if ($(this).attr('event') == 'status') {
            callback = function() {
                $.get('?app=cloud&controller=issue&action=apply', null, function(json) {
                    if (json.state) {
                        parent.find(".mar_r_8").html(json.message);
                    } else {
                        parent.find(".mar_r_8").html(json.error);
                    }
                }, 'json');
            }
        }

		if (parent.hasClass('cloud-hide')) {
			parent.removeClass('cloud-hide').addClass('cloud-show');
			parent.find('.cloud-panel').hide().slideDown('200', callback);
		} else {
			parent.removeClass('cloud-show').addClass('cloud-hide');
			parent.find('.cloud-panel').show().slideUp('200');
		}
	});

	thirdLoginTable = new ct.table('#thirdlogin-table', {
		rowIdPrefix : 'tl_',
		template : thirdlogin_template,
		baseUrl  : '?app=cloud&controller=thirdlogin&action=page',
		rowCallback : function(id, tr) {
			var stateTD = tr.find('.state');
			if (tr.prev().length === 0) {
				tr.addClass('first-child');
			}
			if (parseInt(stateTD.html(), 10)) {
				stateTD.html('启用');
				tr.find('.set_thirdlogin_state').html('禁用').attr('href', '?app=cloud&controller=cloud&action=set_api_state&id='+id+'&state=0');
			} else {
				stateTD.html('禁用');
				tr.find('.set_thirdlogin_state').html('启用').attr('href', '?app=cloud&controller=cloud&action=set_api_state&id='+id+'&state=1');
			}
		}
	});
	thirdLoginTable.load();

	var sortable = function(type) {
		var startPostion, stopPostion, inSorting = false;
		$('#'+type+'-item').sortable({
			'handle':'td:first-child',
			'axis':'y',
			'start':function(e,u) {
				if (inSorting) {
					return false;
				}
				u.item.css('border-top','1px solid #DDD').css('padding-top', '4px');
				startPostion = $('#'+type+'-item').find('tr').index(u.item);
			},
			'stop':function(e,u) {
				stopPostion = $('#'+type+'-item').find('tr').index(u.item);
				var deltaPostion = stopPostion - startPostion, passby = [],
					rid = parseInt(u.item.attr('id').substr(3), 10);
				if (deltaPostion > 0) { // 下移
					for (var i=startPostion; i<=stopPostion - 1; i++) {
						passby.push(parseInt($('#'+type+'-item').children('tr').eq(i).attr('id').substr(3), 10));
					}
					inSorting = true;
					$.get('?app=cloud&controller='+type+'&action=set_sort', {'sort':'-1','id':passby.join(',')}, function(json) {
						if (json.state) {
							$.get('?app=cloud&controller='+type+'&action=set_sort', {'sort':deltaPostion,'id':rid}, function(json) {
								inSorting = false;
								thirdLoginTable.reload();
							}, 'json');
						} else {
							ct.error('排序失败');
							return;
						}
					}, 'json');
				} else if (deltaPostion < 0) { // 上移
					for (var i=stopPostion+1; i<= startPostion; i++) {
						passby.push(parseInt($('#'+type+'-item').children('tr').eq(i).attr('id').substr(3), 10));
					}
					inSorting = true;
					$.get('?app=cloud&controller='+type+'&action=set_sort', {'sort':'1','id':passby.join(',')}, function(json) {
						if (json.state) {
							$.get('?app=cloud&controller='+type+'&action=set_sort', {'sort':deltaPostion,'id':rid}, function(json) {
								inSorting = false;
								if (type == 'thirdlogin') {
									thirdLoginTable.reload();
								} else if (type == 'share') {
									shareTable.reload();
								}
							}, 'json');
						} else {
							ct.error('排序失败');
							return;
						}
					}, 'json');
				}
			}
		});
	};

	/* 规则 */
	$('.rule').find('.cloud-banner-setting').one('click', function(event) {
		var box = $(event.target).parents('.cloud-box');
		box.find('#rule_auto_update').bind('click', function(event) {
			var checked = $(event.target).is(':checked');
			$.post('?app=cloud&controller=cloud&action=set_setting', {
				'rule_auto_update': (checked ? 1 : 0)
			}, function(req) {
				if (!req.state) ct.error('请求异常');
			}, 'json');
		});
		box.find('form').bind('submit', function(event) {
			var ruleAddress = $(this).find('[name="rule_address"]').val();
			$.post('?app=cloud&controller=cloud&action=set_setting', {
				rule_address: ruleAddress
			}, function() {
				$.get('?app=cloud&controller=cloud&action=cron_rule', {
					force: 1
				}, function(req) {
					if (!req.state) {
						return ct.error(req.error || '通讯失败');
					}
					var d = new Date;
					$('.ruleupdatetime').html(d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate() + ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds());
					$('.ruleversion').html(req.version);
				}, 'json');
			}, 'json');
			return false;
		});
	});

	$('#bshareSetting').hover(function (){
		$('#bshareSetting').children('ul').show();
	}, function(){
		$('#bshareSetting').children('ul').hide();
	});
	sortable('thirdlogin');
	bshare.login();
	ct.listenAjax();
});

// CmsTop官方提供的云服务
var form_submit = function(form) {
	$.post(form.getAttribute('action'), $(form).serialize(), function(json) {
		if (json.state) {
			ct.ok('保存成功');
		} else {
			ct.error('保存失败');
		}
	},'json');
};

var spider_ping = function() {
	$.get('?app=cloud&controller=cloud&action=ping', {
		'url': $('[name="spider_address"]').val() + '?action=ping',
		'_': (new Date()).getTime()
	}, function(response) {
		try {
			response = eval('('+response+')');
			response.state ? ct.ok('通讯成功') : ct.error('通讯失败');
		} catch (e) {
			ct.error('通讯失败');
		};
	});
};

// 第三方提供的云服务
var set_state = function(obj) {
	$.get(obj.attr('href'), null, function(json) {
		if (json.state) {
			var tr = $(obj).parents('tr');
			var stateTD = tr.find('.state');
			var id = tr.attr('id').substr(3);
			if (obj.attr('href')[obj.attr('href').length-1] == '1') {
				stateTD.html('启用');
				obj.html('禁用').attr('href', '?app=cloud&controller=cloud&action=set_api_state&id='+id+'&state=0');
			} else {
				stateTD.html('禁用');
				obj.html('启用').attr('href', '?app=cloud&controller=cloud&action=set_api_state&id='+id+'&state=1');
			}
		} else {
			ct.error('操作失败');
		}
	}, 'json');
};

// 第三方登录
var thirdLoginAdd = function() {
	ct.formDialog('添加接口', '?app=cloud&controller=thirdlogin&action=add', function(json) {
		if (json.state) {
			thirdLoginTable.reload();
			return true;
		} else {
			ct.error(json.error);
		}
	}, undefined, function() {
		var rst = true;
		$.each($('.third-params-tr').find('input:text'), function(i,k) {
			if (!k.value) {
				ct.error('必须填写全部api参数');
				rst = false;
			}
		});
		return rst;
	});
};

var thirdLoginEdit = function(id) {
	ct.formDialog({'title':'修改接口', 'height':'320'}, '?app=cloud&controller=thirdlogin&action=edit&id='+id, function(json) {
		if (json.state) {
			thirdLoginTable.reload();
			return true;
		} else {
			ct.error(json.error);
		}
	}, undefined, function(form) {
		$.each($('.third-params-tr').find('input:text'), function(i,k) {
			if (!k.value) {
				ct.error('必须填写全部api参数');
				return false;
			}
		});
		return true;
	});
};

var paramRowAdd = function(elm, name, value) {
	name = name || '';
	value = value || '';
	elm.append(new Array('<tr class="param_row">',
				'<td>名称:<input type="text" name="paramname[]" value="'+name+'" /></td>',
				'<td>　值:<input type="text" name="paramvalue[]" value="'+value+'" /></td>',
				'<td><a href="javascript:;" style="background:url(\''+IMG_URL+'css/images/icons.gif\') 0 -38px; width:16px;height:16px;display:block;" onclick="paramRowRemove($(this).parent().parent())"></a></td>',
			'</tr>').join('\r\n'));
};

var paramRowRemove = function(elm) {
	elm.remove();
};

var changeInterface = function(interfaceName) {
	var table = $('#thirdlogin_add>table');
	$('.third-params-tr').remove();
	$.get('?app=cloud&controller=thirdlogin&action=get_param', {'interface':interfaceName}, function(json) {
		for (var key in json) {
			table.append(new Array('<tr class="third-params-tr">',
					'<th><span class="c_red">*</span> '+key+':</th>',
					'<td>',
						'<input type="hidden" name="paramname[]" value="'+json[key]+'" />',
						'<input type="text" name="paramvalue[]" value="" />',
					'</td>',
				'</tr>').join('\r\n'));
		}
	}, 'json');
};

var shareSave = function() {
	$.post('?app=cloud&controller=cloud&action=share_save',{data:$('#share-textarea')[0].value}, function(json) {
		if (json.state) {
			ct.ok('保存成功');
		} else {
			ct.error('保存失败');
		}
	}, 'json');
};

var onekeyfollowSave = function() {
	$.post('?app=cloud&controller=cloud&action=onekeyfollow_save',{data:$('#onekeyfollow')[0].value}, function(json) {
		if (json.state) {
			ct.ok('保存成功');
		} else {
			ct.error('保存失败');
		}
	}, 'json');
};

// 搜狐畅言注册
var registerChanyan = function(form) {
	var $form, action, data;
	$form = $(form);
	action = $form.attr('action');
	data = $form.serializeObject();
	if (!data.user || !data.password) {
		return ct.error('请输入用户名或密码');
	}
	$.post(action, data, function(req) {
		if (!req.state) {
			return ct.error(req.error || '注册失败');
		}
		$('#changyanCode').val(req.code);
		$('#changyanRegisterForm').hide();
		$('#changyanCodeForm').show();
		$('#changyanUser').html(data.user);
		ct.ok('注册成功');
	}, 'json');
}

var getChangyanCode = function() {
	var action = '?app=cloud&controller=changyan&action=code';
	$.get(action, null, function(req) {
		if (!req.state) {
			return ct.error(req.error || '获取代码失败');
		}
		$('#changyanCode').val(req.code);
		ct.ok('重置代码成功');
	}, 'json');
}

var logoutChangyan = function() {
	ct.confirm('确认要登出畅言?', function() {
		var action = '?app=cloud&controller=changyan&action=logout';
		$.post(action, null, function(req) {
			if (!req.state) {
				return ct.error(req.error || '登出失败');
			}
			$('#changyanRegisterForm').show();
			$('#changyanCodeForm').hide();
		}, 'json');
	});
}

var saveChangyanCode = function(form) {
	var $form, action, data;
	$form = $(form);
	action = $form.attr('action');
	data = $form.serializeObject();
	if (!data.code) {
		return ct.error('代码不能为空');
	}
	$.post(action, data, function(req) {
		if (!req.state) {
			return ct.error(req.error || '设置代码失败');
		}
		ct.ok('设置代码成功');
	}, 'json');
}

$(function() {
	$('#sohuchangyan').find('.cloud-banner-setting').one('click', function() {
		if ($(this).attr('data-first') == 0) {
			$.post('?app=cloud&controller=changyan&action=autoRegister', null, function(req) {
				if (!req.state) {
					return;
				}
				$('#changyanCode').val(req.code);
				$('#changyanRegisterForm').hide();
				$('#changyanUser').html(req.user);
				$('#changyanCodeForm').show();
			}, 'json');
		}
	});
})

// 手机短信码验证 - 保存设置
var mobileVerification = function(form) {
    var $form, action, data;
    $form = $(form);
    action = $form.attr('action');
    data = $form.serializeObject();
    if (!data.key || !data.message) {
        return ct.error('请确保每个选项都不为空');
    }
    $.post(action, data, function(req) {
        if (!req.state) {
            return ct.error(req.error || '保存失败');
        }
        ct.ok('保存成功');
    }, 'json');
}

// 手机短信验证码 - 获取当前余额
var getbalance = function () {
    $.getJSON('/?app=cloud&controller=mverification&action=getbalance', function (json) {
        if (json.state) {
            $("#getbalance").val(json.message + '条');
        } else {
            ct.error(json.error);
        }
    })
};

// 手机短信验证码 - 编辑