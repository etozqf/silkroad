var CURRENT_SECTIONID;
var CURRENT_SECTION_CHANGED;
(function(){
var dialog = null;
var editEl = null;
var editmode = 0;
var noconfirm = 0;
var ivallock = null;
var fragment = null;
var preview_w = null;
var IN_PREVIEW = false;
var clearIlock = function(){
    ivallock && clearInterval(ivallock);
};
var setEditing = function(id){
    editmode = 1;
    clearIlock();
    CURRENT_SECTIONID = id;
    ivallock = setInterval(function(){
    	if (!editmode) {
            clearIlock();
            return;
        }
        ct.stopListenOnce();
        $.post('?app=page&controller=section&action=lock','sectionid='+id,
        function(json){
        	if (!json.state)
        	{
        		clearIlock();
        		ct.warn(json.error+'，请退出编辑');
        	}
        },'json');
    }, 10000);
};
var exitEditing = function(){
    editmode = 0;
    clearIlock();
    CURRENT_SECTIONID && $.post('?app=page&controller=section&action=unsave','sectionid='+CURRENT_SECTIONID);
    CURRENT_SECTIONID = null;
    if (preview_w && preview_w.data('previewing')) {
    	var m = preview_w.data('masker');
    	_moveNodes(fragment, preview_w);
    	m.attr('title', preview_w.data('title'));
    	m[0].className = preview_w.data('className');
    	preview_w.data('previewing', 0);
    	preview_w = null;
    	_resetPosition();
    }
};

var sectionaction = {
    edit:function(w, masker, sectionid){
        masker.trigger('click');
    },
    publish:function(w, masker, sectionid){
        var url = '?app=page&controller=section&action=publish';
        $.post(url,'sectionid='+sectionid, function(json){
            if (json.state) {
                var title = w.attr('title');
                masker.removeClass('red').addClass('green');
                masker.attr('title', title+' 已生成到最新');
            } else {
                ct.error(json.error);
            }
        },'json');
    },
    property:function(w, masker, sectionid){
    	var url = '?app=page&controller=section&action=property&sectionid='+sectionid;
    	ct.form('设置区块“'+w.attr('title')+'”属性', url, 480, 380,
        function(json){
        	if (json.state) {
        		masker.removeClass('green').addClass('red');
        		var title = json.name;
	        	masker.attr('title', title+' 设置了属性');
	        	w.attr('title', title);
        		return true;
        	}
        },function(form){
        	form[0].data && $(form[0].data).editplus({
        		buttons:'fullscreen,wrap',
        		height:150
        	});
        }, function() {
            var editor = tinyMCE && tinyMCE.activeEditor && tinyMCE.activeEditor;
            editor && editor.save() && editor.remove();
        });
    },
    grap:function(w, masker, sectionid){
        var url = '?app=page&controller=section&action=grap';
        $.post(url,'sectionid='+sectionid, function(json){
            if (json.state) {
                var title = w.attr('title');
                masker.removeClass('red').addClass('green');
                masker.attr('title', title+' 刚刚抓取成功');
                w.html(json.html||'');
                _resetPosition();
            } else {
                ct.error(json.error);
            }
        },'json');
    },
    moveDown:function(w, masker) {
    	masker.css('z-index',parseInt(masker.css('z-index'))-1);
    	w.css('z-index',parseInt(w.css('z-index'))-1);
    }
};

var _visibleChilds = function(div)
{
	var el = div.firstChild;
	if (!el) return [];
	var elems = [];
	do {
		if ((el.nodeType == 3 && $.trim(el.nodeValue).length) ||
			 (el.nodeType == 1 && (el.offsetHeight || el.offsetWidth)))
		{
			elems.push(el);
		}
	} while (el = el.nextSibling);
	return $(elems);
};
var _moveNodes = function(src, target)
{
	var el = src[0].firstChild, temp;
	target = target.empty()[0];
	while (el) {
		temp = el;
		el = el.nextSibling;
		target.appendChild(temp);
	}
};
var _compareDim = function(el, dim)
{
	var offset = $(el).offset();
	if (offset.left <  dim.minL) {
		dim.minL = offset.left;
	}
	if (offset.top < dim.minT) {
		dim.minT = offset.top;
	}
	var r = offset.left + el.offsetWidth;
	var b = offset.top + el.offsetHeight;
	if (r > dim.maxR) {
		dim.maxR = r;
	}
	if (b > dim.maxB) {
		dim.maxB = b;
	}
};
var _descendants = function(elem, elems)
{
	elems.push(elem);
	if ($.css(elem, 'overflow') == 'visible')
	{
		$('>*:visible', elem).each(function(){
			_descendants(this, elems);
		});
	}
};
var _descendantDimensions = function(elem, dim)
{
	var elems = [];
	_descendants(elem, elems);
	$.each(elems,function(){
		_compareDim(this, dim);
	});
};
var _dimensions = function(w)
{
    var childs = _visibleChilds(w[0]),
        parent = w.parent(),
        H, W,
        dim = {
		minT : 99999,
		minL : 99999,
		maxR : 0,
		maxB : 0
	};
	if (childs.length) {
		childs.each(function(){
	    	if (this.nodeType==3) {
	    		// wrap textNode to comulate dimensions
	    		var p = document.createElement('xx');
	    		var o = this.parentNode;
	    		o.insertBefore(p, this);
	    		p.appendChild(this);
	    		_compareDim(p, dim);
	    		o.insertBefore(this, p);
	    		o.removeChild(p);
	    	} else {
	    		_descendantDimensions(this, dim);
	    	}
	    });
	    H = dim.maxB - dim.minT;
	    W = dim.maxR - dim.minL;
	} else {
		W = w.width();
		H = w.height();
		var offset = w.offset();
		dim.minL = offset.left;
		dim.minT = offset.top;
	}
    if (H < 20) {
    	H = 20;
    }
    if (W < 20) {
    	W = 20;
    }
    return {width:W,height:H,left:dim.minL,top:dim.minT};
};
var _resetOnePos = function() {
	var w = $(this), masker = w.data('masker'), dim = _dimensions(w);
	masker.css({
		width:dim.width,
	    height:dim.height,
	    left:dim.left,
	    top:dim.top
	});
};
var _resetPosition = function(){
	$('span.section').each(_resetOnePos);
};
var _prepareSection = function(){
    var w = $(this);
    var dim = _dimensions(w);
	var section_id = w.attr('id');
	var masker = $('<div class="section_marsker"></div>')
	.css({
	    width:dim.width,
	    height:dim.height,
	    position:'absolute',
	    left:dim.left,
	    top:dim.top,
	    opacity:.3
	}).hover(function(){
	    masker.addClass('hover');
	},function(){
	    masker.removeClass('hover');
	});
	w.data('masker', masker);
	masker.appendTo(document.body);
	var title = w.attr('title');
	w.hasClass('updated')
	    ? masker.addClass('red').attr('title',title+'  未生成到最新')
	    : masker.addClass('green').attr('title',title+'  已生成到最新');
	var type = w.attr('type').toLowerCase();
	if (type == 'feed' || type == 'rpc' || type == 'json')
    {
        masker.contextMenu('#section_menu_grap',
    	function(action, el, pos) {
    		sectionaction[action](w, masker, section_id);
    	});
    	masker.click(function(e){
    	    masker.trigger('contextMenu',[e]);
    	});
    }
    else
    {
        masker.contextMenu('#section_menu_html',
    	function(action, el, pos) {
    		sectionaction[action](w, masker, section_id);
    	});
    	var next_click = function(){
		    var url = '?app=page&controller=section&action=visual&sectionid='+section_id;
		    $.getJSON(url,function(json){
    	        masker.removeClass('hover');
			    if (!json.state)
			    {
			        ct.error(json.error);
			        return;
			    }
			    var t = '编辑区块:'+w.attr('title');
                var previewShow = function(callback) {
                    IN_PREVIEW = true;
                    var widget = dialog.parent(),
                        origin = {
                            width: widget.width(),
                            height: widget.height(),
                            left: widget.offset().left,
                            top: widget.offset().top
                        },
                        animate = {
                            width: 155,
                            height: 60,
                            left: origin.left + origin.width - 155,
                            top: origin.top + origin.height - 60
                        };
                    dialog.css('visibility', 'hidden');
                    widget.data('origin', origin).animate(animate, 'fast', function() {
                        dialog.hide();
                        widget.find('.btn_area button:eq(1)').text('返回');
                        widget.find('.pop_title').css('max-width', 110);
                        (ct.func(callback) || function() {})();
                    });
                };
                var previewRestore = function(options) {
                    options = options || {};
                    IN_PREVIEW = false;
                    var widget = dialog.parent(), origin = widget.data('origin'), win,
                        callback = function() {
                            dialog.css({visibility: 'visible'});
                            widget.find('.btn_area button:eq(1)').text('预览');
                            widget.find('.pop_title').css('max-width', 'none');
                        };
                    if (options.center) {
                        win = $(window);
                        origin.left = Math.floor((win.width() - origin.width) / 2);
                        origin.top = $(document).scrollTop() + Math.floor((win.height() - origin.height) / 2);
                    }
                    widget.removeData('origin');
                    dialog.show();
                    if (options.unsave) {
                        exitEditing();
                    }
                    if (options.close) {
                        widget.hide().css(origin);
                        callback();
                    } else {
                        widget.show().animate(origin, 'fast', function() {
                            callback();
                        });
                    }
                };
			    if (!dialog)
    			{
    			    dialog = $('<div/>');
    			    dialog.dialog({
                        autoOpen:true,
            			bgiframe: true,
            			width : 750,
            			height: 460,
            			modal : false,
            			title : t,
            			buttons : {'确定':function(){}},
            			beforeclose: function(){
            			    if (noconfirm)
            			    {
            			        noconfirm = 0;
            			        return true;
            			    }
            			    return window.confirm('退出当前编辑吗？');
            			},
            			close: function(){
            			    noconfirm = 0;
                            IN_PREVIEW && previewRestore({center: true, close: true});
                            exitEditing();
            			},open:function(){

            			}
            		});
    			}
    			else
    			{
                    if (IN_PREVIEW) {
                        previewRestore({center: true, unsave: CURRENT_SECTIONID !== section_id});
                    } else {
                        dialog.dialog('option', 'position', 'center');
                    }
    				dialog.prev().find('>:first').text(t);
    			}
                setEditing(section_id);
    			dialog.html(json.html||'');
			    var buttons;
			    var overlay = $('<div style="background:#44DAF4;z-index:2;position:absolute;display:none;width:100%;height:100%"/>').css('opacity',.4);
                dialog.prepend(overlay);
                var save = function(data, after, preview) {
                    overlay.show();
			        dialog.nextAll('div.btn_area').children('button').attr('disabled','disabled');
			        $.ajax({
			            url:url,
			            data:data,
			            dataType:'json',
			            type:'POST',
			            success:function(res){
			                if (res.state)
			                {
			                    if (preview && !w.data('previewing'))
			                    {
			                    	_moveNodes(w, fragment);
			                    	preview_w = w;
			                    	w.data('previewing', 1);
			                    	w.data('className', masker[0].className);
			                    	w.data('title', masker.attr('title'));
			                    }
			                    w.html(res.html||'');
			                    _resetPosition();
			                    after(dialog);
			                    if (! preview)
			                    {
			                    	fragment.empty();
			                    	w.data('previewing', 0);
			                    	if (preview_w == w) {
			                    		preview_w = null;
			                    	}
			                    	noconfirm = 1;
			                    	dialog.dialog('close');
			                    }
			                }
			                else
			                {
			                    var info = $('<div class="error"><sub></sub>'+res.error+'</div>').prependTo(dialog);
			                    setTimeout(function(){info && info.hide()},2000);
			                }
			            },complete:function(){
			                overlay.hide();
			                dialog.nextAll('div.btn_area').children('button').removeAttr('disabled');
			            }
			        });
                };
			    if (type == 'html' || type == 'auto') {
			        editEl = dialog.find('textarea#data');
        		    buttons = {
        			    '保存':function(){
                            var editor = tinyMCE && tinyMCE.activeEditor && tinyMCE.activeEditor;
                            editor && editor.save();
        			    	var data = 'do=save&data='+encodeURIComponent(editEl.val());
        			    	var time_publish = dialog.find('input[name=commit_publish]')[0].checked;
        			    	if (time_publish)
        			    	{
        			    		data += '&nextupdate='
        			    			+encodeURIComponent(dialog.find('input[name=nextupdate]').val());
        			    	}
        			        save(data,function(){
        			        	if (time_publish)
        			        	{
	        			            masker.removeClass('green').addClass('red');
	        			            masker.attr('title',w.attr('title')+' 未生成到最新');
        			        	}
        			        	else
        			        	{
        			        		masker.removeClass('red').addClass('green');
        			            	masker.attr('title',w.attr('title')+' 已生成到最新');
        			        	}
                                IN_PREVIEW && previewRestore({center: true, close: true});
        			        });
        			    },
        		    	'预览':function(){
                            if (IN_PREVIEW) {
                                previewRestore();
                            } else {
                                previewShow(function() {
                                    save('do=preview&data='+encodeURIComponent(editEl.val()),function(){
                                        masker.removeClass('green').addClass('red');
                                        masker.attr('title',w.attr('title')+' 预览中');
                                    },1);
                                });
                            }
        		    	},
        			    '取消':function(){
        			        noconfirm = 1;
                            // IN_PREVIEW && previewRestore({center: true, close: true});
        			        dialog.dialog('close');
        			    }
        			};
			    } else if (json.type == 'hand') {
			    	editEl = null;
			        var table = dialog.find('table.table_info');
                    dialog.css({position:'relative'});
                    handaction.scantable(table);
    			    dialog.find('input[name=addrow]').click(function(){
    			        handaction.addrow(table, this.getAttribute('pos'));
    			    });
                    handaction.init({
                        table:table,
                        rows:json.rows
                    });
    			    buttons = {
        			    '保存':function(){
        			    	var data = 'do=save';
        			    	var time_publish = dialog.find('input[name=commit_publish]')[0].checked;
        			    	if (time_publish)
        			    	{
        			    		data += '&nextupdate='
        			    			+encodeURIComponent(dialog.find('input[name=nextupdate]').val());
        			    	}
        			        save(data,function(){
        			        	if (time_publish)
        			        	{
	        			            masker.removeClass('green').addClass('red');
	        			            masker.attr('title',w.attr('title')+' 未生成到最新');
        			        	}
        			        	else
        			        	{
        			        		masker.removeClass('red').addClass('green');
        			            	masker.attr('title',w.attr('title')+' 已生成到最新');
        			        	}
        			        });
                            IN_PREVIEW && previewRestore({center: true, close: true});
        			    },
    			    	'预览':function(){
                            if (IN_PREVIEW) {
                                previewRestore();
                            } else {
                                previewShow(function() {
                                    save('do=preview',function(){
                                        masker.removeClass('green').addClass('red');
                                        masker.attr('title',w.attr('title')+' 预览中');
                                    },1);
                                });
                            }
    			    	},
        			    '取消':function(){
        			        noconfirm = 1;
                            // IN_PREVIEW && previewRestore({center: true, close: true});
        			        dialog.dialog('close');
        			    }
        			};
			    } else if (json.type == 'push') {
                    editEl = null;
                    dialog.css({position:'relative'});
                    pushaction.init(dialog);
                    buttons = {
                        '保存':function(){
                            var data = 'do=save';
                            var time_publish = dialog.find('input[name=commit_publish]')[0].checked;
                            if (time_publish)
                            {
                                data += '&nextupdate='
                                    +encodeURIComponent(dialog.find('input[name=nextupdate]').val());
                            }
                            save(data,function(){
                                if (time_publish)
                                {
                                    masker.removeClass('green').addClass('red');
                                    masker.attr('title',w.attr('title')+' 未生成到最新');
                                }
                                else
                                {
                                    masker.removeClass('red').addClass('green');
                                    masker.attr('title',w.attr('title')+' 已生成到最新');
                                }
                            });
                            IN_PREVIEW && previewRestore({center: true, close: true});
                        },
                        '预览':function(){
                            if (IN_PREVIEW) {
                                previewRestore();
                            } else {
                                previewShow(function() {
                                    save('do=preview',function(){
                                        masker.removeClass('green').addClass('red');
                                        masker.attr('title',w.attr('title')+' 预览中');
                                    },1);
                                });
                            }
                        },
                        '取消':function(){
                            noconfirm = 1;
                            // IN_PREVIEW && previewRestore({center: true, close: true});
                            dialog.dialog('close');
                        }
                    };
                } else { editEl = null;}
    			dialog.dialog('option','buttons',buttons).dialog('open');
    			if (editEl && editEl.is(':visible')) {
    				editEl.editplus({
    					buttons: (type=='auto' ? 'fullscreen,wrap,|,db,content,discuz,phpwind,shopex,|,loop,ifelse,elseif,|,preview' : 'fullscreen,wrap,visual'),
    					width:725,
    					height:300
    				});
    			}
			});
		};
    	masker.click(function(){
            if (editmode && CURRENT_SECTIONID && CURRENT_SECTIONID != section_id) {
                if (window.confirm('退出当前编辑吗？')) {
                    next_click();
                }
            } else {
                next_click();
            }
    	});
    }
};
window.init = function(){
    $('span.section').each(_prepareSection);
    window.onresize = _resetPosition;
    fragment = $('<div/>');

    // timeout
    $.cookie(COOKIE_PRE + 'timeout', '0', {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
    if (window.__timeCountHandle) {
        clearTimeout(window.__timeCountHandle);
    }
    var refresh = function() {
        var timeout = $.cookie(COOKIE_PRE + 'timeout')>>>0;
        if (++timeout > 10) {
            return logout();
        }
        $.cookie(COOKIE_PRE + 'timeout', String(timeout), {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
        window.__timeCountHandle = setTimeout(refresh, autolock * 6000);
    },
    bind = function() {
        $('body').bind('mousemove', function() {
            $.cookie(COOKIE_PRE + 'timeout', '0', {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
        }).bind('keypress', function() {
            $.cookie(COOKIE_PRE + 'timeout', '0', {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
        });
    },
    logout = function() {
        var body = document.body,
        overlay = $('<div></div>').appendTo(body).css({
            'position': 'fixed',
            'width': '100%',
            'height': '100%',
            'background': 'black',
            'opacity': '0.4',
            'top': '0',
            'left': '0',
            'z-index': '999998'
        }),
        dialog = $('<div class="dialog-box"></div>').css({
            'position': 'absolute',
            'width': '400px',
            'top': '50%',
            'left': '50%',
            'height': '200px',
            'margin-top': '-100px',
            'margin-left': '-245px',
            'background': '#f5f5f5',
            'z-index': '999999',
            'background-image': 'url(css/images/bg_login.jpg)',
            'background-position-y': '8px',
            'border': '5px solid #666666',
            'background-image': 'url(css/images/timeout.png)',
            'background-repeat': 'no-repeat'
        });
        $('<link>').attr({
            "rel" : "stylesheet",
            "type" : "text/css",
            "href" : IMG_URL + "js/lib/jquery-ui/dialog.css"
        }).appendTo($('head'));
        $.get('?app=system&controller=admin&action=timeout', null, function(html) {
            var s = new Array();
            html = html.replace(/<script(?:[^>]+?type="([^"]*)")?[^>]*>([^<][\s\S^(?:<\/script>)]+?)<\/script>/ig, function($1, $2, $3){
                if ($2 && $2 != 'text/javascript') {
                    return $1;
                }
                s.push($3);
                return '';
            });
            dialog.append(html).appendTo(body);
            for (var i=0,l=s.length; i<l; i++) {
                window[ "eval" ].call( window, s[i] );
            }
            dialog.find('form').bind('submit', function(event) {
                form = $(this);
                $.post(form.attr('action'), form.serialize(), function(req) {
                    if (!req.state) {
                        $('#seccode_image').click();
                        return ct.error(req.error || '登陆失败');
                    }
                    overlay.remove();
                    dialog.remove();
                    $.cookie(COOKIE_PRE + 'timeout', '0', {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
                    refresh();
                }, 'json');
                return false;
            });
        }, 'string');
    };
    bind();
    refresh();
};
})();