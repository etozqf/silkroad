var comment = {
	page: function(topicid, page, pagesize)
	{
		$.getJSON(APP_URL+'?app=comment&controller=review&action=page&topicid='+topicid+'&page='+page+'&pagesize='+pagesize, function(response){
			$('#comment_list').empty();
			delete(response.total);
			var html = $('#page_template').html();
			var follow = $('#follow_template').html();
			$.each(response.data, function (i, k) {
				var htmls = '';
				var follows = '';
				if (k.follow) {
					if (k.follow.supports == 0) k.follow.supports = ''; 
					follows = follow.replace('{username}', k.follow.username).replace('{content}', k.follow.content).replace('{date}', k.follow.date).replace('{location}', k.follow.location).replace('{commentid}', k.follow.commentid).replace('{commentid}', k.follow.commentid).replace('{commentid}', k.follow.commentid).replace('{supports}', k.follow.supports);
				}
				if (k.supports == 0) k.supports = ''; 
				htmls = html.replace('{username}', k.username).replace('{content}', k.content).replace('{date}', k.date).replace('{location}', k.location).replace('{commentid}', k.commentid).replace('{commentid}', k.commentid).replace('{commentid}', k.commentid).replace('{supports}', k.supports).replace('{follow}', follows);
				comment.html(htmls);
			});
		});
	},

	html: function (html, before) {
		if (before) {
			$('#comment_list').prepend(html);
		} else {
			$('#comment_list').append(html);
		}
	},
	
	reply: function(commentid)
	{
		$.getJSON(APP_URL+'?app=comment&controller=review&action=comment&jsoncallback=?&followid=' + commentid, function (data) {
			window.replyDialog = new window.dialog({
				width: 648,
				height: 226,
				html: data,
				hasOverlay: 1,
				hasCloseIco: 1
			});
			replyDialog.open();
		})
	},

	support: function(commentid, obj)
	{
		$.getJSON(APP_URL+'?app=comment&controller=comment&action=support&jsoncallback=?&commentid='+commentid, function(response){
			var msg = response.state ? response.supports : response.error;
			$(obj).html(msg);
		});
	},

	report: function(commentid, obj)
	{
		$.getJSON(APP_URL+'?app=comment&controller=comment&action=report&jsoncallback=?&commentid='+commentid, function(response){
			var reportString,report,msg = response.state ? '已举报' : response.error;
			$(obj).html(msg);
			reportString = $.cookie('comment_report');
			report = reportString ? reportString.split(',') : [];
			report.push(commentid);
			$.cookie('comment_report', report.join(','), {'expires':1, 'domain':COOKIE_DOMAIN});
		});
	},

	display: function(obj)
	{
		$(obj).prev().slideDown("slow").end().remove();
	}
}