var scrollPaper = function(box, url, itemRender, pagesize, distance) {
    var _paper = $('<div class="diy-scrollpaper"></div>').appendTo(box), _where,
        _current = 0, _count = 1, _xhr, _runtime, _loading = 0;

    pagesize = pagesize || 10;
    distance = distance || 20;

    function reinit(){
        _paper.empty();
        _runtime = (new Date).getTime();
        _xhr && (_xhr.abort(), _xhr = null);
        _current = 0;
        _count = 1;
        _loading = 0;
    }
    function load(url, success, complete){
        var loadtime = _runtime;
        _xhr = $.ajax({
            type:'GET',
            dataType:'json',
            data:_where,
            url:url,
            success:function(json){
                _runtime == loadtime && success && success(json);
            },
            complete:function(){
                _runtime == loadtime && complete && complete();
            }
        });
    }
    function loadPage(){
        if (_loading) return;
        _loading = 1;
        var spin = spinning().appendTo(_paper);
        load(url.replace('%p', _current + 1).replace('%s', pagesize), function(json){
            if (_current == 0) {
                _count = json.count || Math.ceil(json.total / pagesize) || 1;
            }
            _current++;
            $.each(json.data, function(k, v){
                itemRender($('<div class="diy-scrollpaper-item"></div>').appendTo(_paper), v);
            });
        }, function(){
            _loading = 0;
            spin.remove();
        });
    }

    box.mousewheel(function(e){
        var st = box.scrollTop(), delta = e.delta, dh = _paper.outerHeight() - st - box.height();
        if ((delta > 0 && st <= 0) || (delta < 0 && dh <= 0)) {
            e.stopPropagation();
            e.preventDefault();
        }
        if (_loading || delta > 0 || dh > distance || _current >= _count) {
            return;
        }
        loadPage();
    });
    this.query = function(where){
        reinit();
        _where = where;
        loadPage();
    };
};