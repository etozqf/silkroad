var Company = (function($){
    var Company = {
        PAGE : 1,
        QUERYING : false,
        TYPE : '',
        AJAX : '',
        PAGEMOD : {
            shownum : 10
        },
        _clickMenu : function(e) {
            var that = this,
                self = $(e.target).get(0).tagName == 'LI' ? $(e.target) : $(e.target).closest('li');
            that.TYPE = self.attr('data-type');
            self.addClass('curr').siblings().removeClass('curr');
            that.PAGE = 1;
	    window.location.hash = that.TYPE;
            if(that.AJAX) {
                that.AJAX.abort();
            }
            that._queryData();
        },

        _queryData : function() {
            var that = this;
            if(that.QUERYING) return;
            that.AJAX = $.ajax({
                type : 'get',
                url : APP_URL+'?app=system&controller=company&action=getdata',
                data : {'type':that.TYPE, 'page':that.PAGE},
                dataType : 'jsonp',
                success : function (s) {
                    that.QUERYING = false;
                    if(s.error) {
                        alert('Data request failed, please try again!');
                    } else {
                        that.DATA = s.data;
                        that._createList();
                        that._createPage();
                    }
                },
                error : function () {
                    that.QUERYING = false;
                    alert('Data request failed, please try again!');
                }
            });
        },

        _createList : function () {
            var that = this;
            $('#list').empty();
            if(!that.DATA.list.length) {
                $('#list').html('<li class="nocontent">No relevant content</li>');
                return;
            }
            var html = '';
            $.each(that.DATA.list, function(i) {
                html += '<li><h3><a href="'+that.DATA.list[i].url+'" target="_blank">'+that.DATA.list[i].title+'</a></h3><span>'+that.DATA.list[i].published+'</span></li>';
            });
            $('#list').html(html);
        },

        _createPage : function() {
            var that = this;
            if(that.DATA.total <= that.DATA.length) {
                $('.pagemod').empty();
                return;
            }
            that.PAGEMOD.maxpage = Math.ceil(that.DATA.total / that.DATA.length);
            that.PAGEMOD.current = that.DATA.current;
            var first = 0, end = 0, prev = 0, next = 0;
            if(that.PAGEMOD.maxpage == 1) {
                $('.pagemod').empty();
                return;
            }
            if(that.PAGEMOD.shownum > that.PAGEMOD.maxpage) {
                that.PAGEMOD.from = 1;
                that.PAGEMOD.to = that.PAGEMOD.maxpage;
            } else {
                that.PAGEMOD.from = that.PAGEMOD.current - 5;
                that.PAGEMOD.to = that.PAGEMOD.from + that.PAGEMOD.shownum - 1;
                if(that.PAGEMOD.from < 1) {
                    that.PAGEMOD.to = that.PAGEMOD.current + 1 - that.PAGEMOD.from;
                    that.PAGEMOD.from = 1;
                    if(that.PAGEMOD.to - that.PAGEMOD.from < that.PAGEMOD.shownum) {
                        that.PAGEMOD.to = that.PAGEMOD.shownum;
                    }
                } else if(that.PAGEMOD.to > that.PAGEMOD.maxpage) {
                    that.PAGEMOD.from = that.PAGEMOD.maxpage - that.PAGEMOD.shownum + 1;
                    that.PAGEMOD.to = that.PAGEMOD.maxpage;
                }
            }
            var pagearr = [];
            for(var i=that.PAGEMOD.from; i<=that.PAGEMOD.to; i++) {
                pagearr.push(i);
            }
            if($.inArray(1, pagearr) == -1) {
                first = 1;
            }
            if($.inArray(that.PAGEMOD.maxpage, pagearr) == -1) {
                end = 1;
            }
            if(that.PAGEMOD.current > 1) {
                prev = 1;
            }
            if(that.PAGEMOD.current < that.PAGEMOD.maxpage) {
                next = 1;
            }

            var pageHtml = '<ul>';
            if(prev) {
                pageHtml += '<li><a href="javascript:;"  class="pre">&lt</a></li>';
            }
            if(first) {
                pageHtml += '<li><a href="javascript:;"  class="first">First</a></li>';
            }
            $.each(pagearr, function(i){
                var iscurrent = pagearr[i] == that.PAGEMOD.current ? 'on' : '';
                pageHtml += '<li><a href="javascript:;"  class="number '+iscurrent+'">'+pagearr[i]+'</a></li>';
            });
            if(end) {
                pageHtml += '<li><a href="javascript:;"  class="end">End</a></li>';
            }
            if(next) {
                pageHtml += '<li><a href="javascript:;"  class="next">&gt</a></li>';
            }
            pageHtml += '</ul>';
            $('.pagemod').html(pageHtml);
        },

        _clickPage : function (e) {
            e.stopPropagation();
            var that = this,
                self = $(e.target).get(0).tagName == 'A' ? $(e.target) : $(e.target).closest('a');
            if(self.hasClass('on')) {
                return;
            }
            if(self.hasClass('pre')) {
                this.PAGE--;
            } else if(self.hasClass('next')) {
                this.PAGE++;
            } else if(self.hasClass('number')) {
                this.PAGE = parseInt(self.text());
            } else if(self.hasClass('first')) {
                this.PAGE = 1;
            } else if(self.hasClass('end')) {
                this.PAGE = this.PAGEMOD.maxpage;
            }
            this.PAGE = this.PAGE < 1 ? 1 : this.PAGE;
            this.PAGE = this.PAGE > this.PAGEMOD.maxpage ? this.PAGEMOD.maxpage : this.PAGE;
            that._queryData();
        },

        init : function() {
            $('#leftmenus').on('click', 'li', this._clickMenu.bind(this));
            $('.pagemod').delegate('a', 'click', this._clickPage.bind(this));

            var hashs = [];
            $('#leftmenus li').each(function(){
                hashs.push($(this).attr('data-type'));
            });
            var hash = window.location.hash;
            this.hash = hash.replace('#', '');
            if(this.hash && $.inArray(this.hash, hashs) != -1) {
                $('#leftmenus').find('[data-type="'+this.hash+'"]').trigger('click');
            } else {
                $('#leftmenus').find('li:first').trigger('click');
            }
    	    $('[data-from="companies"]').on('click', 'a', function(){
                var url = $(this).attr('href');
                param = url.split('#')[1];
                if(typeof param != 'undefined' && param) {
                    $('#leftmenus').find('[data-type="'+param+'"]').trigger('click');
                }
            });
	    $('[data-from="companies"]').closest('li').css('background', '#1a7da2').siblings('li').removeClass('curr');
        }
    };

    return Company;

})(jQuery);
