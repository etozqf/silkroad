var HG = (function($){
	var HG = {
		PAGE : 1,
		NAME : '',
		CODE : '',
		QUERYING : false,
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
				'loadfail' : 'Data loading failed'
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
				'loadfail' : '数据加载失败'
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
						that._initSearch();  //初始化搜索
						that._createTable(); //创建表格
						that._createLabel(); //创建图例
						that._createMap();   //创建图形
						that._initPage();  //初始化分页
						that._createScroll(); //创建横向模拟滚动条
					} else {
						that.QUERYING = false;
						alert(s.info);
					}
				},
				error: function() {
					that.QUERYING = false;
					alert(that.LANGS[that.LANG].loadfail);
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
			var piewidth = 694;
			linewidth = linewidth < 694 ? 694 : linewidth;
			barwidth = barwidth < 694 ? 694 : barwidth;

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
			var	self = $(e.target).get(0).tagName == 'H3' ? $(e.target).closest('li') : $(e.target);
			that.PAGE = 1;
			that.NAME = self.find('h3').text();
			that.CODE = self.find('h3').attr('data-id');
			that.DATA = '';
			that.QUERY = '';
			that.INITLABEL = false;
			self.addClass('active').siblings().removeClass('active');
			that._queryData();
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
				$(pageHtml).insertAfter($('.time-data'));
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

		init : function(){
			this.LANG = typeof LANG != 'undefined' ? LANG : 'cn';
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

var HONGG = (function($){
	var HONGG = {
		PAGE : 1,
		NAME : '',
		CODE : '',
		QUERYING : false,
		QUERY : '',
		DATA : '',
		CID : 0,
		LANGS : {
			'en' : {
				'searchtip' : '*You can hold down the Ctrl key to multiple conditions',
				'search' : 'Search',
				'all' : 'All',
				'prev' : 'Prev',
				'next' : 'Next',
				'first' : 'First',
				'end' : 'End',
				'loadfail' : 'Data loading failed'
			},
			'cn' : {
				'searchtip' : '*条件可以按住Ctrl键进行多选',
				'search' : '查询',
				'all' : '全部',
				'prev' : '上页',
				'next' : '下页',
				'first' : '首页',
				'end' : '尾页',
				'loadfail' : '数据加载失败'
			}
		},
		INITLABEL : false,
		_queryData : function(){
			var that = this;
			if(that.QUERYING) return;
			that.QUERYING = true;
			$.ajax({
				type: 'get',
				url: APP_URL+'?app=system&controller=honggdata&action=query',
				data : {'scid':that.SCID, 'query':that.QUERY, 'lang':that.LANG, 'page':that.PAGE},
				dataType:'jsonp',
				success: function(s) {
					if(!s.error) {
						that.DATA = s.data;
						that.QUERYING = false;
						that._createTable(); //创建表格
						that._createMap();   //创建图形
						that._initPage();  //初始化分页
						that._initSearch();
					} else {
						that.QUERYING = false;
						alert(s.info);
					}
				},
				error: function() {
					that.QUERYING = false;
					alert(that.LANGS[that.LANG].loadfail);
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

		_createTable : function() {
			var that = this;
			if(!that.DATA) return;

			$('#tablecontain').empty();
			var tabletitles = that.DATA.tabletitles;
			var titlebody = '<thead><tr class="data-box-header">';
			$.each(tabletitles, function(i){
				var ismaplabel = tabletitles[i].ismap == 1 ? 'ismaplabel="1"' : 'ismaplabel="0"';
				titlebody += '<th code="'+tabletitles[i].code+'">'+tabletitles[i].name+'</th>';
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
		},

		_createMap : function() {
			var that = this;
			var barvalue = [];
			var linevalue = [];
			var min, max;
			$.each(that.DATA.maptitles, function (i) {
				var b = {'name':that.DATA.maptitles[i].name, 'type':'bar', 'barWidth':'20', 'barMinHeight':'5', 'barGap':'25%', 'data':[]};
				var l = {'name':that.DATA.maptitles[i].name, 'type':'line', 'data':[]};
				$.each(that.DATA.mapdata, function(j) {
					that.DATA.mapdata[j][i].value = parseFloat(that.DATA.mapdata[j][i].value);
					if(i==0 && j==0) {
						min = that.DATA.mapdata[j][i].value;
						max = that.DATA.mapdata[j][i].value;
					} else {
						min = min > that.DATA.mapdata[j][i].value ? that.DATA.mapdata[j][i].value : min;
						max = max < that.DATA.mapdata[j][i].value ? that.DATA.mapdata[j][i].value : max;
					}
					b.data.push(that.DATA.mapdata[j][i].value);
					l.data.push(that.DATA.mapdata[j][i].value);
				});
				barvalue.push(b);
				linevalue.push(l);
			});
			max = parseFloat(max);
			min = parseFloat(min);
			cha = (max - min) / 6;
			console.log(cha+'|'+min+'|'+max);
			cha = cha < 1 ? cha.toFixed(2) : Math.round(cha);
			cha = parseFloat(cha);
			min = min < cha && min > 0 ? 0 : min - cha;
			max = max + cha;
			max = max.toFixed(3);
			min = min.toFixed(3);
			if(cha > 9) {
				min = parseInt(min);
				max = Math.ceil(max);
			}

			console.log(cha+'|'+min+'|'+max);

			var barwidth = that.DATA.mapdata.length * 60;
			var linewidth = 694;
			barwidth = barwidth < 694 ? 694 : barwidth;

			$('.n_ind_contmain .pic').eq(1).width(barwidth);
			$('.n_ind_contmain .pic').eq(0).width(linewidth);
			$('.n_ind_contmain').scrollLeft(0);


			var echart_bar = echarts.init($('#barechart').get(0));  //柱状图
			var echart_line = echarts.init($('#lineechart').get(0)); //折线图

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
					left:'100',
					height:'300',
					width:barwidth - 120
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
					name : that.DATA.mapunit ? that.DATA.mapunit : '',
					splitNumber : 8,
					min:min,
					max:max
				}]
			};
			option.series = barvalue;
			echart_bar.setOption(option);
			option = {};
			option = {
				title: {
					text: that.NAME
				},
				tooltip: {
					trigger: 'axis',
					axisPointer: {
						animation: false
					},
					formatter: function (params) {
						return params[0].name + '<br />' + params[0].value;
					}
				},
				grid: {
					left: '3%',
					right: '4%',
					bottom: '3%',
					containLabel: true
				},
				xAxis: {
					type: 'category',
					data: that.DATA.mapcategory,
					// axisLabel: {
					// 	formatter: function (value, idx) {
					// 		var date = new Date(value);
					// 		return idx === 0 ? value : [date.getMonth() + 1, date.getDate()].join('-');
					// 	}
					// },
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
					name : that.DATA.mapunit ? that.DATA.mapunit : '',
					splitNumber : 8,
					min:min,
					max:max,
					boundaryGap: false,
					splitLine: {
						show: false
					}
				},
				series: [{
					type: 'line',
					data: linevalue[0].data,
					hoverAnimation: false,
					symbolSize: 6,
					itemStyle: {
						normal: {
							color: '#c23531'
						}
					},
					showSymbol: false
				}]
			};
			echart_line.setOption(option);
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
				$(pageHtml).insertAfter($('.time-data'));
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
			if($(window).scrollTop() > $('#tablecontain').offset().top) {
				$(window).scrollTop($('#tablecontain').offset().top);
			}
		},

		//下载文件
		_downLoad : function(e) {
			var that = this,
				self = $(e.target).get(0).tagName == 'button' ?  $(e.target) : $(e.target).closest('button');
			var type = self.attr('down-load');
			if("ActiveXObject" in window) {
				window.open(APP_URL+'?app=system&controller=honggdata&action=download&scid=' + that.SCID +'&name='+ that.NAME +'&downtype='+type+'&lang='+that.LANG+'&page=' + that.PAGE+'&query='+JSON.stringify(that.QUERY));
			} else {
				$('#tablecontain').tableExport({
					filename: that.NAME,
					format: type == 'CSV' ? 'csv' : 'txt',
					head_delimiter: type == 'CSV' ? ","  : "\t",
					column_delimiter: type == 'CSV' ? ","  : "\t"
				});
			}
		},

		_getCategory : function(e) {
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
						self.find('em').html('+');
					} else {
						self.children('h3').addClass('open');
						self.children('h3').find('em').html('-');
						sonul.show();
					}
					that.QUERYING = false;
					return;
				}
			}

			if(that.SCID && that.allowQuery) {
				that.PAGE = 1;
				that.NAME = self.children('h3').text();
				$('[ispid="0"]').removeClass('active');
				self.addClass('active');
				that._queryData();
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
						alert(that.LANGS[that.LANG].loadfail);
					} else {
						that.QUERYING = false;
						that.CATEGORY = s.data;
						that._createCategory();
						if(!that.inited) {
							that.inited = true;
							//第一次打开页面，直接请求默认数据
							that.NAME = 'GDP：累计值';
							that.SCID = 'EMM00000004';
							that._queryData();
						}
					}
				},
				error : function () {
					that.QUERYING = false;
					alert(that.LANGS[that.LANG].loadfail);
				}
			});
		},

		_createCategory : function() {
			var that = this;
			if(!that.CID) {
				var html = '';
				$.each(that.CATEGORY, function(i) {
					if(that.CATEGORY[i].ispid == 1) {
						html += '<li data-cid="'+that.CATEGORY[i].cid+'" ispid="'+that.CATEGORY[i].ispid+'" class="parentli"><h3><em>+</em>'+that.CATEGORY[i].cname+'</h3></li>';
					} else {
						html += '<li data-cid="'+that.CATEGORY[i].cid+'" ispid="'+that.CATEGORY[i].ispid+'"><h3>'+that.CATEGORY[i].cname+'</h3></li>';
					}

				});
				$('.default_menu').html(html).attr('level', 0);
			} else {
				var level = $('[data-cid="'+that.CID+'"]').closest('ul').attr('level');
				level++;
				var html = '<ul level="'+level+'">';
				$.each(that.CATEGORY, function(i) {
					if(that.CATEGORY[i].ispid > 0) {
						html += '<li data-cid="'+that.CATEGORY[i].cid+'" data-scid="'+that.CATEGORY[i].scid+'" ispid="'+that.CATEGORY[i].ispid+'" class="parentli"><h3><em>+</em>'+that.CATEGORY[i].cname+'</h3></li>';
					} else {
						html += '<li data-cid="'+that.CATEGORY[i].cid+'" data-scid="'+that.CATEGORY[i].scid+'" ispid="'+that.CATEGORY[i].ispid+'" ><h3>'+that.CATEGORY[i].cname+'</h3></li>';
					}
				});
				html += '</ul>';
				$('[data-cid="'+that.CID+'"]').find('h3').addClass('open');
				$('[data-cid="'+that.CID+'"]').find('em').text('-');
				$('[data-cid="'+that.CID+'"]').append(html);
			}
		},

		_searchMenu : function (e) {
			var that = this,
				self = $(e.target).get(0).tagName != 'button' ? $(e.target).closest('button') : $(e.target),
				input = self.siblings('input');
			if(input.val() == '') {
				$('#search_menu').hide();
				$('.default_menu').show();
				return;
			}
			if(that.QUERYING) return;
			that.QUERYING = true;
			$.ajax({
				type:'get',
				url:APP_URL+'?app=system&controller=honggdata&action=searchcategory',
				data:{'text':input.val()},
				dataType:'jsonp',
				success:function(s){
					that.QUERYING = false;
					that.searchMenu = s.data;
					that._initSearchMenu();
				},
				error:function () {
					that.QUERYING = false;
					alert(that.LANGS[that.LANG].loadfail);

				}
			});
		},

		_initSearchMenu : function(){
			var that = this;
			if(that.searchMenu) {
				var html = '';
				$.each(that.searchMenu, function (i) {
					html += '<li data-cid="'+that.searchMenu[i].cid+'" data-scid="'+that.searchMenu[i].scid+'" ispid="'+that.searchMenu[i].ispid+'"><h3>'+that.searchMenu[i].cname+'</h3></li>';
				});
				$('.default_menu').hide();
				$('#search_menu').html(html).show();
			}
		},

		init : function() {
			this.LANG = typeof LANG != 'undefined' ? LANG : 'cn';
			this.inited = false;
			this.CID = '0001';
			this._getCategory();
			$('.yq-tab-menu1').delegate('li', 'click', this._getCategory.bind(this));
			$('.container').delegate('.data-button', 'click', this._searchData.bind(this));
			$('.container').delegate('#prevpage, #nextpage, #firstpage, #endpage', 'click', this._clickPage.bind(this));
			$('[down-load]').on('click', this._downLoad.bind(this));
			$('[name="searchCategory"]').on('click', this._searchMenu.bind(this));
		}
	};

	return HONGG;
})(jQuery);

var CEIC = (function ($) {
	var CEIC =
	{
		PAGE : 1,
		NAME : '',
		CODE : '',
		QUERYING : false,
		QUERY : '',
		DATA : '',
		CID : 0,
		LANGS : {
			'en' : {
				'searchtip' : '*You can hold down the Ctrl key to multiple conditions',
				'search' : 'Search',
				'all' : 'All',
				'prev' : 'Prev',
				'next' : 'Next',
				'first' : 'First',
				'end' : 'End',
				'loadfail' : 'Data loading failed'
			},
			'cn' : {
				'searchtip' : '*条件可以按住Ctrl键进行多选',
				'search' : '查询',
				'all' : '全部',
				'prev' : '上页',
				'next' : '下页',
				'first' : '首页',
				'end' : '尾页',
				'loadfail' : '数据加载失败'
			}
		},

		_queryData : function(){
			var that = this;
			if(that.QUERYING) return;
			that.QUERYING = true;
			$.ajax({
				type: 'get',
				url: APP_URL+'?app=system&controller=ceicdata&action=query',
				data : {'scid':that.SCID, 'query':that.QUERY, 'lang':that.LANG, 'page':that.PAGE},
				dataType:'jsonp',
				success: function(s) {
					if(!s.error) {
						that.DATA = s.data;
						that.QUERYING = false;
						that._createTable(); //创建表格
						that._createMap();   //创建图形
						that._initPage();  //初始化分页
						that._initSearch();
					} else {
						that.QUERYING = false;
						alert(s.info);
					}
				},
				error: function() {
					that.QUERYING = false;
					alert(that.LANGS[that.LANG].loadfail);
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

		_createTable : function() {
			var that = this;
			if(!that.DATA) return;

			$('#tablecontain').empty();
			var tabletitles = that.DATA.tabletitles;
			var titlebody = '<thead><tr class="data-box-header">';
			$.each(tabletitles, function(i){
				var ismaplabel = tabletitles[i].ismap == 1 ? 'ismaplabel="1"' : 'ismaplabel="0"';
				titlebody += '<th code="'+tabletitles[i].code+'">'+tabletitles[i].name+'</th>';
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
		},

		_createMap : function() {
			var that = this;
			var barvalue = [];
			var linevalue = [];
			var min, max;
			$.each(that.DATA.maptitles, function (i) {
				var b = {'name':that.DATA.maptitles[i].name, 'type':'bar', 'barWidth':'20', 'barMinHeight':'5', 'barGap':'25%', 'data':[]};
				var l = {'name':that.DATA.maptitles[i].name, 'type':'line', 'data':[]};
				$.each(that.DATA.mapdata, function(j) {
					that.DATA.mapdata[j][i].value = parseFloat(that.DATA.mapdata[j][i].value);
					if(i==0 && j==0) {
						min = that.DATA.mapdata[j][i].value;
						max = that.DATA.mapdata[j][i].value;
					} else {
						min = min > that.DATA.mapdata[j][i].value ? that.DATA.mapdata[j][i].value : min;
						max = max < that.DATA.mapdata[j][i].value ? that.DATA.mapdata[j][i].value : max;
					}
					b.data.push(that.DATA.mapdata[j][i].value);
					l.data.push(that.DATA.mapdata[j][i].value);
				});
				barvalue.push(b);
				linevalue.push(l);
			});
			max = parseFloat(max);
			min = parseFloat(min);
			cha = (max - min) / 6;
			console.log(cha+'|'+min+'|'+max);
			cha = cha < 1 ? cha.toFixed(2) : Math.round(cha);
			cha = parseFloat(cha);
			min = min < cha && min > 0 ? 0 : min - cha;
			max = max + cha;
			max = max.toFixed(3);
			min = min.toFixed(3);
			if(cha > 9) {
				min = parseInt(min);
				max = Math.ceil(max);
			}

			console.log(cha+'|'+min+'|'+max);

			var barwidth = that.DATA.mapdata.length * 60;
			var linewidth = 694;
			barwidth = barwidth < 694 ? 694 : barwidth;

			$('.n_ind_contmain .pic').eq(1).width(barwidth);
			$('.n_ind_contmain .pic').eq(0).width(linewidth);
			$('.n_ind_contmain').scrollLeft(0);


			var echart_bar = echarts.init($('#barechart').get(0));  //柱状图
			var echart_line = echarts.init($('#lineechart').get(0)); //折线图

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
					left:'100',
					height:'300',
					width:barwidth - 120
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
					name : that.DATA.mapunit ? that.DATA.mapunit : '',
					splitNumber : 8,
					min:min,
					max:max
				}]
			};
			option.series = barvalue;
			echart_bar.setOption(option);
			option = {};
			option = {
				title: {
					text: that.NAME
				},
				tooltip: {
					trigger: 'axis',
					axisPointer: {
						animation: false
					},
					formatter: function (params) {
						return params[0].name + '<br />' + params[0].value;
					}
				},
				grid: {
					left: '3%',
					right: '4%',
					bottom: '3%',
					containLabel: true
				},
				xAxis: {
					type: 'category',
					data: that.DATA.mapcategory,
					// axisLabel: {
					// 	formatter: function (value, idx) {
					// 		var date = new Date(value);
					// 		return idx === 0 ? value : [date.getMonth() + 1, date.getDate()].join('-');
					// 	}
					// },
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
					name : that.DATA.mapunit ? that.DATA.mapunit : '',
					splitNumber : 8,
					min:min,
					max:max,
					boundaryGap: false,
					splitLine: {
						show: false
					}
				},
				series: [{
					type: 'line',
					data: linevalue[0].data,
					hoverAnimation: false,
					symbolSize: 6,
					itemStyle: {
						normal: {
							color: '#c23531'
						}
					},
					showSymbol: false
				}]
			};
			echart_line.setOption(option);
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
				$(pageHtml).insertAfter($('.time-data'));
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
			if($(window).scrollTop() > $('#tablecontain').offset().top) {
				$(window).scrollTop($('#tablecontain').offset().top);
			}
		},

		//下载文件
		_downLoad : function(e) {
			var that = this,
				self = $(e.target).get(0).tagName == 'button' ?  $(e.target) : $(e.target).closest('button');
			var type = self.attr('down-load');
			if("ActiveXObject" in window) {
				window.open(APP_URL+'?app=system&controller=ceicdata&action=download&scid=' + that.SCID +'&name='+ that.NAME +'&downtype='+type+'&lang='+that.LANG+'&page=' + that.PAGE+'&query='+JSON.stringify(that.QUERY));
			} else {
				$('#tablecontain').tableExport({
					filename: that.NAME,
					format: type == 'CSV' ? 'csv' : 'txt',
					head_delimiter: type == 'CSV' ? ","  : "\t",
					column_delimiter: type == 'CSV' ? ","  : "\t"
				});
			}
		},

		_getCategory : function(e) {
			var that = this;
			that.PAGE = 1;
			that.QUERY = '';
			if(that.QUERYING) return;
			that.allowQuery = true;
			if(typeof e != 'undefined') {
				e.stopPropagation();
				var self = $(e.target).get(0).tagName != 'LI' ? $(e.target).closest('li') : $(e.target);
				that.CID = self.attr('data-cid');
				that.LEVEL = self.attr('data-level');
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
						self.find('em').html('+');
					} else {
						self.children('h3').addClass('open');
						self.children('h3').find('em').html('-');
						sonul.show();
					}
					that.QUERYING = false;
					return;
				}
			}

			if(that.SCID && that.allowQuery) {
				that.PAGE = 1;
				that.NAME = self.children('h3').text();
				$('[ispid="0"]').removeClass('active');
				self.addClass('active');
				that._queryData();
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
						alert(that.LANGS[that.LANG].loadfail);
					} else {
						that.QUERYING = false;
						that.CATEGORY = s.data;
						that._createCategory();
						if(!that.inited) {
							that.inited = true;
							//第一次打开页面，直接请求默认数据
							that.NAME = that.LANG == 'cn' ? '互联网用户数量' : 'Gross Domestic Product (GDP)'; //that.LANG == 'cn' ? '国内生产总值' : 'CN：GDP';
							that.SCID = that.LANG == 'cn' ? '246702903' : '368921927';//'369703417';
							that._queryData();
						}
					}
				},
				error : function () {
					that.QUERYING = false;
					alert(that.LANGS[that.LANG].loadfail);
				}
			});
		},

		_createCategory : function () {
			var that = this;
			if(!that.CID) {
				var html = '';
				$.each(that.CATEGORY, function(i) {
					if(that.CATEGORY[i].ispid == 1) {
						html += '<li data-cid="'+that.CATEGORY[i].cid+'" data-level="'+that.CATEGORY[i].nodelevel+'" ispid="'+that.CATEGORY[i].ispid+'" class="parentli"><h3><em>+</em>'+that.CATEGORY[i].cname+'</h3></li>';
					} else {
						html += '<li data-cid="'+that.CATEGORY[i].cid+'" data-level="'+that.CATEGORY[i].nodelevel+'" ispid="'+that.CATEGORY[i].ispid+'"><h3>'+that.CATEGORY[i].cname+'</h3></li>';
					}

				});
				$('.default_menu').html(html).attr('level', 0);
			} else {
				var level = $('[data-cid="'+that.CID+'"]').closest('ul').attr('level');
				level++;
				var html = '<ul level="'+level+'">';
				$.each(that.CATEGORY, function(i) {
					if(that.CATEGORY[i].ispid > 0) {
						html += '<li data-cid="'+that.CATEGORY[i].cid+'" data-level="'+that.CATEGORY[i].nodelevel+'" data-scid="'+that.CATEGORY[i].scid+'" ispid="'+that.CATEGORY[i].ispid+'" class="parentli"><h3><em>+</em>'+that.CATEGORY[i].cname+'</h3></li>';
					} else {
						html += '<li data-cid="'+that.CATEGORY[i].cid+'" data-level="'+that.CATEGORY[i].nodelevel+'" data-scid="'+that.CATEGORY[i].scid+'" ispid="'+that.CATEGORY[i].ispid+'" ><h3>'+that.CATEGORY[i].cname+'</h3></li>';
					}
				});
				html += '</ul>';
				$('[data-cid="'+that.CID+'"]').find('h3').addClass('open');
				$('[data-cid="'+that.CID+'"]').find('em').text('-');
				$('[data-cid="'+that.CID+'"]').append(html);
			}
		},

		_searchMenu : function (e) {
			var that = this,
				self = $(e.target).get(0).tagName != 'button' ? $(e.target).closest('button') : $(e.target),
				input = self.siblings('input');
			if(input.val() == '') {
				$('#search_menu').hide();
				$('.default_menu').show();
				return;
			}
			if(that.QUERYING) return;
			that.QUERYING = true;
			$.ajax({
				type:'get',
				url:APP_URL+'?app=system&controller=ceicdata&action=searchcategory',
				data:{'text':input.val(), 'lang':that.LANG},
				dataType:'jsonp',
				success:function(s){
					that.QUERYING = false;
					that.searchMenu = s.data;
					that._initSearchMenu();
				},
				error:function () {
					that.QUERYING = false;
					alert(that.LANGS[that.LANG].loadfail);

				}
			});
		},

		_initSearchMenu : function(){
			var that = this;
			if(that.searchMenu) {
				var html = '';
				$.each(that.searchMenu, function (i) {
					html += '<li data-cid="'+that.searchMenu[i].cid+'" data-level="'+that.searchMenu[i].nodelevel+'" data-scid="'+that.searchMenu[i].scid+'" ispid="'+that.searchMenu[i].ispid+'"><h3>'+that.searchMenu[i].cname+'</h3></li>';
				});
				$('.default_menu').hide();
				$('#search_menu').html(html).show();
			}
		},

		init : function () {
			this.LANG = typeof LANG != 'undefined' ? LANG : 'cn';
			this.CID = 'GLOBAL';//'CN';
			this.inited = false;
			this._getCategory();
			$('.yq-tab-menu1').delegate('li', 'click', this._getCategory.bind(this));
			$('.container').delegate('.data-button', 'click', this._searchData.bind(this));
			$('.container').delegate('#prevpage, #nextpage, #firstpage, #endpage', 'click', this._clickPage.bind(this));
			$('[down-load]').on('click', this._downLoad.bind(this));
			$('[name="searchCategory"]').on('click', this._searchMenu.bind(this));
		}
	};
	return CEIC;
})(jQuery);


var FTEN = (function($){
	var FTEN = {
		QUERYING : false,
		LANGS : {
			'en' : {
				'loadfail' : 'Data loading failed'
			},
			'cn' : {
				'loadfail' : '数据加载失败'
			}
		},
		CLASSIFY : [],

		_getClassifyList : function() {
			var that = this;
			$.ajax({
				type : 'get',
				url : APP_URL + '?app=system&controller=ften&action=rank&lang='+that.LANG,
				dataType : 'jsonp',
				success : function (s) {
					if(s.error) {
						alert(s.info);
						return;
					}
					that.CLASSIFY = s.data;
					that._createClassify();
				},
				error : function() {
					alert(that.LANGS[that.LANG]['loadfail']);
				}

			});
		},

		_createClassify : function(){
			var that = this;
			if(that.CLASSIFY) {
				var classkeys_1 = ['01','02','03','01','02','03','01','04','05','06','04','05','06','04','07','08','09','07','08','09','07','10','11','12','10','11','12','10'];
				var classkeys_2 = ['10','11','12','10','11','12','10'];
				var html = '';
				$.each(that.CLASSIFY, function(i){
					var classkey = i <= 27 ?  classkeys_1[i] : classkeys_2[(i-28)%7];
					html += '<li class="bg'+classkey+'"><a href="'+that.CLASSIFY[i]['url']+'" title="'+that.CLASSIFY[i]['name']+'">'+that.CLASSIFY[i]['name']+'</a></li>';
				});
				$('#classify_list').html(html);
			}
		},

		_createCompany : function(){
			if(window.location.hash.length <= 1) return;
			var that = this, classname = window.location.hash.replace('#', '');
			classname = decodeURI(classname);
			$('#classify_name').html(classname);
			$.ajax({
				type : 'get',
				url : APP_URL + '?app=system&controller=ften&action=getcompany',
				dataType : 'jsonp',
				data : {'classname':encodeURI(classname), 'lang':that.LANG},
				success : function(s) {
					if(s.error) {
						alert(s.info);
						return;
					}
					var html = '';
					$.each(s.data, function(i){
						html += '<li><a href="'+s.data[i].url+'" target="_blank"><em class="f-l">&bull;</em>'+s.data[i].name+'</a></li>';
					});
					$('#company_list').html(html);
					$('.classify-list').eq(0).show();
					$('.classify-list').eq(1).hide();
					var h = $('.classify-list-r').height();
					$('.classify-list-l').height(h);
					$('#classify_name').css('margin-top', (h-$('#classify_name').height())/2 + 'px');
				},
				error : function() {
					alert(that.LANGS[that.LANG]['loadfail']);
				}
			});
		},

		init : function () {
			this.LANG = typeof LANG != 'undefined' ? LANG : 'cn';
			if($('#classify_list').length) {
				this._getClassifyList();
			}
			if($('[name="keyword"]').length) {
				$('[name="keyword"]').autocompleter({
					source : APP_URL + '?app=system&controller=ften&action=search',
					dataType : 'jsonp',
					highlightMatches : true,
					limit:10,
					encodeURI : true,
					callback : function(value,index,s) {
						$('[name="keyword"]').val(s.label);
						window.location.href = s.url;
					}
				});
			}
			if($('#classify_name').length) {
				this._createCompany();
			}
		}
	};
	return FTEN;
})(jQuery);



