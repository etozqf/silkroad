(function($) {
    var sortString = function(s1, s2) {
        var i = 0, c1, c2;
        while (1){
            c1 = s1[i];
            c2 = s2[i];
            if (typeof c2 == 'undefined') {
                return -1;
            }
            if (typeof c1 == 'undefined') {
                return 1;
            }
            if (c1 == c2) {
                i++;
                continue;
            }
            return (c2 > c1) ? 1 : -1;
        }
    };
window.group = {
    init: function(items) {
        var that = this;

        this.imageList = ct.imageList({
            multiple: true,
            dragsort: true,
            deleteLocalImage: false,
            updateLocalImageDesc: false,
            localImageList: $('#local_image_list'),
            remoteImageList: false
        });

        this.imageList.renderLocalImage = function(data) {
            var self = this;
            data.aid = data.aid || 0;
            var item =  $([
                '<li class="image-thumb-item" aid="', data.aid, '">',
                    '<input type="hidden" data-role="aid" name="pictures[', data.aid, '][aid]" value="', data.aid, '" />',
                    '<input type="hidden" data-role="thumb" name="pictures[', data.aid, '][thumb]" value="', self.imageUrl(data.thumb) || '', '" />',
                    '<input type="hidden" data-role="image" name="pictures[', data.aid, '][image]" value="', self.imageUrl(data.image), '" />',
                    '<input type="hidden" data-role="desc" name="pictures[', data.aid, '][note]" value="', self.htmlspecialchars(data.desc || ''), '" />',
                    '<input type="hidden" data-role="pictureid" name="pictures[', data.aid, '][pictureid]" value="', data.pictureid, '" />',
                    '<input type="hidden" data-role="sort" name="pictures[', data.aid, '][sort]" value="', data.sort || '', '" />',
                    '<a data-role="thumb-show" class="image-thumb-item-a" href="javascript:;" title="拖动以排序"></a>',
                    '<span class="image-thumb-item-action">',
                        '<span data-role="sort-label" class="image-thumb-item-num"></span>',
                        '<span class="image-thumb-item-message"></span>',
                        '<a class="image-thumb-item-action-repick" href="javascript:;" title="重新上传"><img src="images/ico_pic.gif" alt="" /></a>',
                        '<a class="image-thumb-item-action-edit" href="javascript:;" title="编辑"><img src="images/edit.gif" alt="" /></a>',
                        '<a class="image-thumb-item-action-delete" href="javascript:;" title="删除"><img src="images/del.gif" alt="" /></a>',
                    '</span>',
                    '<span class="image-thumb-item-desc" title="点击以编辑">',
                        '<textarea rows="2">', self.htmlspecialchars(data.desc || ''), '</textarea>',
                    '</span>',
                '</li>'
            ].join(''));
            if (! data.thumb) {
                self.getThumb(data.image, function(thumb) {
                    thumb = self.imageUrl(thumb);
                    item.find('[data-role=thumb]').val(thumb);
                    self.updateThumbShow(item.find('[data-role=thumb-show]'), thumb);
                });
            } else {
                this.updateThumbShow(item.find('[data-role=thumb-show]'), data.thumb);
            }
            item.find('.image-thumb-item-action-repick').click(function(e) {
                e.stopPropagation();
                var d = ct.iframe({
                    url: '?app=picture&controller=picture&action=image&single=1',
                    width: 746,
                    height: 420
                }, {
                    insert: function(aid, src, desc, sort) {
                        var result = that.add({
                            aid: aid,
                            image: src,
                            desc: desc,
                            sort: sort,
                            replace: item
                        });
                        if (result === false) {
                            return result;
                        }
                        d.dialog('close');
                    },
                    close: function() {
                        d.dialog('close');
                    }
                });
            });
            return item;
        };

        $('[data-role=group-add]').click(function() {
            var url = '?app=picture&controller=picture&action=image';
            
            // 网编工具箱标志
            if ($('[name="toolbox"]').length && ($('[name="toolbox"]').val() == '1')) {
                url += '&toolbox=1';
            }
            if ($('[name="catid"]').length) {
                url += '&catid=' + $('[name="catid"]').val();
            }
            var d = ct.iframe({
                url: url,
                width: 746,
                height: 420
            }, {
                insert: function(aid, src, desc, sort, moreToAdd) {
                    var result = that.add({
                        aid: aid,
                        image: src,
                        desc: desc,
                        sort: sort
                    });
                    if (result === false) {
                        return result;
                    }
                    ! moreToAdd && d.dialog('close');
                },
                close: function() {
                    d.dialog('close');
                }
            });
        });

        if (items && items.length) {
            var item,
                delayAdd = function() {
                    item = items.shift();
                    that.add({
                        aid: item.aid,
                        image: item.image,
                        desc: item.note,
                        sort: item.sort,
                        pictureid: item.pictureid,
                        contentid: item.pictureid
                    }, items.length);
                    items.length && setTimeout(delayAdd, 50);
                };
            delayAdd();
        }

        // 表单重置时清空图片
        $('form:first').bind('reset', function() {
            that.clear();
        });
    },

    add: function(item, moreToAdd) {
        $('#asc_order').css('visibility','visible');
        $('#desc_order').css('visibility','visible');
        return this.imageList.addLocalImage(item, moreToAdd);
    },

    clear: function() {
        this.imageList && this.imageList.clearLocalImages();
    },

    order: function(order) {
        var localImageList = group.imageList.localImageList.children('ul'),
        item = localImageList.children('.image-thumb-item');
        item.sort(function(a, b) {
            var desc1 = $(a).children('[data-role="desc"]').val(),
            desc2 = $(b).children('[data-role="desc"]').val();
            if (order == 'asc') {
                return sortString(desc2, desc1);
            } else if (order ='desc') {
                return sortString(desc1, desc2);
            }
        });
        $.each(item, function(k, v) {
            $(v).children('[data-role="sort"]').val(k+1);
            localImageList.append(item);
        });
    }
};
})(jQuery);