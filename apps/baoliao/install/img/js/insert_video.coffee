warnningPanel = null
forceSubmit = false

warnning = (text)->
	warnningPanel.innerHTML = text
	warnningPanel.style.display = 'block'

window.postForm = (form)->
	return true if forceSubmit	# when get video info and auto submit
	url = form.video.value
	return false if url.length is 0
	unless /^(\w)+:\/\/.*\.swf(\?|&|$)/i.test url
		# request cloud spider
		$.post "?app=baoliao&controller=index&action=spider&token=#{token}",
			url: url
		, (res)->
			unless res.state
				warnning if typeof res.error is 'string' then res.error else '提示：cmstop暂不支持该网站的播放页地址'
				return false
			form.video.value = res.url
			form.thumb.value = res.thumb
			forceSubmit = true
			$('#form').submit()
		, 'json'
		return false
	true

window.onload = ()->
	warnningPanel = document.getElementById 'warnning'