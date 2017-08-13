var interview = 
{
	createguest: function ()
	{
		$.getJSON('?app=interview&controller=html&action=guest', function(response){
			if (response.state)
			{
				ct.ok('操作成功');
			}
			else
			{
				ct.error(response.error);
			}
		});	
	},
	
	chat: function (contentid)
	{
		ct.assoc.open('?app=interview&controller=chat&action=index&contentid='+contentid, 'newtab');
	},
	
	question: function (contentid)
	{
		ct.assoc.open('?app=interview&controller=question&action=index&contentid='+contentid, 'newtab');
	},
	
	set_review: function (contentid)
	{
		ct.form('精彩观点', '?app=interview&controller=interview&action=review&contentid='+contentid, 425, 271, function (response){
			if (response.state)
			{
				$('#review_content').html(response.data);
				return true;
			}
			else
			{
				ct.error(response.error);
				return false;
			}
		}, function (d){
			d.find('textarea').editor('mini',{forced_root_block : ''});
		});
	},
	
	set_notice: function (contentid)
	{
		ct.form('滚动公告', '?app=interview&controller=interview&action=notice&contentid='+contentid, 425, 271, function (response){
			if (response.state)
			{
				$('#notice_content').html(response.data);
				return true;
			}
			else
			{
				ct.error(response.error);
				return false;
			}
		}, function (d){
			d.find('textarea').editor('mini',{forced_root_block : ''});
		});
	},
	
	set_state: function (contentid, state)
	{
		$.getJSON('?app=interview&controller=interview&action=state&contentid='+contentid+'&state='+state, function (response){
			if (response.state)
			{
				ct.ok('设置成功！');
			}
			else
			{
				ct.error(response.error);
			}
		});
	},
	
	picture_load: function (contentid)
	{
		$('#picture_group').load('?app=interview&controller=interview&action=picture&contentid='+contentid, function () {
			window.setTimeout(function(){
                $("#pictures").jCarouselLite({
                    btnNext: ".nextimg",
                    btnPrev: ".previmg",
                    circular: true,
                    auto: 2000,
                    speed: 1000,
                    scroll: 1,
                    visible: 4,
                    start: 0
                });
            },500);
		});
	},

	remove: function (contentid)
	{
		var msg;
		if (contentid === undefined)
		{
			contentid = tableApp.checkedIds();
			msg = '确定删除选中的<b style="color:red">'+contentid.length+'</b>条记录吗？';
		}
		else
		{
			msg = '确定删除编号为<b style="color:red">'+contentid+'</b>的记录吗？';
		}
		if (contentid.length === 0)
		{
			ct.warn('请选择要操作的记录');
			return false;
		}

        if (!$.isArray(contentid)) {
            contentid = [contentid];
        }

		ct.confirm(msg, function(){
            content._common_execute(contentid, 'remove', 1, 0);
		});
	}
};