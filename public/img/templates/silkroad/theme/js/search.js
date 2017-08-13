$(function(){

	$('.search-result-list').css('min-height', $(window).height() - 307);
  $('#sort').add($('#category')).on('mouseenter', function(){
      $(this).find('.select-box').show();
  }).find('.select-box').on('mouseleave', function(){
      $(this).hide()
  }).on('click', function () {
  });
	
	$(".checkbox").change(function(){
		var contentids_array = [];
		var username = $.cookie(COOKIE_PRE+'username');
		var contentids = $.cookie('search_'+username);
		var checked = $(this).prop("checked");
		var value = $(this).attr('value');
		var url = $(this).attr("url");
		var title = $(this).attr("title");
		$(".db-right").css('display','block');
		if(contentids){
			contentids_array = contentids.split(',') 
		}
		
			if(contentids_array.length<10){
				if(checked){
					contentids_array.push(value);
					createLi(value,url,title);
				}else{
					contentids_array.splice(contentids_array.indexOf(value),1);
					//lost(this,value);
					$("#db").find("#"+value).remove();
					$(".db-right .db-r-top h3").html('['+contentids_array.length+'/10]已选结果');
				}
			}else{
				if(checked){
					$(this).removeProp("checked");
					alert('文章选择不能超过10篇');return false;
				}else{
					contentids_array.splice(contentids_array.indexOf(value),1);
					//lost(this,value);
					$("#db").find("#"+value).remove();
					$(".db-right .db-r-top h3").html('['+contentids_array.length+'/10]已选结果');
				}
			}
		contentids = contentids_array.join(',');
		$.cookie('search_'+username,contentids);
	})
	//选择排序
	$("#newsort").change(function(){
		var value = $(this).val();
		var url = $(this).find("option:selected").attr('url');
		//设置cookie
		var username = $.cookie(COOKIE_PRE+'username');
		$.cookie('search_order'+username,value,60*60*1000);
		//console.log(url);return false;
		window.location.href=url;
		
	})
	//点击搜索 清除排序cookie值
	$(".search-input-panel").find("input[type=submit]").click(function(){
		//清除cookie
		var username = $.cookie(COOKIE_PRE+'username');
		$.cookie('search_order'+username,'');
	})
	//导出download
	$("#download").click(function(){
		var downtype = $("select[name=daochutype]").val();
		
		if(downtype=='null'){
			alert('请选择导出类型');return false;
		}
		download(downtype);
	})
	//发送邮箱
	$("#sendemail").click(function(){
		var downtype = $("select[name=daochutype]").val();
		
		if(downtype=='null'){
			alert('请选择导出类型');return false;
		}
		sendemail(downtype);
	})

})

//删除li
function lost(obj,contentid)
{
	var username = $.cookie(COOKIE_PRE+'username');
	var id = $(obj).parent().attr('id');
	$(".article-picture-item").each(function(){
		//alert(222);
			var value = $(this).find('.checkbox').attr('value');
			//alert(value);
			if(value.toString()==id.toString()){
				//$(this).find('.checkbox').attr("checked",false);
				$(this).find('.checkbox').removeAttr("checked");
			}
	})
	$(obj).parent().remove();
	//删除对应的cookie值
	var content = contentid.toString();
	var contentids = $.cookie('search_'+username);
	// console.log(a);
	contentids_array = contentids.split(',');
	contentids_array.splice(contentids_array.indexOf(content),1);
	contentids = contentids_array.join(',');
	$(".db-right .db-r-top h3").html('['+contentids_array.length+'/10]已选结果');
	//重新写入cookie
	$.cookie('search_'+username,contentids);
}
//删除全部
function lost_all(){
	$("#db").empty();
	$(".db-right .db-r-top h3").html('0/10]已选结果');
	$(".article-picture-item").each(function(){
		$(this).find('.checkbox').removeAttr("checked");
	})
	//删除cookie
	var username = $.cookie(COOKIE_PRE+'username');
	$.cookie('search_'+username,'');
}
//创建
function createLi(contentid,url,title){
	
	var Tmp = "<li id='"+contentid+"'>\
						<em class='db-li-close' onclick='lost(this,"+contentid+");'></em>\
						<a href='"+url+"' target='_blank'>"+title+"</a>\
						</li>";
	$(".db-r-list ul").append(Tmp);
	//获取当前对比框内容条数
	var max = document.getElementById("db").getElementsByTagName("li").length;
	if(max <= 10){
		$(".db-right .db-r-top h3").html('['+max+'/10]已选结果');
	}else{
		$(".db-right .db-r-top h3").html('[10/10]已选结果');
	}
	
}
function download(downtype)
{
	var username = $.cookie(COOKIE_PRE+'username');
	var contentids = $.cookie('search_'+username);
	if(!contentids){
		alert('请选择导出文章'); return false;
	}
	window.open(APP_URL+'?app=search&controller=index&action=DownLoad&jsoncallback=?&downtype='+downtype+'&contentids='+contentids);
}
function sendemail(downtype)
{
	var username = $.cookie(COOKIE_PRE+'username');
	var contentids = $.cookie('search_'+username);
	if(!contentids){
		alert('请选择导出文章'); return false;
	}
	$.getJSON(APP_URL+'?app=search&controller=index&action=SendEmail&jsoncallback=?&downtype='+downtype+'&contentids='+contentids,function(json){
		alert(json.message);
	});
}
