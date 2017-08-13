function onEndFinishJS(isFullScreen){
    if(!autoPlayList) return 0;
    var nextVideo = $('.list-point').find('li:has(a.now)').next();
    if(nextVideo.length == 0) return 0;
    if(typeof isFullScreen == 'undefined') return 1;
    var vid = nextVideo.find('a').attr('vid');
    var player = nextVideo.find('a').attr('player');
    var nextUrl = nextVideo.find('a').attr('href');
    if(isFullScreen == '0'){
        window.location.href = nextUrl;
        return 0;
    }
    var playerLoad = false;
    if(player && vid){
        switch(player){
            case 'ctvideo':
                playerLoad = load_ctvideo({"vid":vid});
                break;
            case 'sobey':
                playerLoad = load_sobey({"vid":vid});
                break;
            default:
                break;
        }
    }
    if(playerLoad) {
        $('span.title').html(nextVideo.find('a').html());
        nextVideo.prev().find('a').removeClass('now');
        nextVideo.find('a').addClass('now');
    }
    if(playerLoad) return 1;
    window.location.href = nextUrl;
    return 0;
}

function load_ctvideo(options){
    if(typeof(ctVideoServer) == 'object'){
        window.setTimeout(function(){
            ctVideoServer.getMovie().loadAndPlay(options.vid);
        }, 100);
        return true;
    }
    return false;
}

function load_sobey(options){
    if(typeof(createPlayer) == 'function'){
        window.setTimeout(function(){
            var obj = 'MyVideoPlayer';
            var videoObj = document[obj] ? document[obj] : (window[obj] ? window[obj] : undefined);
            if(videoObj){
                if(typeof(videoObj.loadAndPlay) == "function") {
                    videoObj.loadAndPlay("video://vid:"+options.vid);
                }
            }
        }, 100);
        return true;
    }
    return false;
}
$(function(){
    // auto play
    autoPlayList = $('#autoplaylist').attr('checked');
    $('#autoplaylist').click(function(){
        autoPlayList = $(this).attr('checked');
        $.cookie(COOKIE_PRE+'videoAutoPlaylist', autoPlayList ? 1 : 0, {expires: 7, path: '/'});
    });
    if($.cookie(COOKIE_PRE+'videoAutoPlaylist') == 0 && $('#autoplaylist').attr('checked') == true){
        $('#autoplaylist').attr('checked', false);
    }
    // video list
    var ulist = $('.list-point').children(),
        pageBox = $('.page'),
        pageSize = 10,
        pageTotal = 1,
        pageNow = 1;
    var total = ulist.length;
    pageTotal = Math.ceil(ulist.length/pageSize);
    pageBox.empty();
    if(pageTotal > 1){
        for(var i=0;i<pageTotal;i++){
            pageBox.append('<a href="javascript:void(0);" class="">'+(i+1)+'</a>');
        }
        pageBox.children().click(function(){
            pageNow = $(this).html();
            $(this).addClass('current');
            $(this).siblings().removeClass('current');
            ulist.hide();
            ulist.slice(pageSize*(pageNow-1), pageSize*(pageNow)).show();
        });
    }
    var videoNow = ulist.find('a').filter(function(){
        return this.getAttribute('href') === location.href;
    });
    if(videoNow.length == 0){
        $('.video-list').remove();
    }else{
        videoNow.addClass('now');
        var liNumber = ulist.index(videoNow.parent());
        pageNow = Math.ceil((liNumber+1)/pageSize);
        pageBox.find('a').eq((pageNow-1)).click();
    }
});