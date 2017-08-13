<?php 

return array(
	'create'  => "tcpdf",		  //选择生成服务:tcpdf|wkhtmltopdf;

	'iscache'     => false,		  //是否开启国别报告内容缓存:true|false;
	'cachetime'   => 3,			  //国别报告内容缓存时间:分钟;
	'iserrorlog'  => true,		  //生成报告出错是否记录日志:true|false;

	'wkhtmltopdf' => "D:/cmstop-server/wkhtmltopdf/bin/wkhtmltopdf.exe", 
	
//中文
	//国别报告栏目ID
	'gbbg'    => 14,
	//投资建议栏目ID 页面左侧图片和文字介绍
	'jianjie' => 45,
	//生成pdf
	//字体:cid0cs|stsongstdlight|hysmyeongjostdmedium|droidsansfallback
	'pdfmsg'  => array(
			'SelfFont' => 'stsongstdlight',    //字体
			'SelfName' => 'name', 				  //栏目标题字段
			'msg'	   => '投融资环境与风险分析', //标题后缀
			'ps'       => '内部资料请勿外传!',    //页眉警告备注信息
			'table'    => '目录',                 //目录
			'str'      => date('(Y版)'),          //那年版
		),
//英文
	//国别报告栏目ID
	'en_gbbg'    => 108,
	// 页面左侧图片和文字介绍
	'en_jianjie' => 110,
	//生成pdf      
	'en_pdfmsg'  => array(
			'SelfFont' => 'times',   
			'SelfName' => 'alias', 
			'msg'      => 'Investment and Financing Environment and Risk Analysis', 
			'ps'       => 'Copyright reserved. No redistribution without permission.',
			'table'    => 'Content ',  
			'str'      => date('(Y)'),
		),
	
	);