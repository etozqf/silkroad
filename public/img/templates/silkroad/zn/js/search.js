/**
 * Created by Administrator on 2016/8/2.
 */
$(function () {
	// 收藏
	$(".info .fav").click(function () {
		$(this).toggleClass("on");
	});

	$(".park-ul li").mousemove(function () {
		$(this).addClass("on");
	});
	$(".park-ul li").mouseleave(function () {
		$(this).removeClass("on");
	});
	//检查是否已经在对比框
	$(".box960 .park-ul li").each(function(){
		var userid = $.cookie(COOKIE_PRE+'userid');
		if($.cookie('db'+userid)){
			var ondbcookie = $.cookie('db'+userid);
			var db_contentid = $(this).attr("data");
			var db_res = ondbcookie.split(',');
			if(db_res.indexOf(db_contentid) != -1){
				$(this).children(".park-box").children(".cont-r").children(".mar-t-10").children(".db").children("em").addClass("on");
			}
		}
	})
	

	// 对比
	
	$(".db .db-em").click(function () {
		var userid = $.cookie(COOKIE_PRE+'userid');
		var db = $.cookie('db'+userid);
		if(db){
			var arrdb = db.split(',');
		}else{
			var arrdb = new Array();
		}
		var contentid = $(this).attr("contentid");
		var url = $(this).attr("url");
		var thumb = $(this).attr("thumb");
		var title = $(this).attr("title");
		$(this).toggleClass("on");
		var on = $(this).hasClass('on');
		if(on){
			//如果隐藏,点击则弹出对比框
			var display = $('.db-right').css("display");
			if(display=='none'){
				$('.db-right').css('display','block');
			}
			//写入cookie
			// db.lenght();
			if(arrdb.length > 1){
				arrdb.shift(); 
			}
			arrdb.push(contentid);
			$.cookie('db'+userid,arrdb);
			createLi(contentid,url,title,thumb);
		}else{
			//删除对应的园区
			lost_li(contentid);

		}
	});
	//园区搜索表单提交
	$("select[name=property]").change(function(){
		//检查地区和园区类别是否已选
		var province = $("select[name=province] option:selected");
		var cate = $("select[name=cate] option:selected");
		var property = $(this).val();
		if(province.val()=='0'){
			alert('请选择地区');return;
		}else if(cate.val()=='0'){
			alert('请选择园区类别');return;
		}else if(province.val()=='all' && cate.val()=='all' && property=='all'){
			//提交表单事件
			// location.reload();
			var total = $(".page").attr("total");
			$(".box960").prepend("<p class='total-num mar-t-16'>新华丝路数据库为您找到相关结果约"+total+"个</p>")
			// alert(333);return;
		}else{
			$("#yq_search").submit();
		}
		
	});
	function createLi(contentid,url,title,thumb){
		var Tmp = "<li id='"+contentid+"'><em class='db-li-close' onclick='lost(this,"+contentid+");'></em><a href='"+url+"' target='_blank' class='r-img'><img src='"+thumb+"' width='90' height='68' alt=''/></a><h3 class='mar-t-6'><a href='"+url+"' target='_blank'>"+title+"</a></h3></li>";
		//获取当前对比框内容条数
		var max = document.getElementById("db").getElementsByTagName("li").length;
		if(max == 2){
			$("#db li:first-child").remove();
		}else if(max == 0){
			$(".db-right .db-r-top h3").html('[1/2]对比框');
		}else if(max==1){
			$(".db-right .db-r-top h3").html('[2/2]对比框');
		}
		$(".db-r-list ul").append(Tmp);
	}
})

//删除对比li
function lost(obj,contentid){
	var userid = $.cookie(COOKIE_PRE+'userid');
	$(obj).parent().hide();
	//删除对应的cookie值
	var content = contentid.toString();
	var a = $.cookie('db'+userid);
	// console.log(a);
	var b = a.replace(content,'');
	var i = b.indexOf(',');
	if(i==-1){
		$(".db-right .db-r-top h3").html('[0/2]对比框');
	}else{
		$(".db-right .db-r-top h3").html('[1/2]对比框');
	}
	var c = b.replace(',','');
	
	//重新写入cookie
	$.cookie('db'+userid,c);
}
//删除全部
function lost_all(){
	$("#db").empty();
	//删除cookie
	var userid = $.cookie(COOKIE_PRE+'userid');
	$.cookie('db'+userid,'');
}
//删除单个
function lost_li(contentid){
	var userid = $.cookie(COOKIE_PRE+'userid');
	$("#"+contentid+" .db-li-close").parent().hide();
	var content = contentid.toString();
	var a = $.cookie('db'+userid);
	// console.log(a);
	var b = a.replace(content,'');
	var i = b.indexOf(',');
	if(i==-1){
		$(".db-right .db-r-top h3").html('[0/2]对比框');
	}else{
		$(".db-right .db-r-top h3").html('[1/2]对比框');
	}
	var c = b.replace(',','');
	
	//重新写入cookie
	$.cookie('db'+userid,c);
}
//跳转
function go_db(){
	var userid = $.cookie(COOKIE_PRE+'userid');
	var db = $.cookie('db'+userid);
	var arr = db.split(',');
	if(arr.length!=2){
		alert('请选择两个园区进行对比');return;
	}
	window.open(APP_URL+'?app=system&controller=yuanqu&action=yq_db&contentid_first='+arr[0]+'&contentid_third='+arr[1]);
	// console.log(db);
}
