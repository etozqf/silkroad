var digg = {
    done : [],
    data : null,
    get  : function(contentid, obj){
    },
    set: function(contentid, obj){
        var t = this;
        if(t.done[contentid]) {
            alert('您已经顶过了');
            return;
        }
        $.getJSON(APP_URL+'?app=digg&controller=digg&action=digg&contentid='+contentid+'&jsoncallback=?&flag=1', function(data){
            if(data > 0) {
                t.done[contentid] = true;
                var diggtime;
                t.render(data, obj);
            } else {
                alert('您已经顶过了');
                return;
            }
        });
    },
    render: function(v, obj){
            var t = this;
            s = parseInt(v);
            $('#'+obj).html(s);
    }
};

(function () {
    // use strict;
    var dateMenu = $('#date-menu'),
        tabContent = $('#tabcontent');

    dateMenu
        .on('mouseenter', 'a', function (event) {
            var a = $(event.target);
            dateMenu.find('.now').removeClass('now');
            a.addClass('now');
            tabContent.children('.tab').hide().eq(dateMenu.children('a').index(a)).show();
        });
}());