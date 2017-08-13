var trNum = 0;
var hitObj	= undefined;
$('#options').sortable({
	'handle':'td:first-child',
	'axis':'y',
	'start':function(e,u) {
		hitObj = u.item.children().eq(0);
		$(e.target).find('tr').find('td').css('border-top','1px solid #D0E6EC').css('padding-top', '4px');
	},
	'stop':function(e,u) {
		var rid	= u.item.children().eq(0).find('input.sort').val();
		$.each($(this).find("tr"), function(i, tr) {
			if ($(tr).children().eq(0).find('input.sort').val() == rid) {
				var c	= i + 1 - rid;
				option.move(hitObj.parent('tr'), c);
				return false;
			}
		});
		hitObj.parent('tr').find('td').css('padding-bottom', '5px');
		$(e.target).find('tr').find('td').css('border-top','none').css('padding-top', '5px');
		hitObj = undefined;
	}
});

var option =
{
	add: function (name, votes, sort, optionid, link, thumb)
	{
		var isTinyMCE = (typeof (tinyMCE) != 'undefined');
		trNum++;
		if (typeof name === 'undefined') name = '';
		if (typeof link === 'undefined') link = '';
		if (typeof thumb === 'undefined') thumb = '';
		if (typeof votes === 'undefined') votes = '';
        sort = parseInt(sort);
		if (!sort) sort = trNum;
		if (typeof optionid === 'undefined') optionid = '';
		
		html  = '<tr id="'+trNum+'" width="694">';
		html += '<td class="t_c" style="cursor:move;">';
		html += '<div data-role="sort" style="width:28px;">'+sort+'</div>';
		html += '<input type="hidden" name="option['+trNum+'][optionid]" id="optionid_'+trNum+'" value="'+optionid+'"/>';
		html += '<input type="hidden" name="option['+trNum+'][sort]" class="sort" id="sort_'+trNum+'" value="'+sort+'" size="1" onchange="option.order_sort('+trNum+', this.value)"/>';
		html += '</td>';
		html += '<td style="margin:0;"><input type="text" name="option['+trNum+'][name]" id="name_'+trNum+'" value="'+name+'" size="50" maxlength="100" uncount="1" style="width:'+ (isTinyMCE ? 268 : 318) +'px;" /></td>';
		/* 追加链接,图片 start */
		html += '<td><div style="position: relative; width: 94px; overflow:hidden;">';
		html += '<input type="text" class="vote-input-link" name="option['+trNum+'][link]" value="'+link+'" style="width:70px; padding-right:19px;" tips="'+link+'" />';
		html += '<div class="vote-ico-link" style="background:url('+IMG_URL+'js/lib/dropdown/bg.gif) no-repeat scroll 0 -96px transparent; top:0;right:4px;width:16px;height:20px;position: absolute;cursor: pointer;"></div>';
		html += '</div></td>';

		html += '<td><div style="position: relative; width: 105px; overflow:hidden;">';
		html += '<input type="text" class="vote-input-thumb" name="option['+trNum+'][thumb]" value="'+thumb+'" style="width:80px; padding-right:19px;" tips="'+thumb+'" />';
		html += '<div class="vote-ico-thumb" style="background:url('+IMG_URL+'js/lib/dropdown/bg.gif) no-repeat scroll 0 -70px transparent; top:0;right:20px;width:16px;height:20px;position: absolute;cursor: pointer;"></div>';
		html += '<div class="vote-ico-attachment" style="background:url('+IMG_URL+'css/images/attachment.png) no-repeat scroll transparent; top:4px;right:3px;width:16px;height:20px;position: absolute;cursor: pointer;"></div>';
		html += '</div></td>';
		/* 追加链接,图片 end */
		html += '<td class="t_c"><input class="" type="text" name="option['+trNum+'][votes]" id="votes_'+trNum+'" value="'+votes+'" size="4" style="width:47px;" /></td>';
		html += '<td style="padding:5px 0px;text-align:center">';
		html += '<img src="images/del.gif" height="16" width="16" alt="删除" title="删除" onclick="option.remove('+trNum+')" class="hand" style="padding:0 11px;" />';
		html += '</td></tr>';
		var tr = $(html);
		var uploader = tr.find('.vote-ico-thumb');
		tr.find('.vote-ico-thumb').uploader({
			script : '?app=vote&controller=vote&action=upload',
			fileDataName : 'Filedata',
			fileExt : '*.jpg;*.jpeg;*.gif;*.png;',   
			multi : false,
			complete : function(response, data) {
				if(response != '0') {
					uploader.prev().val(response).change();
				} else {
					ct.error(response.msg);
				}
			}
		}).css('position','absolute');
		tr.find('.vote-ico-attachment').click(function (){
			var url = '?app=system&controller=attachment&action=index&select=1&single=1&ext_limit=jpg,jpeg,png,gif&size_limit=0';
			var attachment = $(this);
			var d = window.cmstop.iframe({
					title:url,
					width:820,
					height:465
				},{
					ok : function(res){
						if (res.src) {
							attachment.prev().prev().val(res.src).change();
						}
						d.dialog('close');
					}
				});
		}).css('position','absolute');
		tr.find('.vote-input-link').attrTips('tips');
		tr.find('.vote-input-link').bind('change', function() {
			var val = tr.find('.vote-input-link').val();
			if (val) {
				tr.find('.vote-input-link').attr('tips', val);
			} else {
				tr.find('.vote-input-link').removeAttr('tips');
			}
		}).change();
        tr.find('.vote-ico-link').click(function() {
            var link = $(this);
            $.datapicker({
                picked:function(items) {
                    link.prev().val(items[0].url).change();
                }
            });
        });
		tr.find('.vote-input-thumb').attrTips('tips');
		tr.find('.vote-input-thumb').bind('change', function() {
			var val = tr.find('.vote-input-thumb').val();
			if (val) {
				tr.find('.vote-input-thumb').attr('tips', '<img src="'+val+'" />');
			} else {
				tr.find('.vote-input-thumb').removeAttr('tips');
			}
		}).change();
		$('#options').append(tr);
	},
	
	remove: function (line)
	{
		if($('#options>tr').length < 3)
		{
			ct.error('至少得保留两个投票选项');
			return false;
		}
		$('#'+line).remove();
		
		$.each($('#options>tr'), function(i, tr) {
			tr = $(tr);
			tr.find('[data-role=sort]').html(i+1);
			tr.find('.sort').val(i+1);
			tr[0].style.top = i*35 + 'px';
		});
		trNum--;
	},
	
	order_sort: function (i, val)
	{
		if(isNaN(val))
		{
			ct.warn('请输入阿拉伯数字！');
			$('#sort_'+i).val('0');
			return ;
		}
		
		var data = new Array();
		$('#options>tr').each(function(i){
			var id = $(this).attr('id');
			data[i] = [$('#name_'+id).val(), $('#votes_'+id).val(), $('#sort_'+id).val(), $('#optionid_'+id).val()];
		});
		data.sort(function(a, b) {
			return a[2]-b[2];
		});
		
		$('#options').empty();
		$.each(data, function(i, r){
			option.add(r[0], r[1], r[2], r[3]);
		});
	},
	move : function(obj, c) {
		var parentObj = obj.parent();
		var index = parentObj.find('tr').index(obj[0]);
		if (c < 0) { // 上移
			c = Math.abs(c);
			for (var i=0; i<c; i++) {
				option.change(parentObj.find('tr').eq(index+i), parentObj.find('tr').eq(index+i+1));
			}
		} else if(c > 0) { // 下移
			for (var i=0; i<c; i++) {
				option.change(parentObj.find('tr').eq(index-i), parentObj.find('tr').eq(index-i-1));
			}
		}
	},
	change : function(o1, o2) {
		var s1 = o1.find('.sort').val(),
			s2 = o2.find('.sort').val();
		o1.find('.sort').val(s2);
		o2.find('.sort').val(s1);
		o1.children(':first').find('[data-role=sort]').html(s2);
		o2.children(':first').find('[data-role=sort]').html(s1);
	}
}