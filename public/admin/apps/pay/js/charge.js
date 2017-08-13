//JavaScript Document
//the js file for pay charge page

var charge = {
	
	view: function (chargeid) 
	{
		var url = '?app=pay&controller=charge&action=view&chargeid='+chargeid;
		var title = '查看交易订单';
		ct.form(title, url, 600,'auto',function(json) {
			return true;
		},function(form, dialog) {
		});
	},
}

