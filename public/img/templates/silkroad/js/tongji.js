/**
 *	Created by Hmy on 2017/3/7.
 */
var second = 0;
window.setInterval(function () {
    second ++;
}, 1000);
var tjArr = localStorage.getItem("jsArr") ? localStorage.getItem("jsArr") : '[{}]';
$.cookie('tjRefer', getReferrer() ,{expires:1,path:'/'});

window.onbeforeunload = function() {
    if($.cookie('tjRefer') == ''){
        var tjT = eval('(' + localStorage.getItem("jsArr") + ')');
        if(tjT){
            tjT[tjT.length-1].time += second;
            var jsArr= JSON.stringify(tjT);
            localStorage.setItem("jsArr", jsArr);
        }
    } else {
        var tjArr = localStorage.getItem("jsArr") ? localStorage.getItem("jsArr") : '[{}]';
        var dataArr = {
			'start_time' : Date.parse(new Date()),
            'timeOut' : Date.parse(new Date()) + (second * 1000),
			'catname' : '',
			'username' : $.cookie(COOKIE_PRE+'username'),
            'url' : location.href,
            'title' : $(document).attr("title"),
			'remark' : '',
			'published' : ''
        };
		$.ajax({
		    type: 'GET',
			data: dataArr,
		    url: 'http://api.db.silkroad.news.cn/?app=system&controller=behavior&action=record&key=ce3e2d95110beb3102b969d6a370eced&sign=ab4393ed0331a8f8dcb684e6d683601e',
		    contentType: 'application/x-www-form-urlencoded',
		    dataType: 'json',
		    success: function(data){ console.log(data); }
		});
        tjArr = eval('(' + tjArr + ')');
        tjArr.push(dataArr);
        tjArr= JSON.stringify(tjArr);
		console.log(dataArr);
        localStorage.setItem("jsArr", tjArr);
	
    }
};
function getReferrer() {
    var referrer = '';
    try {
        referrer = window.top.document.referrer;
    } catch(e) {
        if(window.parent) {
            try {
                referrer = window.parent.document.referrer;
            } catch(e2) {
                referrer = '';
            }
        }
    }
    if(referrer === '') {
        referrer = document.referrer;
    }
    return referrer;
}



