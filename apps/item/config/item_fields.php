<?php
/*
 * 定义项目应用中的分类的数据类别
 */
return array(
	// 定义所属行业的类别
	'trade' => array(
					'1' => array('zh'=>'农、林、牧、渔业','cn'=>'Agriculture'), 
					'2' => array('zh'=>'采掘业','cn'=>'Mining'), 
					'3' => array('zh'=>'制造业','cn'=>'Manufacturing'), 
					'4' => array('zh'=>'电力、煤气及水的生产和供应业','cn'=>'Power, Gas and Water Supply'), 
					'5' => array('zh'=>'建筑业','cn'=>'Building'),
					'6' => array('zh'=>'交通运输、仓储业','cn'=>'Transport & Warehousing'), 
					'7' => array('zh'=>'信息技术业','cn'=>'IT'), 
					'8' => array('zh'=>'批发和零售贸易','cn'=>'Wholesale & Retail'), 
					'9' => array('zh'=>'金融、保险业','cn'=>'Financing'), 
					'10' => array('zh'=>'房地产业','cn'=>'Real Estate'), 
					'11'=> array('zh'=>'社会服务业','cn'=>'Social Services'),
					'12'=> array('zh'=>'传播与文化产业','cn'=>'Media & Culture'),
					'13'=> array('zh'=>'综合类','cn'=>'Other Industry'),
				),
	//定义项目类型的类别
	'itemtype' => array(
					'9' => array('zh'=>'工程建设','cn'=>'Engineering Construction'), 
					'2' => array('zh'=>'商贸供应','cn'=>'Commercial Supply'), 
					'3' => array('zh'=>'商贸需求','cn'=>'Commercial Demand'), 
					'4' => array('zh'=>'股权投资','cn'=>'Equity Investment'), 
					'5' => array('zh'=>'债券投资','cn'=>'Bond Investment'), 
					'6' => array('zh'=>'绿地投资','cn'=>'Greenfield Investment'), 
					'7' => array('zh'=>'资产交易','cn'=>'Transaction in Assets'), 
					'8' => array('zh'=>'融资租凭','cn'=>'Financial Leasing'), 
					'1' => array('zh'=>'其它','cn'=>'Other Financing Mode'), 
				),
	//定义投资方式的类别
	'investmenttype' => array(
				'1' => array('zh'=>'合作','cn'=>'Cooperative'), 
				'2' => array('zh'=>'合资','cn'=>'Joint-Venture'), 
				'3' => array('zh'=>'独资','cn'=>'Solely-Invested'), 
				'4' => array('zh'=>'技术转让','cn'=>'Technology Transfer'), 
				'5' => array('zh'=>'BOT','cn'=>'BOT'), 
				'6' => array('zh'=>'补偿贸易','cn'=>'Compensation Trade'), 
				'7' => array('zh'=>'并购','cn'=>'M&A'), 
				'8' => array('zh'=>'普通贸易','cn'=>'Ordinary Trade'), 
				'9' => array('zh'=>'公司合营(PPP)','cn'=>'PPP'), 
				'10' => array('zh'=>'服务贸易','cn'=>'Service Trade'),
				'11' => array('zh'=>'其它','cn'=>'Other Investment Mode'),  
			),
	//定义项目性质的类别
	'itemnature' => array(
				'1' => array('zh'=>'鼓励类','cn'=>'Encouraged'), 
				'2' => array('zh'=>'限制类','cn'=>'Limited'), 
				'3' => array('zh'=>'禁止类','cn'=>'Prohibited'), 
				'4' => array('zh'=>'其它','cn'=>'Other Type'),
			),
);