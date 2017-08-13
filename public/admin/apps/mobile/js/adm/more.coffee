$ = jQuery
container = null
list = (url, horizon, vertical)->
	panel = $ '<div class="ad-more-item"></div>'

	label0 = $ '<label><span class="name">广告链接：</span></label>'
	input0 = $('<input />').attr
		type: 'text'
		name: 'url[]'
		value: url or ''
	label0.append input0

	label1 = $ '<label><span class="name">横屏广告图片：</span></label>'
	input1 = $('<input />').attr
		type: 'text'
		name: 'horizon[]'
		size: 20
		readonly: on
		value: horizon or ''
	label1.append input1
	input1.imageInput on
	input1.bind 'change', ()->
		$(this).data 'changed', on

	label2 = $ '<label><span class="name">竖屏广告图片：</span></label>'
	input2 = $('<input />').attr
		type: 'text'
		name: 'vertical[]'
		size: 20
		readonly: on
		value: vertical or ''
	label2.append input2
	input2.imageInput on
	input2.bind 'change', ()->
		$(this).data 'changed', on

	del = $('<img />').addClass('delete hand').attr
		src: 'images/delete.gif'
	del.one 'click', 
		panel: panel
	,(event)->
		event.data.panel.remove()

	container.append panel.append(label0).append(label1).append(label2).append(del)

error = (panel, msg)->
	panel.addClass 'error'
	panel.append '<div class="error">'+msg+'</div>'
	panel.one 'click', ()->
		panel.unbind().removeClass 'error'
		panel.find('.error').remove()
	ct.endLoading()

vaild = (callback)->
	changedElement = new Array()
	ret = on
	panel = null
	$.each container.find('.ad-more-item'), (i, k)->
		panel = $ k
		u = panel.find 'input[name="url[]"]'
		h = panel.find 'input[name="horizon[]"]'
		v = panel.find 'input[name="vertical[]"]'
		unless h.val() or v.val() or u.val()
			panel.remove()
			return on
		unless h.val() and v.val() and u.val()
			ret = off
			return off

		changedElement.push h if h.data 'changed'
		changedElement.push v if v.data 'changed'

	if ret
		sizeCheck changedElement, callback
	else
		error panel, '缺少参数'
	

sizeCheck = (changedElement, callback)->
	return callback() if changedElement.length is 0
	elm = changedElement.pop()
	img = $ '<img />'
	img.bind 'load',
		name: elm.attr 'name'
		panel: elm.parents '.ad-more-item'
		changedElement: changedElement
		callback: callback
	, (event)->
		name = event.data.name
		if name is 'horizon[]'
			unless this.width is horizonWidth and this.height is horizonHeight
				error event.data.panel, "横屏广告必须是#{horizonWidth}x#{horizonHeight}的图片"
				return off
		if name is 'vertical[]'
			unless this.width is verticalWidth and this.height is verticalHeight
				error event.data.panel, "竖屏广告必须是#{verticalWidth}x#{verticalHeight}的图片"
				return off
		sizeCheck event.data.changedElement, event.data.callback
	img.bind 'error',
		name: elm.attr 'name'
		panel: elm.parents '.ad-more-item'
		changedElement: changedElement
		callback: callback
	, (event)->
		error event.data.panel, '图片加载失败'
		return off
	src = elm.val()
	src = UPLOAD_URL + src unless src.substr(0,4) is 'http'
	img.attr 'src', src

success = ()->
	form = $('form')
	data = form.serializeObject()
	$.post form.attr('src'), data, (res)->
		if res.state
			ct.ok '保存成功'
		else
			ct.error res.error or '保存失败'
	, 'json'

$ ()->
	container = $ '#ad-more'
	if data and data.url and data.url.length > 0
		for i in [0...data.url.length]
			list data.url[i], data.horizon[i], data.vertical[i]
	else
		list()
	$('#add').bind 'click', ()->
		list() if $('.ad-more-item').length < 5
	$('#submit').bind 'click', ()->
		ct.startLoading 0, '正在校验图片尺寸'
		vaild ()->
			ct.endLoading()
			success()
