(function(){
var data, form, tableList, tableAdd;

var _init = function() {
	tableList = $('.table_list');
	tableAdd = $('.table_add');

	var bindSelect = function (elm) {
		elm.bind('click', function(){
			$.datapicker({
				url: '?app=system&controller=port&action=picker&modelid=8',
				picked: function(items){
					var baseUrl = APP_URL + '?app=vote&controller=vote&action=ajaxresult'
					$.ajaxSetup({jsonp:'jsoncallback'});
					$.get(baseUrl, {
						contentid: items[0].contentid
					}, function(response){
						form[0].contentid.value = items[0].contentid;
						form[0].created.value = items[0].published;
						form[0].url.value = items[0].url;
						form[0]['vote[catid]'].value = response.catid;
						form[0]['vote[title]'].value = items[0]['title'];
						form[0]['vote[color]'].value = response['color'];
						form[0]['vote[type]'].value = response['type'];
						form[0]['vote[maxoptions]'].value = response['maxoptions'];
						form[0]['vote[display]'].value = response['display'];
						form[0]['vote[thumb_width]'].value = response['thumb_width'];
						form[0]['vote[thumb_height]'].value = response['thumb_height'];
						form[0]['vote[thumb]'].value = items[0]['thumb'];
						loadSubmittedContent();
					}, 'jsonp');
				},
				multiple: false
			});
			return false;
		});
	}

	var loadSubmittedContent = function() {
		var thumb = form[0]['vote[thumb]'].value,
		title = form[0]['vote[title]'].value,
		url = form[0]['url'].value,
		created = new Date(parseInt(form[0]['created'].value, 10) * 1000),
		date = created.getFullYear() + '-' + created.getMonth() + '-' + created.getDate() + ' ' + created.getHours() + ':' + created.getMinutes();

		thumb = thumb ? thumb : IMG_URL + 'images/nopic.gif';
		if (thumb.indexOf('http') !== 0) {
			thumb = UPLOAD_URL + thumb;
		}
		tableList.html('<tr><td rowspan="3" width="160"><a href="'+url+'" target="_blank"><img src="'+thumb+'" /></a></td><td><h4><a href="'+url+'" target="_blank">'+title+'</a></h4></td></tr>\
			<tr><td>'+date+'</td></tr>\
			<tr><td><button type="button">重新选择</button></td></tr>');
		bindSelect(tableList.find('button'));
	}

	if (form[0].contentid.value) {
		// 加载已提交内容
		loadSubmittedContent();
	} else {
		bindSelect(tableList.find('button'));
	}

	tableAdd.hide();
	form.find('.tabs').find('li').bind('click', function(){
		var li = $(this),
		ul = li.parent(),
		index = ul.children('li').index(li);
		if (index == 0) {
			form[0].select_type.value = 1;
		} else {
			form[0].select_type.value = 0;
		}
		ul.children('.active').removeClass('active');
		li.addClass('active');
		form.find('.tab').hide().eq(index).show();
	});

	option.add();
	option.add();

}

DIY.registerEngine('vote', {
	dialogWidth : 750,
	addFormReady:function(_form, dialog) {
		form = _form;
		_init();
		dialog.find('[data-role=catid]').selectree();
	},
	editFormReady:function(_form, dialog) { 
		form = _form;
		_init();
		dialog.find('[data-role=catid]').selectree();
	},
	afterRender: function(widget) { },
	beforeSubmit:function(form, dialog){
		if (tableList.css('display') == 'none') {
			if (form.find('[name="catid"]').val() == '') {
				ct.error('请选择栏目');
				return false;
			}
			form[0].contentid.value = '';
			form[0].created.value = '';
			form[0].url.value = '';
			form[0]['vote[thumb]'].value = '';
			form[0]['vote[catid]'].value = form[0]['catid'].value;
			form[0]['vote[title]'].value = form[0]['title'].value;
			form[0]['vote[color]'].value = form[0]['color'].value;
			form[0]['vote[type]'].value = form.find('[name="type"]:checked').val()
			form[0]['vote[maxoptions]'].value = form[0]['maxoptions'].value;
			form[0]['vote[display]'].value = form.find('[name="display"]:checked').val()
			form[0]['vote[thumb_width]'].value = form[0]['thumb_width'].value;
			form[0]['vote[thumb_height]'].value = form[0]['thumb_height'].value;
		}
	},
	afterSubmit:function(form, dialog){}
});

})();