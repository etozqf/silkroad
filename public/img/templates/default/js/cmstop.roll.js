$.fn.isnot = function(filter, is, not){
    return this.each(function(i){
        (typeof filter == 'function'
            ? filter.call(this)
            : ($.multiFilter(filter, [this]).length > 0)
        ) ? (is && is.apply(this, [i]))
          : (not && not.apply(this, [i]));
    });
};
(function(){
var _channel,_size,_page,_auto,_date,
    channels,pagesizes,intervals,
    maxTimer = 180,
    auto, autoOn, autoOff, timer, autoIval, least,
    pager, pagination, dataContainer, condDate,
_where = function(){
    var where = [];
    _channel && where.push('channel='+_channel);
    _date && where.push('date='+encodeURIComponent(_date));
    _size && where.push('size='+_size);
    _page && where.push('page='+_page);
    return where.join('&');
},
_fillData = function(data){
    var html = [];
    var k = 0;
    html[k++] = ['<ul>'];
    for(var i=0,l=data.length;i<l;i++)
    {
        var d = data[i];
        html[k++] = '<li class="item icon16x16"><em class="ico"></em><span class="date">'+d.date+'</span><a class="title-link" target="_blank" href="'+d.caturl+'">['+d.catname+']</a><a class="title" target="_blank" href="'+d.url+'">'+d.shorttitle+'</a></li>';
        if ((i+1)%5 == 0) {
            html[k++] = '<li class="dashed"></li>';
        }
    }
    dataContainer.html(html.join(''));
};
var APP = {
    init:function(channelid,pagesize,interval){
        _channel = parseInt((/[#&]channel=(\d*)/i.exec(location.hash)||{1:0})[1]);
        _size = parseInt((/[#&]size=(\d*)/i.exec(location.hash)||{1:30})[1]);
        _page = parseInt((/[#&]page=(\d*)/i.exec(location.hash)||{1:1})[1]);
        condDate = $('#cond-date');

        if(channelid != undefined) {
            _channel = channelid;
        }
        if(pagesize != undefined) {
            _size = pagesize;
        }
        if(interval != undefined) {
            maxTimer = interval;
        }
        
        channels = $('#data-channel>a').each(function(){
            var a = $(this);
            var ch = a.attr('href');
            _channel == ch && $.className.add(this, 'now');
            a.click(function(e){
                e.preventDefault();
                APP._channel != ch && APP.channel(ch);
            });
        });

        pagesizes = $('#data-pagesize>a').each(function(){
            var a = $(this);
            var au = a[0].getAttribute('href', 2);
            _size == au && (a.children().addClass('blue'));
            a.click(function(e){
                e.preventDefault();
                APP._pagesize != au && APP.pagesize(au);
            });
        });

        if (_date) {
            d = _date.split('-');
            condDate.text(d[0]+'年'+d[1]+'月'+d[2]+'日');
        } else {
            d = /(\d+)年(\d+)月(\d+)日/.exec(condDate.text());
            d.shift();
            _date = d.join('-');
        }

        intervals = $('#data-interval>a').each(function(){
            var a = $(this);
            var au = a[0].getAttribute('href', 2);
            maxTimer == au && (a.children().addClass('blue'));
            a.click(function(e){
                e.preventDefault();
                maxTimer != au && APP.autoTimer(au);
            });
        });

        $('#hand').click(function(){
            APP.query();
        });
        
        auto = $('#auto').click(function(){
            APP.auto(this.checked);
        });
        autoOn = $('#auto-on');
        timer = autoOn.find('b');
        autoOff = $('#auto-off');
        this.auto(true);
        pageOption = {
            callback: function(index){
                APP.page(index+1);
            },
            current_page:_page,
            items_per_page:_size,
            num_display_entries:10,
            num_edge_entries:2,
            prev_text:'&lt;',
            next_text:'&gt;'
        };
        pager = $('#pagination');
        
        dataContainer = $('#data-container');
        
        this.query();
    },
    query:function(){
        least = maxTimer;
        var where = _where();
        location.hash = where;
        
        $.getJSON(APP_URL+'roll.php?do=query&callback=?',where,
        function(json){
            pageOption.current_page = json.page-1;
            pageOption.items_per_page = json.size;
            _page = json.page;
            _size = json.size;
            location.hash = _where();
            json.total > json.size
                ? pager.pagination(json.total, pageOption)
                : pager.empty();
            _fillData(json.data);
        });
    },
    channel:function(channel){
        channels.isnot(function(){
            return this.getAttribute('href', 2) == channel;
        },function(){
            $(this).addClass('now');
        },function(){
            $(this).removeClass('now');
        });
        _channel = channel;
        this.query();
    },
    pagesize:function(size){
        pagesizes.isnot(function(){
            return $(this)[0].getAttribute('href', 2) == size;
        },function(){
            $(this).children().addClass('blue');
        },function(){
            $(this).children().removeClass('blue');
        });
        _size = size;
        this.query();
    },
    date:function(date){
        var d = date.split('-');
        condDate.text(d[0] + '年' + d[1] + '月' + d[2] + '日');
        _date = date;
        this.query();
    },
    page:function(page){
        _page = page;
        this.query();
    },
    autoTimer:function(t){
        intervals.isnot(function(){
            return $(this)[0].getAttribute('href', 2) == t;
        },function(){
            $(this).children().addClass('blue');
        },function(){
            $(this).children().removeClass('blue');
        });
        maxTimer = t;
        this.auto(true, true);
    },
    auto:function(flag, changeTimer){
        if (!changeTimer && (!flag == !autoIval)) return;
        if (flag) {
            if(autoIval){
                clearInterval(autoIval);
                autoIval = null;
            }
            least = maxTimer;
            autoOn.show();
            timer.text(least);
            autoOff.hide();
            auto[0].checked = true;
            autoIval = setInterval(function(){
                timer.text(--least);
                if (least < 1) {
                    APP.query();
                }
            }, 1000);
        } else {
            autoOn.hide();
            autoOff.show();
            auto[0].checked = false;
            clearInterval(autoIval);
            least = maxTimer;
            autoIval = null;
        }
    }
};
window.APP = APP;
})();

$(function() {
    $('#date').click(function () {
        DatePicker($('#date')[0], {
            format:'yyyy-MM-dd',
            change: function (date) {
                date && APP.date(date);
            }
        });
    });

    $('.column-main').css('min-height', $(window).height() - 464);
});