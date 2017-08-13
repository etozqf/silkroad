$(function() {
    var title = $('#title');
    var content = $('#content');
    var comment = $('#comment');
    var description = $('[name=description]');
    var thumb = $('[name=thumb]');
    var source = $('[name=source]');
    var editor;

    MobileContent.init();

    // 编辑器
    content.editor('mini');

    // 选取内容
    var rowReference = $('#row-reference');
    var referenceid = rowReference.find('[name=referenceid]');
    rowReference.find('a:eq(1)').click(function() {
        ct.assoc.open('?app=article&controller=article&action=edit&contentid=' + referenceid.val(), 'newtab');
        return false;
    });
    rowReference.find('a:eq(2)').click(function() {
        referenceid.val('');
        rowReference.hide();
        comment.show();
        MobileContent.formChanged = true;
    });
    $('#btn-pick').click(function() {
        $.datapicker({
            url: '?app=system&controller=port&action=picker&modelid=1&catid='+(MobileContent.bindCatids || ''),
            picked: function(items, port){
                port || (port = 'cmstop');
                if (port == 'cmstop') {
                    loadContent(items[0].contentid);
                } else {
                    title.val(items[0].title).trigger('change');
                    thumb.val(items[0].thumb).trigger('change');
                    description.val(items[0].description);
                    referenceid.val('');
                    rowReference.css('visibility', 'hidden');
                    $.getJSON('?app=system&controller=port&action=request&port='+port+'&type=content', {
                        contentid: items[0].id || items[0].contentid
                    }, function(json) {
                        if (json && json.state) {
                            tinyMCE.activeEditor.setContent(json.content || '');
                        }
                    });

                    rowReference.hide();
                }
                MobileContent.formChanged = true;
            },
            multiple: false
        });
        return false;
    });

    // Tags
    var tags = $('[name=tags]');
    title.bind('change', function() {
        var title = $.trim(this.value);
        title && !tags.val() && $.post('?app=system&controller=tag&action=get_tags', {title:title}, function(json) {
            tags.val(json.data);
            MobileContent.formChanged = true;
        }, 'json');
    });

    // 相关
    MobileRelated.init();

    // form
    var lock = false;
    $('form').ajaxForm(function(json) {
        if (json && json.state) {
            lock = true;
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

        content.val($.trim(tinyMCE.activeEditor.getContent()));
        if (!content.val()) {
            ct.error('请填写内容');
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

        lock = true;
        return true;
    });

    var loadContent = function(contentid) {
        $.post('?app=article&controller=article&action=detail', {contentid:contentid}, function(json) {
            if (json && json.state) {
                if (json.data.source_name || json.data.source) {
                    source.val(json.data.source_name || json.data.source);
                }
                title.val(json.data.title).trigger('change');
                thumb.val(json.data.thumb).trigger('change');
                description.val(json.data.description);
                tinyMCE.activeEditor.setContent(json.data.content.replace(/<p class="mcePageBreak">.*?<\/p>/ig, ''));
                referenceid.val(json.data.contentid);
                rowReference.find('a:eq(0)').attr('href', json.data.url).text(json.data.title);
                rowReference.css('visibility', 'visible').show();
                comment.hide();
            }
        }, 'json');
    };
    window.loadContent = loadContent;
});