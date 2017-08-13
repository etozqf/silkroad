var CURRENT_SECTIONID;
var CURRENT_SECTION_CHANGED;
var page = function(){
//-----------------------------------------------
var viewBox = null, overlay = null;
var sectionList = null, sectionBox = null, sectionCount = null;
var pageid = null;
var READONLY;
var editEl = null;
var ivalstate = null;
var clearIstate = function(){
	ivalstate && clearTimeout(ivalstate);
};
var ivallock = null;
var clearIlock = function(){
    ivallock && clearInterval(ivallock);
};
function innerWidth(){
	return document.documentElement.clientWidth;
}
function innerHeight(){
	return document.documentElement.clientHeight;
}
function floatBox(html, pos, afterHtml) {
	$('div.floatbox').remove();
	var div = $('<div class="floatbox" style="position:fixed;visibility:hidden"></div>')
		.appendTo(document.body);
	var img = $('<img src="images/close.gif" />').css({
		position:'absolute',
		top:1,
		right:2,
		cursor:'pointer'
	}).click(function(){
		div.remove();
	});
	
	div.html(img).append(html);
	typeof afterHtml == 'function' && afterHtml(div);
	var style = cmstop.pos(pos, div.outerWidth(true), div.outerHeight(true));
	style.visibility = 'visible';
	div.css(style);
	return div;
}
var showMessage = function(json){
    var message = overlay.find('.message');
    message.html(json.error);
    if (json.userid) {
        var button = $('<button class="button_style_4">解除锁定</button>').appendTo(message);
        button.click(function() {
            s.unlock(CURRENT_SECTIONID);
            return false;
        });
        button.wrap('<p style="margin:10px;"></p>');
    }
    overlay.show();
    READONLY = true;
};
var hideMessage = function(){
    overlay.hide();
    READONLY = false;
};
var scrollToSection = function(id) {
    var offset = 0, li = $('#section_'+id);
    li.prevAll('li').each(function(){
        offset += $(this).outerHeight(true);
    });
    if ((offset + 5 + li.outerHeight(true) - sectionBox.scrollTop()) > sectionBox.innerHeight()) {
        sectionBox.scrollTop(offset);
    } else if(offset < sectionBox.scrollTop()) {
        sectionBox.scrollTop(offset);
    }
};
var reloadCurrentSection = function() {
    var id = CURRENT_SECTIONID;
    CURRENT_SECTIONID = null;
    s.unsave(id, function() {
        s.editSection(id);
    });
};
var setEditing = function(){
    clearIlock();
    ivallock = setInterval(function(){
        var id = CURRENT_SECTIONID;
        ct.stopListenOnce();
        $.post('?app=page&controller=section&action=lock','sectionid='+id,
            function(json){
                if (json.state) {
                    if (READONLY) {
                        reloadCurrentSection();
                    }
                    hideMessage();
                } else {
                    showMessage(json);
                }
            },'json');
    }, 15000);
};
var exitEditing = function(){
    clearIlock();
};
var section_state_lock = false;
var section_state = function(callback){
	if (section_state_lock) return;
	var origLiset = sectionList.find('>li');
	ct.stopListenOnce();
	section_state_lock = true;
	clearIstate();
    $.ajax({
    	url:'?app=page&controller=page&action=sections&pageid='+pageid,
    	dataType:'json',
    	success:function(json){
            sectionCount.html('('+json.total+')');
    		for (var i=0,l;l=json.data[i++];) {
				_build_li_section(l,true);
			}
			origLiset.remove();
			sectionList.find('>li').show();
			if (CURRENT_SECTIONID) {
                $('#section_'+CURRENT_SECTIONID).addClass('active');
			}
			section_state_lock = false;
    		typeof callback=='function' && callback();
    		ivalstate = setTimeout(section_state,60000);
    	},error:function(){
    		section_state_lock = false;
    		typeof callback=='function' && callback();
    		ivalstate = setTimeout(section_state,60000);
    	}
    });
};
var _build_li_section = function(item,hidden){
    var li = $('<li id="section_'+item.sectionid
        +'" class="section-item '+item.type
        +'" title="'+item.name
        +'"><a href="">'+item.name+'</a></li>');
    var a = li.find('a').click(function(){
    	s.editSection(item.sectionid);
    	return false;
    });
    li.click(function(){
    	s.editSection(item.sectionid);
    }).hover(function(){
    	li.addClass('hover');
    },function(){
    	li.removeClass('hover');
    });
    item.locked && (a.addClass('locked'), a.attr('title',item.lockedby+' 编辑中'));
    ct.IE7 && a.focus(function(){this.blur();});
	hidden && li.hide();
    sectionList.append(li);
};
var goFirstSection = function(){
	var li = sectionList.find('>li:first');
    li.length ? li.click() : (viewBox.empty());
};
var s = {
    init:function(id) {
        var m = /&sectionid=(\d+)/.exec(location.search);
        var sectionid = m && m[1];
        viewBox = $("#viewBox");
        overlay = $('#overlay');
        sectionList = $("#sectionList");
        sectionCount = $('#sectionCount');
        var sectionPanel = $('#sectionPanel');
        sectionBox = $('#sectionBox');
        var bodyContainer = $('#bodyContainer');
        var panelWidth = sectionPanel.outerWidth(true) + 15;
        var adapt = function(){
            var w = innerWidth() - panelWidth;
            viewBox.css('width', w);
            overlay.css('width', w);
            var h = innerHeight() - bodyContainer.offset().top - 1;
            bodyContainer.css('height', h);
            sectionPanel.css('height', h);
            sectionBox.css('height', sectionPanel.innerHeight() - 24);
        };
        adapt();
        window.onresize = adapt;

        pageid = id;
        section_state(function(){
            if (sectionid) {
                s.editSection(sectionid);
            } else {
                goFirstSection();
            }
        });

        $(window).bind('unload',function(){
            clearIstate();
            clearIlock();
            CURRENT_SECTIONID && s.unsave(CURRENT_SECTIONID);
        });
    },
    editSection:function(id) {
        if (!id) return;
        function next() {
            CURRENT_SECTIONID = id;
            setEditing();
            $.getJSON('?app=page&controller=section&action=edit&sectionid='+id, function(json) {
                if (json.state) {
                    hideMessage();
                } else {
                    showMessage(json);
                }
                viewBox.html(json.html);
                var frm = viewBox.find('form');
                if (json.type == 'html' || json.type == 'auto' || json.type == 'json' || json.type == 'rpc' || json.type == 'feed') {
                    editEl = $('#data');
                    if (editEl.length) {
                        setTimeout(function() {
                            // 预览
                            if (window.privPriview) {
                                $.post('?app=page&controller=section&action=previewhtml', {
                                    sectionid: id
                                }, function(html){
                                    editEl.hide().after('<div id="previewData">'+html+'</div>');
                                });
                            } else {
                                editEl.hide().after('<div id="previewData" class="warning t_c">没有模板预览权限</div>');
                            }
                            $('[name="submit"]').bind('click', function(){
                                $('#previewData').remove();
                                $('<input />').attr({
                                    type: 'submit',
                                    name: 'submit',
                                    value: '保存'
                                }).addClass('button_style_2').insertAfter($(this));
                                $(this).remove();
                                editEl.show();
                                // 编辑
                                editEl.editplus({
                                    buttons:(json.type == 'auto' ? 'fullscreen,wrap,|,db,content,discuz,phpwind,shopex,|,loop,ifelse,elseif,|,preview' : 'fullscreen,wrap,visual'),
                                    width:editEl.parent().innerWidth() || 750
                                });
                            });
                        }, 10);
                    }
                } else if (json.type == 'hand') {
                    var table = viewBox.find('table.table_info:first');
                    handaction.scantable(table);
                    viewBox.find('input[name=addrow]').click(function () {
                        handaction.addrow(table, this.getAttribute('pos'));
                    });
                    handaction.init({
                        table:table,
                        rows:json.rows
                    });
                } else if (json.type == 'push') {
                    pushaction.init(viewBox);
                }

                var calendar = viewBox.find('div.calendar');
                if (calendar.length) {
                    var dp = new DatePanel({'place':calendar[0], 'format':'yyyy-MM-dd'});
                    dp.bind('DATE_CLICKED', function () {
                        viewBox.find('div.logtable').load('?app=page&controller=section&action=logpack&sectionid=' + id + '&d=' + encodeURIComponent(dp.format()));
                    });
                }
                frm.ajaxForm(function (json) {
                    if (json.state) {
                        CURRENT_SECTION_CHANGED = false;
                        ct.ok(json.info);
                        reloadCurrentSection();
                    } else {
                        ct.error(json.error);
                    }
                }, null, function() {
                    var editor = tinyMCE && tinyMCE.activeEditor && tinyMCE.activeEditor;
                    editor && editor.save();
                });
                section_state(function() {
                    scrollToSection(id);
                });
            });
        }
        if (CURRENT_SECTIONID) {
            if (CURRENT_SECTIONID == id) return;
            if (CURRENT_SECTION_CHANGED) {
                ct.confirm('操作尚未保存，确定要退出当前编辑吗？', function() {
                    CURRENT_SECTION_CHANGED = false;
                    exitEditing();
                    s.unsave(CURRENT_SECTIONID);
                    next();
                });
                return;
            }
            exitEditing();
            s.unsave(CURRENT_SECTIONID);
        }
        next();
    },
    previewSection:function(form, presave) {
        var id = form && form.sectionid && form.sectionid.value || CURRENT_SECTIONID;
        var url = '?app=page&controller=section&action=preview&pageid='+pageid+'&sectionid='+id;
        function next(json) {
            if (!json.state) {
                ct.openWindow.endLoop();
                ct.warn('无预览');
                return;
            }
            if (json.open) {
                ct.openWindow(url+'&gen='+Math.random()+'#'+id, 'previewsection_'+id);
            } else {
                ct.openWindow.endLoop();
                $('<div class="section-preview">'+json.html+'</div>').dialog({
                    title:'预览区块'
                });
            }
        }
        ct.openWindow.startLoop();
        if (presave) {
            var editor = tinyMCE && tinyMCE.activeEditor && tinyMCE.activeEditor;
            editor && editor.save();
            $.post(url, 'data='+encodeURIComponent(form.data.value) , next, 'json');
        } else {
            $.getJSON(url+'&detect=1', next);
        }
    },
    unlock:function(id,btn){
    	ct.confirm('解锁会导致他人正在编辑的区块无法保存，确定要解锁？',function(){
	        $.post('?app=page&controller=section&action=unlock','sectionid='+id,
	        function(json){
	            if (json.state) {
                    CURRENT_SECTIONID = null;
                    CURRENT_SECTION_CHANGED = false;
                    s.editSection(id);
	            }
	        },'json');
    	});
    },
    unsave:function(id, callback) {
        $.post('?app=page&controller=section&action=unsave', 'sectionid='+id, callback);
	},
    viewLog:function(id) {
        var url = '?app=page&controller=section&action=preview&pageid='+pageid+'&sectionid='+CURRENT_SECTIONID+'&logid='+id;
        ct.openWindow.startLoop();
        $.getJSON(url+'&detect=1', function(json) {
            if (!json.state) {
                ct.openWindow.endLoop();
                ct.warn('无预览');
                return;
            }
            if (json.open) {
                ct.openWindow(url+'&gen='+Math.random()+'#'+CURRENT_SECTIONID, 'previewsection_'+CURRENT_SECTIONID);
            } else {
                ct.openWindow.endLoop();
                $('<div class="section-preview">'+json.html+'</div>').dialog({
                    title:'预览区块历史记录'
                });
            }
        });
    },
    clearLog:function() {
    	var sid = CURRENT_SECTIONID;
    	sid && ct.confirm('此操作不可恢复，确定要清空吗？',function(){
            $.getJSON('?app=page&controller=section&action=clearlog&sectionid='+sid,
            function(json) {
                if (json.state) {
                    ct.ok("清空完毕");
                    viewBox.find('div.logtable').load('?app=page&controller=section&action=logpack&sectionid='+sid);
                } else {
                    ct.error("清空失败");
                }
            },'json');
        });
    },
    restoreLog:function(id) {
        var sid = CURRENT_SECTIONID;
        var data = id=='orig' ? ('logid=orig&sectionid='+sid) : ('logid='+id);
        ct.confirm('确定恢复吗？',function() {
            $.post('?app=page&controller=section&action=restorelog',data,
            function(json) {
                if (json.state) {
                    ct.ok(json.info);
                    CURRENT_SECTIONID = null;
                    s.editSection(sid);
                } else {
                    ct.error(json.error);
                }
            },'json');
        });
    },
    getLog:function(id) {
        $.getJSON('?app=page&controller=section&action=getlog&logid='+id,
        function(json) {
            if (json.state) {
                editEl.val(json.data);
            }
        });
    },
    grapSection:function(id) {
        $.post('?app=page&controller=section&action=grap','sectionid='+id, function(json){
            if (json.state) {
                ct.ok(json.info);
                reloadCurrentSection();
            } else {
                ct.error(json.error);
            }
        },'json');
    },
    visualEdit:function(id) {
        id == undefined && (id = pageid);
        ct.openWindow('?app=page&controller=page&action=visualedit&pageid='+id, 'view_edit');
    },
    publish:function(id) {
        $.post('?app=page&controller=section&action=publish','sectionid='+id,
            function(json){
                if (json.state) {
                    ct.tips(json.info,'success');
                } else {
                    ct.error(json.error);
                }
            },'json');
    },
    publishPage:function(id) {
        id == undefined && (id=pageid);
        $.post('?app=page&controller=page&action=publish','pageid='+id,
            function(json) {
                if (json.state) {
                    ct.ok(json.info);
                } else {
                    ct.error(json.error);
                }
            },'json');
    },
    searchSection:function(o) {
    	var div = floatBox('\
    	<input type="text" class="bdr_6" size="30"\
    		url="?app=page&controller=section&action=searchall&pageid='+pageid+'&keyword=%s"\
    	/>', o,
    	function(box){
    		box.find('input').autocomplete({
    			itemSelected:function(a, item){
    				s.editSection(item.sectionid);
    				div.remove();
    			}, itemPrepared:function(a, item){
    				a.addClass('section-item '+item.type).css('padding-left', 16);
    			}
    		});
    	});
    },
    logs:function(id) {
        var dialog = ct.iframe({
            url:'?app=page&controller=page&action=logs&pageid='+id,
            width: 600,
            height: 490
        }, {
            edit:function(sectionid) {
                dialog.dialog('close');
                s.editSection(sectionid);
            }
        });
    },
    pagePriv:function(pageid) {
        ct.iframe({
            url: '?app=page&controller=page_priv&action=index&pageid='+pageid,
            width: 350,
            height: 300
        });
    },
    sectionPriv:function(sectionid) {
        ct.iframe({
            url: '?app=page&controller=section_priv&action=index&sectionid='+sectionid,
            width: 350,
            height: 300
        }, {}, null, function() {
            page.getSectionEditors(sectionid, function(data) {
                $('#section-editors').empty();
                if (data.length) {
                    var html = '', count = 0;
                    $.each(data, function(i, n) {
                        if (count >= 5) return false;
                        count++;
                        html += '<a href="javascript:url.member('+ n.userid+')">'+n.username+'</a>&nbsp;';
                    });
                    if (count >= 5) html += '...';
                    $('#section-editors').html(html);
                }
            });
        });
    },
    getSectionEditors:function(sectionid, callback) {
        $.getJSON('?app=page&controller=section_priv&action=ls', {
            sectionid:sectionid
        }, function(json) {
            if (json.state && json.data) {
                callback && callback(json.data);
            }
        });
    }
};
return s;
}();