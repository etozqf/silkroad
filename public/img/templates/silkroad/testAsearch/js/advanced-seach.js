function WindowLoad()
{
	var ndExample1 = document.getElementById('datechooserex1');
	ndExample1.DateChooser = new DateChooser();
	ndExample1.DateChooser.setUpdateField({'monthselectex1':'n', 'dayselectex1':'j', 'yearselectex1':'Y'});
	ndExample1.DateChooser.setIcon('http://img.db.silkroad.news.cn/templates/silkroad/testAsearch/img/rl.jpg', 'yearselectex1');
	var ndExample2 = document.getElementById('datechooserex2');
	ndExample2.DateChooser = new DateChooser();
	ndExample2.DateChooser.setUpdateField({'monthselectex2':'n', 'dayselectex2':'j', 'yearselectex2':'Y'});
	ndExample2.DateChooser.setIcon('http://img.db.silkroad.news.cn/templates/silkroad/testAsearch/img/rl.jpg', 'yearselectex2');
	return true;
}


$(function(){
	$("input[type='checkbox']").click(function(){
		if($(this).is(':checked')){
			$(this).attr("checked","checked");
			$(this).parent().addClass("c_on").removeClass("c_off");
		}else{
			$(this).removeAttr("checked");
			$(this).parent().removeClass("c_on").addClass(" c_off");
		}
	});
	$("input[type='radio']").click(function(){
		$("input[type='radio']").removeAttr("checked");
		$(this).attr("checked","checked");
		$(this).parent().removeClass("r_off").addClass("r_on").siblings().removeClass("r_on").addClass("r_off");
	});

	});
