
	$(function(){

			//打开页面自动加载时的数据
			$.getJSON(APP_URL+"?app=system&controller=case&action=flashdata&jsoncallback=?",function(json){
					
					if(json.status =="success")

					{	
						var length=json['content'].length;
						var str='';

						for(i=0;i<length;i++)
						{
							
							if(!json['content'][i]['thumb'])
							{
								str += '<li catid="'+json['content'][i]['catid']+'" contentid="'+json['content'][i]['contentid']+'"><h2><a href="';
								str +=json['content'][i]['url'];
								str +='"';
								str +=' id=';
								str +=json['content'][i]['contentid'];
								str +='>';
								str +=json['content'][i]['title'];
								str +='</a></h2><p>';
								if(json['content'][i]['description']!=null)
								{
									str +=json['content'][i]['description'];
								}
								else
								{
									str +='';
								}
								str +='</p><div class="info"><span class="time">';
								str +=json['content'][i]['date'];
								str +='<b>';
								str +=json['content'][i]['hour'];
								str +='</b></span><span class="fav">收藏</span></div></li>';

							}
							else
							{
								str += '<li catid="'+json['content'][i]['catid']+'" contentid="'+json['content'][i]['contentid']+'"><img src="http://upload.db.silkroad.news.cn/'+json['content'][i]['thumb']+'" width="130" height="100" alt=""><h2><a href="';
								str +=json['content'][i]['url'];
								str +='"';
								str +=' id=';
								str +=json['content'][i]['contentid'];
								
								str +='>';
								str +=json['content'][i]['title'];
								str +='</a></h2><p>';
								if(json['content'][i]['description']!=null)
								{
									str +=json['content'][i]['description'];
								}
								else
								{
									str +='';
								}
								str +='</p><div class="info"><span class="time">';
								str +=json['content'][i]['date'];
								str +='<b>';
								str +=json['content'][i]['hour'];
								str +='</b></span><span class="fav">收藏</span></div></li>';
							}
						}
						
						$(".news ul").append(str);
					}
					else
					{
				 				str="<li style='text-align:center'>"+json['content']+"</li>";
				 				$(".news ul").append(str);

					}	
				}	
			);
		$('.n_ind_contitle ul span:first').click(function(){
			//打开页面自动加载时的数据
			$(".news ul li").remove();
			$('.more').css('display','block');
			$('.more').attr('id','more');
			$('.more').html('更多最新内容>>')
			$.getJSON(APP_URL+"?app=system&controller=case&action=flashdata&jsoncallback=?",function(json){
					
					if(json.status =="success")

					{	
						var length=json['content'].length;
						var str='';

						for(i=0;i<length;i++)
						{
							
							if(!json['content'][i]['thumb'])
							{
								str += '<li catid="'+json['content'][i]['catid']+'" contentid="'+json['content'][i]['contentid']+'"><h2><a href="';
								str +=json['content'][i]['url'];
								str +='"';
								str +=' id=';
								str +=json['content'][i]['contentid'];
								str +='>';
								str +=json['content'][i]['title'];
								str +='</a></h2><p>';
								if(json['content'][i]['description']!=null)
								{
									str +=json['content'][i]['description'];
								}
								else
								{
									str +='';
								}
								str +='</p><div class="info"><span class="time">';
								str +=json['content'][i]['date'];
								str +='<b>';
								str +=json['content'][i]['hour'];
								str +='</b></span><span class="fav">收藏</span></div></li>';

							}
							else
							{
								str += '<li catid="'+json['content'][i]['catid']+'" contentid="'+json['content'][i]['contentid']+'"><img src="http://upload.db.silkroad.news.cn/'+json['content'][i]['thumb']+'" width="130" height="100" alt=""><h2><a href="';
								str +=json['content'][i]['url'];
								str +='"';
								str +=' id=';
								str +=json['content'][i]['contentid'];
								
								str +='>';
								str +=json['content'][i]['title'];
								str +='</a></h2><p>';
								if(json['content'][i]['description']!=null)
								{
									str +=json['content'][i]['description'];
								}
								else
								{
									str +='';
								}
								str +='</p><div class="info"><span class="time">';
								str +=json['content'][i]['date'];
								str +='<b>';
								str +=json['content'][i]['hour'];
								str +='</b></span><span class="fav">收藏</span></div></li>';
							}
						}
						
						$(".news ul").append(str);
							
					}
					else
					{
				 				str="<li style='text-align:center'>"+json['content']+"</li>";
				 				$(".news ul").append(str);	

					}
				}	
			);
		});


		//ajax的点击加载
		$('.n_ind_contitle ul span').not($('.n_ind_contitle ul span')[0]).bind('click',function(json){

			$('.more').css('display','block');

			$('.more').html('更多最新内容>>')
			//删除之前已经存在的div显示区
			$(".news ul li").remove();
			var id=$(this).attr('id');
			$.getJSON(APP_URL+"?app=system&controller=case&action=zhudi&jsoncallback=?&proid="+id,function(json){
					
					if(json.status =="success")

					{	
						var length=json['content'].length;
						var str='';

						for(i=0;i<length;i++)
						{
							
							if(!json['content'][i]['thumb'])
							{
								str += '<li catid="'+json['content'][i]['catid']+'" contentid="'+json['content'][i]['contentid']+'"><h2><a href="';
								str +=json['content'][i]['url'];
								str +='"';
								str +=' id=';
								str +=json['content'][i]['contentid'];
								str +=' proid=';
								str +=id;
								str +='>';
								str +=json['content'][i]['title'];
								str +='</a></h2><p>';
								if(json['content'][i]['description']!=null)
								{
									str +=json['content'][i]['description'];
								}
								else
								{
									str +='';
								}
								str +='</p><div class="info"><span class="time">';
								str +=json['content'][i]['date'];
								str +='<b>';
								str +=json['content'][i]['hour'];
								str +='</b></span><span class="fav">收藏</span></div></li>';

							}
							else
							{
								str += '<li catid="'+json['content'][i]['catid']+'" contentid="'+json['content'][i]['contentid']+'"><img src="http://upload.db.silkroad.news.cn/'+json['content'][i]['thumb']+'" width="130" height="100" alt=""><h2><a href="';
								str +=json['content'][i]['url'];
								str +='"';
								str +=' id=';
								str +=json['content'][i]['contentid'];
								str +=' proid=';
								str +=id;
								str +='>';
								str +=json['content'][i]['title'];
								str +='</a></h2><p>';
								if(json['content'][i]['description']!=null)
								{
									str +=json['content'][i]['description'];
								}
								else
								{
									str +='';
								}
								str +='</p><div class="info"><span class="time">';
								str +=json['content'][i]['date'];
								str +='<b>';
								str +=json['content'][i]['hour'];
								str +='</b></span><span class="fav">收藏</span></div></li>';
							}
						}
						$(".news ul").append(str);
					}
					else
					{
				 				str="<li style='text-align:center'>"+json['content']+"</li>";
				 				$(".news ul").append(str);
				 				$('.more').css('display','none');

					}
				}	
			);
		});


			//点击加载更多
			$('.more').click(function(){
				var str='';

				$(this).html('正在加载数据');
				$(".news ul li a").each(function(){
         			   str += $(this).attr('id')+',';
         			   proid=$(this).attr('proid');
     			});
     			var value=$('.news ul li').last().attr('page');
     			if(typeof(value) =="undefined"){
     				value=1;
     			} 
     			var value=value*1+1;
     			var bodyheight=$(document.body).outerWidth();
     			var liheight=$('.news ul li').height();
     			var navheight=bodyheight-10*liheight;
     			height=navheight+liheight*(value-1)*10;
     			height=(height*1)+80;
     			$.getJSON(APP_URL+"?app=system&controller=case&action=get_moreat&jsoncallback=?&str="+str+"&proid="+proid,function(json){
     					

     					if(json.newsate){

     							if(json.status =="success")

					{	
						var length=json['content'].length;
						var str='';

						for(i=0;i<length;i++)
						{
							
							if(!json['content'][i]['thumb'])
							{
								str += '<li page="'+value+'" catid="'+json['content'][i]['catid']+'" contentid="'+json['content'][i]['contentid']+'"><h2><a href="';
								str +=json['content'][i]['url'];
								str +='"';
								str +=' id=';
								str +=json['content'][i]['contentid'];
								str +='>';
								str +=json['content'][i]['title'];
								str +='</a></h2><p>';
								if(json['content'][i]['description']!=null)
								{
									str +=json['content'][i]['description'];
								}
								else
								{
									str +='';
								}
								str +='</p><div class="info"><span class="time">';
								str +=json['content'][i]['date'];
								str +='<b>';
								str +=json['content'][i]['hour'];
								str +='</b></span><span class="fav">收藏</span></div></li>';

							}
							else
							{
								str += '<li page="'+value+'" catid="'+json['content'][i]['catid']+'" contentid="'+json['content'][i]['contentid']+'"><img src="http://upload.db.silkroad.news.cn/'+json['content'][i]['thumb']+'" width="130" height="100" alt=""><h2><a href="';
								str +=json['content'][i]['url'];
								str +='"';
								str +=' id=';
								str +=json['content'][i]['contentid'];
								
								str +='>';
								str +=json['content'][i]['title'];
								str +='</a></h2><p>';
								if(json['content'][i]['description']!=null)
								{
									str +=json['content'][i]['description'];
								}
								else
								{
									str +='';
								}
								str +='</p><div class="info"><span class="time">';
								str +=json['content'][i]['date'];
								str +='<b>';
								str +=json['content'][i]['hour'];
								str +='</b></span><span class="fav">收藏</span></div></li>';
							}
						}
						
						$(".news ul").append(str);
						$('body,html').animate({scrollTop:height},100);
							
					}
					else
					{
				 				str="<li style='text-align:center'>"+json['content']+"</li>";
				 				$(".news ul").append(str);	
				 				$('.more').css('display','none');

					}








     					}






     				else{

     				 	if(json.status =="success")

						{	
						var length=json['content'].length;
						var str='';

						for(i=0;i<length;i++)
						{
							
							if(!json['content'][i]['thumb'])
							{
								str += '<li page="'+value+'" catid="'+json['content'][i]['catid']+'" contentid="'+json['content'][i]['contentid']+'"><h2><a href="';
								str +=json['content'][i]['url'];
								str +='"';
								str +=' id=';
								str +=json['content'][i]['contentid'];
								str +=' proid=';
								str +=proid;
								str +='>';
								str +=json['content'][i]['title'];
								str +='</a></h2><p>';
								if(json['content'][i]['description']!=null)
								{
									str +=json['content'][i]['description'];
								}
								else
								{
									str +='';
								}
								str +='</p><div class="info"><span class="time">';
								str +=json['content'][i]['date'];
								str +='<b>';
								str +=json['content'][i]['hour'];
								str +='</b></span><span class="fav">收藏</span></div></li>';

							}
							else
							{
								str += '<li page="'+value+'" catid="'+json['content'][i]['catid']+'" contentid="'+json['content'][i]['contentid']+'"><img src="http://upload.db.silkroad.news.cn/'+json['content'][i]['thumb']+'" width="130" height="100" alt=""><h2><a href="';
								str +=json['content'][i]['url'];
								str +='"';
								str +=' id=';
								str +=json['content'][i]['contentid'];
								str +=' proid=';
								str +=proid;
								str +='>';
								str +=json['content'][i]['title'];
								str +='</a></h2><p>';
								if(json['content'][i]['description']!=null)
								{
									str +=json['content'][i]['description'];
								}
								else
								{
									str +='';
								}
								str +='</p><div class="info"><span class="time">';
								str +=json['content'][i]['date'];
								str +='<b>';
								str +=json['content'][i]['hour'];
								str +='</b></span><span class="fav">收藏</span></div></li>';
							}
						}
						$(".news ul").append(str);
						$('.more').html('更多最新内容');
						$('body,html').animate({scrollTop:height},100);
					}

					else
					{
				 				str="<li style='text-align:center'>"+json['content']+"</li>";
				 				$(".news ul").append(str);
				 				$('.more').css('display','none');

					}

				}
					









     			});
			});
		

	})
