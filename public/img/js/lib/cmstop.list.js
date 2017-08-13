/**
 * based on jQuery 1.3.x
 *
 * @author     kakalong (firebing.cn & hi.baidu.com/emkiao)
 * @copyright  2010 (c) cmstop.com
 * @version    $Id: cmstop.list.js 7552 2012-10-24 08:43:39Z dengguanglei $
 */
(function($){
var CLASSES = {
	list:'fn-list',
	actived: 'actived',
	selected:'selected'
},
OPTIONS = {
	paramVal:'value',
	extraClass:'',
	data:null,
	multi:false,
	itemRender:function(item, li){
		return '<span>'+item.text+'</span>';
	},
	context:null,
	ctrl:null
};
function hasScroll(el) {
	var has = false;
	if ( el.scrollLeft > 0 ) {
		return true;
	}
	el.scrollLeft = 1;
	has = (el.scrollLeft > 0);
	el.scrollLeft = 0;
	return has;
}
var docElem = document.documentElement, $doc = $(document),
contains = docElem.compareDocumentPosition ? function(a, b) {
	return a === b || !!(a.compareDocumentPosition(b) & 16);
} : function(a, b) {
	return (a.nodeType === 9 ? a.documentElement : a).contains(b);
};
var list = function(options) {
	var t = this, o = $.extend({}, OPTIONS, options||{}),
		ctrl = o.ctrl, context = o.context, checked = o.checked;
	t.options = o;

	var panel = $('<ul class="'+CLASSES.list+'"></ul>').appendTo(document.body);
	o.extraClass && panel.addClass(o.extraClass);
	t.panel = panel;
	t.context = context ? (context.jquery ? context : $(context)) :  $doc;
	panel.mousedown(function(e){
		e.preventDefault();
		e.stopPropagation();
		ctrl && ctrl.trigger('mousedown', [e]);
	});
	t._ihide = function(e){
		var el = e.target;
		contains(panel[0], el) || (ctrl && contains(ctrl[0], el)) || t.hide();
	};
	t._checked = $.isArray(checked)
		? [].concat(checked)
		: (checked == undefined ? []  : ((typeof checked == 'string') ? checked.split(',') : [checked]));
	t.checked = [];
	t.storedData = {};
	t.actived = null;
	t.inited = 0;
    t.ctrl = ctrl;
	t.setData(o.data);
};
list.prototype = {
	setData:function(data){
		var t = this;
		if (t.inited) {
			t.checked = [];
			t.storedData = {};
			t.actived = null;
			t.panel.empty();
		}
		function fill(json) {
			for (var i=0,d;d=json[i++];) {
				t.add(d);
			}
			t.context.triggerHandler(t.inited ? 'resetd' : 'initd', [t]);
			t.inited || (t.inited = 1);
		}
		if (data) {
			typeof data == 'string'
			? $.getJSON(data, function(json){
				fill(json);
			})
			: (data.length && fill(data));
		}
		return t;
	},
	add:function(item){
		var t = this, panel = t.panel, o = t.options, paramVal = o.paramVal,
			val = item[paramVal]
				|| (typeof item.getAttribute == 'function' && item.getAttribute(paramVal))
				|| '',
		li = $('<li val="'+val+'"></li>')
		.click(function(){
			o.multi
				? li.triggerHandler(t.checked.indexOf(val) == -1 ? '_select_' : '_unselect_')
				: t.checked.indexOf(val) == -1 && li.triggerHandler('_select_');
			t.context.triggerHandler('changed', [t, li]);
		}).bind('_select_',function(){
			o.multi || t.selected().triggerHandler('_unselect_');
			li.addClass(CLASSES.selected);
			t.checked.indexOf(val) == -1 && t.checked.push(val);
		}).bind('_unselect_',function(){
			li.removeClass(CLASSES.selected);
			var i = t.checked.indexOf(val);
			i != -1 && t.checked.splice(i, 1);
		}).bind('select unselect',function(e){
			li.triggerHandler('_'+e.type+'_');
			t.context.triggerHandler('changed', [t, li]);
		}).mouseover(function(){
			t.actived && t.actived.mouseout();
			t.actived = li;
			li.addClass(CLASSES.actived);
		}).mouseout(function(){
			t.actived = null;
			li.removeClass(CLASSES.actived);
		}).appendTo(panel);
		t.storedData[val] = item;
		if (t._checked.indexOf(val) != -1) {
			t.checked.push(val);
			li.triggerHandler('_select_');
		}
		li.html(o.itemRender(item, li));
		return t;
	},
	select:function(val){
		var t = this, li = t.panel.find('li');
		if (val == undefined) {
			li.triggerHandler('_select_');
		} else {
			$.isArray(val) || (val = val ? ((typeof val == 'string') ? val.split(',') : [val]) : []);
			if (! val.length) {
				return this;
			}
			li.filter(function(){
				return val.indexOf(this.getAttribute('val')) != -1;
			}).triggerHandler('_select_');
		}
		
		t.context.triggerHandler('changed', [t, li]);
		return t;
	},
	selected:function(filter){
		var selected = this.panel.find('li.'+CLASSES.selected);
		return filter ? selected.filter(filter) : selected;
	},
	active:function(val){
		this.panel.find('li[val="'+val+'"]').mouseover();
		return this;
	},
	move:function(direction){
		var li = null;
		if (this.actived) {
			li = this.actived[direction < 0 ? 'prev' : 'next']();
			li.length || (li = null);
		}
		if (! li) {
			li = this.panel.find('li:'+(direction < 0 ? 'last' : 'first'));
			li.length || (li = null);
		}
		li && li.mouseover();
		return this;
	},
	show:function(css){
		var t = this, panel = t.panel;
		setTimeout(function(){
			$doc.bind('mousedown', t._ihide);
		}, 0);
		panel.css($.extend(css||{},{
			display:'block',
			visibility:'hidden'
		}));
		if ($.browser.msie && (document.documentMode < 8 || parseInt($.browser.version) < 8))
		{
			panel.width(panel.width());
		}
		if (hasScroll(panel[0])) {
			var width = panel.width(), maxWidth = parseInt(panel.css('max-width'));
			(isNaN(maxWidth) || maxWidth < 0 || maxWidth > 800) && (maxWidth = 800);
			while (hasScroll(panel[0]) && (width += 3) < maxWidth) {
				panel.width(width);
			}
		}
		panel.css('visibility','visible');
		t.context.triggerHandler('show.list');
		return t;
	},
	hide:function(){
		var t = this;
		t.panel.hide();
		$doc.unbind('mousedown', t._ihide);
		t.context.triggerHandler('hide.list');
		return t;
	},
	visible:function(){
		return this.panel.is(':visible');
	},
    destroy:function(){
        var t = this;
        t.hide();
        t.panel.remove();
    }
};
$.list = list;
})(jQuery);


(function($){
var CLASSES = {
	selectlist:'selectlist'
},
OPTIONS = {
	render:function(val, txt, item, multi, l){
		var span = l.selected().eq(0).find('span').clone();
		val.length > 1 && span.append('<em>...</em>');
		span.attr('title', txt.join(','));
		return span;
	},
	itemRender:function(item){
		return '<span>'+item.text+'</span>';
	},
	options:null,
	extraClass:null,
	name:null,
	value:null,
	multiple:false,
    direction:'down',
	alt:'请选择'
};
var selectlist = function(elem, options){
	var o = $.extend({}, OPTIONS, options || {}),
		$elem = elem.jquery ? elem : $(elem),
		valBox = null,
		name = $elem.attr('name') || o.name,
		val = $elem.val() || o.value || "",
		alt = $elem.attr('alt') || o.alt,
		multi = $elem.attr('multiple') || o.multiple,
		classes = (o.extraClass ? (o.extraClass+' ') : '')
            +CLASSES.selectlist
            +(o.direction&&(o.direction=='up')?(' '+CLASSES.selectlist+'-up'):''),
		a = $('<a tabindex="0" class="'+classes+'"></a>'),
		list;
    elem = $elem[0];
	$elem.hide();
	if (name) {
		if (elem.nodeName == 'INPUT' || elem.nodeName == 'TEXTAREA') {
			valBox = $elem;
		} else {
			valBox = $('<input name="'+name+'" type="hidden" />').insertAfter(elem).hide();
			$elem.removeAttr('name');
			elem.nodeName == 'SELECT' && $elem.attr('disabled', 'true');
		}
	}

	a.mousedown(function(ev, orig){
		if (list.visible()) {
            if (!orig) {
                list.hide();
                ev.preventDefault();
            }
			return;
		}
		var off = a.offset(),
            param = {
                left:off.left,
                minWidth:a.outerWidth() - 2
            };
        if (o.direction == 'up') {
            param.top = off.top - list.panel.outerHeight(true) - 1;
        } else {
            param.top = off.top + a.outerHeight(true) + 1;
        }
		list.show(param);
		a.focus();
	}).keyup(function(e){
		// ENTER
		if (e.keyCode == 13) {
			if (multi) {
				list.hide();
			} else {
				list.actived ? list.actived.triggerHandler('select') : list.hide();
			}
		}
		// SPACE
		if (e.keyCode == 32 && multi) {
			list.actived && list.actived.click();
		}
	}).keydown(function(e){
		// ENTER
		if (e.keyCode == 13 || e.keyCode == 32) {
			return false;
		}
		// TAB ESC
		if (e.keyCode == 9 || e.keyCode == 27) {
			list.hide();
		}
		// DOWN UP
		if (e.keyCode == 40 || e.keyCode == 38) {
			a.mousedown();
			list.move(e.keyCode == 40 ? 1 : -1);
			return false;
		}
	}).insertAfter(elem);
	$elem.bind('changed initd',function(e, t){
		var checked = t.checked, data = t.storedData,
			title = [], span = '<span>'+alt+'</span>';
		if (checked.length) {
			for (var i=0,l=checked.length;i<l;i++) {
				var v = checked[i];
				data[v] && title.push(data[v].text);
			}
			span = o.render(checked, title, data, multi, t);
		}
		a.html(span);
		valBox && valBox.val(checked.join(','));
		a.attr('title', title.join(',', title));
		multi || t.hide();
	}).bind('resetd', function(){
		a.html('<span>'+alt+'</span>');
		valBox && valBox.val('');
		a.attr('title', '');
	}).bind('DOMNodeRemoved', function(){
		a.remove();
		list.destroy();
	});
	
	list = new $.list({
		paramVal:'value',
		multi:multi,
		extraClass:classes,
		data:$elem.attr('url') || elem.options || o.options,
		context:$elem,
		checked:val,
		itemRender:o.itemRender,
		ctrl:a
	});
	$elem.data('listObj', list);
	list.panel.bind('mousedown mouseup', function(){
		setTimeout(function(){a.focus();},0);
	});
};
$.selectlist = selectlist;
$.fn.selectlist = function(options){
	if (typeof options == 'string') {
		var t = $(this[0]).data('listObj');
		return t[options].apply(t, Array.prototype.slice.call(arguments, 1));
	} else {
		return this.each(function(){
			selectlist(this, options);
		});
	}
};

var modelOptions = {
	itemRender:function(item, li){
		var ico = (item.ico || (typeof item.getAttribute == 'function' && item.getAttribute('ico')) || '');
		return '<span>'+(ico ? ('<b class="'+ico+'"></b>') : '')+item.text+'</span>';
	},
	extraClass:'modelset'
};
$.fn.modelset = function(){
	return this.each(function(){
		selectlist(this, modelOptions);
	});
};

var iconOptions = {
    itemRender:function(item, li){
        var icon = (item.icon || (typeof item.getAttribute == 'function' && item.getAttribute('icon')) || '');
        return '<span>'+(icon ? ('<img src="'+icon+'" height="16" />') : item.text)+'</span>';
    },
    extraClass:'iconlist',
    container:null
};
$.fn.iconlist = function(options){
    return this.each(function(){
        var elem = $(this),
            o = $.extend({}, iconOptions, options);
        selectlist(this, o);
        elem.bind('show.list', function() {
            var elem = $(this),
                list = elem.data('listObj'),
                panel = list.panel;
            panel.width((o.container.jquery ? o.container : $(o.container)).innerWidth());
        });
    });
};
})(jQuery);