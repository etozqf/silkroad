var LANG = {
	BUTTON_OK : '确定',
	BUTTON_CANCEL:'取消'
};
function localForm(opt, tpl, ready, ok, cancel) {
	var dialog = $(document.createElement('DIV')),
		form = null,
		submit = function(flag) {
			ok && ok(form, flag);
			(flag == 'ok' || flag == 'nextstep') && dialog.dialog('destroy').remove();
		}, buttons = {};
	
	LANG.BUTTON_OK && (buttons[LANG.BUTTON_OK] = function(){
		form && form.length && submit('ok');
	});
	LANG.BUTTON_NEXTSTEP && (buttons[LANG.BUTTON_NEXTSTEP] = function(){
		form && form.length && submit('nextstep');
	});
	LANG.BUTTON_PREVIEW && (buttons[LANG.BUTTON_PREVIEW] = function(){
		form && form.length && submit('preview');
	});
	LANG.BUTTON_SAVEAS && (buttons[LANG.BUTTON_SAVEAS] = function(){
		form && form.length && submit('saveas');
	});
	LANG.BUTTON_CANCEL && (buttons[LANG.BUTTON_CANCEL] = function() {
		dialog.dialog('close');
	});
	LANG.BUTTON_OK = '确定';
	LANG.BUTTON_CANCEL = '取消';
	LANG.BUTTON_NEXTSTEP = null;
	LANG.BUTTON_PREVIEW = null;
	LANG.BUTTON_SAVEAS = null;
	
	typeof opt == 'object' || (opt = {title:opt ? opt.toString() : ''});
	opt = $.extend({
		width :450,
		height:'auto',
		minHeight:80,
		maxHeight:700,
		resizable:false,
		modal : true,
		zIndex:100
	}, opt, {
		buttons : buttons,
		close:function(){
			dialog.dialog('destroy').remove();
			cancel && cancel();
		}
	});
	dialog.html(TEMPLATE[tpl]||'').dialog(opt).css('position', 'relative');
	form = dialog.find('form:first');
	form && form.length && form.submit(function(e){
		e.preventDefault();
		e.stopPropagation();
		submit();
	});
	ready && ready(form, dialog);
	return dialog;
}
function serializeArray(form) {
	var rselectTextarea = /select|textarea/i,
		rinput = /text|hidden|password|search/i;
	return form.find('input,select,textarea')
	.filter(function(){
		return this.name && !this.disabled &&
			(this.checked || rselectTextarea.test(this.nodeName) ||
				rinput.test(this.type));
	})
	.map(function(i, elem){
		var val = $(this).val();

		return val == null ?
			null :
			$.isArray(val) ?
				$.map( val, function(val, i){
					return {name: elem.name, value: val};
				}) :
				{name: elem.name, value: val};
	}).get();
}
function ajaxForm(opt, url, jsonok, formReady, beforeSubmit, afterSubmit, cancel, afterSerialize, thisVar) {
	url = envUrl(url);
    thisVar = thisVar || window;
	var dialog = $(document.createElement('DIV')),
	wrap, masker,
	form = null, buttons = {}, buttonArea,
	submit = function(flag) {
		buttonArea.children('button').attr('disabled', 'disabled');
		masker.css({height:wrap.height(), width:wrap.width()}).show();
		if (beforeSubmit && beforeSubmit.call(thisVar, form, dialog) === false) {
			complete();
			return;
		}
		
		var data = serializeArray(form);
		afterSerialize && afterSerialize.call(thisVar, data);
		$.ajax({
			url:flag == 'preview' ? (url + (url.indexOf('?') != -1 ? '&' : '?') + 'preview=1') : url,
			type:'POST',
			dataType:'json',
			data:$.param(data),
			success:function(json){
				if (json.state) {
					jsonok.call(thisVar, json, flag);
					flag != 'preview' && dialog.dialog('destroy').remove();
				} else {
					showwarn(json.error);
				}
			},
			error:function(){
				showwarn('请求异常');
			},
			complete:complete
		});
		afterSubmit && afterSubmit.call(thisVar, form,dialog);
	},
	viewReady = function(){
		wrap = dialog.parent();
		masker = $('<div class="masker"></div>').insertBefore(dialog);
		form.submit(function(e){
			e.preventDefault();
			e.stopPropagation();
			buttonArea.children('button').eq(0).click();
		});
		formReady && formReady.call(thisVar, form, dialog);
	},
	complete = function(){
		masker.hide();
		buttonArea.children('button').attr('disabled', false).removeAttr('disabled');
	},
	showwarn = function(msg){
	    dialog.message('fail', msg);
	};
	buttons[LANG.BUTTON_OK] = function(){
		form && form.length && submit('ok');
	};
	LANG.BUTTON_NEXTSTEP && (buttons[LANG.BUTTON_NEXTSTEP] = function(){
		form && form.length && submit('nextstep');
	});
	LANG.BUTTON_PREVIEW && (buttons[LANG.BUTTON_PREVIEW] = function(){
		form && form.length && submit('preview');
	});
	buttons[LANG.BUTTON_CANCEL] = function() {
		dialog.dialog('close');
	};
	LANG.BUTTON_OK = '确定';
	LANG.BUTTON_CANCEL = '取消';
	LANG.BUTTON_NEXTSTEP = null;
	LANG.BUTTON_PREVIEW = null;
	typeof opt == 'object' || (opt = {title:opt ? opt.toString() : ''});
	opt = $.extend({
		width :450,
		height:'auto',
		minHeight:80,
		maxHeight:700,
		resizable:false,
		modal : true
	}, opt, {
		autoOpen: false,
		buttons : buttons,
		close:function(){
			dialog.dialog('destroy').remove();
			cancel && cancel.call(thisVar);
		}
	});
	dialog.dialog(opt).load(url, function(){
		form = dialog.find('form:first');
		form && form.length && viewReady();
		dialog.dialog('open');
	}).css('position', 'relative');
	buttonArea = dialog.nextAll('div.btn_area');
	return dialog;
}
$.extend(DIY,{
	form:ajaxForm
});