define(function (require) {

    var $ = require('jquery');
    var Template = require('cmstop.template');
    require('jquery.mobile-events');

    env.share = {
        app: parseInt($('html').data('app-mode'), 10),
        wechat: /\bMicroMessenger\//.test(navigator.userAgent)
    };

    env.flash = {};
    env.flash.support = (function () {
        var support = 0;

        var activeXDetectRules = [
            {
                "name":"ShockwaveFlash.ShockwaveFlash.7",
                "version":function(obj){
                    return getActiveXVersion(obj);
                }
            },
            {
                "name":"ShockwaveFlash.ShockwaveFlash.6",
                "version":function(obj){
                    var version = "6,0,21";
                    try{
                        obj.AllowScriptAccess = "always";
                        version = getActiveXVersion(obj);
                    }catch(err){}
                    return version;
                }
            },
            {
                "name":"ShockwaveFlash.ShockwaveFlash",
                "version":function(obj){
                    return getActiveXVersion(obj);
                }
            }
        ];

        var getActiveXObject = function(name){
            var obj = -1;
            try{
                obj = new ActiveXObject(name);
            }catch(err){
                obj = {activeXError:true};
            }
            return obj;
        };

        if (navigator.plugins && navigator.plugins.length > 0) {
            var type = 'application/x-shockwave-flash';
            var mimeTypes = navigator.mimeTypes;
            if (mimeTypes && mimeTypes[type] && mimeTypes[type].enabledPlugin && mimeTypes[type].enabledPlugin.description) {
                support = 1;
            }
        } else if (navigator.appVersion.indexOf("Mac") == -1 && window.execScript) {
            var version = -1;
            for (var i = 0; i < activeXDetectRules.length && version == -1; i++) {
                var obj = getActiveXObject(activeXDetectRules[i].name);
                if (!obj.activeXError) {
                    support = 1;
                    break;
                }
            }
        }
        return function () {
            return support;
        };
    })();

    env.video = {};
    env.video.support = (function () {
        var video;
        return function (type) {
            if (!video) {
                video = document.createElement('video');
            }
            return video.canPlayType && video.canPlayType('video/' + type).replace(/no/, '');
        };
    })();
    env.video.player = function (data) {
        var video = data.file || data.video;
        if (video.lastIndexOf('.') == -1) {
            if (env.flash.support()) {
                return 'flash';
            }
            if (env.video.support('mp4')) {
                return 'mp4';
            }
            return false;
        }
        var ext = video.substr(video.lastIndexOf('.') + 1).toLowerCase();
        if (ext == 'mp4') {
            if (env.video.support('mp4')) {
                return 'mp4';
            }
            if (env.flash.support()) {
                return 'cmstop';
            }
            return false;
        }
        return env.flash.support() ? 'flash' : false;
    };

    env.audio = {};
    env.audio.support = (function () {
        var audio;
        return function (type) {
            if (!audio) {
                audio = document.createElement('audio');
            }
            return audio.canPlayType && audio.canPlayType('audio/' + type).replace(/no/, '');
        };
    })();
    env.audio.player = function (url) {
        var ext = url && url.substr(url.lastIndexOf('.') + 1).toLowerCase();
        if (ext == 'amr') {
            if (env.audio.support('amr')) {
                return 'jplayer';
            }
            if (env.audio.support('mp3')) {
                return 'amr';
            }
            return false;
        }
        return 'jplayer';
    };

    $(function () {

        var OLD_CACHE = [], NEW_CACHE = [], OFFSET = 0, PAGESIZE = 20, TIMEOUT = 30000;
        var $sectionMeta = $('.section-meta');
        var $sectionContent = $('.section-content');
        var $itemlist = $sectionContent.find('.item-list');
        var $loadMore = $sectionContent.find('.item-refresh');
        var $notifyLoader = $sectionContent.find('.item-notify.loader');
        var $notifyNew = $sectionContent.find('.item-notify.new');
        var $notifyRefresh = $sectionContent.find('.item-notify.refresh');
        var $titleOpen = $sectionContent.find('.section-title.open');
        var $titleClosed = $sectionContent.find('.section-title.closed');

        var itemTemplate = new Template($('#item-template').html());
        var imageTemplate = new Template($('#image-gallery-template').html());
        var videoCmsTopTemplate = new Template($('#video-cmsotp-template').html());
        var videoFlashTemplate = new Template($('#video-flash-template').html());
        var page = 0;

        function isClosed() {
            return parseInt(env.closed || 0, 10);
        }

        function renderPosts(posts) {
            var html = '', player;
            $.each(posts, function (index, post) {
                post = $.extend(true, {}, post);

                if (post.text && env.share.app) {
                    post.text_share = $('<div>' + post.text + '</div>').text();
                }

                if (post.video) {
                    if ((post.video_player = env.video.player(post.video))) {
                    } else {
                        delete(post.video);
                    }
                    if (post.video && post.video.duration) {
                        post.video.duration = formatSeconds(post.video.duration);
                    }
                }

                if (post.audio) {
                    if ((post.audio_player = env.audio.player(post.audio))) {
                        post.audio_duration = formatSeconds(post.audio_duration);
                    } else {
                        delete(post.audio);
                    }
                }

                html += itemTemplate.render(post);
            });
            return html;
        }

        function loadPosts(offset, callback) {
            var query = {
                liveid: env.liveid,
                _: new Date().getTime()
            };
            if (offset !== false) {
                query['offset'] = offset;
            }
            $.getJSON('?app=mobile&controller=eventlive&action=query', query, callback);
        }

        function loadNewPosts() {
            loadPosts(OFFSET, function (ret) {
                if (ret && ret.state) {
                    if (ret.data && ret.data.length) {
                        var created = parseInt(ret.data[0].created || 0, 10);
                        if (created > OFFSET) {
                            OFFSET = created;
                        }
                        NEW_CACHE = ret.data.concat(NEW_CACHE);
                    }

                    if (OLD_CACHE.length) {
                        // 显示新消息提示
                        if (NEW_CACHE.length) {
                            showElement($notifyNew);
                        }
                    } else {
                        // 直接渲染，同时填充旧数据
                        OLD_CACHE = [].concat(NEW_CACHE);
                        renderNewPosts();
                    }

                    // 如果尚未关闭
                    env.closed = ret.closed;
                    if (isClosed()) {
                        $titleOpen.removeClass('show');
                        $titleClosed.addClass('show');
                    } else {
                        setTimeout(loadNewPosts, TIMEOUT);
                    }
                }
            });
        }

        function renderNewPosts() {
            if (!NEW_CACHE.length) {
                return;
            }

            var posts = [].concat(NEW_CACHE);
            NEW_CACHE = [];
            hideElement($notifyNew);

            $itemlist.prepend(renderPosts(posts));
        }

        function showElement($element) {
            $element.css('display', 'block');
        }

        function hideElement($element) {
            $element.css('display', 'none');
        }

        function renderMore() {
            var start = page * PAGESIZE, posts = OLD_CACHE.slice(start, start + PAGESIZE);
            if (posts.length) {
                $itemlist.append(renderPosts(posts));
                if (OLD_CACHE[(page + 1) * PAGESIZE]) {
                    showElement($loadMore);
                } else {
                    hideElement($loadMore);
                }
                page += 1;
            }
        }

        // 显示更多
        $loadMore.on('tap', renderMore);

        // 查看新消息
        $notifyNew.on('tap', renderNewPosts);

        // 加载已发布的信息
        loadPosts(false, function (ret) {
            hideElement($notifyLoader);

            if (ret && ret.state) {
                OLD_CACHE = ret.data || [];
                OFFSET = parseInt(ret.data && ret.data[0].created || 0, 10);
                renderMore();

                // 轮询新发布的信息
                if (isClosed()) {
                    $titleClosed.addClass('show');
                } else {
                    $titleOpen.addClass('show');
                    setTimeout(loadNewPosts, TIMEOUT);
                }
            } else {
                showElement($notifyRefresh);
            }
        });

        function formatNumber(input) {
            return input > 10000 ? ((input / 10000).toFixed(1) + '万') : input;
        }

        function formatSeconds(input) {
            input = parseInt(input, 10);
            if (!input) {
                return 0;
            }
            var minutes = Math.floor(input / 60);
            if (minutes < 10) {
                minutes = '0' + minutes;
            }
            var seconds = input - minutes * 60;
            if (seconds < 10) {
                seconds = '0' + seconds;
            }
            return [minutes, seconds].join(':');
        }

        // 图片浏览
        var $gallery, gallery;
        env.share.app || $itemlist.on('tap', '.item-image', function (e) {
            var $item = $(this);

            if ($gallery) {
                $gallery.remove();
            }

            $('<img />').on('load', function () {
                var image = this;
                require.async(['photoswipe', 'photoswipe.ui-default'], function (PhotoSwipe, PhotoSwipeUI_Default) {
                    $gallery = $(imageTemplate.render({})).appendTo(document.body);
                    gallery = new PhotoSwipe($gallery[0], PhotoSwipeUI_Default, [{
                        src: image.src,
                        w: image.width,
                        h: image.height
                    }], {});
                    gallery.init();
                });
            }).attr('src', $item.data('original'));

            e.preventDefault();
            e.stopPropagation();
        });

        // 音频播放
        var $jPlayer, jPlayerReady = 0;
        function startAudioJPlayer($item, audio) {
            function play() {
                $item.addClass('audio-playing');
                $jPlayer.data('item', $item);
                $jPlayer.jPlayer('setMedia', {
                    mp3: audio
                });
                $jPlayer.jPlayer('play');
            }
            if ($jPlayer) {
                if (jPlayerReady) {
                    play();
                } else {
                    $jPlayer.on($.jPlayer.event.ready, play);
                }
                return;
            }
            require.async('jquery.jplayer', function () {
                var id = 'audio-' + new Date().getTime().toString(32);
                $jPlayer = $('<div id="' + id + '" class="audio-player jplayer"></div>').appendTo(document.body);
                $jPlayer.jPlayer({
                    ready: function () {
                        jPlayerReady = 1;
                        play();
                    },
                    cssSelectorAncestor: '#' + id,
                    size: { width: '200px', height: '50px' },
                    swfPath: IMG_URL + 'js/lib/jplayer/Jplayer.swf',
                    supplied: 'mp3,amr',
                    wmode: 'window'
                });
                $jPlayer.on($.jPlayer.event.ended, function () {
                    $jPlayer.data('item') && $jPlayer.data('item').removeClass('audio-playing');
                });
            });
        }
        function stopAudioJPlayer() {
            if (!$jPlayer) {
                return;
            }
            if (jPlayerReady) {
                $jPlayer.jPlayer('stop');
            }
            $jPlayer.data('item') && $jPlayer.data('item').removeClass('audio-playing');
        }

        var $amrPlayer, amrPlayer, AmrPlayer;
        function startAudioAmrPlayer($item, audio) {
            function play() {
                $item.addClass('audio-playing');
                amrPlayer = new AmrPlayer();
                $amrPlayer.data('item', $item);
                amrPlayer.addList([{ url: audio, time: 200 }]);
                amrPlayer.onended = function () {
                    $amrPlayer.data('item') && $amrPlayer.data('item').removeClass('audio-playing');
                };
                amrPlayer.play();
            }
            if ($amrPlayer) {
                play();
                return;
            }
            require.async('cmstop.amrplayer', function (Player) {
                $amrPlayer = $('<div class="audio-player amrplayer"></div>').appendTo(document.body);
                AmrPlayer = Player;
                play();
            });
        }
        function stopAudioAmrPlayer() {
            if (!$amrPlayer) {
                return;
            }
            amrPlayer.stop();
            $amrPlayer.data('item') && $amrPlayer.data('item').removeClass('audio-playing');
        }

        function playAudio($item) {
            stopAudio();

            var player = $item.data('player');
            var audio = $item.data('audio');

            switch (player) {
                case 'jplayer':
                    startAudioJPlayer($item, audio);
                    break;
                case 'amr':
                    startAudioAmrPlayer($item, audio);
                    break;
            }
        }
        function stopAudio() {
            stopAudioJPlayer();
            stopAudioAmrPlayer();
        }

        $itemlist.on('tap', '.item-audio', function (e) {
            var $item = $(this);
            if ($item.hasClass('audio-playing')) {
                stopAudio();
            } else {
                playAudio($item);
            }
        });

        // 视频播放
        function playVideo($item) {
            var player = $item.data('player');
            var video = $item.data('video');
            var vid = $item.data('vid');
            var $thumb = $item.find('.video-thumb');
            var $player = $item.find('.video-player');

            var data = {
                video: video,
                vid: vid
            };

            switch (player) {
                case 'cmstop':
                    $player.append(videoCmsTopTemplate.render(data));
                    break;
                case 'flash':
                    $player.append(videoFlashTemplate.render(data));
                    break;
            }

            $thumb.fadeOut();
        }

        $itemlist.on('tap', '.video-thumb', function (e) {
            var $thumb = $(this), $item = $thumb.closest('.item-video');
            if ($item.data('lock')) {
                return;
            }
            $item.data('lock', 1);
            stopAudio();
            playVideo($item);
        });

        // 赞
        var $totalSupports = $sectionMeta.find('.number .support');
        $itemlist.on('tap', '.item-action .support', function () {
            var $action = $(this);
            var $item = $action.closest('.item');
            if ($item.data('supported')) {
                return;
            }
            $item.data('supported', 1);

            var number = parseInt($action.data('support') || 0, 10) || 0, total;
            var $text = $action.find('b');
            $.post('?app=mobile&controller=eventlive&action=support', {
                liveid: env.liveid,
                postid: $item.data('post-id')
            }, function (ret) {
                if (ret && ret.state) {
                    $text.text(number + 1);
                    total = parseInt($totalSupports.data('support') || 0, 10) + 1;
                    $totalSupports.data('support', total);
                    $('.number .support').find('b').text(formatNumber(total));
                } else {
                    alert(ret && ret.error || '点赞失败了 ... 请稍候重试');
                    $item.removeData('supported');
                }
            }, 'json');
        });

        // 分享
        var defaultShareData = {
            title: document.title,
            desc: $('.section-info .text p').text(),
            link: location.href,
            imgUrl: $('.section-cover').data('image'), // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        };
        var $wechatShare;
        function showWechatShare() {
            if (!$wechatShare) {
                $wechatShare = $('.section-wechat-share');
                $wechatShare.find('.close').on('tap', function () {
                    $wechatShare.hide();
                });
            }
            $wechatShare.fadeIn();
        }
        function changeShareData(data) {
            var share = $.extend({}, defaultShareData);
            $.each(['title', 'desc', 'imgUrl', 'type', 'dataUrl'], function (index, key) {
                if (data[key]) {
                    share[key] = data[key];
                }
            });
            if (window.wx) {
                wx.onMenuShareTimeline(share);
                wx.onMenuShareAppMessage(share);
                wx.onMenuShareQQ(share);
                wx.onMenuShareWeibo(share);
            }
        }
        if (env.share.wechat || env.share.app) {
            $itemlist.addClass('share-enabled');
            changeShareData(defaultShareData);
        }
        var $totalShares = $sectionMeta.find('.number .share');
        $itemlist.on('tap', '.item-action .share', function () {
            var $action = $(this);

            if (env.share.wechat) {
                showWechatShare();
            }

            var $item = $action.closest('.item');
            if ($item.data('shared')) {
                return;
            }
            $item.data('shared', 1);

            var number = parseInt($action.data('share') || 0, 10) || 0, total;
            var $text = $action.find('b');
            $.post('?app=mobile&controller=eventlive&action=share', {
                liveid: env.liveid,
                postid: $item.data('post-id')
            }, function (ret) {
                if (ret && ret.state) {
                    //$text.text(number + 1);
                    $totalShares.find('b').text(formatNumber(parseInt($totalShares.data('share') || 0, 10) + 1));

                    total = parseInt($totalShares.data('share') || 0, 10) + 1;
                    $totalShares.data('share', total);
                    $('.number .share').find('b').text(formatNumber(total));
                } else {
                    $item.removeData('shared');
                }
            }, 'json');
        });

        // 管理
        function showMenu(title, actions) {
            require.async('eventlive.menu', function (Menu) {
                Menu.confirm(title, actions);
            });
        }

        function showDialog(message, actions) {
            require.async('eventlive.dialog', function (Dialog) {
                Dialog.show(message, actions);
            });
        }

        var $sectionActions = $('.section-actions');
        $sectionActions.on('tap', '.action.close', function () {
            showMenu('关闭后将不可再发布内容，确认关闭？', [
                {
                    label: '关闭直播',
                    classes: 'dangerous',
                    callback: closeLive
                }
            ]);
        });
        function closeLive() {
            $.post('?app=mobile&controller=eventlive&action=close', {
                contentid: env.contentid
            }, function (ret) {
                if (ret && ret.state) {
                    $('html').attr('data-closed', 1);
                } else {
                    showDialog(ret && ret.error || '关闭直播失败', [
                        {
                            label: '确定'
                        }
                    ]);
                }
            }, 'json');
        }

        $itemlist.on('tap', '.item-menu', function (e) {
            var $menu = $(this);
            var privManage = $menu.data('priv-manage');
            var memberId = $menu.data('member-id');
            var memberType = $menu.data('member-type');
            var memberName = $menu.data('member-name');
            var memberRemoved = $menu.data('member-removed');
            var postId = $menu.data('post-id');
            var actions = [];
            if (privManage && !memberRemoved) {
                switch (memberType + '') {
                    case '2':
                        actions.push({
                            label: '移除直播员',
                            callback: function () {
                                showMenu('确定要移除直播员「' + memberName + '」权限？', [
                                    {
                                        label: '确定',
                                        callback: function () {
                                            $.post('?app=mobile&controller=eventlive&action=remove_speaker', {
                                                contentid: env.contentid,
                                                memberid: memberId
                                            }, function (ret) {
                                                if (ret && ret.state) {
                                                    $sectionMeta.find('[data-member-id=' + memberId + ']').remove();
                                                    $menu.data('member-removed', 1);
                                                    showDialog('移除直播员权限成功', [{
                                                        label: '确定'
                                                    }]);
                                                } else {
                                                    showDialog(ret && ret.error || '移除直播员权限失败', [{
                                                        label: '确定'
                                                    }]);
                                                }
                                            }, 'json');
                                        }
                                    }
                                ]);
                            }
                        });
                        break;
                    case '3':
                        actions.push({
                            label: '移除嘉宾直播权限',
                            callback: function () {
                                showMenu('确定要移除嘉宾「' + memberName + '」权限？', [
                                    {
                                        label: '确定',
                                        callback: function () {
                                            $.post('?app=mobile&controller=eventlive&action=remove_guest', {
                                                contentid: env.contentid,
                                                memberid: memberId
                                            }, function (ret) {
                                                if (ret && ret.state) {
                                                    $sectionMeta.find('[data-member-id=' + memberId + ']').remove();
                                                    $menu.data('member-removed', 1);
                                                    showDialog('移除嘉宾权限成功', [{
                                                        label: '确定'
                                                    }]);
                                                } else {
                                                    showDialog(ret && ret.error || '移除嘉宾权限失败', [{
                                                        label: '确定'
                                                    }]);
                                                }
                                            }, 'json');
                                        }
                                    }
                                ]);
                            }
                        });
                        break;
                }
            }
            actions.push({
                label: '删除直播内容',
                classes: 'dangerous',
                callback: function () {
                    showMenu('确定要删除该直播内容吗？', [
                        {
                            label: '确定',
                            callback: function () {
                                $.post('?app=mobile&controller=eventlive&action=remove_post', {
                                    contentid: env.contentid,
                                    postid: postId
                                }, function (ret) {
                                    if (ret && ret.state) {
                                        var $parent = $menu.parent().fadeOut('fast', function () {
                                            $parent.remove();
                                        });
                                    } else {
                                        showDialog(ret && ret.error || '删除直播内容失败', [{
                                            label: '确定'
                                        }]);
                                    }
                                }, 'json');
                            }
                        }
                    ]);
                }
            });

            showMenu('', actions);
        });
    });

});