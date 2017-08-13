(function(){
function addPage(success){
    var panel = $('<div class="diy-addpage">'+
        '<div class="diy-addpage-border">'+
            '<div class="diy-addpage-head">'+
                '<span class="diy-addpage-title"></span>'+
                '<i class="diy-addpage-close"></i>'+
            '</div>'+
            '<div class="diy-addpage-box"></div>'+
        '</div>'+
    '</div>').appendTo(document.body), titleBar = panel.find('.diy-addpage-title'), _from = null, _fromData = null;
    panel.find('.diy-addpage-close').click(function(){
        panel.remove();
    });
    var reel = new Reel(panel.find('.diy-addpage-box'));
    reel.add('boot', function(){
        titleBar.html('选择专题创建方式');
    }, function(entry, shell){
        var page = $('<div class="diy-addpage-from">'+
            '<div class="diy-addpage-fromitem" target="fromscheme"><i class="diy-addpage-icon diy-addpage-icon-scheme"></i>选择专题方案创建</div>'+
            '<div class="diy-addpage-fromitem" target="frompage"><i class="diy-addpage-icon diy-addpage-icon-page"></i>克隆已有专题页创建</div>'+
            '<div class="diy-addpage-fromitem" target="fromtemplate"><i class="diy-addpage-icon diy-addpage-icon-template"></i>上传/选择模板创建</div>'+
        '</div>').appendTo(shell);
        page.children('.diy-addpage-fromitem').click(function(){
           reel.show(this.getAttribute('target'));
        });
    }, 0).add('fromscheme', function(){
        titleBar.html('选择专题方案创建');
    }, function(entry, shell){
        var h = shell.height(), w = shell.width(), page = $('<div class="diy-addpage-fromscheme">'+
            '<div class="diy-addpage-schemetype" style="height:'+h+'px">'+
                '<div class="diy-addpage-schemetypeitem" typeid="0">全部</div>'+
            '</div>'+
            '<div class="diy-addpage-schemebody">'+
                '<div class="diy-addpage-area">'+
                    '<input type="text" class="diy-addpage-search" />'+
                '</div>'+
                '<div class="diy-addpage-schemebox"></div>'+
                '<div class="diy-addpage-area">'+
                    '<span class="diy-addpage-button" target="boot">上一步</span>'+
                    '<span class="diy-addpage-button" target="post">下一步</span>'+
                '</div>'+
            '</div>'+
        '</div>').appendTo(shell), _ACTION = {
            boot:function(){
                reel.show('boot');
            }, post:function(){
                if (_focused) {
                    _from = entry;
                    _fromData = _focused.attr('entry');
                    reel.show('post');
                } else {
                    shell.message('warn', '请选择一个方案');
                }
            }
        }, schemetype = page.find('.diy-addpage-schemetype'), _focused = null;
        // init buttons
        page.find('.diy-addpage-button').click(function(){
            _ACTION[this.getAttribute('target')]();
        });
        // init pagination
        var pagination = new Pagination(page.find('.diy-addpage-schemebox'), {width:w-schemetype.outerWidth(), height:h-80}, 18, function(p){
            p.addClass('diy-addpage-schemepage');
        }, function(t, data){
            var subt = subtitle(data.name, 6);
            t.addClass('diy-addpage-schemeitem');
            t.attr('entry', data.entry);
            subt != data.name && t.attr('tip', data.name+':center:bottom-27:500');
            t.append('<img src="'+(data.thumb || 'apps/special/images/diy-nothumb.png')+'" /><span>'+subt+'</span>');
            t.click(function(){
                if (_focused != t) {
                    _focused && _focused.removeClass('diy-actived');
                    _focused = t;
                    _focused.addClass('diy-actived');
                }
            }).dblclick(function(){
                t.click();
                _ACTION.post();
            });
            _focused || t.click();
        });
        pagination.setUrl('?app=special&controller=setting&action=searchScheme&page=%p&pagesize=%s', '', true);
        var search = page.find('.diy-addpage-search'), lastQueryString = '',
            _currentTypeid = null, _currentType = null;
        function query(){
            var qs = 'keyword='+encodeURIComponent(search.val())+'&typeid='+_currentTypeid;
            if (qs !== lastQueryString) {
                _focused = null;
                lastQueryString = qs;
                pagination.query(qs);
            }
        }
        // init keyword search
        search.input(function(){
            this.style.cssText = this.value === '' ? '' : 'background-image:none';
            query();
        });

        // init types tab
        schemetype.click(function(e){
            var t = e.target, $t, typeid;
            if (t.nodeType !== 1) {
                return;
            }
            $t = $(t);
            typeid = $t.attr('typeid');
            if (typeid && _currentTypeid != typeid) {
                _currentType && _currentType.removeClass('diy-actived');
                $t.addClass('diy-actived');
                _currentTypeid = typeid;
                _currentType = $t;
                query();
            }
        }).children(':first').click();

        // load types
        $.getJSON('?app=special&controller=setting&action=getSchemeTypes', function(json){
            $.each(json, function(i,v){
                $('<div class="diy-addpage-schemetypeitem" typeid="'+ v.typeid+'">'+ v.name +'</div>').appendTo(schemetype);
            });
        });
    }, 1).add('fromtemplate', function(){
        titleBar.html('上传/选择模板创建');
    }, function(entry, shell){
        var h = shell.height(), w = shell.width(), page = $('<div class="diy-addpage-fromtemplate">'+
            '<div class="diy-addpage-area">'+
                '<span class="diy-addpage-uploader">上传模板</span>'+
                '<input type="text" class="diy-addpage-search" />'+
            '</div>'+
            '<div class="diy-addpage-templatebox"></div>'+
            '<div class="diy-addpage-area">'+
                '<span class="diy-addpage-button" target="boot">上一步</span>'+
                '<span class="diy-addpage-button" target="post">下一步</span>'+
            '</div>'+
        '</div>').appendTo(shell), _ACTION = {
            boot:function(){
                reel.show('boot');
            }, post:function(){
                if (_focused) {
                    _from = entry;
                    _fromData = _focused.attr('entry');
                    reel.show('post');
                } else {
                    shell.message('warn', '请选择一个模板');
                }
            }
        }, _focused = null;
        // init buttons
        page.find('.diy-addpage-button').click(function(){
            _ACTION[this.getAttribute('target')]();
        });
        // init pagination
        var pagination = new Pagination(page.find('.diy-addpage-templatebox'), {width:w, height:h-90}, 21, function(p){
            p.addClass('diy-addpage-templatepage');
        }, function(t, data){
            var subt = subtitle(data.name, 6);
            t.addClass('diy-addpage-templateitem');
            t.attr('entry', data.entry);
            subt != data.name && t.attr('tip', data.name+':center:bottom-27:500');
            t.append('<img src="'+(data.thumb || 'apps/special/images/diy-nothumb.png')+'" /><span>'+subt+'</span>');
            t.click(function(){
                if (_focused != t) {
                    _focused && _focused.removeClass('diy-actived');
                    _focused = t;
                    _focused.addClass('diy-actived');
                }
            }).dblclick(function(){
                t.click();
                _ACTION.post();
            });
            _focused || t.click();
        });
        pagination.setUrl('?app=special&controller=setting&action=searchTemplate&page=%p&pagesize=%s');
        var search = page.find('.diy-addpage-search'), lastSearchText = '';
        function query(){
            var t = search.val();
            if (t !== lastSearchText) {
                _focused = null;
                lastSearchText = t;
                pagination.query('keyword='+encodeURIComponent(t));
            }
        }
        // init keyword search
        search.input(function(){
            this.style.cssText = this.value === '' ? '' : 'background-image:none';
            query();
        });
        // init upload
        page.find('.diy-addpage-uploader').uploader({
            fileExt:'*.zip',
            fileDesc:'ZIP文件',
            jsonType : 1,
            script:'?app=special&controller=setting&action=addTemplate',
            multi:false,
            uploadComplete:function(json){
                if (json.state) {
                    lastSearchText = null;
                    search.val(json.data.name);
                    search.input();
                } else {
                    ct.error(json.error);
                }
            },
            error:function(err){
                ct.error(err.file.name+'：上传失败，'+err.error.type+':'+err.error.info);
            }
        });
    }, 1).add('frompage', function(){
        titleBar.html('克隆已有专题页创建');
    }, function(entry, shell){
        var h = shell.height(), w = shell.width(), page = $('<div class="diy-addpage-frompage">'+
            '<div class="diy-addpage-pageleft" style="height:'+h+'px">'+
                '<div class="diy-addpage-pagehead">'+
                    '<input class="diy-addpage-catid" />'+
                    '<input type="text" class="diy-addpage-search" />'+
                    '<span class="diy-addpage-filter">'+
                        '<a value="all">全部</a><a value="today">今日</a>'+
                        '<a value="tomorrow">昨日</a><a value="week">本周</a><a value="month">本月</a>'+
                    '</span>'+
                '</div>'+
                '<div class="diy-addpage-specialshell"></div>'+
            '</div>'+
            '<div class="diy-addpage-pageright" style="height:'+h+'px">'+
                '<div class="diy-addpage-pagehead">选择页面</div>'+
                '<div class="diy-addpage-pageshell">'+
                    '<div class="diy-addpage-pagebox"></div>'+
                '</div>'+
                '<div class="diy-addpage-area">' +
                    '<span class="diy-addpage-button" target="boot">上一步</span>'+
                    '<span class="diy-addpage-button" target="post">下一步</span>'+
                '</div>'+
            '</div>'+
        '</div>').appendTo(shell), _ACTION = {
            boot:function(){
                reel.show('boot');
            }, post:function(){
                if (_focused) {
                    _from = entry;
                    _fromData = _focused.attr('entry');
                    reel.show('post');
                } else {
                    shell.message('warn', '请选择一个页面');
                }
            }
        }, _focused = null, _focusedContent = null, _headheight = page.find('.diy-addpage-pagehead').outerHeight(),
        _specialShell = page.find('.diy-addpage-specialshell'), _pageShell = page.find('.diy-addpage-pageshell'),
        _pageBox = _pageShell.find('.diy-addpage-pagebox'),
        _contentUrl = '?app=special&controller=special&action=search&page=%p&pagesize=%s',
        _pageUrl = '?app=special&controller=online&action=getPages&contentid=%i';

        // init heights
        _specialShell.height(h - _headheight);
        _pageShell.height(h - _headheight - 50);

        // init buttons
        page.find('.diy-addpage-button').click(function(){
            _ACTION[this.getAttribute('target')]();
        });

        function loadPages(contentid) {
            _focused = null;
            _pageBox.html(spinning());
            $.getJSON(_pageUrl.replace('%i', contentid), function(json){
                _pageBox.empty();
                $.each(json, function(k, v){
                    var t = $('<div class="diy-addpage-pageitem" entry="'+v.pageid+'">'+
                        '<a target="_blank" href="'+v.url+'">查看≫</a>'+
                        '<i></i><span title="'+v.name+'">'+subtitle(v.name, 12)+'</span>'+
                    '</div>').appendTo(_pageBox);
                    t.click(function(){
                        if (_focused != t) {
                            _focused && _focused.removeClass('diy-actived');
                            _focused = t;
                            _focused.addClass('diy-actived');
                        }
                    }).dblclick(function(){
                        t.click();
                        _ACTION.post();
                    });
                    _focused || t.click();
                });
            });
        }

        var paper = new scrollPaper(_specialShell, _contentUrl, function(item, data){
            item.click(function(){
                if (_focusedContent !== item) {
                    _focusedContent && _focusedContent.removeClass('diy-actived');
                    item.addClass('diy-actived');
                    _focusedContent = item;
                    loadPages(data.contentid);
                }
            }).append('<em>'+data.created+'</em>'+data.title);
            _focusedContent || item.click();
        }, 15);

        var search = page.find('.diy-addpage-search'), catid = page.find('.diy-addpage-catid').selectree({
            alt:'选择栏目',
            url:'?app=system&controller=category&action=cate&catid=%s',
            initUrl:'?app=system&controller=category&action=name&catid=%s',
            paramVal:'catid',
            paramTxt:'name',
            multiple:1,
            selectMult:1,
            extraClass:'diy-addpage-selectree'
        }), catids = '', filter = page.find('.diy-addpage-filter'), created = null, lastQueryString = '';
        function query(){
            var qs = 'keyword='+encodeURIComponent(search.val())+'&catid='+catids+'&created='+created.attr('value');
            if (qs !== lastQueryString) {
                _focusedContent = null;
                lastQueryString = qs;
                paper.query(qs);
            }
        }
        // init keyword search
        search.input(function(){
            this.style.cssText = this.value === '' ? '' : 'background-image:none';
            query();
        });
        catid.bind('changed', function(e, c){
            catids = c;
            query();
        });
        filter.children('a').each(function(){
            var t = $(this);
            t.click(function(){
                if (created != t) {
                    created && created.removeClass('diy-actived');
                    t.addClass('diy-actived');
                    created = t;
                    query();
                }
            });
            created || t.click();
        });
    }, 1).add('post', function(){
        titleBar.html('完善专题属性');
    }, function(entry, shell){
        var page = $('<div class="diy-addpage-post">'+
            '<div class="diy-addpage-postform"></div>'+
            '<div class="diy-addpage-area">'+
                '<span class="diy-addpage-button" target="up">上一步</span>'+
                '<span class="diy-addpage-button" target="create">创建页面</span>'+
            '</div>'+
        '</div>').appendTo(shell), _ACTION = {
            up:function(){
                reel.show(_from);
            }, create:function(){
                var spin = spinning().addClass('diy-addpage-moon').appendTo(shell),
                    data = page.find('form').serializeArray(), from = {name:_from, value:_fromData};
                data.push(from);
                $.ajax({
                    dataType:'json',
                    url:envUrl('?app=special&controller=online&action=addPage'),
                    type:'POST',
                    data:$.param(data),
                    success:function(json){
                        if (json.state) {
                            success(json) && panel.remove();
                        } else {
                            shell.message('fail', json.error);
                        }
                    }, error:function(){
                        shell.message('fail', '请求异常');
                    }, complete:function(){
                        spin.fadeOut('fast', function(){
                            spin.remove();
                        });
                    }
                });
            }
        };
        page.find('.diy-addpage-button').click(function(){
            _ACTION[this.getAttribute('target')]();
        });
        page.find('.diy-addpage-postform').load(envUrl('?app=special&controller=online&action=addPage'));
    }, 2).show('boot');
}

// exports
(window.DIY || window).addPage = addPage;
})();

