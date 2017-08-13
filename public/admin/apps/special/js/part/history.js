var History = {
	_data:{},
	_pointer:0,
	_length:0,
	_savePoint:0,
	_unback:0,
	log:function(type, params){
		this._data[this._pointer] = {
			type:type,
			params:params
		};
		this._length = ++this._pointer;
		DIY.trigger('hasBack');
		DIY.trigger('noForward');
		this._unback || DIY.trigger('changed');
	},
	unback:function(){
		if (! this._unback) {
			this._unback = 1;
			DIY.trigger('changed');
		}
	},
	savePoint:function(){
		this._savePoint = this._pointer;
		this._unback = 0;
		DIY.trigger('unchanged');
	},
	back:function(){
		if (this._pointer < 1) return;
		DIY.trigger('hasForward');
		var h = this._data[--this._pointer];
		this._pointer < 1 && DIY.trigger('noBack');
		var undo = this.undo[h.type];
		undo && undo.apply(this, h.params);
		this._unback || DIY.trigger(this._savePoint == this._pointer ? 'unchanged' : 'changed');
	},
	forward:function(){
		if (this._pointer >= this._length) return;
		DIY.trigger('hasBack');
		var h = this._data[this._pointer++];
		this._pointer >= this._length && DIY.trigger('noForward');
		var redo = this.redo[h.type];
		redo && redo.apply(this, h.params);
		this._unback || DIY.trigger(this._savePoint == this._pointer ? 'unchanged' : 'changed');
	},
	undo:{
		remove:function(elem, area, place){
			elem.show();
			if (place && place.parentNode == area) {
				elem.insertBefore(place);
			} else {
				elem.appendTo(area);
			}
			scrollIntoView(elem);
			blink(elem);
		},
		move:function(movement, origPos, newPos) {
			if (origPos.place && origPos.place.parentNode == origPos.area) {
				movement.insertBefore(origPos.place);
			} else {
				movement.appendTo(origPos.area);
			}
			movement.hasClass('.diy-wrapper') && setColor(movement);
			return scrollIntoView(movement.show());
		},
		add:function(elem, area, place){
			elem.fadeOut('fast',function(){
				elem.appendTo($fragments);
			});
		},
		resize:function(h, p, n, origScale, newScale) {
			p.width(origScale.pw);
			n.width(origScale.nw);
			h.css('left', origScale.hl);
		},
        visible:function(place, state) {
            state ? place.removeClass('diy-hidden') : place.addClass('diy-hidden');
        },
        pageStyle:function(body, n, o) {
            Theme.setRule('body', o.body);
            Theme.setRule('a', o.a);
            body.attr({
                'body-style': o.body,
                'a-style': o.a
            });
        },
        frameStyle:function(frame, n, o) {
            var id = frame[0].id, title = frame.find('.diy-title'),
                ntt = Theme.getThemeName('title', n['title-theme']),
                ott = Theme.getThemeName('title', o['title-theme']),
                nft = Theme.getThemeName('frame', n['frame-theme']),
                oft = Theme.getThemeName('frame', o['frame-theme']);
            switchClass(title, ntt, ott, 'title-');
            switchClass(frame, nft, oft, 'frame-');
            Theme.setRule('#'+id, o['frame-style']);
            Theme.setRule('#'+id+'-t', o['title-style']);
            Theme.setRule('#'+id+'-t *', o['title-w-style']);
            Theme.setRule('#'+id+'-t a', o['title-a-style']);
            frame.attr(o);
        },
        widgetStyle:function(widget, n, o){
            var id = widget[0].id,
                engine = widget.attr('engine'),
                title = widget.find('.diy-title'),
                content = widget.find('.diy-content'),
                nwt = Theme.getThemeName('widget', n['widget-theme']),
                ntt = Theme.getThemeName('title', n['title-theme']),
                nct = Theme.getThemeName('content/'+engine, n['content-theme']),
                owt = Theme.getThemeName('widget', o['widget-theme']),
                ott = Theme.getThemeName('title', o['title-theme']),
                oct = Theme.getThemeName('content/'+engine, o['content-theme']);

            // switch class
            switchClass(widget, nwt, owt, 'widget-');
            switchClass(title, ntt, ott, 'title-');
            switchClass(content, nct, oct, 'content-'+engine+'-');

            // set cssText
            Theme.setRule('#'+id, o['widget-style']);
            Theme.setRule('#'+id+'-i', o['inner-style']);
            Theme.setRule('#'+id+'-t', o['title-style']);
            Theme.setRule('#'+id+'-c', o['content-style']);
            Theme.setRule('#'+id+'-i *', o['inner-w-style']);
            Theme.setRule('#'+id+'-t *', o['title-w-style']);
            Theme.setRule('#'+id+'-c *', o['content-w-style']);
            Theme.setRule('#'+id+'-i a', o['inner-a-style']);
            Theme.setRule('#'+id+'-t a', o['title-a-style']);
            Theme.setRule('#'+id+'-c a', o['content-a-style']);
            widget.attr(o);
        }
	},
	redo:{
		remove:function(elem, area, place){
			elem.fadeOut('fast',function(){
				elem.appendTo($fragments);
			});
		},
		move:function(movement, origPos, newPos){
			if (newPos.place && newPos.place.parentNode == newPos.area) {
				movement.insertBefore(newPos.place);
			} else {
				movement.appendTo(newPos.area);
			}
			movement.hasClass('.diy-wrapper') && setColor(movement);
			return scrollIntoView(movement);
		},
		add:function(elem, area, place){
			elem.show();
			if (place && place.parentNode == area) {
				elem.insertBefore(place);
			} else {
				elem.appendTo(area);
			}
			scrollIntoView(elem);
			blink(elem);
		},
		resize:function(h, p, n, origScale, newScale) {
			p.width(newScale.pw);
			n.width(newScale.nw);
			h.css('left', newScale.hl);
		},
        visible:function(place, state) {
            state ? place.addClass('diy-hidden') : place.removeClass('diy-hidden');
        },
        pageStyle:function(body, n, o) {
            Theme.setRule('body', n.body);
            Theme.setRule('a', n.a);
            body.attr({
                'body-style': n.body,
                'a-style': n.a
            });
        },
        frameStyle:function(frame, n, o) {
            var id = frame[0].id, title = frame.find('.diy-title'),
                ntt = Theme.getThemeName('title', n['title-theme']),
                ott = Theme.getThemeName('title', o['title-theme']),
                nft = Theme.getThemeName('frame', n['frame-theme']),
                oft = Theme.getThemeName('frame', o['frame-theme']);
            switchClass(title, ott, ntt, 'title-');
            switchClass(frame, oft, nft, 'frame-');
            Theme.setRule('#'+id, n['frame-style']);
            Theme.setRule('#'+id+'-t', n['title-style']);
            Theme.setRule('#'+id+'-t *', n['title-w-style']);
            Theme.setRule('#'+id+'-t a', n['title-a-style']);
            frame.attr(n);
        },
        widgetStyle:function(widget, n, o) {
            var id = widget[0].id,
                engine = widget.attr('engine'),
                title = widget.find('.diy-title'),
                content = widget.find('.diy-content'),
                nwt = Theme.getThemeName('widget', n['widget-theme']),
                ntt = Theme.getThemeName('title', n['title-theme']),
                nct = Theme.getThemeName('content/'+engine, n['content-theme']),
                owt = Theme.getThemeName('widget', o['widget-theme']),
                ott = Theme.getThemeName('title', o['title-theme']),
                oct = Theme.getThemeName('content/'+engine, o['content-theme']);

            // switch class
            switchClass(widget, owt, nwt, 'widget-');
            switchClass(title, ott, ntt, 'title-');
            switchClass(content, oct, nct, 'content-'+engine+'-');

            // set cssText
            Theme.setRule('#'+id, n['widget-style']);
            Theme.setRule('#'+id+'-i', n['inner-style']);
            Theme.setRule('#'+id+'-t', n['title-style']);
            Theme.setRule('#'+id+'-c', n['content-style']);
            Theme.setRule('#'+id+'-i *', n['inner-w-style']);
            Theme.setRule('#'+id+'-t *', n['title-w-style']);
            Theme.setRule('#'+id+'-c *', n['content-w-style']);
            Theme.setRule('#'+id+'-i a', n['inner-a-style']);
            Theme.setRule('#'+id+'-t a', n['title-a-style']);
            Theme.setRule('#'+id+'-c a', n['content-a-style']);
            widget.attr(n);
        }
	}
};