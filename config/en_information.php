<?php
/*
 +英文资讯频道列表属性数组 
*/
return array(
	array('proid'=>153,'alias'=>'Policy'),
	array('proid'=>160,'alias'=>'Economy'),
	array('proid'=>15,'alias'=>'Industries','son'=>array(
		array('proid'=>119,'alias'=>'Agriculture'),
		array('proid'=>326,'alias'=>'Mining'),
		array('proid'=>17,'alias'=>'Manufacturing'),
		array('proid'=>81,'alias'=>'Power'),
		array('proid'=>327,'alias'=>'Public Utility'),
		array('proid'=>328,'alias'=>'Construction'),
		array('proid'=>54,'alias'=>'Infrastructure'),
		array('proid'=>140,'alias'=>'Logistics'),
		array('proid'=>134,'alias'=>'IT'),
		array('proid'=>133,'alias'=>'Real Estate'),
		array('proid'=>136,'alias'=>'Media & Culture'),
		array('proid'=>122,'alias'=>'Finance'),
	)),
	array('proid'=>330,'alias'=>'Financial Market','son'=>array(
			array('proid'=>338,'alias'=>'Stock Market'),
			array('proid'=>339,'alias'=>'Bond Market'),
			array('proid'=>340,'alias'=>'Forex Market'),
			array('proid'=>341,'alias'=>'Futures Market'),
	)),
	array('proid'=>164,'alias'=>'Companies'),
	array('proid'=>159,'alias'=>'Alerts'),
	array('proid'=>154,'alias'=>'Views'),
	array('proid'=>287,'alias'=>'World'),
	array('proid'=>334,'alias'=>'AIIB'),
	array('proid'=>335,'alias'=>'Silk Road Fund'),
	array('proid'=>166,'alias'=>'Logistics & Cargo'),
);
