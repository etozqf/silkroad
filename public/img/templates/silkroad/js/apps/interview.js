(function($){
var interview = {
	question: {
		add: function (contentid)
		{
			var nickname = $.cookie(COOKIE_PRE+'username');
			var content = $("#content").val();
			if(islogin){
				if (!nickname)
				{
					alert('请先登陆');
					return false;
				}
			}else{
				nickname = defaultname;
			}			
			if (content == '')
			{
				alert('内容不能为空');
				return false;
			}
			$.getJSON(APP_URL+"?app=interview&controller=question&action=add&contentid="+contentid+"&nickname="+encodeURIComponent(nickname)+"&content="+encodeURIComponent(content)+"&jsoncallback=?", function (response){
				if (response.state)
				{
					if (response.ischeck) {
						alert('发送成功,等待审核');
					}
					interview.question.load();
					$('#content').val('');
					$('#content').focus();
				}
				else
				{
					alert(response.error);
				}
			});
		},
		
		load: function ()
		{
			$.getJSON(APP_URL+'?app=interview&controller=question&action=load&contentid='+contentid+'&jsoncallback=?', function(data) {
				if(data.length)
				{
					var html = '';
					$.each(data, function(key, r) {
						//html += '<li class="item"><p class="content comment"><span>'+r.nickname+'：</span>'+r.content+'</p></li>';
						html += '<div class="comment-panel ov"><div class="comment-infos"><div class="comment-address-date ov"><a class="fl-l user">'+r.nickname+'</a><span class="fl-r">'+r.created+'</span></div><div class="comment-content"><p class="">'+r.content+'</p></div></div></div><div class="hr10"></div><div class="hline"></div><div class="hr20"></div>';
					});
					$('#question').html(html);
					document.getElementById('question').scrollTop = document.getElementById('question').scrollHeight;
				}
				else
				{
					$('#question').html('<div class="interview-unstart-content-no">快来给嘉宾第一个提问吧</div>');
				}
			});
		},
		
		scroll: function (ms)
		{
			if (ms === undefined) ms = 10000;
			window.question_timer = setInterval(function () {interview.question.load();}, ms);
		},
		
		stop: function ()
		{
			clearInterval(window.question_timer);
		}
	},

	chat: {	
		load: function ()
		{
			$.getJSON(APP_URL+'?app=interview&controller=chat&action=page&contentid='+contentid+'&jsoncallback=?', function(data) {
				if(data)
				{
					var html = '';
					$.each(data, function(key, r) {
						html += '<li class="item">';
				        html += '<div class="hr30"></div>';
						if (r.guestid == null)
						{
							html += '<a href="javascript:;" target="_blank"><img class="fl-l photo" src="'+IMG_URL+'images/host.png" width="40" height="40" alt=""></a>';
					        html += '<div class="content">';
					        html += '<h3 class="h3"><a href="javascript:;" target="_blank">主持人</a></h3>';
						}
						else
						{
							html += '<a href="'+r.url+'" target="_blank"><img class="fl-l photo" src="'+r.photo+'" width="40" height="40" alt=""></a>';
					        html += '<div class="content">';
					        html += '<h3 class="h3"><a href="'+r.url+'" target="_blank">'+r.name+'</a></h3>';
						}
						html += '<div class="hr10"></div>';
				        html += '<p class="text">'+r.content+'</p>';
				        html += '<div class="hr10"></div>';
				        html += '<div class="hline"></div>';
				        html += '</div>';
				        html += '</li>';
					});
					$('#chat').html(html);
				}
				else
				{
					$('#chat').html('<li class="item"><div class="content">暂无文字实录</div></li>');
				}
			});
		},
		
		scroll: function (ms)
		{
			if (ms === undefined) ms = 10000;
			window.chat_timer = setInterval(function () {interview.chat.load();}, ms);
		},
		
		stop: function ()
		{
			clearInterval(window.chat_timer);
		}
	}
};
window.interview = interview;
})(jQuery);