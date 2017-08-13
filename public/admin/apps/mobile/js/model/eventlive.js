$(function() {
    var title = $('#title');
    var description = $('[name=description]');
    var thumb = $('[name=thumb]');
    var source = $('[name=source]');
    var lock = false;
    var dialog;
    var postEdited = false;

    MobileContent.init();
    MobileRelated.init();

    $('form').ajaxForm(function(json) {
        if (json && json.state) {
            MobileContent.success(json);
        } else {
            lock = false;
            ct.error(json && json.error || '操作失败，请重试');
        }
    }, null, function(form) {
        if (lock) {
            ct.error('正在提交，请稍后');
            return false;
        }

        var category = form.find('[name=catid]');
        if (!category.val()) {
            ct.error('请选择栏目');
            return false;
        }

        var thumb = $('[name="thumb"]').val();
        if (thumb && (['jpg','jpeg','png','bmp','gif'].indexOf(thumb.split('.').pop()) == -1)) {
            ct.warn('列表缩略图不合法');
            return false;
        }

        var thumbSlider = $('[name="thumb_slider"]').val();
        if (thumbSlider && (['jpg','jpeg','png','bmp','gif'].indexOf(thumbSlider.split('.').pop()) == -1)) {
            ct.warn('幻灯片缩略图不合法');
            return false;
        }
        var hostavatar = $('[name*="avatar"]').val();
        if (!hostavatar) {
            ct.warn('请上传直播员头像');
            return false;
        }
        lock = true;
        form.find('[name="post"]').val(JSON.stringify(post));
    });

    $('#upload-avatar').bind('click', function () {
        ct.fileManager(function(at){
            $('[name*="avatar"]').val(at.src);
            $('#upload-avatar > img').attr('src', UPLOAD_URL + at.src);
        });
    });

    $('#add-post').bind('click', function () {
        if (!dialog) {
            createDialog();
        } else {
            dialog.dialog('open').find('form').trigger('reset');
        }
    });

    var postTemplate = $('#live-list-template').html();
    function addPost() {
        var item = {}, index, element;
        item = {
            text: dialog.data('editor').getCode(),
            image: dialog.find('[name="image"]').val(),
            video: dialog.find('[name="video"]').val(),
            audio: dialog.find('[name="audio"]').val(),
            created: dialog.find('[name="created"]').val() || moment().format("YYYY-MM-DD HH:mm:ss")
        };
        post.push(item);
        post.sort(function (a, b) {
            return moment(a.created) < moment(b.created) ? 1 : -1;
        });
        index = post.indexOf(item);
        $.each($('<div>'+dialog.data('editor').getDoc().body.innerHTML+'</div>').text().replace(/&nbsp;/g, ' ').split(/[\r\n]/), function () {
            var text = this.trim();
            if (text) {
                createPostElement(index, text.substring(0, 40), item.created);
                return false;
            }
        });
        postEdited = true;
    }

    function createPostElement(index, text, created) {
        if (index === 0) {
            element = $(postTemplate).prependTo($('#live-contents'));
        } else {
            element = $(postTemplate).insertAfter($('#live-contents > li').eq(index - 1));
        }
        element.find('a').html(text);
        element.find('time').html(created);
        element.find('.edit').bind('click', function (event) {
            var li = $(event.target).parent('li');
            var index = li.parent().children().index(li);
            dialog.find('[name="image"]').val(post[index].image);
            dialog.find('[name="video"]').val(post[index].video);
            try {
                var video = JSON.parse(post[index].video);
                dialog.find('.delete-video').show();
                dialog.find('[name="video"]').prev().val(video.video);
            } catch (exception) {}
            dialog.find('[name="audio"]').val(post[index].audio);
            dialog.find('[name="created"]').val(post[index].created);
            dialog.find('[name="index"]').val(index);
            dialog.dialog('open');
            dialog.data('editor').getDoc().body.contentEditable = true;
            dialog.data('editor').setCode(post[index].text);
        });
        element.find('.delete').bind('click', function (event) {
            ct.confirm('确认删除?', function () {
                var li = $(event.target).parent('li');
                var index = li.parent().children().index(li);
                li.remove();
                post.splice(index, 1);
                postEdited = true;
            });
        });
    }

    function createDialog() {
        dialog = $(document.createElement('div'));
        dialog.dialog({
            title: "直播内容",
            width: 650,
            height: 540,
            buttons: {
                "确定": function() {
                    if (!$('<div>' + dialog.data('editor').getCode() + '</div>').text().trim()) {
                        ct.error('内容不能为空');
                        return;
                    }
                    var video = dialog.find('[name="video"]').val();
                    if (video) {
                        try {
                            video = JSON.parse(video);
                            if (dialog.find('[name="video"]').prev().val() !== video.video) {
                                video = {
                                    video: dialog.find('[name="video"]').prev().val()
                                };
                            }
                            video = JSON.stringify(video);
                        } catch (e) {
                            video = '';
                        }
                        dialog.find('[name="video"]').val(video);
                    }
                    if (dialog.find('[name="index"]').val()) {
                        editPost();
                    } else {
                        addPost();
                    }
                    dialog.dialog('close');
                },
                "取消": function() {
                    dialog.dialog('close');
                }
            }
        }).load("?app=mobile&controller=eventlive&action=post", function () {
            if (dialog.parents('.ui-dialog').css('visibility') === 'hidden') {
                setTimeout(function () {
                    dialog.parents('.ui-dialog').css('visibility', 'visible');
                    dialog.dialog('close');
                }, 200);
            }
            $('#btn-pick').click(function() {
                var _dialog = ct.iframe({
                    url:'?app=video&controller=vms&action=index&selector=1',
                    width:600
                }, {
                    ok: function(item) {
                        dialog.find('[name="video"]').val(JSON.stringify({
                            thumb: item.pic || '',
                            video: '[ctvideo]' + item.id + '[/ctvideo]'
                        })).prev().val('[ctvideo]' + item.id + '[/ctvideo]');
                        _dialog.dialog('close');
                        $('.delete-video').show();
                    }
                });
                return false;
            });
            $('#btn-pick-audio').click(function() {
                ct.fileManager(function (result) {
                    $('[name="audio"]').val(result.src);
                }, 'mp3');
            });
            var editor = new $.Rte($('#post-content'), {
                disable: ['insertimage']
            });
            dialog.data('editor', editor);
            dialog.find('.delete-video').bind('click', function () {
                dialog.find('[name="video"]').val('').prev().val('');
                $(this).hide();
            });
            $('input.input_calendar').change(function() {
                MobileContent.formChanged = true;
            }).DatePicker({'format':'yyyy-MM-dd HH:mm:ss'});
            dialog.find('form').bind('reset', function () {
                dialog.find('[name="index"]').val('');
                dialog.data('editor').setCode('<p>&nbsp;</p>');
                dialog.find('[name="video"]').val('');
                setTimeout(function () {
                    dialog.find('[name="created"]').val(moment().format("YYYY-MM-DD HH:mm:ss"));
                    dialog.data('editor').getDoc().body.contentEditable = true;
                    dialog.find('.delete-video')[(dialog.find('[name="video"]').val() ? 'show' : 'hide')]();
                }, 1);
            });
            dialog.find('form').trigger('reset');
        });
    }

    function editPost() {
        var index = +dialog.find('[name="index"]').val();
        var newIndex;
        var element = $('#live-contents > li').eq(index);
        var item = post[index];
        item.text = dialog.data('editor').getCode();
        item.image = dialog.find('[name="image"]').val();
        item.video = dialog.find('[name="video"]').val();
        item.audio = dialog.find('[name="audio"]').val();
        item.created = dialog.find('[name="created"]').val();
        item.edited = true;
        post.sort(function (a, b) {
            return new Date(a.created) < new Date(b.created) ? 1 : -1
        });
        $.each($('<div>' + dialog.data('editor').getDoc().body.innerHTML + '</div>').text().replace(/&nbsp;/g, ' ').split(/[\r\n]/), function () {
            var text = this.trim();
            if (text) {
                element.find('a').html(text.substring(0, 40));
                return false;
            }
        });
        element.find('time').html(moment(item.created).format('YYYY-MM-DD HH:mm:ss'));
        newIndex = post.indexOf(item);
        postEdited = true;
        if (index !== newIndex) {
            if (newIndex === 0) {
                element = element.prependTo($('#live-contents'));
            } else {
                element = element.insertAfter($('#live-contents > li').eq(newIndex - 1));
            }
        }
    }

    if (window.liveid) {
        $.get('?app=mobile&controller=eventlive&action=get_post&liveid=' + window.liveid, null, function (res) {
            if (!res.state) {
                return ct.error(res.error);
            } else {
                window.post = res.data;
                createDialog();
                dialog.parents('.ui-dialog').css('visibility', 'hidden');
                $.each(post, function (index, item) {
                    var inserted = false;
                    if (item.created.indexOf('-') === -1) {
                        item.created = moment(+item.created * 1000).format("YYYY-MM-DD HH:mm:ss");
                    }
                    $.each($('<div>'+item.text+'</div>').text().replace(/&nbsp;/g, ' ').split(/[\r\n]/), function () {
                        var text = this.trim();
                        if (text) {
                            createPostElement(index, text.substring(0, 40), item.created);
                            inserted = true;
                            return false;
                        }
                    });
                    if (!inserted) {
                        createPostElement(index, '');
                    }
                });
            }
        }, 'json');
    }

    window.onbeforeunload = function () {
        if (MobileContent.formChanged) {
            return '';
        }
        if (postEdited) {
            return '';
        }
    }
});