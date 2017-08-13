(function($) {
	var defaults = {
		url: '', // 数据源地址
		// dataUrl: '',
		type: 'single', // single: 单选, multiple: 多选, tree: 直接显示的树状, select: 下拉菜单
		rootClass: '', // 自定义全局className，可以通过此项来设置全部栏目的最大高度
		recently: true, // 最近使用开关
		height : 'auto', 
		disable: '',
		checkParents: false, // true: 可以选择父栏目，false:不可选大栏目
		checkedAfter: function( dataObject ){} // dataObject : {catid: ,name: ,node: .item-block}
	},

	classes = {
		overlay_select 			: 'selectree',
		overlay_select_hover	: 'selectree-hover',
		selectree_text 			: 'text',
		arrow          			: 'arrow',
		sprite         			: 'selectree-sprite',
		datalist 				: 'selectree-datalist',
		recently 				: 'selectree-recently',
		head 					: 'head',
		title 					: 'title',
		sear_ico 				: 'search-ico',
		head_up					: 'up',
		overall 				: 'selectree-overall',
		tree 					: 'selectree-list',
		item     				: 'item',
		block    				: 'item-block',
		input    				: 'input',
		radio    				: 'radio',
		checkbox 				: 'checkbox',
		fold 					: 'fold-status',
		unfold 					: 'unfold-status',
		foldarrow 				: 'fold',
		unfoldarrow 			: 'unfold',
		hover 					: 'hover',
		inputfocus 				: 'search-input-focus',
		blurmark 				: '_a_',
		selected 				: 'selectree-selected',
		ie6 					: 'ie6root',
		hasparent 				: 'hasparent'
	},
	doc = $(document),
	doclickhide = {
		bindEventClose :  function(e,selectElem,hideElem,fn,eFn) 
		{
			var el = e.target, tag = el.tagName || '*';
			el == selectElem[0] || selectElem.find(tag).index(el) != -1 || el == hideElem[0] || hideElem.find(tag).index(el) != -1 || doclickhide.executeClose(hideElem,fn,eFn);
		},
		executeClose: function(selectElem, fn, eFn)
		{
			fn(selectElem);
			doc.unbind('mousedown',eFn);
		}
	},
	tmplates = {
		select : function( text )
		{
			var select_str = ['<div class="'+classes['overlay_select']+'">',
						'<span class="'+classes['selectree_text']+'">'+text+'</span>', 
						'<i class="'+classes['arrow']+' '+classes['sprite']+'"></i>', 
					'</div>'].join('');
			return $(select_str);
		},
		datalist_outline: function()
		{
			var cls = classes;
			var self = this;
			var parent_str = '<div class="'+cls['datalist']+' hidden '+this.settings['rootClass']+'"></div>';
			var recently_str = ['<div class="'+cls['recently']+'">',
									'<div class="'+cls['head']+'">',
										'<span class="'+cls['title']+'">最近使用</span><a href="" title="点击修改" class="cookie-count">'+cookie_count+'</a>',
										'<span class="'+cls['sprite']+' '+cls['arrow']+' '+cls['head_up']+'"></span>',
									'</div>',
								'</div>'].join('');
			var search_ico = '<span class="'+cls['sprite']+' '+cls['sear_ico']+'"></span>';
			var overall_str = ['<div class="'+cls['overall']+'">',
									'<div class="'+cls['head']+'"><span class="'+cls['title']+'">全部</span></div>',
								'</div>'].join('');
			var search_str = ['<div class="selectree-search" style="display:none;">',
									'<div class="'+cls['head']+'"><span class="'+cls['title']+'">快捷搜索</span><span class="search-close '+cls['sprite']+'"></span></div>',
									'<div class="search-trigger"><input type="text" autocomplete="off" value="输入拼音或名称" class="search-input" /><label class="search-submit selectree-sprite"></label></div>',
									'<div class="search-result"></div>',
								'</div>'].join('');	
			var selected_str = ['<div class="'+cls['selected']+'">',
									'<div class="'+cls['head']+'"><span class="'+cls['title']+'">已选</span></div>',
								'</div>'].join('');	
			var parent = $(parent_str), overall = $(overall_str), search = $(search_str);
			
			if(this.settings['recently'] == true) {
				recently = $(recently_str);
				parent.append(recently);
				var searchelem = $(search_ico);
				recently.find('.head').append(searchelem);
				if(this.settings['disable'] == 'search') searchelem.hide();
				recently.find('.cookie-count').click(function(){
					modify_cookiecount.call(self,$(this).parent().parent());
					return false;
				});
				recently.find('.arrow').click(function(){
					var arrow = $(this);
					var ul = $(this).parent().parent().find('.selectree-list');
					if(ul.height() == 0) 
					{
						ul.animate({
							'height': ul.attr('data-height') + 'px'
						},function(){
							arrow.removeClass('down').addClass('up');
							$(this).css({
								'padding' : '3px 0'
							})
							.prev()
							.css('borderBottomWidth',0);
						});
					} 
					else {
						ul
						.attr('data-height',ul.height())
						.animate({
							'height': 0
						},function(){
							arrow.addClass('down').removeClass('up');
							$(this).css('padding',0).prev().css('borderBottomWidth',0);
						});
					}
				});
			} else {
				var $sear_ico = $(search_ico).css({
					'right' : 10 + 'px'
				});
				overall.find('.head').append($sear_ico);
			}
			parent
				.append(overall)
				.append(search);

			if(this.settings['type'] == 'multiple') 
			{
				$(selected_str).appendTo(parent);
			}
			
			CookieUtil.set('recently_count',cookie_count);
			
			return parent;
		},
		datalist_tree : function(container, data)
		{
			var selected_list_data = this.selector.data('selected.list');
			var self = this;
			var cls = classes;
			var ul_str = '<ul class="'+cls['tree']+'"></ul>';
			var parent = $(ul_str).appendTo(container);
			if(parent.prev().parent().hasClass(classes['overall'])) parent.addClass(this.settings['rootClass']);
			$.each(data,function(i,item) {
				var catided = fliter_selected(selected_list_data,item);
				var li_str = '<li class="'+cls['item']+'"></li>',
					block_str = '<div class="'+cls['block']+'"></div>',
					raido_str = '<div class="'+cls['input']+' '+cls['sprite']+' '+cls['radio']+'"></div>',
					checkbox_str = '<div class="'+cls['input']+' '+cls['sprite']+' '+cls['checkbox']+'"></div>',
					arrow_str = '<div class="arrow fold selectree-sprite"></div>',
					name_str = '<span class="'+cls['selectree_text']+'">'+item['name']+'</span>';
				var li = $(li_str).appendTo(parent),
					block = $(block_str).appendTo(li);
				if(catided !== 0) li.addClass('checked');
				set_hoverclass(block,cls['hover']);

				block.click(function( e ) {
					var rootDatalist = self.datalistor.find('.selectree-overall>.selectree-list');
					var me = $(this), name = me.children('.text').text(), catid = me.attr('data-catid');
					self.check(me,name);
				});
				block.parent().toggle(function() {
					var me = $(this);
					var single = me.is('.radio'),multiple = me.is('.checkbox');
					if(single || multiple) {
						me.addClass('checked');
					} 
				},function(){
					var me = $(this);
					var single = me.is('.radio'),multiple = me.is('.checkbox');
					if(single || multiple) {
						me.removeClass('checked');
					}
				});
				block.attr('data-catid',item['catid']);

				// TODO: 判断是否有子栏目
				if(item['hasChildren'] == true) 
				{
					var arrow_elem = $(arrow_str)
					block
						.append(arrow_elem)
						.parent()
						.attr('data-hasChildren',true);
					// 
					if(self.settings.checkParents == true) {
						var parent_input = $('<div class="input selectree-sprite checkbox"></div>');
						arrow_elem.after(parent_input);
						parent_input.click(function(e) {
							var rootDatalist = self.datalistor.find('.selectree-overall>.selectree-list');
							var me = $(this).parent(), name = me.children('.text').text(), catid = me.attr('data-catid');
							$(this).attr('data-select',true);
							self.check(me,name);
							e.stopPropagation();
						});
					}
					block.click(function()
					{
						var me = $(this);

						// TODO: 超过一分钟重新请求数据
						if(!me.data('timeline')) {
							me.data('timeline',+new Date());
							self.load('cate',me);
						} 
						else {
							var seconds = (+new Date() - me.data('timeline'))/1000;
							if(Math.floor(seconds) > 3 ) {
								me.data('timeline',+new Date());
							}
							toggle_fold.call(self,me);
						}
					});
				} 
				else 
				{	
					var conf = self.settings;
					if(conf['type'] == 'single') {
						$(raido_str).appendTo(block);
					} else if(conf['type'] == 'multiple') {
						$(checkbox_str).appendTo(block);
					} else {
						block.addClass('list-type');
					}
				}
				$(name_str).appendTo(block);
				
			});
		}
	};

	var CookieUtil = {get: function (name){var cookieName = encodeURIComponent(name) + "=",cookieStart = document.cookie.indexOf(cookieName), cookieValue = null; if (cookieStart > -1){var cookieEnd = document.cookie.indexOf(";", cookieStart); if (cookieEnd == -1){cookieEnd = document.cookie.length; } cookieValue = decodeURIComponent(document.cookie.substring(cookieStart + cookieName.length, cookieEnd)); } return cookieValue; }, set: function (name, value, expires, path, domain, secure) {var cookieText = encodeURIComponent(name) + "=" + encodeURIComponent(value); var exp = new Date(); exp.setTime(exp.getTime() + 1*24*60*60*1000); cookieText += "; expires=" + exp.toGMTString(); if (path) {cookieText += "; path=" + path; } if (domain) {cookieText += "; domain=" + domain; } if (secure) {cookieText += "; secure"; } document.cookie = cookieText; }, unset: function (name, path, domain, secure){this.set(name, "", new Date(0), path, domain, secure); } }
	
	function set_hoverclass(elem, cls)
	{
		elem.hover(function(){
			$(this).toggleClass(cls);
		},function(){
			$(this).toggleClass(cls);
		});
	}
	// 重新定位,显示时触发
	function update_offset(relative,target)
	{
		var pos = relative.offset(),
			height = relative.outerHeight() - 1,
			left = pos.left,
			top = pos.top,
			target_width = target.outerWidth(),
			relative_width = relative.outerWidth(),
			pagewidth = doc.width(),
			target_height = target.outerHeight(),
			relative_height = relative.outerHeight(),
			pageheight = doc.height();
		var l = left + target_width >= pagewidth ? (left + relative_width - target_width ) : left;
		var t = top + height + target_height >= pageheight  ?  top - target_height  : top + height;
		t = Math.max(0, t);
		target.animate({
			'left' : l + 'px',
			'top' : t + 'px'
		},300);
	}
	function toggle_fold(elem)
	{
		var item = elem.parent();
		if(!item.hasClass(classes['unfold'])) {
			item.addClass(classes['unfold']);
			elem.children('.arrow').addClass('unfold');
			elem.next().show();
		} else {
			item.removeClass(classes['unfold']);
			elem.children('.arrow').removeClass('unfold');
			elem.next().hide();
		}
		limit_height.call(this);
		if(this.settings.checkParents == true) {
			if(item.hasClass('checked')) {
				item.find('.selectree-list .item-block').attr('data-locked',true);
			}
		}
	}
	// 限制容器的最大高度
	function limit_height() 
	{
		var $this = this;
		var limit_elem = this.datalistor.find('.selectree-overall>.selectree-list');
		
		if(!limit_elem[0]) {
			setTimeout(function(){
				limit_height.call($this);
			}, 500);
			return;
		}

		var items = limit_elem.find('>.item');
		var size = items.size();
		var height = 0;
		items.each(function(i,li){
			height += $(li).height();
		});
		limit_elem.height(height >= this.settings.height ? this.settings.height - 24 : 'auto');
		this.datalistor.find('.selectree-overall').height(this.settings.height);

		update_offset.call(this,this.selector,this.datalistor);
	}
	function json_parse(str) 
	{
        return (new Function( "return " + str ))();
	}

	function fliter_selected(array, item)
	{
		array = array || [];
		var v = 0;
		$.each(array, function(k,value) {
			var active_value = parseInt(item['catid'],10),
				curr_value = parseInt(value[0],10);
				if(active_value == curr_value) {
					v = curr_value;
				}
		});
		return v;
	}
	function init_selected_value( elem )
	{
		var self = this, catid;
		var len = elem.attr('data-catid').split(',').length;
		if(len == 1)
		{
			catid = parseInt(elem.attr('data-catid').length == 0 ? 0 : elem.attr('data-catid'),10);
			if(catid > 0)
			{
				this.load('name', elem, catid, function( data ) {
					update_selected_value.call(self, [ [ data[0]['catid'] , data[0]['name']] ]);
				});
			} 
		} 
		else if(len > 1)
		{
			this.load('name', elem, elem.attr('data-catid'), function( data ) {
				var arr = [];
				$.each(data,function(i, item) {
					arr[i] = [item['catid'],item['name']];
				});
				update_selected_value.call(self,arr);
			});
		}
	}
	function init_selecttree(target ,text)
	{
		var self = this,
			elem = tmplates.select(text);
			
			elem.attr({
				'data-catid' : this.pit_elem.attr('data-catid')
			});
			target.after(elem);
			init_selected_value.call(this,this.pit_elem);
			set_hoverclass(elem,classes['overlay_select_hover']);
			
			elem.click(function( e ) {
				if(!self.datalistor) 
				{
					self.datalistor = init_datalist.call(self,$(this));
					self.load('recently_parent');
					self.datalistor.css('left',self.selector.offset().left + 'px');
					doc.keydown(function( e ){
						if(e.keyCode == 27) {
							self.datalistor.addClass('hidden');
						}
					});
					limit_height.call(self);
				}
				// 超过5分钟重新刷新
				if(self.datalistor.data('timeline')) 
				{
					var seconds = (+new Date() - self.datalistor.data('timeline'))/1000;
					if(Math.floor(seconds) > 300 ) {
						self.datalistor.find('.'+classes['overall']+',.'+classes['recently']).find('.'+classes['tree']).remove();
						self.load('recently_parent');
						self.datalistor.data('timeline',+new Date());
					}
				}
				
				self.datalistor.toggleClass('hidden');
				
				if(!self.datalistor.hasClass('hidden')) 
				{
					update_offset.call(self,elem,self.datalistor); 
					update_recently.call(self);
					if(self.settings['type'] == 'multiple')
					{
						update_actived_plate.call(self);
					}
					doc.bind('mousedown',fire_hide);
				}
			});

			function fire_hide( e )
			{	
				doclickhide.bindEventClose(e,elem,self.datalistor,function( elem ){
					elem.addClass('hidden');
				},arguments.callee);
			}

		return elem;
	}

	function init_datalist(relative)
	{	
		var datalist_elem = tmplates.datalist_outline.call(this);
			datalist_elem.appendTo(document.body);
			init_search.call(this,datalist_elem);
			datalist_elem.css({
				'position': 'absolute',
				'top' : relative.offset().top + 'px'
			});
			if(isie6()) datalist_elem.addClass(classes['ie6']);
			if(this.settings.checkParents == true) datalist_elem.addClass(classes['hasparent']);

			return datalist_elem;
	}
	function init_search(container)
	{	var self = this;
		var sear_ico = container.find('.search-ico');
			set_hoverclass(sear_ico,'search-ico-hover');
		var sear_panel = container.find('.selectree-search');
		var close_ico = sear_panel.find('.search-close');
			set_hoverclass(close_ico,'close');
		var input = sear_panel.find('.search-input');
		var cls = classes;
		close_ico.click(function(){
			sear_panel.animate({
				height: 0 
			},function(){
				$(this).hide();
			});
			$('.lose').parent().hide();
			if(self.settings.type == 'single') {
				var catid = self.selector.data('selected.list')[0][0];
				self.datalistor.find('.selectree-list .item').removeClass('checked');
				self.datalistor.find('.selectree-list .item-block[data-catid='+catid+']').parent().addClass('checked');
			}
		});
		sear_ico.click(function(){
			var full_height = self.settings['recently'] == false ? container.find('.'+classes['overall']).outerHeight() : container.outerHeight();
			sear_panel.show().height(0);
			sear_panel.animate({
				height: full_height +'px'
			},function(){
				input.select();
			});
			sear_panel.attr('data-height',full_height);
		});

		var show_t = null;
		var cancelPropagation = function( e ){
			e.stopPropagation();
		}
		input.focus(function( e ){
			toggle_input_default($(this),cls['inputfocus'],e.type);
		}).blur(function( e ){
			toggle_input_default($(this),cls['inputfocus'],e.type)
		}).keyup(function( e ){
			toggle_input_default($(this),cls['inputfocus'],e.type);
		}).autocomplete({
			url : self.settings.url + '?app=system&controller=category&action=search&keyword=%s', //http://admin.silkroad.news.cn/?app=system&controller=category&action=search&keyword=s
			CLASSES: {
				autocomplete:'autocomplete-box autocomplete-result'
			},
			cache: false,
			textFiled: 'pathname',
			replaceJson: function( json ){
				return json['data'];
			},
			displayAfter: function(div)
			{
				div.css('height',sear_panel.outerHeight() - 67 + 'px');
				div.mousedown(cancelPropagation);
			},
			hideAfter: function(div)
			{
				div.unbind('mousedown',cancelPropagation);
			},
			activeAfter: function(a,dataItem,input){
				input.addClass('search-input-focus');
			},
			selectAfter: function(a,dataItem,input)
			{
				self.multiple($('<div data-catid="'+dataItem['catid']+'"></div>'),dataItem['name']);
				if(self.settings['type'] == 'multiple') update_actived_plate.call(self);
				input.select(function(){});
				if(self.settings.type == 'single') {
					input
						.val('');
					input
						.parent().parent()
						.hide();
					self.datalistor
						.addClass('hidden');
					self.datalistor
						.find('.item')
						.removeClass('checked');
					self.datalistor
						.find('[data-catid='+dataItem['catid']+']')
						.parent()
						.addClass('checked');
				}
				if(self.settings.type == 'single' || self.settings.type == "multiple") {
					self.subscribe_cookies([{id:dataItem['catid'],name:dataItem['name']}]);
				}
			},
			loseAfter: function(div)
			{
				clearTimeout(show_t);
				div.append($('<a class="lose">不存在该栏目</a>')).show();
				show_t = setTimeout(function(){
					div.empty().hide();
				},5000);
			},
			itemFormat:function(item, k){
				return k ? item['pathname'].replace(new RegExp(k, 'ig'), function(k){
					return '<strong>' + k + '</strong>';
		    	}) : item['pathname'];
			}
		})
		.select(function(){
			
		})
		.click(function( e ){
			toggle_input_default($(this),cls['inputfocus'],e.type);
		});
		
		var btn = sear_panel.find('.search-submit');
		set_hoverclass(btn,'search-submit-hover');
	}
	
	
	// 更新input值
	function update_actived_plate()
	{
		var data = this.selector.data('selected.list') || [];
		var array = [];
		$.each(data,function(i,item){
			array.push({
				'catid' : item[0],
				'name' : item[1],
				'hasChildren': false
			});
		});
		var container = this.datalistor.find('.selectree-selected'),
			parent = container.find('>.selectree-list');

		parent.remove();
		if(array.length == 0) return;
		tmplates.datalist_tree.call(this,this.datalistor.find('.selectree-selected'),array);
		container.find('.item')
			.addClass('checked')
			.find('.item-block')
			.data('checked',true);
	}
	function toggle_input_default(input, cls, ev_type)
	{
		var value = input.val(),
			def_val = input.get(0).defaultValue;
		if(value != def_val && value.length != 0)
		{
			if(cls) input.addClass(cls);
		}
		if(ev_type == 'focus' || ev_type == 'click') {
			if(value == def_val) {
				input.val('');
			}
			input.select();
		} else if(ev_type == 'blur') {
			if(value.length == 0) {
				input.val(def_val);
				input.removeClass(cls);
			}
		}
	}
	var cookie_count = CookieUtil.get('recently_count') || 5;

	// TODO: 最近使用的栏目存入cookie，如果有相同项则忽略，并且不超过最大条目,格式：Cookie: recently_selectree="1@新闻&&2@国内&&3@财经"
	function inject_cookie(data)
	{
		var obj_str = data['id']+'@'+data['name'];
		var repeat = false;
		if(!CookieUtil.get('recently_selectree')) 
		{
			CookieUtil.set('recently_selectree',obj_str);
		}
		else 
		{
			var cookie_value = CookieUtil.get('recently_selectree').split('&&');
			for(var i = 0, length = cookie_value.length ; i < length; i++)
			{
				var id = Number(cookie_value[i].split('@')[0]);
				if(data['id'] == id) {
					cookie_value.unshift(cookie_value.splice(i,1)[0]);
					repeat = true;
					break;
				}
			}
			if(!repeat) cookie_value.unshift(obj_str);
			var len = cookie_value.length;
			if(len > cookie_count)
			{
				cookie_value.pop();
			}
			var new_cookievalue = cookie_value.join('&&');
			CookieUtil.set('recently_selectree',new_cookievalue);
		}
	}
	// 更新最近使用显示区域
	function update_recently()
	{
		if(!CookieUtil.get('recently_selectree')) return;
		
		var cookie_value = CookieUtil.get('recently_selectree').split('&&');
		var count = parseInt(CookieUtil.get('recently_count'));
		if(cookie_value.length > count) {
			var d = cookie_value.length - count;
			for(var i = 0; i < d; i++)
			{
				cookie_value.pop();
			}
		}
		
		CookieUtil.set('recently_selectree',cookie_value.join('&&'));
		var data = [];
		$.each(cookie_value,function(i,item) {
			var arr = item.split('@');
			data[i] = {catid:arr[0],name:arr[1]}
		});
		var recently = this.datalistor.find('.'+classes['recently']);
		recently.find('.selectree-list').remove();
		tmplates.datalist_tree.call(this,recently,data);
		recently.find('.cookie-count').text(count);
	}
	function save_recently_conf(elem, container,t)
	{
		var size = parseInt(elem.val());
		if(size > 10 || size <= 0) {
			alert('最多10条，最少1条');
			elem.select().val(5);
			return;
		}
		if(isNaN(size))
		{
			alert('只能输入数字!');
			elem.select().val(5);
			return;
		}
		cookie_count = size;
		CookieUtil.set('recently_count',cookie_count);
		container.find('.cookie-count').text(size);
		elem.hide();
		update_recently.call(this);
	}
	function modify_cookiecount(parent)
	{
		var self = this;
		var value = parent.find('.cookie-count').text();
		var input_html = '<input title="条数（1-10）" class="cookie-count-input" type="text" maxlength="2" value="'+value+'" />';
		var input = parent.find('.cookie-count-input');
		if(input.size() == 0) 
		{
			$(input_html)
				.appendTo(parent)
				.focus()
				.select()
				.keypress(function( e ){
					var me = $(this);
					if(e.keyCode == 13) {
						if(!$.browser.msie) save_recently_conf.call(self,me,parent);
						e.stopPropagation();
					}
				})
				.blur(function(e){
					save_recently_conf.call(self,$(this),parent);
				})
				.css({
					'left' : 55 + 'px',
					'top' : 2 + 'px'
				});
		} else {
			input
				.val(value)
				.show()
				.focus()
				.select();
		}
	}
	// 更新选择框的值
	function update_selected_value( array )
	{
		var title_arr = [], id_arr = [], span = this.selector.children('span'), is_em = false;
		
		span.empty();

		if(array.length == 0)
		{
			span.text(this.pit_elem.attr('data-value'));
		} 
		if(this.settings.type == 'single') 
		{
			if(array.length > 1) {
				array = [array[array.length-1]];
			}
			this.selector.attr('title',array[0][1]);
			this.selector.children('.text').text(array[0][1]);
			this.pit_elem.val(array[0][0]);
			this.selector.data('selected.list',array);
		} else {
			for(var i = 0; i < array.length; i++)
			{
				title_arr.push(array[i][1]);
				id_arr.push(array[i][0]);
				if(i < 1) {
					$('<em>'+array[array.length - 1][1]+'</em>').appendTo(span);
				} else {
					if(is_em === false) {
						is_em =  $('<em>...</em>').appendTo(span);
					} 
				}
			}
			this.selector.attr('title',title_arr.join(','));
			this.pit_elem.val(id_arr.join(','));
			this.selector.data('selected.list',array);
		}
	}
	function isie6() {
	    if ($.browser.msie) {
	        if ($.browser.version == "6.0") return true;
	    }
	    return false;
	}
	function Selectree(element, defaults, options)
	{
		this.pit_elem = element;
		this.container = element;
		this.settings = $.extend({}, defaults||{}, options||{});
        this.events = {};
		this.initialization();
	}	
	Selectree.prototype = {
        bind: function(event, func) {
            (event in this.events) || (this.events[event] = []);
            this.events[event].push(func);
            return this;
        },
        trigger: function(event, args) {
            if (event in this.events) {
                for (var index = 0, func;
                     (func = this.events[event][index++]) && (func.apply(this, args || []) !== false); ) {
                }
            }
            return this;
        },
		initialization : function()
		{
			var type = this.settings.type;
            $.isFunction(this.settings.checkedAfter) && this.bind('checked', this.settings.checkedAfter);
			if(type == 'single' || type == 'multiple') 
			{
				this.pit_elem.hide();
				this.selector = init_selecttree.call(this, this.pit_elem, this.pit_elem.attr('data-value'));
			} 
			if(type == 'tree') 
			{
				this.load('recently_parent');
			}
			if(type == 'select')
            {
                this.dropmenu();
            }


		},
		load: function(action, elem, catid, fn)
		{	
			var self = this;
			if(action == 'recently_parent') 
			{
				$.ajax({
				   type: "GET",
				   url: this.settings.url,
				   data: self.settings.dataUrl||"app=system&controller=category&action=cate&catid=tree",
				   success: function(data) {
				  		if(self.settings.type == 'single' || self.settings.type == 'multiple')
				  		{
					  		var data = json_parse(data);
					  		if(!self.datalistor.data('timeline')) {
					  			self.datalistor.data('timeline',+new Date());
					  		} 
					  		tmplates.datalist_tree.call(self,self.datalistor.find('.'+classes['overall']),data);
				  		}
				  		if(self.settings.type == 'tree' || self.settings.type == 'dropmenu') {
				  			var data = json_parse(data);
					  		if(!self.container.data('timeline')) {
					  			self.container.data('timeline',+new Date());
					  		} 
					  		tmplates.datalist_tree.call(self,self.container,data);
					  		if(self.settings.type == 'dropmenu') {
					  			self.dropmenu();
					  		}
				  		}
				  		// update_offset.call(self,self.selector,self.datalistor);
				   }
				});
			} 
			else if(action == 'cate')
			{
				$.ajax({
				   type: "GET",
				   url: this.settings.cateUrl || '?app=system&controller=category&action=cate',
				   data: "catid="+elem.attr('data-catid'),
				   success: function(data) {
				  		var data = json_parse(data);
				  		if(data.length > 0)
				  		{
				  			var container = elem.parent();
				  			elem.next().remove(function(){});
				  			tmplates.datalist_tree.call(self,container, data);
				  			toggle_fold.call(self,elem);
				  		}
				   }
				});
			}
			else if(action == 'name') {
				$.ajax({
					type: "GET",
				   	url: this.settings.url,
				   	data: "app=system&controller=category&action=name&catid="+catid,
				   	success: function(data) {
				   		var data = json_parse(data);
				   		if(fn) fn(data);
				   	}
				})
			}
		},
		check: function(handler,name)
		{
			var input = handler.children('.input');
			if(input.hasClass('radio')) 
			{
				this.single(handler,name);
			}
			else if(input.hasClass('checkbox')) {
				this.multiple(handler,name);
			}
		},
		single: function(handler,name)
		{
			var self = this, id = handler.attr('data-catid'), datalistor = this.datalistor;
			datalistor
				.find('.item')
				.removeClass('checked');
			handler
				.parent()
				.addClass('checked');
			update_selected_value.call(this,[[id,name]]);
			this.subscribe_cookies([{id:id,name:name}]);
			setTimeout(function(){
				datalistor.addClass('hidden');
			},100);

            this.trigger('checked', [{catid:id, name: name, node: handler}]);
		},
		multiple: function(handler,name)
		{
			if(this.settings.checkParents == true && handler.parent().attr('data-haschildren') == 'true' && handler.children('.input').attr('data-select')  != 'true') {
				return ;
			} else {
				handler.children('.input').removeAttr('data-select');
			}
			if(this.settings.checkParents == true) {
				if(handler.attr('data-locked') == 'true') return;
			}
			var id             = handler.attr('data-catid'), 
				array          = this.selector.data('selected.list') || [],
				len            = array.length,
				span           = this.selector.children('span'),
				is_em          = false,
				is_add         = true;

			if(!handler.data('checked'))
			{ 
				if(this.settings.checkParents == true) {
					handler.next().find('.item-block').attr('data-locked',true);
				}
				handler.data('checked',true);
				if(len == 0) 
				{
					array.push([id,name]);
				} 
				else 
				{
					for( var i = 0; i < len; i++ )
					{
						if( array[i][0] == id ) {
							is_add = false;
							break;
						}
					}
					if(is_add == true) {
						array.push([id,name]);
					}
				}
				this.datalistor.find('[data-catid='+id+']').parent().addClass('checked');
				this.subscribe_cookies([{id:id,name:name}]);
                this.trigger('checked', [{catid:id, name: name, node: handler}]);
			}
			else 
			{
				if(this.settings.checkParents == true) {
					handler.next().find('.item-block').removeAttr('data-locked');
					handler.removeClass('checked');
					if(handler.parent().attr('data-haschildren') == 'true') {
						this.datalistor.find('.selectree-recently').find('[data-catid='+handler.attr('data-catid')+']').removeClass('checked')
						.data('checked',false);
					}
				}
				handler.data('checked',false);
				for( var i = 0; i < len; i++ )
				{
					if(array[i][0] == id) {
						array.splice(i,1);
						break;
					}
				}
				this.datalistor.find('[data-catid='+id+']').parent().removeClass('checked');
				this.subscribe_cookies([{id:id,name:name}]);
			}
			if(this.settings.checkParents == true) {
				var all = true;
				var allLi = handler.parent().parent().find('.item');
				for( var i = 0,lilen = allLi.size(); i < lilen; i++ ) {
					if(!allLi.eq(i).hasClass('checked')) {
						all = false;
						break;
					}
				}
				if(handler.parent().parent().parent().hasClass('selectree-recently')) all = false;
				if(all == true) {
					var cookies = CookieUtil.get('recently_selectree').split('&&');
					var ids = [];
					allLi.each(function(i,li){
						ids.push($(li).find('.item-block').attr('data-catid'));
					});
					for( var i = 0; i < ids.length; i++) {
						for( var y = 0; y < array.length; y++) {
							if(parseInt(array[y][0],10) == parseInt(ids[i],10)) {
								array.splice(y,1);
							}
						}
						for( var s = 0; s < cookies.length; s++) {
							if(parseInt(cookies[s].split('@')[0],10) == parseInt(ids[i],10)) {
								cookies.splice(s,1);
							}
						}
					}
					CookieUtil.set('recently_selectree',cookies.join('&&'));
					var phandler = handler.parent().parent().parent().find('>.item-block');
					var pid =  phandler.attr('data-catid'), pname = phandler.find('.text').text();
					array.push([pid,pname]);
					this.subscribe_cookies([{id:pid,name:pname}]);
					phandler.parent().addClass('checked').find('.selectree-list .item-block').attr('data-locked',true);
					handler.parent().parent().prev().data('checked',true);
					handler.parent().parent().find('.item').removeClass('checked').find('.item-block').data('checked',false);
					var recently_elem = this.datalistor.find('.selectree-recently .item');
					recently_elem.removeClass('checked').find('[data-catid='+pid+']').addClass('checked').data('checked',true);
				}
			}
			update_selected_value.call(this,array); // 刷新选择框
			update_actived_plate.call(this); // 刷新input
		},
		subscribe_cookies: function(datas)
		{
			var ids = [];
			$.each(datas,function(i,data){
				ids.push(data['id']);
			});
			this.pit_elem.val(ids.join(','));
			if(this.settings.type == 'single' || this.settings.type == 'multiple') inject_cookie(datas[0]);
		},
		dropmenu: function()
		{
			var $origin = this.origin_select = this.container;
			
			var origin = $origin[0];
			var size = origin.options.length;
			var index = origin.selectedIndex;

			var def_val = origin.options[index].text;
			var offset = $origin.offset();
			var over_select = $('<div class="selectree"><span class="text">'+def_val+'</span><i class="arrow selectree-sprite"></i></div>')
			.css({
				'position': 'absolute',
				'left' : offset.left + 'px',
				'top': offset.top + 'px'
			}).appendTo(doc[0].body);
			set_hoverclass(over_select,classes['overlay_select_hover']);
			var dropmenu_list = $('<ul class="dropmenu-list hidden '+classes['blurmark']+'"></ul>')
			.css({
				'position': 'absolute',
				'left' : offset.left + 'px',
				'top' : offset.top + over_select.height() + 'px',
				'width' : over_select.outerWidth() + 'px'
			}).appendTo(over_select.parent());
			
			for( var i = 0; i < size; i++)
			{
				var value = origin.options[i].value,
					text = origin.options[i].text;
				var li = $('<li class="'+classes['blurmark']+'">'+text+'</li>')
				.data('val',value)
				.attr('data-i',i)
				.appendTo(dropmenu_list)
				.click(function(){
					over_select.children('.text').text($(this).text());
					origin.options[$(this).attr('data-i')].selected = true;
					dropmenu_list.find('li').removeClass('checked');
					$(this).addClass('checked');
					dropmenu_list.addClass('hidden');
				})
				.addClass(origin.options[i].getAttribute('data-ico'));
				set_hoverclass(li,'hover');
			}
			over_select.click(function(){
				dropmenu_list.toggleClass('hidden');
				if(!dropmenu_list.hasClass('hidden')) {
					doc.bind('mousedown',fire_hide);
				}
			});
			$origin.hide();

			function fire_hide( e )
			{	
				doclickhide.bindEventClose(e,over_select,dropmenu_list,function( elem ){
					elem.addClass('hidden');
				},arguments.callee);
			}
		}
	}

	$.Selectree = Selectree;

	$.fn.Selectree = function( options )
	{
		return this.each(function() {
			var selectree = new Selectree($(this), defaults||{}, options||{});
			$(this).data('selectree', selectree);
		});
	}
})(jQuery);
