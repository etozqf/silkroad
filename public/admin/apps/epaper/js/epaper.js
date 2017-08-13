var epaper = {
	dialog: new dialog(),
	'add': function() {
		ct.assoc.open("?app=epaper&controller=epaper&action=add", 'newtab');
	},
	'edit': function(id) {
		ct.assoc.open("?app=epaper&controller=epaper&action=edit&id="+id, 'newtab');
	},
	'delete': function(id) {
		ct.confirm('确定要删除吗?', function(){
			$.get('?app=epaper&controller=epaper&action=delete&id='+id, {}, function(json) {
				if (!json.state) {
					ct.error('删除失败');
				}
				tableApp.reload();
			}, 'json');
		});
	},
	'import': function(id) {
		ct.assoc.open('?app=epaper&controller=epaper&action=import&id='+id, 'newtab');
	}
}

var insertInput = function(elm, string) {
	if (document.selection == undefined) {
		elm.value = elm.value.substring(0, elm.selectionStart) + string + elm.value.substring(elm.selectionEnd, elm.value.length);
	} else {
		elm.focus();
		var range = document.selection.createRange();
 		range.text+=string;
	}
}