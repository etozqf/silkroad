var HONGG = (function($){
    var HONGG = {
        QUERYING : false,
        SCIDS : '',
        C_FIRSTINIT : false,
        D_FRISTINIT : false,
        MAXNUM : 10,
        LANGS : {
            'en' : {
                'searchtip' : '*You can hold down the Ctrl key to multiple conditions',
                'search' : 'Search',
                'all' : 'All',
                'prev' : 'Prev',
                'next' : 'Next',
                'first' : 'First',
                'end' : 'End',
                'loadfail' : 'The system is busy. Try again later',
                'checkerror' : '',
                'nodata' : 'No search to data',
                'indexcount' : '',
                'selecterror' : 'Please select the time',
                'nocontent' : 'Have not contents',
                'filename' : 'Macro data'
            },
            'cn' : {
                'searchtip' : '*条件可以按住Ctrl键进行多选',
                'search' : '查询',
                'all' : '全部',
                'prev' : '上页',
                'next' : '下页',
                'first' : '首页',
                'end' : '尾页',
                'loadfail' : '系统繁忙，稍后再试',
                'checkerror' : '复选的指标项已达到最大个数，请重新选择。',
                'nodata' : '没有搜索到数据',
                'indexcount' : '指标项',
                'selecterror' : '请选择时间',
                'nocontent' : '没有相关内容',
                'filename' : '宏观数据'
            }
        },

        _initMenus : function(e) {
            var that = this;
            that.PAGE = 1;
            that.QUERY = '';
            if(that.QUERYING) return;

            that.allowQuery = true;
            if(typeof e != 'undefined') {
                e.stopPropagation();
                var self = $(e.target).get(0).tagName != 'LI' ? $(e.target).closest('li') : $(e.target);
                that.CID = self.attr('data-cid');
                if(!self.attr('data-scid')) {
                    that.allowQuery = false;
                } else {
                    that.SCID = self.attr('data-scid');
                    that.allowQuery = true;
                }
                var level = self.closest('ul').attr('level');
                level++;
                var sonul = self.find('[level="'+level+'"]');
                if(sonul.length) {
                    if(sonul.is(':visible')) {
                        self.find('ul').hide();
                        self.find('h3').removeClass('open');
                    } else {
                        self.children('h3').addClass('open');
                        sonul.show();
                    }
                    that.QUERYING = false;
                    return;
                }
            }

            if(that.SCID && that.allowQuery) {
                that.PAGE = 1;
                that.NAME = self.children('h3').text();
                var maxnum = that.MAXNUM;
                if(self.find('a').hasClass('active')) {
                    self.find('a').removeClass('active');
                    self.find('input').prop('checked', false);
                } else {
                    if(self.find('input').prop('checked')) {
                        maxnum++;
                    }
                    if(self.closest('.yq-tab-menu1').find('[name="scid"]:checked').length >= maxnum) {
                        self.find('input').prop('checked', false);
                        alert(that.LANGS[that.LANG]['checkerror']);
                    } else {
                        self.find('a').addClass('active');
                        self.find('input').prop('checked', true);
                    }

                }
                return;
            }

            that.QUERYING = true;

            $.ajax({
                type : 'get',
                url : APP_URL+'?app=system&controller=honggdata&action=getcategory',
                data : {'cid':that.CID},
                dataType : 'jsonp',
                success : function (s) {
                    if(s.error) {
                        that.QUERYING = false;
                        console.log('getcategory load fail');
                        if(!that.C_FIRSTINIT) {
                            that.C_FIRSTINIT = true;
                            that._initMenus();
                        }
                    } else {
                        that.C_FIRSTINIT = true;
                        that.QUERYING = false;
                        that.MENUS = s.data;
                        that._createMenus();
                    }
                },
                error : function () {
                    that.QUERYING = false;
                    console.log('getcategory system error');
                    if(!that.C_FIRSTINIT) {
                        that.C_FIRSTINIT = true;
                        that._initMenus();
                    }
                }
            });
        },

        _createMenus : function() {
            var that = this;
            var level = $('[data-cid="'+that.CID+'"]').closest('ul').attr('level');
            level++;
            var html = '<ul level="'+level+'">';
            $.each(that.MENUS, function(i) {
                if(that.MENUS[i].ispid > 0) {
                    html += '<li data-cid="'+that.MENUS[i].cid+'" data-scid="'+that.MENUS[i].scid+'" ispid="'+that.MENUS[i].ispid+'" class="parentli"><h3 class="close"><em></em><a href="javascript:;" title="'+that.MENUS[i].cname+'">'+that.MENUS[i].cname+'</a></h3></li>';
                } else {
                    html += '<li data-cid="'+that.MENUS[i].cid+'" data-scid="'+that.MENUS[i].scid+'" ispid="'+that.MENUS[i].ispid+'" ><h3><input type="checkbox" name="scid" value="'+that.MENUS[i].scid+'"/><a href="javascript:;" title="'+that.MENUS[i].cname+'">'+that.MENUS[i].cname+'</a></h3></li>';
                }
            });
            html += '</ul>';
            $('[data-cid="'+that.CID+'"]').find('h3').addClass('open');
            $('[data-cid="'+that.CID+'"]').append(html);
        },

        _searchConfirm : function () {
            var that = this;
            var ul = $('#search_menu').is(':visible') ? $('#search_menu') : $('.default_menu');
            if(ul.find('[name="scid"]:checked').length > that.MAXNUM) {
                alert(that.LANGS[that.LANG]['checkerror']);
                return;
            } else if(ul.find('[name="scid"]:checked').length == 0) {
                $('[data-type="show"]').trigger('click').removeClass('menuopen');
                return;
            }
            that.SCIDS = [];
            that.PAGE = 1;
            that.newlength = 10;
            $('[name="searchtype"]').eq(1).trigger('click');    //默认选择最新记录
            ul.find('[name="scid"]:checked').each(function(i){
                $(this).val() != '' && that.SCIDS.push($(this).val());
            });
            that.SCIDS = $.unique(that.SCIDS);
            that._searchData();
        },

        _searchData : function () {
            var that = this;
            var searchtype = $('[name="searchtype"]:checked').val();
            that.newlength = 10; //最新记录条数
            that.starttime = '';  //查询开始时间
            that.endtime = '';   //查询结束时间
            if(searchtype == 0) {
                that.newlength = 0; //最新记录条数
                that.starttime = $('#starttime').val();
                that.endtime = $('#endtime').val();
                if(!that.starttime || !that.endtime) {
                    alert(that.LANGS[that.LANG]['selecterror']);
                    return;
                }
            } else {
                that.newlength = $('[name="newlength"]').val();
                that.starttime = '';
                that.endtime = '';
            }

            if(that.SCIDS) {
                $.ajax({
                    type : 'GET',
                    url : APP_URL + '?app=system&controller=honggdata&action=morequery',
                    data : {'lang':that.LANG, 'page':that.PAGE, 'starttime':that.starttime, 'endtime':that.endtime, 'newlength':that.newlength, 'scids':that.SCIDS.join(',')},
                    dataType : 'jsonp',
                    success : function(s) {
                        if(s.error) {
                            if(!that.D_FRISTINIT) {
                                that.D_FRISTINIT = true;
                                that._searchData();
                                console.log(s.info);
                            } else {
                                alert(s.info);
                            }
                            return;
                        }
                        that.D_FRISTINIT = true;
                        $('[data-type="showbox"]').hide();
                        that.TITLES = s.data.titles;
                        that._initIndex();
                        that.TABLETITLES = s.data.tabletitles;
                        that.TABLEDATA = s.data.tabledata;
                        that._initTable();
                        that.MAPTITLES = s.data.maptitles;
                        that.MAPDATA = s.data.mapdata;
                        that.MAPCATEGORY = s.data.mapcategory;
                        that._initMap();
                        return;
                    },
                    error : function(){
                        if(!that.D_FRISTINIT) {
                            that.D_FRISTINIT = true;
                            that._searchData();
                            console.log(that.LANGS[that.LANG]['loadfail']);
                        } else {
                            alert(that.LANGS[that.LANG]['loadfail']);
                        }
                    }
                });

            }
        },

        _initMap : function() {
            var that = this;
            var barvalue = [];
            var linevalue = [];
            $.each(that.MAPDATA, function(i){
                var b = {'name':that.MAPTITLES[i].name, 'type':'bar', 'barWidth':'20', 'barMinHeight':'5', 'barGap':'25%', 'data':that.MAPDATA[i]};
                b = {'name':that.MAPTITLES[i].name, 'type':'bar', 'data':that.MAPDATA[i]};
                var l = {'name':that.MAPTITLES[i].name, 'type':'line', 'hoverAnimation':false, 'symbolSize':6,'showSymbol':false, 'data':that.MAPDATA[i]};
                barvalue.push(b);
                linevalue.push(l);
            });

            var barwidth = 30 * that.MAPDATA.length * that.MAPCATEGORY.length;
            var linewidth = 960;
            barwidth = barwidth < 960 ? 960 : barwidth;
            barwidth = 960; //不使用滚动条
            $('.n_ind_contmain .pic').eq(1).width(barwidth);
            $('.n_ind_contmain .pic').eq(0).width(linewidth);
            $('.n_ind_contmain').scrollLeft(0);

            var echart_bar = echarts.init($('#barechart').get(0));  //柱状图
            var echart_line = echarts.init($('#lineechart').get(0)); //折线图

            // 指定图表的配置项和数据
            var option = {
                title: {
                },
                tooltip: {
                    show : true
                },
                grid:{
                    zlevel:100,
                    left:'100',
                    height:'300',
                    top:'30',
                    width:barwidth - 120
                },
                legend:{
                    data:that.MAPTITLES,
                    top:'360',
                    left:'90'
                },
                xAxis: {
                    data: that.MAPCATEGORY,
                    name:'',
                    axisTick: {
                        interval: 0
                    },
                    type:'category'
                },
                yAxis: [{
                    position :{
                        left:20
                    },
                    type : 'value',
                    splitNumber : 8
                    // min:min,
                    // max:max
                }]
            };
            option.series = barvalue;
            echart_bar.setOption(option);
            option = {
                title: {
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        animation: false
                    },
                    formatter: function (params) {
                        var html = '';
                        $.each(params, function(i) {
                            html += that.MAPTITLES[i].name + ' ' + params[i].name + ' ' + params[i].value + '<br/>';
                        });
                        return html;
                    }
                },
                legend:{
                    data:that.MAPTITLES,
                    top:'360',
                    left:'90'
                },
                grid: {
                    left: '50',
                    top: '30',
                    height:320,
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: that.MAPCATEGORY,
                    splitLine: {
                        show: false
                    },
                    boundaryGap: false
                },
                yAxis: {
                    position :{
                        left:20
                    },
                    type : 'value',
                    splitNumber : 8,
                    // min:min,
                    // max:max,
                    boundaryGap: false,
                    splitLine: {
                        show: false
                    }
                },
                series: linevalue
            };
            echart_line.setOption(option);
        },

        _initTable : function() {
            var that = this;
            if(!that.TABLETITLES) return;
            $('#tablecontain').empty();
            var titlebody = '<thead><tr class="data-box-header">';
            $.each(that.TABLETITLES, function(i){
                titlebody += '<th>'+that.TABLETITLES[i].name+'</th>';
            });
            titlebody += '</tr></thead>';
            $('#tablecontain').append(titlebody);
            if(that.TABLEDATA.length == 0) {
                $('#tablecontain').append('<tr><td style="text-align:left;" colspan="'+that.TABLETITLES.length+'">'+that.LANGS[that.LANG]['nocontent']+'</td></tr>');
                return;
            }
            $.each(that.TABLEDATA, function(i){
                var html = '<tr>';
                $.each(that.TABLEDATA[i], function (j) {
                    that.TABLEDATA[i][j] == '' ? that.TABLEDATA[i][j] = '--' : '';
                    html += '<td>'+that.TABLEDATA[i][j]+'</td>';
                });
                html += '</tr>';
                $('#tablecontain').append(html);
            });

        },

        _initIndex : function() {
            var that =  this;
            if(that.TITLES) {
                var html = '';
                $.each(that.TITLES, function(i){
                    var key = i + 1;
                    html += '<tr><td>'+key+'</td><td style="text-align: left">'+that.TITLES[i].name+'</td><td>'+that.TITLES[i].frequency+'</td><td>'+that.TITLES[i].unit+'</td><td>'+that.TITLES[i].starttime+'</td><td>'+that.TITLES[i].endtime+'</td><td>'+that.TITLES[i].region+'</td><td>'+that.TITLES[i].source+'</td></tr>';
                })
                $('.index-item').html($('.index-item').find('thead').prop('outerHTML') + html);
            }
            $('.indexcount').html(that.LANGS[that.LANG]['indexcount']+'('+that.TITLES.length+')');

        },

        _searchMenus : function(){
            var that = this,
                text = $.trim($('.categorysearch form').find('[name="text"]').val());
            if(text == '') return;
            $.ajax({
                type : 'get',
                url : APP_URL + '?app=system&controller=honggdata&action=searchcategory', //$('.categorysearch form').attr('action'),
                data : {'lang':that.LANG, 'text':text},
                dataType : 'jsonp',
                beforeSend : function () {
                    $('#search_menu').html('<img src="'+IMG_URL+'templates/silkroad/diy/image/timg.gif"/>').show();
                    $('.default_menu').hide();
                },
                success : function(s) {
                    if(!s.error) {
                        var html = '';
                        if(!s.data.length) {
                            html = '<li>'+that.LANGS[that.LANG]['nodata']+'</li>';
                        }
                        $.each(s.data, function(i) {
                            var ischecked = $.inArray(s.data[i].scid, that.SCIDS) != -1 ? 'checked' : '';
                            var aclass = ischecked != '' ? 'class="active"' : '';
                            html += '<li data-cid="'+s.data[i].cid+'" data-scid="'+s.data[i].scid+'" ispid="'+s.data[i].ispid+'" ><h3><input type="checkbox" name="scid" value="'+s.data[i].scid+'" '+ischecked+'/><a href="javascript:;" title="'+s.data[i].cname+'" '+aclass+'>'+s.data[i].cname+'</a></h3></li>';
                        });
                        $('#search_menu').html(html).show();
                    }
                },
                error : function() {

                }

            });
            return;
        },

        _initSearchBox : function() {
            jeDate({
                dateCell : '#starttime',
                format : 'YYYY-MM-DD'

            });
            jeDate({
                dateCell : '#endtime',
                format : 'YYYY-MM-DD'
            });
        },

        //下载文件
        _downLoad : function(e) {
            var that = this,
                self = $(e.target).get(0).tagName == 'button' ?  $(e.target) : $(e.target).closest('button');
            var type = self.attr('down-load');
            if(1 || "ActiveXObject" in window) {
                window.open(APP_URL+'?app=system&controller=honggdata&action=download&querytype=more&scids=' + that.SCIDS.join(',') +'&downtype='+type+'&lang='+that.LANG+'&newlength='+that.newlength+'&starttime='+that.starttime+'&endtime='+that.endtime);
            } else {
                $('#tablecontain').tableExport({
                    filename: that.LANGS[that.LANG]['filename'],
                    format: type == 'CSV' ? 'csv' : 'txt',
                    head_delimiter: type == 'CSV' ? ","  : "\t",
                    column_delimiter: type == 'CSV' ? ","  : "\t"
                });
            }
        },

        init : function() {
            this.LANG = typeof LANG != 'undefined' ? LANG : 'cn';
            //菜单显示隐藏
            $('[data-type="show"]').on('click', function(){
                if($('[data-type="showbox"]').is(':hidden')) {
                    $('[data-type="showbox"]').slideDown();
                    $(this).addClass('menuopen');
                } else {
                    $('[data-type="showbox"]').slideUp();
                    $(this).removeClass('menuopen');
                }
            });

            this.inited = false;
            this.CID = '0001';
            this._initMenus();  //请求菜单
            $('.yq-tab-menu1').delegate('li', 'click', this._initMenus.bind(this));   //点击菜单
            $('[name="searchConfirm"]').on('click', this._searchConfirm.bind(this)); //确认选择
            $('[name="searchRemove"]').on('click', function(){
                $('.yq-tab-menu1 input:checked').prop('checked', false);
                $('.yq-tab-menu1 a').removeClass('active');
            }); //清除选择
            $('.categorysearch form').on('submit', this._searchMenus.bind(this)); //搜索栏目

            $('.categorysearch form').on('reset', function(){
                $('#search_menu').empty().hide();
                $('.default_menu').show();
            }); //重置搜索

            this.SCIDS = ['EMM00000004']; //默认加载的数据
            if(window.location.hash.replace('#', '') != '') {
                this.SCIDS = [window.location.hash.replace('#', '')];
            }
            this._searchData();
            this._initSearchBox();

            $('[data-type="search"]').on('click', this._searchData.bind(this));
            $('[down-load]').on('click', this._downLoad.bind(this));
        }
    };

    return HONGG;
})(jQuery);

var CEIC = (function($){
    var CEIC = {
        QUERYING : false,
        SCIDS : '',
        MAXNUM : 10,
        C_FRISTINIT : false,
        D_FRISTINIT : false,
        DEFAULT : {},
        LANGS : {
            'en' : {
                'searchtip' : '*You can hold down the Ctrl key to multiple conditions',
                'search' : 'Search',
                'all' : 'All',
                'prev' : 'Prev',
                'next' : 'Next',
                'first' : 'First',
                'end' : 'End',
                'loadfail' : 'The system is busy. Try again later',
                'checkerror' : 'The maximum number of items in the check has been reached.',
                'nodata' : 'No search to data',
                'indexcount' : 'Index items',
                'selecterror' : 'Please select the time',
                'nocontent' : 'Have not contents',
                'filename' : 'CEIC data'
            },
            'cn' : {
                'searchtip' : '*条件可以按住Ctrl键进行多选',
                'search' : '查询',
                'all' : '全部',
                'prev' : '上页',
                'next' : '下页',
                'first' : '首页',
                'end' : '尾页',
                'loadfail' : '系统繁忙，稍后再试',
                'checkerror' : '复选的指标项已达到最大个数，请重新选择。',
                'nodata' : '没有搜索到数据',
                'indexcount' : '指标项',
                'selecterror' : '请选择时间',
                'nocontent' : '没有相关内容',
                'filename' : 'CEIC数据'
            }
        },

        _initMenus : function(e) {
            var that = this;
            that.PAGE = 1;
            that.QUERY = '';
            if(that.QUERYING) return;

            that.allowQuery = false;
            if(typeof e != 'undefined') {
                e.stopPropagation();
                var self = $(e.target).get(0).tagName != 'LI' ? $(e.target).closest('li') : $(e.target);
                that.CID = self.attr('data-cid');
                that.LEVEL = self.attr('data-level');
                if(self.attr('ispid')==1) {
                    that.allowQuery = false;
                } else {
                    that.allowQuery = true;
                }
                var level = self.closest('ul').attr('level');
                level++;
                var sonul = self.find('[level="'+level+'"]');
                if(sonul.length) {
                    if(sonul.is(':visible')) {
                        self.find('ul').hide();
                        self.find('h3').removeClass('open');
                    } else {
                        self.children('h3').addClass('open');
                        sonul.show();
                    }
                    that.QUERYING = false;
                    return;
                }
            }

            if(that.allowQuery) {
                that.PAGE = 1;
                that.NAME = self.children('h3').text();
                var maxnum = that.MAXNUM;
                if(self.find('a').hasClass('active')) {
                    self.find('a').removeClass('active');
                    self.find('input').prop('checked', false);
                } else {
                    if(self.find('input').prop('checked')) {
                        maxnum++;
                    }
                    if(self.closest('.yq-tab-menu1').find('[name="scid"]:checked').length >= maxnum) {
                        self.find('input').prop('checked', false);
                        alert(that.LANGS[that.LANG]['checkerror']);
                    } else {
                        self.find('a').addClass('active');
                        self.find('input').prop('checked', true);
                    }
                }
                return;
            }

            that.QUERYING = true;

            $.ajax({
                type : 'get',
                url : APP_URL+'?app=system&controller=ceicdata&action=getcategory',
                data : {'code':that.CID, 'level':that.LEVEL, 'lang':that.LANG},
                dataType : 'jsonp',
                success : function (s) {
                    if(s.error) {
                        that.QUERYING = false;
                        console.log('getcategory load fail');
                        if(!that.C_FRISTINIT) {
                            that.C_FRISTINIT = true;
                            that._initMenus();
                        }
                    } else {
                        that.QUERYING = false;
                        that.MENUS = s.data;
                        that.C_FRISTINIT = true;
                        that._createMenus();
                    }
                },
                error : function () {
                    that.QUERYING = false;
                    console.log('getcategory system error');
                    if(!that.C_FRISTINIT) {
                        that.C_FRISTINIT = true;
                        that._initMenus();
                    }
                }
            });
        },

        _createMenus : function() {
            var that = this;
            var level = $('[data-cid="'+that.CID+'"]').closest('ul').attr('level');
            level++;
            var html = '<ul level="'+level+'">';
            $.each(that.MENUS, function(i) {
                if(that.MENUS[i].ispid > 0) {
                    html += '<li data-cid="'+that.MENUS[i].cid+'" data-scid="'+that.MENUS[i].scid+'" data-level="'+that.MENUS[i].nodelevel+'" ispid="'+that.MENUS[i].ispid+'" class="parentli"><h3 class="close"><em></em><a href="javascript:;" title="'+that.MENUS[i].cname+'">'+that.MENUS[i].cname+'</a></h3></li>';
                } else {
                    html += '<li data-cid="'+that.MENUS[i].cid+'" data-scid="'+that.MENUS[i].scid+'" data-level="'+that.MENUS[i].nodelevel+'" ispid="'+that.MENUS[i].ispid+'" ><h3><input type="checkbox" name="scid" value="'+that.MENUS[i].scid+'"/><a href="javascript:;" title="'+that.MENUS[i].cname+'">'+that.MENUS[i].cname+'</a></h3></li>';
                }
            });
            html += '</ul>';
            $('[data-cid="'+that.CID+'"]').find('h3').addClass('open');
            $('[data-cid="'+that.CID+'"]').append(html);
        },

        _searchConfirm : function () {
            var that = this;
            var ul = $('#search_menu').is(':visible') ? $('#search_menu') : $('.default_menu');
            if(ul.find('[name="scid"]:checked').length > that.MAXNUM) {
                alert(that.LANGS[that.LANG]['checkerror']);
                return;
            } else if(ul.find('[name="scid"]:checked').length == 0) {
                $('[data-type="show"]').trigger('click').removeClass('menuopen');
                return;
            }
            that.SCIDS = [];
            that.PAGE = 1;
            that.newlength = 10;
            $('[name="searchtype"]').eq(1).trigger('click');    //默认选择最新记录
            ul.find('[name="scid"]:checked').each(function(i){
                $(this).val() != '' && that.SCIDS.push($(this).val());
            });
            that.SCIDS = $.unique(that.SCIDS);
            that._searchData();
        },

        _searchData : function () {
            var that = this;
            var searchtype = $('[name="searchtype"]:checked').val();
            that.newlength = that.DEFAULT.newlength ? that.DEFAULT.newlength : 10; //最新记录条数
            that.starttime = that.DEFAULT.starttime ? that.DEFAULT.starttime : '';  //查询开始时间
            that.endtime = that.DEFAULT.endtime ? that.DEFAULT.endtime : '';   //查询结束时间
            that.PAGE = that.DEFAULT.page ? that.DEFAULT.page : that.PAGE;

            if(searchtype == 0 || that.newlength == 0) {
                $('[name="searchtype"]').eq(0).prop('checked', true);
                that.newlength = 0; //最新记录条数
                that.starttime = that.DEFAULT.starttime ? that.DEFAULT.starttime : $('#starttime').val();
                that.endtime = that.DEFAULT.endtime ? that.DEFAULT.endtime : $('#endtime').val();
                $('#starttime').val(that.starttime);
                $('#endtime').val(that.endtime);
                if(!that.starttime || !that.endtime) {
                    alert(that.LANGS[that.LANG]['selecterror']);
                    return;
                }
            } else {
                that.newlength = that.DEFAULT.newlength ? that.DEFAULT.newlength : $('[name="newlength"]').val();
                that.starttime = '';
                that.endtime = '';
            }
            that.DEFAULT = {};
            if(that.SCIDS) {
                $.ajax({
                    type : 'GET',
                    url : APP_URL + '?app=system&controller=ceicdata&action=morequery',
                    data : {'lang':that.LANG, 'page':that.PAGE, 'starttime':that.starttime, 'endtime':that.endtime, 'newlength':that.newlength, 'scids':that.SCIDS.join(',')},
                    dataType : 'jsonp',
                    success : function(s) {
                        if(s.error) {
                            if(s.needlogin && s.url) {
                               that._gotoLogin(s.url);
                            } else {
                                if(!that.D_FRISTINIT) {
                                    that.D_FRISTINIT = true;
                                    console.log(s.info);
                                    that._searchData();
                                } else {
                                    alert(s.info);
                                }
                            }
                            return;
                        }
                        that.D_FRISTINIT = true;
                        $('[data-type="showbox"]').hide();
                        that.TITLES = s.data.titles;
                        that._initIndex();
                        that.TABLETITLES = s.data.tabletitles;
                        that.TABLEDATA = s.data.tabledata;
                        that._initTable();
                        that.MAPTITLES = s.data.maptitles;
                        that.MAPDATA = s.data.mapdata;
                        that.MAPCATEGORY = s.data.mapcategory;
                        that._initMap();
                        return;
                    },
                    error : function(){
                        if(!that.D_FRISTINIT) {
                            that.D_FRISTINIT = true;
                            console.log(that.LANGS[that.LANG]['loadfail']);
                            that._searchData();
                        } else {
                            alert(that.LANGS[that.LANG]['loadfail']);
                        }
                    }
                });

            }
        },

        //去登陆
        _gotoLogin : function(url) {
            if(this.LANG == 'cn') {
                $('#body-show span:eq(1)').html('您未登录,请先登录后再来访问 !');
                $('#body-show').show();
                setInterval(function(){
                    var number = $('#body-show').find('number').text();
                    if(number==0) return window.location.href = url;
                    $('#body-show').find('number').text(parseInt(number)-1);
               },1000);
            } else {
                $('#body-show span:eq(1)').html('You are not logged in, Please login in first !');
                $('#body-show').show();
                setInterval(function(){
                    var number = $('#body-show').find('number').text();
                    if(number==0) return window.location.href = url;
                    $('#body-show').find('number').text(parseInt(number)-1);
                },1000);
            }
        },

        _initMap : function() {
            var that = this;
            var barvalue = [];
            var linevalue = [];
            $.each(that.MAPDATA, function(i){
                var b = {'name':that.MAPTITLES[i].name, 'type':'bar', 'barWidth':'20', 'barMinHeight':'5', 'barGap':'25%', 'data':that.MAPDATA[i]};
                b = {'name':that.MAPTITLES[i].name, 'type':'bar', 'data':that.MAPDATA[i]};
                var l = {'name':that.MAPTITLES[i].name, 'type':'line', 'hoverAnimation':false, 'symbolSize':6,'showSymbol':false, 'data':that.MAPDATA[i]};
                barvalue.push(b);
                linevalue.push(l);
            });

            var barwidth = 30 * that.MAPDATA.length * that.MAPCATEGORY.length;
            var linewidth = 960;
            barwidth = barwidth < 960 ? 960 : barwidth;
            barwidth = 960; //不使用滚动条
            $('.n_ind_contmain .pic').eq(1).width(barwidth);
            $('.n_ind_contmain .pic').eq(0).width(linewidth);
            $('.n_ind_contmain').scrollLeft(0);

            var echart_bar = echarts.init($('#barechart').get(0));  //柱状图
            var echart_line = echarts.init($('#lineechart').get(0)); //折线图

            // 指定图表的配置项和数据
            var option = {
                title: {
                },
                tooltip: {
                    show : true
                },
                grid:{
                    zlevel:100,
                    left:'100',
                    height:'300',
                    top:'30',
                    width:barwidth - 120
                },
                legend:{
                    data:that.MAPTITLES,
                    top:'360',
                    left:'90'
                },
                xAxis: {
                    data: that.MAPCATEGORY,
                    name:'',
                    axisTick: {
                        interval: 0
                    },
                    type:'category'
                },
                yAxis: [{
                    position :{
                        left:20
                    },
                    type : 'value',
                    splitNumber : 8
                    // min:min,
                    // max:max
                }]
            };
            option.series = barvalue;
            echart_bar.setOption(option);
            option = {
                title: {
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        animation: false
                    },
                    formatter: function (params) {
                        var html = '';
                        $.each(params, function(i) {
                            html += that.MAPTITLES[i].name + ' ' + params[i].name + ' ' + params[i].value + '<br/>';
                        });
                        return html;
                    }
                },

                legend:{
                    data:that.MAPTITLES,
                    top:'360',
                    left:'90'
                },
                grid: {
                    left: '3%',
                    top: '30',
                    height:320,
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: that.MAPCATEGORY,
                    splitLine: {
                        show: false
                    },
                    boundaryGap: false
                },
                yAxis: {
                    position :{
                        left:20
                    },
                    type : 'value',
                    splitNumber : 8,
                    // min:min,
                    // max:max,
                    boundaryGap: false,
                    splitLine: {
                        show: false
                    }
                },
                series: linevalue
            };
            echart_line.setOption(option);
        },

        _initTable : function() {
            var that = this;
            if(!that.TABLETITLES) return;
            $('#tablecontain').empty();
            var titlebody = '<thead><tr class="data-box-header">';
            $.each(that.TABLETITLES, function(i){
                titlebody += '<th>'+that.TABLETITLES[i].name+'</th>';
            });
            titlebody += '</tr></thead>';
            $('#tablecontain').append(titlebody);
            if(that.TABLEDATA.length == 0) {
                $('#tablecontain').append('<tr><td style="text-align:left;" colspan="'+that.TABLETITLES.length+'">'+that.LANGS[that.LANG]['nocontent']+'</td></tr>');
                return;
            }
            $.each(that.TABLEDATA, function(i){
                var html = '<tr>';
                $.each(that.TABLEDATA[i], function (j) {
                    that.TABLEDATA[i][j] == '' ? that.TABLEDATA[i][j] = '--' : '';
                    html += '<td>'+that.TABLEDATA[i][j]+'</td>';
                });
                html += '</tr>';
                $('#tablecontain').append(html);
            });

        },

        _initIndex : function() {
            var that =  this;
            if(that.TITLES) {
                var html = '';
                $.each(that.TITLES, function(i){
                    var key = i + 1;
                    html += '<tr><td>'+key+'</td><td style="text-align: left; width:600px; white-space:normal;">'+that.TITLES[i].name+'</td><td>'+that.TITLES[i].countryname+'</td><td>'+that.TITLES[i].frequency+'</td><td>'+that.TITLES[i].unit+'</td><td>'+that.TITLES[i].starttime+'</td><td>'+that.TITLES[i].endtime+'</td></tr>';
                })
                $('.index-item').html($('.index-item').find('thead').prop('outerHTML') + html);
            }
            $('.indexcount').html(that.LANGS[that.LANG]['indexcount']+'('+that.TITLES.length+')');

        },

        _searchMenus : function(){
            var that = this,
                text = $.trim($('.categorysearch form').find('[name="text"]').val());
            if(text == '') return;
            $.ajax({
                type : 'get',
                url : APP_URL + '?app=system&controller=ceicdata&action=searchcategory',// $('.categorysearch form').attr('action'),
                data : {'lang':that.LANG, 'text':text},
                dataType : 'jsonp',
                beforeSend : function () {
                    $('#search_menu').html('<img src="'+IMG_URL+'templates/silkroad/diy/image/timg.gif"/>').show();
                    $('.default_menu').hide();
                },
                success : function(s) {
                    if(!s.error) {
                        var html = '';
                        if(!s.data.length) {
                            html = '<li>'+that.LANGS[that.LANG]['nodata']+'</li>';
                        }
                        $.each(s.data, function(i) {
                            var ischecked = $.inArray(s.data[i].scid, that.SCIDS) != -1 ? 'checked' : '';
                            var aclass = ischecked != '' ? 'class="active"' : '';
                            html += '<li data-cid="'+s.data[i].cid+'" data-scid="'+s.data[i].scid+'" ispid="'+s.data[i].ispid+'" ><h3><input type="checkbox" name="scid" value="'+s.data[i].scid+'" '+ischecked+'/><a href="javascript:;" title="'+s.data[i].cname+'" '+aclass+'>'+s.data[i].cname+'</a></h3></li>';
                        });
                        $('#search_menu').html(html).show();
                    }
                },
                error : function() {

                }

            });
            return;
        },

        _initSearchBox : function() {
            var that = this;
            var format = that.LANG == 'cn' ? 'YYYY-MM-DD' : 'MM-DD-YYYY';
            jeDate({
                dateCell : '#starttime',
                format : format

            });
            jeDate({
                dateCell : '#endtime',
                format : format
            });
        },

        //下载文件
        _downLoad : function(e) {
            var that = this,
                self = $(e.target).get(0).tagName == 'button' ?  $(e.target) : $(e.target).closest('button');
            var type = self.attr('down-load');
            if(1 || "ActiveXObject" in window) {
                window.open(APP_URL+'?app=system&controller=ceicdata&action=download&querytype=more&scids=' + that.SCIDS.join(',') +'&downtype='+type+'&lang='+that.LANG+'&newlength='+that.newlength+'&starttime='+that.starttime+'&endtime='+that.endtime);
            } else {
                $('#tablecontain').tableExport({
                    filename: that.LANGS[that.LANG]['filename'],
                    format: type == 'CSV' ? 'csv' : 'txt',
                    head_delimiter: type == 'CSV' ? ","  : "\t",
                    column_delimiter: type == 'CSV' ? ","  : "\t"
                });
            }
        },

        init : function() {
            this.LANG = typeof LANG != 'undefined' ? LANG : 'cn';
            //菜单显示隐藏
            $('[data-type="show"]').on('click', function(){
                if($('[data-type="showbox"]').is(':hidden')) {
                    $('[data-type="showbox"]').slideDown();
                    $(this).addClass('menuopen');
                } else {
                    $('[data-type="showbox"]').slideUp();
                    $(this).removeClass('menuopen');
                }
            });
            this.inited = false;
            this.CID = 'GLOBAL';
            this._initMenus();  //请求菜单
            $('.yq-tab-menu1').delegate('li', 'click', this._initMenus.bind(this));   //点击菜单
            $('[name="searchConfirm"]').on('click', this._searchConfirm.bind(this)); //确认选择
            $('[name="searchRemove"]').on('click', function(){
                $('.yq-tab-menu1 input:checked').prop('checked', false);
                $('.yq-tab-menu1 a').removeClass('active');
            }); //清除选择
            $('.categorysearch form').on('submit', this._searchMenus.bind(this)); //搜索栏目

            $('.categorysearch form').on('reset', function(){
                $('#search_menu').empty().hide();
                $('.default_menu').show();
            }); //重置搜索
            var that = this;
            if(window.location.hash.replace('#', '') != '') {
                var param = window.location.hash.replace('#', '').split('|');
                $.each(param, function(i){
                    if(param[i].indexOf(':') == -1) {
                        that.SCIDS = param[i].split(',');
                    } else {
                        var p1 = param[i].split(':')[0], p2 = param[i].split(':')[1];
                        that.DEFAULT[p1] = p2;
                    }
                });
            } else {
                $('[data-type="show"]').trigger('click');
            }
            this._searchData();
            this._initSearchBox();
            $('[data-type="search"]').on('click', function(){
                that.PAGE = 1;
                that._searchData();
            });
            $('[down-load]').on('click', this._downLoad.bind(this));
        }
    };

    return CEIC;
})(jQuery);

var HG = (function($){
    var HG = {
        PAGE : 1,
        NAME : '',
        CODE : '',
        QUERYING : false,
        D_FIRSTINIT : false,
        QUERY : '',
        DATA : '',
        INITLABEL : false,
        LANGS : {
            'en' : {
                'searchtip' : '*You can hold down the Ctrl key to multiple conditions',
                'search' : 'Search',
                'all' : 'All',
                'join' : 'Join Chart',
                'prev' : 'Prev',
                'next' : 'Next',
                'first' : 'First',
                'end' : 'End',
                'loadfail' : 'The system is busy. Try again later',
                'nodata' : 'No search to data'
            },
            'cn' : {
                'searchtip' : '*条件可以按住Ctrl键进行多选',
                'search' : '查询',
                'all' : '全部',
                'join' : '加入图表',
                'prev' : '上页',
                'next' : '下页',
                'first' : '首页',
                'end' : '尾页',
                'loadfail' : '系统繁忙，稍后再试',
                'nodata' : '没有相关数据'
            }
        },
        _queryData : function(){
            var that = this;
            if(that.QUERYING) return;
            that.QUERYING = true;
            $.ajax({
                type: 'get',
                url: APP_URL+'?app=system&controller=hgdata&action=query',
                data : {'code':that.CODE, 'query':that.QUERY, 'page':that.PAGE, 'lang':that.LANG},
                dataType:'jsonp',
                success: function(s) {
                    if(!s.error) {
                        that.DATA = s.data;
                        that.QUERYING = false;
                        that.D_FIRSTINIT = true;
                        that._initSearch();  //初始化搜索
                        that._createTable(); //创建表格
                        that._createLabel(); //创建图例
                        that._createMap();   //创建图形
                        that._initPage();  //初始化分页
                        that._createScroll(); //创建横向模拟滚动条
                    } else {
                        that.QUERYING = false;
                        if(!that.D_FIRSTINIT) {
                            that.D_FIRSTINIT = true;
                            console.log(s.info);
                            that._queryData();
                        } else {
                            alert(s.info);
                        }
                    }
                },
                error: function() {
                    that.QUERYING = false;
                    if(!that.D_FIRSTINIT) {
                        that.D_FIRSTINIT = true;
                        console.log(that.LANGS[that.LANG].loadfail);
                        that._queryData();
                    } else {
                        alert(that.LANGS[that.LANG].loadfail);
                    }
                }
            });
        },

        _createScroll : function() {
            $('.scroll').remove();
            if($('#tablecontain').width() <= 710 || $(window).height() > $('#tablecontain').offset().top + $('#tablecontain').height()) {
                return;
            }

            $('<div class="scroll"><p style="width:'+$('#tablecontain').width()+'px">&nbsp;</p></div>').insertAfter($('.data-box-h'));
            $('.scroll').scroll(function(){
                $('.data-box-h').scrollLeft($(this).scrollLeft());
            });
            $('.scroll').css({'left':$('#tablecontain').offset().left, 'bottom':0});

            $(window).scroll(function () {
                if(!$('.scroll').length) return;
                if($(this).scrollTop() + $(window).height() > $('#tablecontain').offset().top + $('#tablecontain').height() + 10) {
                    $('.scroll').hide();
                } else {
                    $('.scroll').show();
                }
            });
        },

        _initSearch : function() {
            var that = this;
            if(!that.DATA.wheres) return;
            $('.time-list').empty();
            var WHERES = this.DATA.wheres;
            $.each(WHERES, function(i)	{
                var code = $(this).get(0).code;
                var searchHtml = '<span>'+$(this).get(0).name+'：</span><select multiple="true" name="'+code+'">';
                $.each(WHERES[i].value, function(j) {
                    var selected = '';
                    if($.inArray(WHERES[i].value[j], that.DATA.selectwheres[code]) != -1) {
                        selected = 'selected="true"';
                    }
                    searchHtml += '<option '+selected+' value="'+WHERES[i]['value'][j]+'">'+WHERES[i]['value'][j]+'</option>';
                });
                searchHtml += '</select>';
                searchHtml += '<span class="searchtip">'+that.LANGS[that.LANG].searchtip+'</span>'
                $('.time-list').append(searchHtml);
            });
            if(WHERES.length > 0) {
                $('.time-list').show();
                $('.time-list').append('<button type="button" class="data-button">'+that.LANGS[that.LANG].search+'</button>');
            } else {
                $('.time-list').hide();
            }
        },

        _createTable : function() {
            var that = this;
            if(!that.DATA) return;

            $('#tablecontain').empty();
            var tabletitles = that.DATA.tabletitles;
            var titlebody = '<thead><tr class="data-box-header">';
            $.each(tabletitles, function(i){
                var ismaplabel = tabletitles[i].ismap == 1 ? 'ismaplabel="1"' : 'ismaplabel="0"';
                titlebody += '<th code="'+tabletitles[i].code+'" '+ismaplabel+'>'+tabletitles[i].name+'</th>';
            });
            titlebody += '</tr></thead>';
            $('#tablecontain').append(titlebody);

            var tabledata = that.DATA.tabledata;
            $.each(tabledata, function(i){
                var tablebody = '<tr class="data-box-body">';
                $.each(tabledata[i], function(j) {
                    tablebody += '<td>'+tabledata[i][j].value+'</td>';
                });
                tablebody += '</tr>';
                $('#tablecontain').append(tablebody);
            });

            if(tabledata.length > 0) {
                $('.export, .export2').show();
            }
            //$('.data-box-h').scrollTop(0);
        },

        _createMap : function() {
            var that = this;
            var barvalue = [];
            var linevalue = [];
            $.each(that.DATA.maptitles, function (i) {
                if($.inArray(that.DATA.maptitles[i].code, that.lablekeys) != -1) {
                    var b = {'name':that.DATA.maptitles[i].name, 'type':'bar', 'barWidth':'20', 'barMinHeight':'5', 'barGap':'25%', 'yAxisIndex': 0, 'data':[]};
                    var bl = {'name':that.DATA.maptitles[i].name, 'type':'line', 'yAxisIndex': 1, 'data':[]};
                    var l = {'name':that.DATA.maptitles[i].name, 'type':'line', 'data':[]};

                    $.each(that.DATA.mapdata, function(j) {
                        if(that.DATA.mapdata[j][i].dataType == 'percent') {
                            bl.data.push(that.DATA.mapdata[j][i].value);
                        } else {
                            b.data.push(that.DATA.mapdata[j][i].value);
                        }
                        l.data.push(that.DATA.mapdata[j][i].value);
                    });
                    if(b.data.length) {
                        barvalue.push(b);
                    }
                    if(bl.data.length) {
                        barvalue.push(bl);
                    }

                    if(that.DATA.datatypes.length > 1) {

                    }
                    linevalue.push(l);
                }
            });

            var linewidth = that.DATA.mapdata.length * 120;
            var a = 0, b = that.DATA.mapdata.length;
            $.each(that.DATA.maptitles, function(i){
                if(that.DATA.maptitles[i].dataType != 'percent' && $.inArray(that.DATA.maptitles[i].code, that.lablekeys) != -1) {
                    a++;
                }
            });

            var barwidth = (a * b) * 20 + (a - 1) * b * 20 + 25 * b + (150*that.DATA.datatypes.length); //that.lablekeys.length * that.DATA.mapdata.length * 25 + (that.DATA.mapdata.length - 2) * 20;
            var piewidth = 960;
            linewidth = linewidth < 960 ? 960 : linewidth;
            barwidth = barwidth < 960 ? 960 : barwidth;

            that.TITLETOP = 410;
            if(that.DATA.maxcols > 0) {
                that.TITLETOP += that.DATA.maxcols * 10;
            }
            $('.n_ind_contmain .pic').height(that.TITLETOP);
            $('.n_ind_contmain .pic').eq(0).width(barwidth);
            $('.n_ind_contmain .pic').eq(1).width(linewidth);
            $('.n_ind_contmain .pic').eq(2).width(piewidth);
            $('.n_ind_contmain').scrollLeft(0);
            //$('.n_ind_contmain').height(that.TITLETOP + 70 + $('.labelhtml').height() + 20);


            var echart_bar = echarts.init($('#barechart').get(0));  //柱状图
            var echart_line = echarts.init($('#lineechart').get(0)); //折线图
            var echart_pie = echarts.init($('#pieechart').get(0));  //饼状图

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: that.NAME
                },
                tooltip: {
                    show : true
                },
                grid:{
                    zlevel:100,
                    left:'120',
                    height:'300',
                    width:barwidth - (150*that.DATA.datatypes.length)
                },
                legend:{
                    show:false
                },
                xAxis: {
                    data: that.DATA.mapcategory,
                    name:'',
                    axisTick: {
                        interval: 0
                    },
                    type:'category'
                },
                yAxis: [{
                    position :{
                        left:20
                    },
                    type : 'value',
                    name:''
                }]
            };
            option.series = barvalue;
            if(that.DATA.datatypes.length > 1) {
                $.each(that.DATA.datatypes, function(i){
                    if(that.DATA.datatypes[i] == 'percent') {
                        var y = {
                            'position': {
                                'right': 20
                            },
                            'type': 'value',
                            'name': '',
                            'axisLabel': {
                                'formatter': '{value} %'
                            }
                        }
                        option.yAxis.push(y);
                    }
                });
            }

            echart_bar.setOption(option);
            option.series = linevalue;
            option.grid.width = linewidth - 200;
            echart_line.setOption(option);

            var pievalue = [];
            $.each(that.DATA.piecategory, function(i) {
                var pv = 0;
                $.each(that.DATA.piedata[i], function(j){
                    if($.inArray(that.DATA.piedata[i][j].code, that.lablekeys) != -1) {
                        pv +=  isNaN(parseFloat(that.DATA.piedata[i][j].value)) ? 0 : parseFloat(that.DATA.piedata[i][j].value);
                    }
                });
                pievalue.push({name: that.DATA.piecategory[i], value:pv});
            });

            option.xAxis = null;
            option.yAxis = null;
            option.series = {
                name:'金额',
                type:'pie',
                radius : '55%',
                data:pievalue,
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            };
            option.tooltip.formatter = "{b} : {c} ({d}%)";
            option.tooltip.trigger = 'item';
            echart_pie.setOption(option);
        },

        _changeTab : function(e) {
            var that = this;
            var	self = $(e.target).closest('li');
            that.PAGE = 1;
            that.NAME = self.find('h3').text();
            that.CODE = self.find('h3').attr('data-id');
            that.DATA = '';
            that.QUERY = '';
            if(self.find('h3').attr('data-attr')) {
                that.NAME = self.find('h3 .s_tablename').text();
                that.QUERY = [{'code':self.find('h3').attr('data-attr'), 'value':self.find('h3').attr('data-value')}];
            }
            that.INITLABEL = false;
            self.addClass('active').siblings().removeClass('active');
            that._queryData();
            $('[data-type="showbox"]').slideUp();
            $('[data-type="show"]').removeClass('menuopen');
        },

        _searchData : function () {
            var that = this;
            that.PAGE = 1;
            that.QUERY = [];
            $('.time-list select').each(function(i){
                var name = $(this).prop('name');
                if($(this).val()) {
                    that.QUERY[i] = {'code':name, 'value':$(this).val().join('|')};
                } else{
                    that.QUERY[i] = {'code':name, 'value':that.LANGS[that.LANG].all};
                }
                if(that.QUERY[i].value.indexOf(that.LANGS[that.LANG].all+'|') > -1) {
                    that.QUERY[i].value = that.LANGS[that.LANG].all;
                }
            });
            that._queryData();
        },

        _initPage : function() {
            var that = this;
            $('#pages').remove();
            if(this.DATA.pageModel.endPage > 1) {
                var prevHtml = '<a id="prevpage" href="javascript:;">'+that.LANGS[that.LANG].prev+'</a>',
                    nextHtml = '<a id="nextpage" href="javascript:;">'+that.LANGS[that.LANG].next+'</a>',
                    firstHtml = '<a id="firstpage" href="javascript:;">'+that.LANGS[that.LANG].first+'</a>',
                    endHtml = '<a id="endpage" href="javascript:;">'+that.LANGS[that.LANG].end+'</a>';

                if(this.DATA.pageModel.currentPage == 1) {
                    prevHtml = '<a id="prevpage" href="javascript:;" class="unactive">'+that.LANGS[that.LANG].prev+'</a>';
                    firstHtml = '<a id="firstpage" href="javascript:;" class="unactive">'+that.LANGS[that.LANG].first+'</a>';
                }
                if(this.DATA.pageModel.currentPage == this.DATA.pageModel.endPage) {
                    nextHtml = '<a id="nextpage" href="javascript:;" class="unactive">'+that.LANGS[that.LANG].next+'</a>';
                    endHtml = '<a id="endpage" href="javascript:;" class="unactive">'+that.LANGS[that.LANG].end+'</a>';
                }
                var pageHtml = '<div id="pages">' + firstHtml + prevHtml + this.DATA.pageModel.currentPage + '/' + this.DATA.pageModel.endPage + nextHtml + endHtml + '</div>';
                $(pageHtml).insertAfter($('#databox'));
            }
        },

        _clickPage : function (e) {
            var	self = $(e.target).get(0).tagName == 'A' ? $(e.target) : $(e.target).closest('a');
            if(self.hasClass('unactive') || this.QUERYING) return;
            if(self.attr('id') == 'nextpage') {
                this.PAGE++;
            } else if(self.attr('id') == 'prevpage') {
                this.PAGE--;
            } else if(self.attr('id') == 'firstpage') {
                this.PAGE = 1;
            } else if(self.attr('id') == 'endpage') {
                this.PAGE = this.DATA.pageModel.endPage;
            }
            this._queryData();
            $(window).scrollTop($('#tablecontain').offset().top);
        },

        _createLabel : function () {
            var that = this;
            if(!that.INITLABEL) {
                $('#tableheader').html($('#tablecontain').find('thead').html()).show();
                that.lablekeys = [];
                $('#tableheader').find('th').each(function (i) {
                    var th = $('#tablecontain').find('thead th').eq(i);
                    $(this).width(th.width()+12);
                    th.attr('ismaplabel') == '1' && that.lablekeys.push(th.attr('code'));
                    var html = th.attr('ismaplabel') == '1' ? '<a href="javascript:;"><input id="label_'+i+'" type="checkbox" value="'+th.attr('code')+'" data-type="maplabel" checked /><label for="label_'+i+'">'+that.LANGS[that.LANG].join+'</label>': '';
                    $(this).html(html);
                });
                $('#tableheader').width($('#tablecontain').width());
                that.INITLABEL = true;

            } else {
                $('#tableheader').find('th').each(function (i) {
                    var th = $('#tablecontain').find('thead th').eq(i);
                    $(this).width(th.width() + 12);
                });
                $('#tableheader').width($('#tablecontain').width());
            }
        },

        _clickLabel : function(){
            var that = this;
            that.lablekeys = [];
            $.each($('[data-type="maplabel"]'), function(){
                if($(this).prop('checked') == true) {
                    that.lablekeys.push($(this).val());
                }
            });
            this._createMap();
        },

        //下载文件
        _downLoad : function(e) {
            var that = this,
                self = $(e.target).get(0).tagName == 'button' ?  $(e.target) : $(e.target).closest('button');
            var type = self.attr('down-load');
            if("ActiveXObject" in window) {
                window.open(APP_URL+'?app=system&controller=hgdata&action=download&code=' + that.CODE +'&name='+ that.NAME +'&downtype='+type+'&lang='+that.LANG+'&page=' + that.PAGE+'&query='+JSON.stringify(that.QUERY));
            } else {
                $('#tablecontain').tableExport({
                    filename: that.NAME,
                    format: type == 'CSV' ? 'csv' : 'txt',
                    head_delimiter: type == 'CSV' ? ","  : "\t",
                    column_delimiter: type == 'CSV' ? ","  : "\t"
                });
            }
        },

        _searchMenus : function(){
            var that = this,
                text = $.trim($('.categorysearch form').find('[name="text"]').val());
            if(text == '') return;
            $.ajax({
                type : 'get',
                url : APP_URL + '?app=system&controller=hgdata&action=searchcategory',
                data : {'lang':that.LANG, 'text':text},
                dataType : 'jsonp',
                beforeSend : function () {
                    $('#search_menu').html('<img src="'+IMG_URL+'templates/silkroad/diy/image/timg.gif"/>').show();
                    $('.default_menu').hide();
                },
                success : function(s) {
                    if(!s.error) {
                        var html = '';
                        if(!s.data.length) {
                            html = '<li style="border: none;">'+that.LANGS[that.LANG]['nodata']+'</li>';
                        }
                        $.each(s.data, function(i) {
                            var table = '<span class="s_tablename">'+s.data[i].tablename+'</span>';
                            var label = '<span class="s_lable">'+s.data[i].lable+'：</span>';
                            var name = '<span class="s_name">'+s.data[i].name+'</span>';
                            html += '<li><h3 data-id="'+s.data[i].tablecode+'" data-attr="'+s.data[i].attr+'" data-value="'+s.data[i].name+'">'+table + label + name +'</h3></li>';
                        });
                        $('#search_menu').html(html).show();
                    } else {
                        var html = '<li style="border: none;">'+that.LANGS[that.LANG]['nodata']+'</li>';
                        $('#search_menu').html(html).show();
                    }
                },
                error : function() {
                    var html = '<li style="border: none;">'+that.LANGS[that.LANG]['nodata']+'</li>';
                    $('#search_menu').html(html).show();
                }

            });
            return;
        },

        init : function(){
            this.LANG = typeof LANG != 'undefined' ? LANG : 'cn';
            //菜单显示隐藏
            $('[data-type="show"]').on('click', function(){
                if($('[data-type="showbox"]').is(':hidden')) {
                    $('[data-type="showbox"]').slideDown();
                    $(this).addClass('menuopen');
                } else {
                    $('[data-type="showbox"]').slideUp();
                    $(this).removeClass('menuopen');
                }
            });
            $('.categorysearch form').on('submit', this._searchMenus.bind(this)); //搜索栏目

            $('.categorysearch form').on('reset', function(){
                $('#search_menu').empty().hide();
                $('.default_menu').show();
            }); //重置搜索
            $('.yq-tab-menu1').on('click', 'li', this._changeTab.bind(this));
            $('.container').delegate('.data-button', 'click', this._searchData.bind(this));
            $('.container').delegate('#prevpage, #nextpage, #firstpage, #endpage', 'click', this._clickPage.bind(this));
            $('[down-load]').on('click', this._downLoad.bind(this));
            $('#tableheader').delegate('[data-type="maplabel"]', 'click', this._clickLabel.bind(this));
            $('.yq-tab-menu1 li').eq(0).trigger('click');
        }
    };

    return HG;
})(jQuery);