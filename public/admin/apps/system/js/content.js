(function(){

function getPara(para, url){
	url || (url = location.search);
	return ((new RegExp('[&?]'+para+'=(\\w+)').exec(url)) || {1 : null})[1];
}


var model = (typeof(window.toolbox) == 'undefined') ? getPara('app') : window.toolbox['model'];
var action = (typeof(window.toolbox) == 'undefined') ? getPara('action') : window.toolbox['action'];
var contentid = getPara('contentid') ? getPara('contentid') : 0;
var form = '#' + model + '_' + action;
var submit_ok, submit_result;
var passtate = true;
/**
 * 一。内容列表代码
 */
var content = 
{
	add: function (catid, modelid, model)
	{
		ct.ajax('发布内容', '?app=system&controller=content&action=add&catid='+catid+'&modelid='+modelid, 350, 300, function (dialog){
			dialog.find("#category_tree").treeview();
			dialog.find("#category_tree span").unbind('click mouseover');
		}, function () {
			var model = $('#model').val();
			var catid = $('input:checked[name=catid]').val();
			if (!model)
			{
				alert('请选择模型');
				return false;
			}
			if (!catid)
			{
				alert('请选择栏目');
				return false;
			}
			ct.assoc.open('?app='+model+'&controller='+model+'&action=add&catid='+catid, 'newtab');
			return true;
		}, function () {
            return true;
		});
	},
	
	edit: function (contentid)
	{
		var app = $('#row_'+contentid).attr('model');
		if(!app) {
			app = getPara('app');
		}
		if(content.islock(contentid))
		{
			ct.error('当前文档已被锁定，无法修改！');
			return false;
		}
		ct.assoc.open('?app='+app+'&controller='+app+'&action=edit&contentid='+contentid, 'newtab');
	},
	
	view: function (contentid) 
	{
		var app = $('#row_'+contentid).attr('model');
		if(!app) {
			app = getPara('app');
		}
		ct.assoc.open('?app='+app+'&controller='+app+'&action=view&contentid='+contentid, 'newtab');
	},

	category: function (catid) 
	{
		ct.assoc.open('?app=system&controller=content&action=index&catid='+catid, 'newtab');
	},

	search: function (catid, modelid, status)
	{
		ct.ajax('内容搜索', '?app=system&controller=content&action=search&catid='+catid+'&modelid='+modelid+'&status='+status, 360, 350, function(dialog){
            dialog.find('[name=keywords]').val($('#keywords').val());
		    $('input.input_calendar').DatePicker({'format':'yyyy-MM-dd HH:mm'});
		}, function(){
			tableApp.load($('#content_search'));
			return true;
		});
	},
	
	createhtml: function (contentid)
	{
        content._common(contentid, 'show', false);
	},
	
	del: function (contentid)
	{
		if (contentid === undefined)
		{
			contentid = tableApp.checkedIds();
			var msg = '确定删除选中的<b style="color:red">'+contentid.length+'</b>条记录吗？';
		}
		else
		{
			var msg = '确定删除编号为<b style="color:red">'+contentid+'</b>的记录吗？';
		}
		if (contentid.length === 0)
		{
			ct.warn('请选择要操作的记录');
			return false;
		}
		ct.confirm(msg, function(){
			content._common(contentid, 'delete');
		});
	},

	clear: function (catid, title, modelid)
	{
		modelid || (modelid='');
		ct.confirm('<font colr="red">确定要清空'+title+'栏目回收站中的所有内容吗？<br />此操作不可恢复！</font>',function(){
			$.getJSON('?app=system&controller=content&action=clear&catid='+catid+'&modelid='+modelid, function(response){
				if (response.state){
					tableApp.load();
				}else{
					ct.error(response.error);
				}
			});
			return true;
		},function(){
			return true;
		});
	},

	remove: function (contentid) 
	{
		if (contentid === undefined)
		{
			contentid = tableApp.checkedIds();
			var msg = '确定删除选中的<b style="color:red">'+contentid.length+'</b>条记录吗？';
		}
		else
		{
			var msg = '确定删除编号为<b style="color:red">'+contentid+'</b>的记录吗？';
		}
		if (contentid.length === 0)
		{
			ct.warn('请选择要操作的记录');
			return false;
		}

        if (!$.isArray(contentid)) {
            contentid = [contentid];
        }

		ct.confirm(msg, function(){
            content._common_execute(contentid, 'remove', 1, 0);
		});
	},
	
	restore: function (contentid)
	{
		content._common(contentid, 'restore');
	},

	restores: function (catid)
	{
		$.getJSON('?app=system&controller=content&action=restores&catid='+catid, function(response){
			if (response.state)
			{
				$('#list_body').empty();
				ct.ok('操作成功');
			}
			else
			{
				ct.error(response.error);
			}
		});
	},

	approve: function (contentid)
	{
		content._common(contentid, 'approve');
	},
	
	pass: function (contentid)
	{
		if (passtate) {
			content._common(contentid, 'pass');
			passtate = false;
		}
	},
	
	reject: function (contentid)
	{
		content._common(contentid, 'reject');
	},

	publish: function (contentid)
	{
		content._common(contentid, 'publish');
	},
	
	unpublish: function (contentid)
	{
		content._common(contentid, 'unpublish');
	},
	
	islock: function (contentid)
	{
		// error 
		var response = $.ajax({url: '?app=system&controller=content&action=islock&contentid='+contentid, async: false, dataType: "json"}).responseText;
		var a = new Function("return "+response);
		response = a();
		if (!response.state)
		{
			$("#row_"+contentid+" img[src='images/lock.gif']").remove();
		}
		return response.state;
	},
	
	log: function (contentid) 
	{
		ct.iframe({
			title:'?app=system&controller=content_log&action=index&contentid='+contentid,
			width:570,
			height:400
		});
	},
	
	keyword: function(contentid){
		if (contentid == undefined){
			contentid = tableApp.checkedIds();
			if(!contentid.length){
				ct.warn('请选择要操作的记录');
				return false;
			}
			contentid = contentid.join(',');
		}
		ct.form('添加关键词链接','?app=system&controller=keylink&action=content_index&contentid='+contentid, 400, 'auto', function(json){
			if(json.state){
				ct.tips('操作成功！', 'ok');
			}else{
				ct.tips('部分'+json.error, 'error');	
			}
			return true;
		},function(){ return true;});
	},
	
	note: function (contentid)
	{
		ct.iframe({
			title:'?app=system&controller=content_note&action=index&contentid='+contentid,
			width:570,
			height:400
		}).bind('dialogclose',function(){
			if(action=='index') tableApp.reload();
		});
	},
	
	score : function(contentid)
	{
		ct.iframe({title:'?app=system&controller=score&action=index&contentid='+contentid,width:450,height:'auto'}).bind('dialogclose',function(){
			tableApp.reload();
		});
	},
	
	version: function (contentid)
	{
		ct.iframe({
			title:'?app=system&controller=content_version&action=index&contentid='+contentid,
			width:570,
			height:350
		});
	},
	
	tags: function (formid)
	{
	    $('#title').bind('change.tags', function(){
			if ($('#title').val() && get_tag && !$('#tags').val())
	    	{
	    		$.post('?app=system&controller=tag&action=get_tags', $('#'+formid).serialize(), function(response){
	    			if (response.state)
	    			{
	    				typeof(tagInput) == 'object' && tagInput.addItem(response.data);
	    			}
	    		}, 'json');
	    	}
		});
	},

	move: function (contentid) 
	{
		if (contentid == undefined){
			contentid = tableApp.checkedIds();
			if(!contentid.length){
				ct.warn('请选择要操作的记录');
				return false;
			}
		}
		var dialog = ct.ajax('移动内容', '?app=system&controller=content&action=move', 350, 300, function (dialog){
			dialog.find("#category_tree").treeview();
			dialog.find("#category_tree span").unbind('click mouseover');
		}, function(form) {
            var input = form.find('[name=catid]:checked'), catid = input.val();
            if (!input.length) {
                ct.error('请选择栏目');
                return false;
            }

            if (!$.isArray(contentid)) {
                contentid = [contentid];
            }

            function move() {
                var id = contentid.shift(), $row = $('#row_' + id), app = $row.attr('model');
                if (app) {
                    $.post('?app=' + app + '&controller=' + app + '&action=move', {
                        contentid: id,
                        catid: catid
                    }, function (ret) {
                        if (ret.state) {
                            $('#chk_row_'+id).attr('checked', false);
                            if (contentid.length) {
                                move();
                            } else {
                                complete();
                            }
                        } else {
                            ct.error(ret && ret.error || '操作失败');
                        }
                    }, 'json');
                } else {
                    if (contentid.length) {
                        move();
                    } else {
                        complete();
                    }
                }
            }

            function complete() {
                dialog.dialog('close');
                ct.ok('操作成功');
                tableApp.reload();
            }

            move();
        });
	},

	forward:function(contentid)
	{
		if (contentid == undefined){
			contentid = tableApp.checkedIds();
			if(!contentid.length){
				ct.warn('请选择要操作的记录');
				return false;
			}
		}
		if (typeof(contentid) != 'object'){
			contentid = [contentid];
		}
		for (var item;item = contentid.pop();) {
			ct.assoc.open('?app=weibo&controller=weibo&action=index&id='+item, 'newtab');
		}
	},
	pushToMobile: function(contentid, model)
	{
		if (contentid == undefined){
			contentid = tableApp.checkedIds();
			if(!contentid.length){
				ct.warn('请选择要操作的记录');
				return false;
			}
		}
		if (typeof(contentid) != 'object'){
			contentid = [contentid];
		}		
		for (var item, m;item = contentid.pop();) {
			m = (typeof model == 'string') ? model : model.attr('model');
			if (self.location.href.indexOf('system') && self.location.href.indexOf('toolbox') && self.location.href.indexOf('add')) {
				window.location.href = '?app=mobile&controller='+m+'&action=add&id='+item;
			} else {
				ct.assoc.open('?app=mobile&controller='+m+'&action=add&id='+item, 'newtab');
			}
		}
	},
    pushToPage:function(contentid){
        var dialog = ct.iframe({
            url:'?app=page&controller=section&action=recommend&contentid='+contentid,
            width:850,
            height:469
        }, {
            picked:function() {
                tableApp && tableApp.reload();
                dialog.dialog('close');
            }
        });
    },
    pushToPlace:function(){
        function genPlaceIds(titles) {
            var result = [];
            $.each(titles || {}, function(i, n) {
                result.push(n.placeid);
            });
            return result.join(',');
        }
        return function(contentid) {
            var dialog = ct.iframe({
                url:'?app=special&controller=special&action=recommend&contentid='+contentid,
                width:800,
                height:445
            }, {
                picked:function(titles) {
                    var placeids = genPlaceIds(titles);
                    $.getJSON('?app=special&controller=special&action=saveRecommend', {
                        contentid:contentid,
                        placeid:placeids
                    }, function(json) {
                        if (json && json.state) {
                            ct.ok('推送成功');
                            tableApp && tableApp.reload();
                            dialog.dialog('close');
                        } else {
                            ct.error(json && json.error || '推送失败');
                        }
                    });
                }
            });
        };
    }(),

	copy: function (contentid) 
	{
		if (contentid == undefined)
		{
			ct.warn('请选择要操作的记录');
			return false;
		}
		var app = $('#row_'+contentid).attr('model');
		if(!app) {
			app = getPara('app');
		}
		ct.form('复制内容', '?app='+app+'&controller='+app+'&action=copy&contentid='+contentid, 350, 300, function (response){
			if (response.state)
			{
				ct.ok('操作成功');
				return true;
			}
			else
			{
				ct.error(response.error);
				return false;
			}
		}, function (dialog){
			dialog.find("#category_tree").treeview();
			dialog.find("#category_tree span").unbind('click mouseover');
		}, function(form) {
            if (!form.find('[name="catid[]"]:checked').length) {
                ct.error('请选择栏目');
                return false;
            }
        });
	},
	
	reference: function (contentid)
	{
		if (contentid == undefined){
			contentid = tableApp.checkedIds();
			if(!contentid.length){
				ct.warn('请选择要操作的记录');
				return false;
			}
		}
		ct.form('引用内容', '?app=system&controller=content&action=reference&contentid='+contentid, 350, 300, function (response){
			if (response.state)
			{
				ct.ok('操作成功');
				return true;
			}
			else
			{
				ct.error(response.error);
				return false;
			}
		}, function (dialog){
			dialog.find("#category_tree").treeview();
			dialog.find("#category_tree span").unbind('click mouseover');
		}, function(form) {
            if (!form.find('[name="catid[]"]:checked').length) {
                ct.error('请选择栏目');
                return false;
            }
        });
	},

    qrcode:function(id) {
        if (id == undefined){
            ct.warn('请选择要操作的记录');
            return false;
        }
        var row = $('#row_'+id);
        var str = row.attr('data-url');
        var modelid = row.attr('modelid');
        var title = row.find('.title_list').text();
        ct.assoc.open("?app=system&controller=qrcode&action=index&str="+encodeURIComponent(str)+'&note='+encodeURIComponent(title)+'&contentid='+id+'&modelid='+modelid, 'newtab');
    },
	
	_common: function (contentid, action, reload, moreParams)
	{
		var app, controller;
		if (reload == undefined) reload = true;
		if (moreParams == undefined) moreParams ='';
        if (contentid == undefined)
        {
        	contentid = tableApp.checkedIds();
			if (contentid.length === 0)
			{
				ct.warn('请选择要操作的记录');
				return false;
			}
			content._common_execute(contentid, action, reload, 0, moreParams);
			return true;
        }
		app = controller = $('#row_'+contentid).attr('model');
		if(!app) {
			app = controller = getPara('app');
		}
		if (action == 'show') controller = 'html';
		if (action == 'saveSection') { moreParams+='&model='+app;app='system';controller='content';}
		$.getJSON('?app='+app+'&controller='+controller+'&action='+action+'&contentid='+contentid+moreParams, function(response){
			if (response.state){
				if(action == 'delete' || action == 'remove' || action == 'unpublish'){
					if(getPara('action') == 'index'){
						tableApp.deleteRow(contentid);
					}else{
						ct.assoc.close();
					}
				}else if(action == 'restore'){
					if(getPara('action') == 'index') tableApp.load();
				}
				if(action == 'pass') passtate = true;
				ct.ok('操作成功');
			}
			else{
				ct.error(response.error);
			}
		});
	},
	
	_common_execute: function (contentid, action, reload, key, moreParams)
	{
		if (moreParams == undefined) moreParams ='';
		var app = controller = $('#row_'+contentid[key]).attr('model');
		if ((app == 'link' || app == 'special') && action == 'show') return ct.warn('链接和专题不能在此生成！');
		if (action == 'show') controller = 'html';
		if(!app) {
			app = controller = getPara('app');
		}
		if (action == 'saveSection') { 
			moreParams = moreParams.replace(/&model=([\d\w]+)/i,'&model='+app);
			app='system';controller='content';
		}
		$.getJSON('?app='+app+'&controller='+controller+'&action='+action+'&contentid='+contentid[key]+moreParams, function(response){
			if (response.state){
				if(action == 'remove' || action == 'restore'){
					if (typeof(tableApp)!=='undefined' && tableApp) {
						tableApp.deleteRow(contentid[key]);
						if(getPara('action') == 'viewsigns' || model == 'interview'){
							cmstop.assoc.close();
						}
					} else {
						cmstop.assoc.close();
					}
				}else{
					$('#row_'+contentid[key]).removeClass('row_chked');
					$('#chk_row_'+contentid[key]).attr('checked', false);
				}
				key++;
				if (contentid.length > key){
					content._common_execute(contentid, action, reload, key, moreParams);
				}else{
					$('#check_all').attr('checked', false);
					if(typeof(tableApp)!=='undefined' && action =='remove' || action == 'restore') tableApp.load();
					if(getPara('action') == 'index'){
						if(action == 'pass') passtate = true;
						if(typeof(tableApp)!=='undefined' && action == 'delete' || action == 'remove') return tableApp.deleteRow();
						if(typeof(tableApp)!=='undefined' && reload) tableApp.load();
						ct.tips('操作成功', 'success');
					}
					else {
						if(action == 'pass'){
							passtate = true;
							tableApp.load();
						}
						ct.ok('操作成功');
					}
				}
			}
			else{
				ct.error(response.error);
			}
		});
	},

	section: function(url) {
		$.getJSON('?app=page&controller=section&action=get_section_info&url='+url, function(json) {
			url = url.slice(23, url.length-6).replace(/\//g,'');
			var html = '已被推送并显示至：<br /><div class="removeable-list">';
			$.each(json.section, function(key, data) {
				html += '<div class="list-item"><div title="'+data.pagename+' &gt; '+data.sectionname+'" class="item-title"><a href="javascript:;" onclick="ct.assoc.open(\''+data.pageurl+'\', \'newtab\')">'+data.pagename+'</a> > <a href="javascript:;" onclick="ct.assoc.open(\''+data.pageurl+'&sectionid='+data.sectionid+'\', \'newtab\')" >'+data.sectionname+'</a></div></div>';
			});
            html += '</div>';
			ct.tips(html, 'success', $('.section_'+url), 0);
		});
	},

    isModelEnabled:function(catid) {
        catid && $.getJSON('?app=system&controller=category&action=isModelEnabled', {
            model:model,
            catid:catid
        }, function(json) {
            if (json && !json.state) ct.error(json.error);
        });
    }
}

/**
 * 二。内容表单代码
 */

content.lock = function (contentid, model)
{
	$.get('?app='+model+'&controller='+model+'&action=lock&contentid='+contentid);
};

content.unlock = function (contentid, model)
{
	$.get('?app='+model+'&controller='+model+'&action=unlock&contentid='+contentid);
};

content.success = function(response) {
	content.unload_alert = 0;
    content.contentid = response.contentid || contentid;

	if (model == 'survey' && action=='add') {
		window.location = '?app=survey&controller=question&action=index&contentid='+response.contentid;
		return;
	}

    form.submit = null;
    $(form).unbind().submit(function() {
        return false;
    });

    var successMsg = $('#success-msg'),
        type = action == 'add' ? '添加' : '修改',
        message = '恭喜，内容'+type+'成功',
        title = $('#title').val(),
        url = response.url ? response.url : $('#url').val(),
        buttons = {};

    if (action == 'add') {
        if (getPara('app') != 'contribution') {
            buttons['继续添加'] = function() {
                location.reload();
                if(!contentid) {
                    var contentid = response.contentid;
                }
                if(contentid)
                {
                    var catid = $('#catid').val();
                    $('form')[0].reset();
                    $('#related_data').empty();
                    $('#catid').val(catid);
                    $.slider.reset();
                    model == 'picture' && $('#pictures').html('');
                }
                successMsg.hide();
            };
        }
        buttons['修改'] = function() {
            successMsg.hide();
            if(!contentid) {
                contentid = response.contentid;
            }
            if(contentid){
                var _model = model == 'contribution' ? 'article' : model;
                location.href = '?app='+_model+'&controller='+_model+'&action=edit&contentid='+response.contentid;
            }
        };
    } else {
        buttons['继续修改'] = function() {
            location.reload();
        };
    }
    buttons['关闭'] = function() {
        if(top != self) {
            ct.assoc.close()
        } else {
            self.close();
        }
    };

    successMsg.find('[data-role=message]').html(message);
    successMsg.find('[data-role=title]').html(title);
    if (url) {
        successMsg.find('[data-role=url]').attr('href', url).text(url);
    } else {
        successMsg.find('tr:gt(0)').not(':last').hide();
    }
    var btnArea = successMsg.find('[data-role=buttons]');
    $.each(buttons, function(text, func) {
        var clazz = text.length <= 2 ? 'button_style_2' : 'button_style_4';
        $('<button class="'+clazz+'" type="button">'+text+'</button>').click(func).appendTo(btnArea);
    });

    $('<div/>').css({
        position:'fixed',
        zIndex:998,
        left:0,
        top:0,
        width:'100%',
        height:'100%',
        margin:0,
        padding:0,
        opacity:.80,
        background:'#FFF'
    }).appendTo(document.body);
    successMsg.css({
        top: $(document).scrollTop() + Math.floor(($(window).height() - successMsg.outerHeight(true)) / 2),
        zIndex:999
    }).show();

    // 更新列表
    // 捕获框架(网编工具箱)状态下的跨域问题
    try {
	    $('#frame_container iframe', parent.document).filter(function(){
	        if (!/\?app=system&controller=content&action=index/i.test(this.src)){
	            return false;
	        }
	        var catid = getPara('catid', this.src);
	        if (!catid) {
	            return true;
	        }
	        return catid == $('#catid').val();
	    }).each(function(){
	        this.contentWindow.tableApp.reload();
	    });
	} catch(e) {
		return true;
	}
};

content.error = function (response, model){
	$("#submit").attr('disabled', false);
	if(response.filterword){
		var str = "内容中出现以下过滤词语，是否要继续发布：",len = response.filterword.length;
		
		for(var k=0; k<len; k++)
		{
			str += '<span class="filterword">' + response.filterword[k] + '</span> '
		}
		
		ct.confirm(str, function (){
            var form = $('form:first');
			form.append('<input type="hidden" value="1" name="ignoreword"/>');
			form.submit();
		});
		$('div.btn_area button:first').text('继续');
		$('p.ct_confirm>span.filterword').css({color: '#d00'});
	}
	else
	{
		ct.error(response.error);
	}
};
window.content = content;
window.show_subtitle = function(){
	if ($('#has_subtitle').attr('checked') == true){
		$('#tr_subtitle').show();
	}else{
		$('#tr_subtitle').hide();
		$('#subtitle').val('');
	}
};

window.expand = function(obj){
	
	if($(obj).children('span').hasClass("span_open"))
	{
		$(obj).children('span').removeClass("span_open");
		$(obj).children('span').addClass("span_close");
		$('#expand').hide();
	}
	else
	{
		$(obj).children('span').removeClass("span_close");
		$(obj).children('span').addClass("span_open");
		$('#expand').show();
	}
};

// 内容重复标题检测
window.checkRepeat = {
	'checkRepeatWay' : 0,
	'init': function(state) {
		var $title = $('#title');
		checkRepeat.checkRepeatWay = state;	// 0-无, 1-按键检测, 2-keyup检测
		if (checkRepeat.checkRepeatWay == 1) {
			$title.css('width','456px').css('padding-right','22px').wrap('<div class="check-repeat-box"></div>').attr('autocomplete', 'off').after('<div class="check-repeat-ico" onclick="checkRepeat.repeatCheckExec();"></div>');
		}
		if (checkRepeat.checkRepeatWay == 2) {
			$title.attr('autocomplete', 'off').bind('keyup', checkRepeat.repeatCheckExec);
		}
		$title.parents('td').append('<div class="clear"></div><div class="check-repeat-panel" id="checkRepeat" style="display:none;"></div>');
	},
	// 关闭标题重复检测
	'closeRepeatTitle' : function() {
		$('#checkRepeat').fadeOut(200);
		$('#title').unbind('keyup', checkRepeat.repeatCheckExec);
	},

	// 标题重复检测
	'repeatCheckExec' : function() {
		var title = $('#title').val();
		var panel = $('#checkRepeat');
		if (checkRepeat.checkRepeatWay == 2 && (!title || title.length < 4)) {
			panel.fadeOut('fast');
			return;
		}
		$.getJSON('?app=system&controller=content&action=compare', {'title':title}, function(json) {
			try {
				if (json.state) {
					panel.empty().append('<div class="check-repeat-banner">相似标题：<a class="check-repeat-close" href="javascript:;" onclick="checkRepeat.closeRepeatTitle(); return false;">忽略提醒</a></div>');
					$.each(json.data, function(i,k) {
						panel.append('<div class="check-repeat-row"><div class="icon '+ k.type +'"></div><span class="check-repeat-title"><a href="'+(k.url || 'javascript:;')+'" target="_blank">' + k.title + '</a></span><span class="check-repeat-cate">[ ' + k.catname + ' ]</span></div>');
					});
					panel.fadeIn('200');
				} else {
					if (checkRepeat.checkRepeatWay == 1) {
						ct.ok('没有检测到内容标题相似或重复')
					}
					throw "empty";
				}
			} catch (e) {
				panel.fadeOut('fast');
			}
		});
	}
}

//表单部分公共代码
if(action == 'add' || action == 'edit') {
	$(function() {
		//$("#catid option[childids='1']").attr("disabled", "disabled");
		
		$('.tips').attrTips('tips', 'tips_green');
		// content.tags(model+'_'+action);
		var frm = $(form);
		var elements = frm.find('input,textarea,select').not(':button,:submit,:image,:reset,[disabled]');
		elements.filter(function(){var l = parseInt(this.getAttribute('maxLength'));return l > 0 && l < 1000 && !this.getAttribute('uncount')}).maxLength();
		$.fn.autocomplete && elements.filter(function(){
			return (!!this.getAttribute('autocomplete') && this.getAttribute('url'));
		}).autocomplete({
			autoFill:false,
			showEvent:'focus'
		});
		elements.filter('input.input_calendar').DatePicker({'format':'yyyy-MM-dd HH:mm:ss'});
		$.fn.colorInput && elements.filter('input.color-input').colorInput();
		subform(frm);
		elements.filter('[name=title]').focus();
	});
}

function subform(frm){
	frm.ajaxForm(function(json){
        submit_result = json;
        if (json.state){
            submit_ok = true;
            content.success(json);
        }else{
            content.error(json);
        }
    }, null, function(form){
        $().focus();
        if (!parseInt($('#catid').val())) {
            window.scrollTo(0, 0);
            ct.error('请选择栏目');
            return false;
        }
        var thumb = $('[name="thumb"]').val();
        if (thumb && (['jpg','jpeg','png','bmp','gif','PNG','JPG','JPEG','BMP','GIF'].indexOf(thumb.split('.').pop()) == -1)) {
        	ct.warn('缩略图不合法');
        	return false;
        }
        if (model == 'picture' && $('[name^=pictures]').length == 0) {
            ct.warn('至少需要上传一张图片');
            return false;
        }
        if (model == 'vote' && $('#options>tr').length < 2) {
            ct.error('至少得保留两个投票选项');
            return false;
        }
        if (action == 'add' && model == 'special' && submit_ok) {
            return false;
        }
        if (window.tinyMCE) {
            $('#content').val(tinyMCE.activeEditor.getContent());
        }
        if (model == 'article' && !$.trim($('#content').val())) {
            tinyMCE.activeEditor.focus();
            ct.error('内容不能为空');
            return false;
        }
        // 内容重复检测
        if (form.data('needPrefab')) {
        	form.trigger('prefab');
        	return false;
        }
        ct.startLoading('center', '正在保存...');
    });
}

if (action == 'add') {
	content.unload_alert = true;
	window.onbeforeunload = function ()
	{
		if (content.unload_alert && $('#title').val() != '')
		{
			return '内容尚未保存，您确认放弃发布吗？';
		}
	};
}

if(action == 'edit') {
	$(function(){
		content.unload_alert = true;
		window.onbeforeunload = function ()
		{
			if (content.unload_alert && window.changed)
			{
				return '内容尚未保存，您确认放弃修改吗？';
			}
		};
		var imgs = $('.content').find('img');
		if(typeof imgs[0] !=='undefined')
		{
			imgs.each(function(){
				if(this.width>=590) this.width = 560;
			})
		}
	});
	var interval = setInterval(function(){content.lock(contentid, model);}, 10000, contentid);
	$(window).unload(function () {
		clearInterval(interval);
		content.unlock(contentid, model);
	});
}

$(function(){
	if(action =='view'){
		$(window).load(function(){
			$('.content').find('img').each(function(){
				if (this.width>590)
				{
					$(this).removeAttr('height');
					this.style.width = '580px';
					$(this).closest('p').css('text-indent','0');
				}
			});
		});
	}
	if (['add', 'miniadd', 'edit', 'miniedit'].indexOf(action) > -1) {
		// source_link处理
		var sourceLink = $('#source_link'),
		reserved = $('#reserved'),
		sourcePanel = $('.source_panel'),
		openSourceLink = $('#open_source_link'),
		isReserved = function() {
			if (reserved.is(':checked')) {
				// reserved
				sourcePanel.show();
			} else {
				// not reserved
				sourcePanel.hide();
			}
		};

		// 伸展/收缩
		reserved.bind('change', isReserved);
		isReserved();

		// 打开链接
		openSourceLink.bind('click', function() {
			var url = sourceLink.val();
			if (url.length === '') return;
			window.open(url);
		});

		// source_link格式补全
		if (sourceLink && sourceLink.length > 0) {
			sourceLink.bind('change', function() {
				var value = sourceLink.val();
				if (!value) return;
				if (value.indexOf('://') == -1) {
					sourceLink.val('http://' + value);
				}
			});
		}
		// 编辑时判断是否展开
		if (action !== 'add' && sourceLink.val()) {
			reserved[0].checked = true;
			isReserved();
		}
	}
});
})();

var rightMenu = function(obj,e) {
	$(obj).parents('tr').trigger('contextMenu',e);
};
var menuIco = '<a href="javascript:;" onclick="rightMenu(this,event);" class="content-menu"></a>';

var get_thumb = function() {
	var content = tinyMCE.activeEditor.getContent(),
	reg = /<img\s+[^>]*src\s*=\s*(["\']?)([^>"\']+)\1[^>]*[\/]?>/img,imgs, i = 0, box, img;
	$('#get_thumb').empty().css('top', $('#get_thumb_button').offset().top+22+$('.mini-main').scrollTop()+'px');
	while(imgs = reg.exec(content)){
		if(imgs[2].indexOf('/images/ext/') != -1) continue;
		i++;
		box = $('<div class="get_thumb-box"></div>');
		img = $('<img src="'+imgs[2]+'" alt="" style="visibility:hidden;position:relative;" />')
		.one('load', function(e) {
			var img = $(e.currentTarget), width = img.width(), height = img.height();
			if (width > height) {
				img.width(98);
				img.css('top', 49 - img.height()/2 + 'px');
			}
			img.css('visibility', 'visible');
		});
		$('#get_thumb').append(box.append(img));
		box.bind('click', function(obj) {
			var url = $(obj.currentTarget).children('img').attr('src');
			if (typeof(url) == 'undefined' || !url.length) return;
			var length = url.indexOf('?');
			if (length > 0) {
				url = url.slice(0, length);
			}
			$.post('?app=article&controller=article&action=thumb',{url:url},function(url){
				$('input[name="thumb"]:text').val(url).nextAll('button:last').show();
				$('#get_thumb').fadeOut();
			});
			$('body').unbind('.thumb');
		});
	}
	if (i==0) {
		ct.error('找不到缩略图');
		return;
	}
	if (i==1) {
		box.click();
		return;
	}
	if ($('#get_thumb').html() && !$('#get_thumb').is(':visible')) {
		$('#get_thumb').fadeIn('fast');
		var clearThumbBox = function() {
			setTimeout(function() {
				$('body').one('click.thumb', function(e) {
					var obj = $(e.originalTarget);
					if (obj.attr('id') == 'get_thumb' || obj.parents('#get_thumb').length) {
						clearThumbBox();
						return;
					}
					$('#get_thumb').is(':visible') && $('#get_thumb').hide();
				});
			}, '100');
		}
		clearThumbBox();
	}
}

// 自动提取关键词/摘要
$(function () {
	if (typeof modelid === 'undefined') { // 列表页
		return;
	}
	var getDescriptionAndTags, waitEditorLoaded;
	getDescriptionAndTags = function () {
		if (!get_tag) {
			return;
		}
		var title, content;
		title = $('#title').val(),
		content = typeof ed === 'object' ?
			ed.getContent() :
			$('#description').val();
		if (!title || !content) {
			return;
		}
		if ($('#description').val() && $('#tags').val()) {
			return;
		}
		$.post('?app=system&controller=content&action=nlppretreat', {
			title: title,
			modelid: modelid,
			content: content
		}, function (res) {
			if (!res.state) {
				return;
			}
			if (!$('#tags').val()) {
				typeof(tagInput) == 'object' && tagInput.addItem(res.data.tags);
			}
			if (!$('#description').val()) {
				$('#description').val(res.data.description);
			}
		}, 'json');
	}
	$('#title').bind('change', function () {
		getDescriptionAndTags();
	});
	$('#description').bind('change', function () {
		if (typeof ed === 'undefined') {
			getDescriptionAndTags();
		}
	});
	if (+modelid === 1) {
		waitEditorLoaded = setInterval(function () {
			if (typeof ed === 'object') {
				ed.onChange.add(function () {
					getDescriptionAndTags();
				});
				clearInterval(waitEditorLoaded);
			}
		}, 100);
	}

	if (+modelid === 1 && ['miniadd', 'add'].indexOf(action) > -1) {
		if (typeof repeatcheck !== 'undefined' && repeatcheck) {
			$('#article_add').add($('#article_edit')).data('needPrefab', 1).bind('prefab', function () {
				var form = $(this);
				$.post('?app=system&controller=content&action=nlpcompare', {
					title: $('#title').val(),
					content: ed.getContent()
				}, function (res) {
					if (res && res.state && res.data.length) {
						var repeatTips = $('#repeat-tips');
						var insertPoint = repeatTips.find('tr:first');
						$.each(res.data, function () {
							insertPoint = $('<tr><td><a href="'+this.url+'" target="_blank">'+this.title+'</a></td></tr>').insertAfter(insertPoint);
						});
						repeatTips.show();
					} else {
						form.data('needPrefab', 0);
						form.trigger('submit');
					}
				}, 'json');
			});

			$('#goonwhenrepeat').bind('click', function () {
				$('#repeat-tips').hide();
				$('#article_add').data('needPrefab', 0);
				$('#article_add').trigger('submit');

			});

			$('#cancelwhenrepeat').bind('click', function () {
				$('#repeat-tips').hide();
				$('#article_add').data('needPrefab', 1);
			});
		}
	}
});