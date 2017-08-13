(function(window){
/** shared supports **/
var document = window.document, docElem = document.documentElement,
    $doc = $(document),
    R_PATHINFO = /(.*?)\/?([^\/]*)$/,
    R_TRIM  = /\S/.test("\xA0") ? /^[\s\xA0]+|[\s\xA0]+$/g : /^\s+|\s+$/g,
    contains = docElem.compareDocumentPosition ? function(a, b) {
        return a === b || !!(a.compareDocumentPosition(b) & 16);
    } : function(a, b) {
        return (a.nodeType === 9 ? a.documentElement : a).contains(b.nodeType === 9 ? b.documentElement : b);
    }, trim = ''.trim ? function(str){
        return str.trim();
    } : function(str){
        return str.replace(R_TRIM, '');
    },
    _PATH_CACHE = {};

function now(){
    return (new Date).getTime();
}
function natsub(title, limit) {
    var max = limit * 2, length = title.length, l = 0, i = 0, part, s;
    for (i=0; i < length && l <= max; i++) {
        l += title.charCodeAt(i) > 255 ? 2 : 1;
    }
    if (l <= max) {
        return title;
    }
    i = 0, l = 0;
    limit -= 2;
    while (l < limit) {
        var s = title.charCodeAt(i) > 255 ? 2 : 1;
        if (l + s > limit) {
            break;
        } else {
            i++;
            l += s;
        }
    }
    part = title.substr(0, i);
    l += 3;

    i = length;
    while (l < max) {
        var s = title.charCodeAt(i-1) > 255 ? 2 : 1;
        if (l + s > max) {
            break;
        } else {
            i--;
            l += s;
        }
    }
    return part + '...' + title.substring(Math.min(i, length-1), length);
}
function spinning(){
    return $('<div class="ui-finder-spinning"></div>');
}
function isPositionFixed(elem){
    do {
        if (elem.css('position').toLowerCase() == 'fixed') return true;
    } while((elem = elem.offsetParent())[0] != document.body);
    return false;
}
function createLoader(url, method){
    var _xhr = null, _runtime;
    return function(value, data, success, complete, error){
        _runtime = now();
        var loadtime = _runtime;
        _xhr && _xhr.abort();
        _xhr = $.ajax({
            type:method||'GET',
            dataType:'json',
            data:data,
            url:url.replace('%p', encodeURIComponent(value)),
            success:function(json){
                _runtime === loadtime && success && success(json);
            }, complete:function(){
                _runtime === loadtime && complete && complete();
            }, error:function(){
                _runtime === loadtime && error && error();
            }
        });
    };
}
function scrollIntoView(context, elem) {
    var top = elem.offset().top - context.offset().top,
        st = context.scrollTop(), h = context.height();
    if (top < st) {
        context.scrollTop(top);
    } else if (top + elem[0].offsetHeight - st > h) {
        context.scrollTop(top + elem[0].offsetHeight - h);
    }
}
/** end shared supports **/

/**
 * class PathDrop
 *
 * @param navi PathNavi
 * @constructor PathDrop
 */
function PathDrop(navi) {
    var _this = this, _isPositionFixed = navi.isPositionFixed(),
    _pathdrop = $('<div class="ui-pathnavi-pathdrop">' +
        '<div class="ui-pathnavi-dropinner"></div>' +
    '</div>').appendTo(document.body),
    _dropinner = _pathdrop.find('.ui-pathnavi-dropinner');

    _pathdrop.css('position', _isPositionFixed ? 'fixed' : 'absolute');

    function addItem(data){
        var path = data.path || data, name = data.name || R_PATHINFO.exec(path)[2],
            item = $('<div class="ui-pathnavi-dropitem">'+natsub(name, 12)+'</div>').appendTo(_dropinner);
        item.click(function(){
            navi.getActivedSegment().unactive();
            navi.triggerSelected(path);
        });
    }
    function fill(i, item){
        addItem(item);
    }

    _this.drop = function(segment){
        var path = segment.getPath(), _segment = segment.getSegment(), _pos = _segment.offset();

        // 调整位置
        var _top = _pos.top + _segment.outerHeight(), _left = _pos.left + _segment.outerWidth() - 30;
        if (_isPositionFixed) {
            _top = _top - ('pageYOffset' in window ? window.pageYOffset : docElem.scrollTop);
            _left = _left - ('pageXOffset' in window ? window.pageXOffset : docElem.scrollLeft);
        }
        _pathdrop.css({
            top:_top,
            left:_left
        }).show();

        _dropinner.empty();

        // 有数据缓存 且 未过期(2s 2000)
        var cached = _PATH_CACHE[path];
        if (cached != null && (!cached.time || cached.time + 2000 > now())) {
            $.each(cached.data, fill);
            return;
        }

        // 显示spining
        var spin = spinning().appendTo(_dropinner);
        // 加载数据
        navi.loader(path, null, function(json){
            if (json.state) {
                if (json.data.length) {
                    // 缓存数据
                    _PATH_CACHE[path] = {
                        time:now(),
                        data:json.data
                    };
                    $.each(json.data, fill);
                } else {
                    segment.isNaviTail() && segment.setHasChild(0);
                    _this.hide();
                }
            }
        }, function(){
            spin.remove();
        });
    };
    _this.hide = function(){
        _pathdrop.hide();
    };
    _this.contains = function(elem){
        return contains(_pathdrop[0], elem);
    };
}

/**
 * Class PathSegment
 *
 * @param navi PathNavi
 * @param path String
 * @constructor Entry
 */
function PathSegment(navi, path) {
    var name = R_PATHINFO.exec(path)[2], _this = this,
    _segment = $('<div class="ui-pathnavi-segment">' +
        '<div class="ui-pathnavi-segmententry">'+natsub(name, 12)+'</div>'+
        '<div class="ui-pathnavi-segmentarrow"></div>'+
    '</div>'),
    _entry = _segment.find('.ui-pathnavi-segmententry'),
    _arrow = _segment.find('.ui-pathnavi-segmentarrow'),
    _hasChild = 1, _isNaviLookBack = 0, _isNaviHead = 0, _isNaviTail = 0, _isMouseDown = 0;
    _entry.bind('mousedown', function(){
        _isMouseDown = 1;
        _segment.addClass('ui-pathnavi-mousedown');
    }).bind('mouseup', function(){
        if (_isMouseDown) {
            // trigger
            _isMouseDown = 0;
            _segment.removeClass('ui-pathnavi-mousedown');
            navi.triggerSelected(path);
        }
    }).bind('mouseout', function(){
        _isMouseDown = 0;
        _segment.removeClass('ui-pathnavi-mousedown');
    });

    function _isActived(){
        return navi.getActivedSegment() === _this;
    }
    function _blur(e){
        navi.getPathDrop().contains(e.target) || contains(_arrow[0], e.target) || _unactive();
    }
    function _active(){
        var _actived = navi.getActivedSegment();
        _actived && _actived.unactive();
        navi.setActivedSegment(_this);
        _segment.addClass('ui-pathnavi-actived');
        _hasChild && navi.getPathDrop().drop(_this);
        $doc.bind('mousedown', _blur);
    }
    function _unactive(){
        $doc.unbind('mousedown', _blur);
        _segment.removeClass('ui-pathnavi-actived');
        if (_isActived()) {
            navi.setActivedSegment(null);
            navi.getPathDrop().hide();
        }
    }
    _arrow.bind('mousedown', function(){
        _isActived() ? _unactive() : _active();
    });
    _segment.bind('mouseenter', function(){
        navi.getActivedSegment() && !_isActived() && _active();
    });

    _this.setNaviHead = function(flag){
        _isNaviHead = flag;
        _segment[flag ? 'addClass' : 'removeClass']('ui-pathnavi-navihead');
    };
    _this.setNaviTail = function(flag){
        _isNaviTail = flag;
        _segment[flag ? 'addClass' : 'removeClass']('ui-pathnavi-navitail');
    };
    _this.isNaviHead = function(){
        return _isNaviHead;
    };
    _this.isNaviTail = function(){
        return _isNaviTail;
    };
    _this.setNaviLookBack = function(flag){
        _isNaviLookBack = flag;
        _segment[flag ? 'addClass' : 'removeClass']('ui-pathnavi-navilookback');
    };
    _this.isNaviLookBack = function(){
        return _isNaviLookBack;
    };
    _this.setHasChild = function(flag){
        _hasChild = flag;
        _segment[flag ? 'addClass' : 'removeClass']('ui-pathnavi-haschild');
    };
    _this.hasChild = function(){
        return _hasChild;
    };
    _this.getSegment = function(){
        return _segment;
    };
    _this.getEntry = function(){
        return _entry;
    };
    _this.getArrow = function(){
        return _arrow;
    };
    _this.getPath = function(){
        return path;
    };
    _this.active = _active;
    _this.unactive = _unactive;
    _this.isActived = _isActived;
}

/**
 * PathNavi
 *  navi = new PathNavi(width, url, events);
 *  $(selector).append(navi.getWrapper());
 *  navi.setPath(path);
 *
 * @param context jQuery
 * @param path    String
 * @constructor PathNavi
 */
function PathNavi(place, width, loader, selected){
    var _this = this, _wrapper = $('<div class="ui-pathnavi">' +
        '<div class="ui-pathnavi-upward"></div>'+
        '<div class="ui-pathnavi-route"></div>'+
        '<div class="ui-pathnavi-refresh"></div>'+
     '</div>').prependTo(place),
     _upward = _wrapper.find('.ui-pathnavi-upward'),
     _route = _wrapper.find('.ui-pathnavi-route'),
     _refresh = _wrapper.find('.ui-pathnavi-refresh'),
     _upwardMouseDown = 0, _refreshMouseDown = 0,
    _pathDrop, _headSegment, _headSegmentElem,
    _activedSegment = null, _currentPath = null,
    _events = {}, _segmentCache = {}, _fragments = $('<div></div>');

    _route.width(width);

    _upward.bind('mousedown', function(){
        _upwardMouseDown = 1;
        _upward.addClass('ui-pathnavi-mousedown');
    }).bind('mouseup', function(){
        if (_upwardMouseDown) {
            _upwardMouseDown = 0;
            _upward.removeClass('ui-pathnavi-mousedown');
            _currentPath && _this.triggerSelected(_currentPath.replace(R_PATHINFO, '$1'));
        }
    }).bind('mouseout', function(){
        _upwardMouseDown = 0;
        _upward.removeClass('ui-pathnavi-mousedown');
    });
    _refresh.bind('mousedown', function(){
        _refreshMouseDown = 1;
        _refresh.addClass('ui-pathnavi-mousedown');
    }).bind('mouseup', function(){
        if (_refreshMouseDown) {
            _refreshMouseDown = 0;
            _refresh.removeClass('ui-pathnavi-mousedown');
            _currentPath != null && _this.triggerSelected(_currentPath);
        }
    }).bind('mouseout', function(){
        _refreshMouseDown = 0;
        _refresh.removeClass('ui-pathnavi-mousedown');
    });

    _this.loader = loader;

    _this.setPath = function(path){
        if (path == _currentPath) return;
        _currentPath = path ? path : '';
        var _w = _headSegmentElem.outerWidth(true);
        _headSegmentElem.nextAll().appendTo(_fragments);
        _headSegment.setNaviLookBack(0);
        _PATH_CACHE[''] = null;
        if (!path) return;
        var slices = path.split('/'), count = slices.length, i = 0,
            p, t, s, w;
        while (slices.length) {
            p = slices.join('/');
            if (p in _segmentCache) {
                t = _segmentCache[p];
            } else {
                t = new PathSegment(_this, p);
                _segmentCache[p] = t;
            }
            s = t.getSegment();
            _headSegmentElem.after(s);
            t.setHasChild(1);
            w = s.outerWidth();
            if (w + _w > width) {
                _fragments.append(s);
                break;
            }
            _w += w;
            t.setNaviTail(i++ == 0);
            slices.pop();
        }

        if (i < count) {
            _headSegment.setNaviLookBack(1);
            var data = [];
            while (slices.length) {
                data.push(slices.join('/'));
                slices.pop();
            }
            _PATH_CACHE[''] = {
                time:0,
                data:data
            };
        }
    };
    _this.triggerSelected = function(path){
        selected && selected.call(_this, path);
    };
    _this.getWrapper = function(){
        return _wrapper;
    };
    _this.getPathDrop = function(){
        return _pathDrop;
    };
    _this.getActivedSegment = function(){
        return _activedSegment;
    };
    _this.setActivedSegment = function(segment){
        _activedSegment = segment;
    };
    _this.isPositionFixed = function(){
        return isPositionFixed(_wrapper);
    };
    _pathDrop = new PathDrop(_this);
    _headSegment = new PathSegment(_this, '');
    _headSegmentElem = _headSegment.getSegment().appendTo(_route);
    _headSegment.setNaviHead(1);
    _headSegment.setHasChild(1);
}


var R_INVILID_FILE = /[\\\/:*?"<>|]|^\./,
    R_EXT_IMAGE = /^(?:jpg|jpeg|png|gif|bmp)$/i,
    R_EXT_TXT = /^(css|html|htm|xml|js|txt)$/i,
    OPTIONS = {
        width:600,
        height:450,
        baseUrl:'?app=special&controller=resource&action=%a&path=%p',
        allowViewExt:'css|js|html|txt|xml|jpg|png|gif|jpeg:Web 文档;css|js|html|txt|xml:文本文件;jpg|png|gif|jpeg:图像文件;*:所有文件',
        allowUploadExt:'css|js|html|txt|xml|jpg|png|gif|jpeg',
        allowSelectExt:'*',
        allowSelectDir:0,
        multi:0,
        events:null
    }, _OVERLAY;
function stringToRegExp(str){
    return new RegExp('^(?:'+str.replace(/\*/g, '.*').replace(/\s+/g,'')+')$','i')
}
function showOverlay(){
	if (_OVERLAY) {
	    _OVERLAY.show();
		return;
	}
	_OVERLAY = $('<div class="ui-finder-overlay"></div>').appendTo(document.body);
}
function hideOverlay(){
    _OVERLAY.hide();
}
function createContextMenu(menus){
    var menu = $('<div class="ui-finder-contextmenu"></div>');
    for (var action in menus) {
        menu.append('<a class="ui-finder-contextmenuitem" action="'+action+'" hideFocus >'+menus[action]+'</a>');
    }
    menu.appendTo(document.body);
    menu.click(function(e){
        var action = $(e.target).attr('action'), context = menu.data('context');
        action && context && context.triggerHandler('contextmenuclick', [action]);
        menu.blur();
    }).bind('contextmenu', function(){return false;});
    return menu;
}
function dropMenu(context, menu, x, y){
    var lastContext = menu.data('context');
    lastContext !== context && menu.blur();
    menu.data('context', context);
    menu.css({
        visibility:'hidden',
        display:'block'
    });
    var mH = menu[0].offsetHeight, mW = menu[0].offsetWidth,
        clientHeight = window.innerHeight || docElem.clientHeight,
        clientWidth = window.innerWidth || docElem.clientWidth;
    if ((clientHeight / 2) < y) {
        y = y - mH;
    }
    if ((clientWidth / 2) < x) {
        x = x - mW;
    }

    menu.css({
        top: y < 0 ? 0 : y,
        left: x < 0 ? 0 : x,
        visibility:'visible'
    });
    context.triggerHandler('contextmenufocus',[menu]);

    var blurMenu = function(){
        $doc.unbind('mousedown.contextmenu');
        menu.hide();
        context.triggerHandler('contextmenublur');
        menu.data('context') === context && menu.data('context', null);
        return false;
    };
    var iBlur = function(e){
        contains(menu[0], e.target) || blurMenu();
    };
    menu.blur(blurMenu);
    setTimeout(function(){
        $doc.bind('mousedown.contextmenu', iBlur);
    }, 0);
}
function contextMenu(context, menu){
    var trigger = context.children('.ui-finder-contextmenutrigger').click(function(){
        var pos = trigger.offset();
        dropMenu(context, menu,
            pos.left - ('pageXOffset' in window ? window.pageXOffset : docElem.scrollLeft),
            pos.top - ('pageYOffset' in window ? window.pageYOffset : docElem.scrollTop));
        return false;
    });
}

function createPreview(url){
    return function(path, success, error){
        var img = new Image(), iv = setTimeout(function(){
            _complete(0);
        }, 5000);
        function _complete(state){
            img.onload = img.onerror = null;
            clearTimeout(iv);
            if (state) {
                success && success(img);
            } else {
                img = null;
                error && error();
            }
        }
	    img.onload = function(){_complete(1)};
	    img.onerror = function(){_complete(0)};
	    img.src = url.replace('%p', encodeURIComponent(path)) + (url.indexOf('?') > -1 ? '&' : '?') + Math.random(9);
    };
}

function getExt(ext){
    return R_EXT_IMAGE.test(ext)
        ? 'image'
        : R_EXT_TXT.test(ext) ? (ext = ext.toLowerCase(), ext == 'htm' ? 'html' : ext) : 'file';
}

function FileProgress(file, cancel){
    var R_FILE_EXTENSION = /\.(^[\.]+)$/;
    var item = $('<div class="ui-finder-fileitem ui-finder-uploader" title="'+file.name+'">'+
            '<div class="ui-finder-cancel"></div>'+
            '<div class="ui-finder-icon ui-finder-icon-'+getExt((R_FILE_EXTENSION.exec(file.name) || {1:''})[1])+'"></div>'+
            '<div class="ui-finder-title">'+natsub(file.name, 6)+'</div>'+
            '<div class="ui-finder-uploadinfo">'+
                '<div class="ui-finder-progress">'+
                    '<div class="ui-finder-progressbar"></div>'+
                '</div>'+
            '</div>'+
        '</div>'), info = item.find('.ui-finder-uploadinfo'), bar = item.find('.ui-finder-progressbar');
    item.find('.ui-finder-cancel').click(function(){
        cancel(file.id);
    });
    this.getItem = function(){
        return item;
    };
    this.update = function(percentage){
		bar.css('width', percentage.toFixed() + '%');
    };
    this.setError = function(){
        item.addClass('ui-finder-error');
        info.empty();
        setTimeout(function(){
            item.fadeOut(function(){item.remove();});
        }, 2000);
    };
}
/**
 * Class Finder
 *
 * @param baseUrl '?app=special&controller=resource&action=%a&path=%p'
 * @param allowViewExt 'css|js|html|txt|xml|jpg|png|gif|jpeg:Web 文档;css|js|html|txt|xml:文本文件;jpg|png|gif|jpeg:图像文件;*:所有文件'
 * @param allowSelectExt 'css|js'
 * @param allowSelectDir boolean
 * @param multi
 * @constructor Finder
 */
function Finder(options){
    var _this = this,
        _options = $.extend({}, OPTIONS, options||{}),
        _baseUrl = _options.baseUrl || '';

        /* loader functions */
    _this._readDir     = createLoader(_baseUrl.replace('%a', 'readDir'));
    _this._createDir   = createLoader(_baseUrl.replace('%a', 'createDir'), 'POST');
    _this._editFile    = createLoader(_baseUrl.replace('%a', 'editFile'), 'POST');
    _this._renameEntry = createLoader(_baseUrl.replace('%a', 'rename'), 'POST');
    _this._removeEntry = createLoader(_baseUrl.replace('%a', 'remove'));
    _this._imagePreview = createPreview(_baseUrl.replace('%a', 'preview'));

    _this._baseUrl = _baseUrl;

    _this._wrapper = $('<div class="ui-finder-wrapper"></div>');
    _this._finder = $('<div class="ui-finder">' +
        '<div class="ui-finder-header" role="HEADER">' +
            '<div class="ui-finder-area" role="CTRL_AREA">' +
                '<a class="ui-finder-button" role="MKDIR">建目录</a>'+
                '<a class="ui-finder-button" role="UPLOAD">上传</a>'+
            '</div>'+
        '</div>'+
        '<div class="ui-finder-center" role="CENTER">' +
            '<div class="ui-finder-inner" role="INNER"></div>'+
        '</div>'+
        '<div class="ui-finder-footer" role="FOOTER">' +
            '<a class="ui-finder-button ui-finder-button-light" role="POST">确定</a>'+
            '<a class="ui-finder-button" role="CANCEL">取消</a>'+
            '<a class="ui-finder-button" role="RESET">重选</a>'+
        '</div>'+
    '</div>').appendTo(_this._wrapper);
    _this._finder.find('*').each(function(){
        var t = $(this);
        _this['_finderPart'+t.attr('role')] = t;
    });
    _this._menus = {
        ROOT : {
            mkdir:'新建目录'
        }, FOLDER : {
            open:'打开',
            rename:'重命名',
            remove:'删除'
        }, FILE : {
            open:'打开',
            edit:'编辑',
            rename:'重命名',
            remove:'删除'
        }
    };

    _this._editors = {};
    _this._width = _options.width;
    _this._height = _options.height;
    _this._finder.css({
        width:_this._width,
        height:_this._height
    });
    _this._multi = _options.multi;

    _this._focusedItem = null;
    _this._currrentPath  = _options.path || '';
    _this._currentData = [];
    _this._checkedData = {};
    _this._currentFilter = {};
    _this._selectableExt = stringToRegExp(_options.allowSelectExt || '*');
    _this._selectableDir = _options.allowSelectDir;
    _this._viewableExt = /.?/;
    _this._uploadExt = '*.*';
    _this._uploadDesc = '所有文件';
    if (_options.allowViewExt) {
        var ext = _options.allowViewExt.split(';')[0];
        ext = ext.split(':');
        _this._currentFilter.ext = ext[0] || '*';
        _this._viewableExt = stringToRegExp(_this._currentFilter.ext);
        _this._uploadDesc = ext[1] || '';
        _this._uploadExt = [];
        $.each(ext[0].split('|'), function(i,t){
            _this._uploadExt.push('*.'+t);
        });
        _this._uploadExt = _this._uploadExt.join(';');
    }
    _this._events = {};
    _options.events && _this.bind(_options.events);
    _this._uploader = null;
    _this._inited = 0;
}
Finder.prototype = {
    /********************************** private methods *******************************/
    _initStep1:function(){
        var _this = this,
            _header = _this._finderPartHEADER,
            _center = _this._finderPartCENTER,
            _inner = _this._finderPartINNER;
        if (_this._inited > 0) return;
        _this._inited = 1;

        _this._wrapper.css({
            visibility:'hidden',
            display:'block'
        }).appendTo(document.body);
        _this._wrapper.css('marginLeft', -_this._wrapper.outerWidth()/2);

        _this._finder.bind('contextmenu', function(){return false;});

        _this._navi = new PathNavi(_header,
            _this._width - _this._finderPartCTRL_AREA.outerWidth() - 60,
            createLoader(_this._baseUrl.replace('%a', 'subDir')), function(path){
                _this._openPath(path);
            }
        );

        _center.bind('onmousewheel' in document ? 'mousewheel' : 'DOMMouseScroll', function(e){
            var d = e.wheelDelta || -e.detail;
            if (d > 0) {
                if (_center.scrollTop() <= 0) {
                    return false;
                }
            } else if (_inner.outerHeight() - _center.scrollTop() <= _center.height()) {
                return false;
            }
        }).height(_this._height - _header.outerHeight() - _this._finderPartFOOTER.outerHeight());

    },
    _initStep2:function(){
        var _this = this,
            _center = _this._finderPartCENTER,
            _inner = _this._finderPartINNER;
        if (_this._inited > 1) return;
        _this._inited = 2;

        _inner.bind('mousedown', function(e){
            _this._focusedItem && !contains(_this._focusedItem[0], e.target) && _this._focusItem(null);
        });

        _this._finderPartMKDIR.click(function(){
            _this._mkdir();
        });
        _this._finderPartPOST.click(function(){
            _this._post();
        });
        _this._finderPartCANCEL.click(function(){
            _this._hide();
        });
        _this._finderPartRESET.click(function(){
            _this.reset();
        });


        var _inProgress = {}, _infos = [], _errorCount = 0;
        function formatInfos(){
            $.each(_infos, function(i, t){
                 _infos[i] = '上传 <u>'+t.file+'</u>'+
                    (t.state ? '<b style="color:green">成功</b>' : '<b style="color:red">失败</b>')+
                    (t.text ? '<em>'+ t.text+'</em>' : '');
            });
        }
        _this._uploader = new Uploader(_this._finderPartUPLOAD[0], {
            fieldName:'Filedata',
            sizeLimit:10240000,// 10M
            fileExt:_this._uploadExt,
            fileDesc:_this._uploadDesc,
            jsonType:1,
            script:_this._baseUrl.replace('%a', 'upload').replace('%p', ''),
            selectStart:function(){
                _this._uploader.setParam('path', _this._currrentPath || '/');
            },
            selectOne:function(data){
                var p = new FileProgress(data.file, function(id){
                    _this._uploader.cancel(id);
                }), item = p.getItem();
                _inProgress[data.ID] = p;
                _inner.append(item);
                scrollIntoView(_center, item);
            },
            queueStart:function(){
                _infos = [];
                _errorCount = 0;
            },
            queueComplete:function(){
                if (_errorCount) {
                    formatInfos();
                    _this._finder.message('detail', '上传文件结束，失败：'+_errorCount+'，详细信息请点击', _infos);
                }
                _infos = [];
                _errorCount = 0;
            },
            uploadProgress:function(data){
                data.ID in _inProgress && _inProgress[data.ID].update(data.percentage);
            },
            uploadComplete:function(json, data){
                if (data.ID in _inProgress) {
                    var p = _inProgress[data.ID], info = {state:1, file:data.file.name};
                    delete _inProgress[data.ID];
                    _infos.push(info);
                    if (!json || !json.state) {
                        info.state = 0;
                        info.text = json && json.error ? json.error : '响应异常';
                        _errorCount++;
                        p.setError();
                        return;
                    }
                    p.getItem().after(_this._createItem(json.data, 2)).remove();
                }
            },
            uploadCancel:function(data){
                if (data.ID in _inProgress) {
                    _inProgress[data.ID].getItem().remove();
                    delete _inProgress[data.ID];
                }
            },
            error:function(data){
                if (data.ID) {
                    _infos.push({
                        state:0,
                        file:data.file.name,
                        text:data.type+':'+data.info
                    });
                    _errorCount++;
                    if (data.ID in _inProgress) {
                        _inProgress[data.ID].setError();
                        delete _inProgress[data.ID];
                    }
                }
            },
            auto: 1,
            multi: 1,
            plugin:''
        });

        // 延期处理
        // viewModeButton extFilterSelect searchFilterInput

        _this._trigger('init');
    },
    _init:function(step){
        var _this = this;
        _this._inited < step && _this['_initStep'+step]();
    },
    _focusItem:function(item, data){
        var _this = this;
        _this._checkedData = {};
        _this._focusedItem && _this._focusedItem.removeClass('ui-finder-checked');
        _this._focusedItem = item;
        item && item.addClass('ui-finder-checked');
        if (data) {
            _this._checkedData[data.path] = data;
        }
    },
    _toggleItem:function(item, data){
        if (item.hasClass('ui-finder-checked')) {
            item.removeClass('ui-finder-checked');
            delete this._checkedData[data.path];
        } else {
            item.addClass('ui-finder-checked');
            this._checkedData[data.path] = data;
        }
    },
    _checkItem:function(item, data){
        this[this._multi ? '_toggleItem' : '_focusItem'](item, data);
    },
    _getMenu:function(name){
        var _this = this;
        if (!_this._menus[name].jquery) {
            _this._menus[name] = createContextMenu(_this._menus[name]);
        }
        return _this._menus[name];
    },
    _getEditor:function(ext){
        ext = ext.toLowerCase();
        return this._editors[ext];
    },
    _mkdir:function(){
        var _this = this,
            item = $('<div class="ui-finder-fileitem ui-finder-rename" title="新目录">'+
                '<input class="ui-finder-titleinput" type="text" value="新目录" />'+
                '<div class="ui-finder-icon ui-finder-icon-dir"></div>'+
                '<div class="ui-finder-title">新目录</div>'+
            '</div>').appendTo(_this._finderPartINNER),
            input = item.find('.ui-finder-titleinput'), title = item.find('.ui-finder-title');

        scrollIntoView(_this._finderPartCENTER, item);

        function submitCreate(){
            if (!input[0].offsetWidth) return;
            var val = trim(input.val());
            if (!val) {
                item.remove();
                return;
            }
            if (R_INVILID_FILE.test(val)) {
                _this._finder.message('fail', '文件名不合法');
                item.remove();
                return;
            }
            title.text(val);
            item.removeClass('ui-finder-rename');
            _this._createDir(_this._currrentPath, {name:val}, function(json){
                if (json.state) {
                    _this._currentData.push(json.data);
                    item.after(_this._createItem(json.data)).remove();
                } else {
                    _this._finder.message('fail', json.error);
                    item.remove();
                }
            }, null, function(){
                _this._finder.message('fail', '新建目录失败，响应异常');
                item.remove();
            });
        }

        input.focus().select().blur(submitCreate).keypress(function(e){
            e.keyCode === 13 && submitCreate();
        });
    },
    _createItem:function(data, state){
        var _this = this,
            item = $('<div class="ui-finder-fileitem" title="'+data.entry+'">' +
                '<div class="ui-finder-checkicon"></div>'+
                '<div class="ui-finder-contextmenutrigger"></div>'+
                '<input class="ui-finder-titleinput" type="text" />'+
                '<div class="ui-finder-icon"></div>'+
                '<div class="ui-finder-title">'+natsub(data.entry, 6)+'</div>' +
            '</div>'),
            icon = item.find('.ui-finder-icon'),
            input = item.find('.ui-finder-titleinput'),
            title = item.find('.ui-finder-title'),
            isdir = data.isdir,
            selectable = isdir ? _this._selectableDir : _this._selectableExt.test(data.ext),
            editor = !isdir && _this._getEditor(data.ext);

        function renameAction(json){
            if (json.state) {
                var checked = data.path in _this._checkedData;
                if (checked) {
                    delete _this._checkedData[data.path];
                }
                $.extend(data, json.data);
                if (!isdir && !_this._viewableExt.test(data.ext)) {
                    var index = _this._currentData.indexOf(data);
                    index > -1 && _this._currentData.splice(index, 1);
                    if (_this._focusedItem === item) {
                        _this._focusedItem = null;
                    }
                    item.remove();
                    _this._finder.message('info', '重命名成功，但不可见');
                    return;
                }
                if (checked) {
                    _this._checkedData[data.path] = data;
                }
                var newItem = _this._createItem(data, 1);
                if (_this._focusedItem === item) {
                    _this._focusItem(newItem, data);
                } else if (item.hasClass('ui-finder-checked')) {
                    newItem.addClass('ui-finder-checked');
                }
                item.after(newItem).remove();
            } else {
                _this._finder.message('fail', json.error);
            }
        }
        function removeAction(json){
            if (json.state) {
                var index = _this._currentData.indexOf(data);
                index > -1 && _this._currentData.splice(index, 1);

                if (data.path in _this._checkedData) {
                    delete _this._checkedData[data.path];
                }

                if (_this._focusedItem === item) {
                    _this._focusedItem = null;
                }

                item.fadeOut(function(){
                    item.remove();
                });
            } else {
                _this._finder.message('fail', json.error);
            }
        }
        function submitRename(){
            if (!input[0].offsetWidth) return;
            item.removeClass('ui-finder-rename');
            var val = trim(input.val());
            if (!val || data.entry === val) {
                return;
            }
            if (R_INVILID_FILE.test(val)) {
                _this._finder.message('fail', '文件名不合法');
                return;
            }
            _this._renameEntry(data.path, {name:val}, renameAction, null, function(){
                _this._finder.message('fail', '重命名失败，响应异常');
            });
        }
        function rename(){
            var val = data.entry, i = input[0], end = val.lastIndexOf('.'), len = val.length;
            item.addClass('ui-finder-rename');
            i.value = val;
            i.focus();
            i.select();
            if (isdir || end < 0 || end == len - 1) return;
            if (typeof i.setSelectionRange === 'function') {
                i.setSelectionRange(0, end);
            } else {
                var s = document.selection.createRange();
                s.moveEnd('character', end - len);
                s.select();
            }
        }
        function remove(){
            _this._finder.message('confirm', '确定要删除“'+data.entry+'”吗', function(){
                _this._removeEntry(data.path, null, removeAction, null, function(){
                    _this._finder.message('fail', '删除失败，响应异常');
                });
            });
        }

        input.blur(submitRename).keypress(function(e){
            e.keyCode === 13 && submitRename();
        }).click(function(){return false;});

        item.bind('contextmenufocus', function(e, menu){
            item.addClass('ui-finder-mousedown');
            if (!isdir) {
                var a = menu.find('[action="open"]');
                a.attr('href', data.url ? data.url : 'javascript:;');
                a.attr('target', '_blank');
            }
            menu.find('[action="edit"]')[editor ? 'removeClass' : 'addClass']('ui-finder-disabled');
        }).bind('contextmenublur', function(){
            item.removeClass('ui-finder-mousedown');
        }).bind('contextmenuclick', function(e, action){
            if (action == 'edit') {
                editor && editor.call(_this, data, item);
            } else if (action == 'open') {
                isdir && _this._openPath(data.path);
            } else if (action == 'rename') {
                rename();
            } else if (action == 'remove') {
                remove();
            }
        }).dblclick(function(){
            if (isdir) {
                _this._openPath(data.path);
            } else if (selectable && !_this._multi) {
                _this._checkItem(item, data);
                _this._post();
            }
        });
        if (isdir) {
            icon.addClass('ui-finder-icon-dir');
            contextMenu(item, _this._getMenu('FOLDER'));
        } else {
            var ext = getExt(data.ext);
            icon.addClass('ui-finder-icon-' + ext);
            if (ext == 'image') {
                icon.attr('delayimage', data.path);
                state && _this._previewImage(icon);
            }
            contextMenu(item, _this._getMenu('FILE'));
        }
        if (selectable) {
            item.click(function(){
                _this._checkItem(item, data);
            });
            (state == 2 || (data.path in _this._checkedData)) && _this._checkItem(item, data);
        }
        return item;
    },
    _loadData:function(path){
        var _this = this, _inner = _this._finderPartINNER;
        _this._focusedItem  = null;
        _this._currentData  = null;
        _this._currrentPath = null;
        try {
            _this._uploader && _this._uploader.clear();
        } catch(e){}
        _inner.html(spinning());
        _this._readDir(path, _this._currentFilter, function(json){
            _inner.empty();
            _this._currentData = json.data;
            _this._currrentPath = path;
            json.state && $.each(json.data, function(i, data){
                _this._createItem(data).appendTo(_inner);
            });
            _this._finderPartCENTER.unbind('scroll.delaypreview').bind('scroll.delaypreview', function(){
                _this._loadViewPortImage();
            });
            _this._loadViewPortImage();
            _this._init(2);
        }, null, function(){
            _inner.empty();
            _this._finder.message('fail', '加载数据失败，响应异常');
        });
    },
    _previewImage:function(icon){
        var imgHeight = 60, imgWidth = 60, path = icon.attr('delayimage');
        icon.removeAttr('delayimage');
        this._imagePreview(path, function(t){
            if (t.height > imgHeight || t.width > imgWidth) {
                t.height/imgHeight < t.width/imgWidth
                    ? (t.width = imgWidth)
                    : (t.height = imgHeight);
            }
            icon.append('<i></i>');
            icon.append(t);
            icon.removeClass('ui-finder-icon-image');
        });
    },
    _loadViewPortImage:function(){
        var _this = this, _center = _this._finderPartCENTER, _inner = _this._finderPartINNER,
            T = _center.offset().top, H = _center.height(), items = _inner.find('[delayimage]');
        if (!items.length) {
            _center.unbind('scroll.delaypreview');
            return;
        }
        items.each(function(){
            var icon = $(this), t = icon.offset().top + icon[0].offsetHeight / 2;
            if (t >= T && t <= T + H) {
                _this._previewImage(icon);
            }
        });
    },
    _trigger:function(event, args){
        var _this = this;
        if (event in _this._events) {
            var evts = _this._events[event];
            for (var i=0,fn; (fn = evts[i++]) && false !== fn.apply(_this, args||[]);){}
        }
    },
    _post:function(){
        var _this = this, checked = [], _checkedData = _this._checkedData;
        for (var k in _checkedData) {
            _checkedData[k] && checked.push(_checkedData[k]);
        }
        if (!checked.length) {
            _this._finder.message('warn', '没有项目被选择');
            return;
        }
        _this._trigger('post', [checked]);
        _this._hide();
    },
    _hide:function(){
        this._trigger('hide');
        this._wrapper.hide();
        hideOverlay();
    },
    _openPath:function(path){
        var _this = this;
        _this._navi.setPath(path);
        _this._loadData(path);
        return _this;
    },

    /********************************** public methods *******************************/

    setEditor:function(editor, extensions){
        var _this = this;
        $.each(extensions.split('|'), function(i, k){
            if (k) {
                _this._editors[k.toLowerCase()] =  editor;
            }
        });
        return _this;
    },
    bind:function(event, fn){
        var _this = this;
        if (typeof event !== 'string') {
            $.each(event, function(k,f){_this.bind(k,f)});
            return _this;
        }
        if (!(event in _this._events)) {
            _this._events[event] = [];
        }
        _this._events[event].push(fn);
        return _this;

    },
    open:function(path){
        var _this = this;
        _this._init(1);
        _this._openPath(path||'');
        showOverlay();
        _this._wrapper.css({
            display:'block',
            visibility:'visible'
        });
    },
    check:function(path){
        var _this = this;
        _this.reset();
        var m = R_PATHINFO.exec(path);
        if (m[2]) {
            _this._checkedData[path] = 0;
        }
        _this.open(m[1] || '');
    },
    reset:function(){
        var _this = this;
        _this._finderPartINNER.children('.ui-finder-checked').removeClass('ui-finder-checked');
        _this._focusedItem = null;
        _this._checkedData = {};
        return _this;
    }
};

window.Finder = Finder;

})(window);