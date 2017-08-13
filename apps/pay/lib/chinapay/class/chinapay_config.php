<?php

	//私钥文件b2b，在CHINAPAY申请商户号时获取
	define("PRI_KEY_B2B", "MerPrK_808080640000033_20120312145012.key");
	//私钥文件b2c，在CHINAPAY申请商户号时获取
	define("PRI_KEY_B2C", "MerPrK_808080640000034_20120312145024.key");
	//公钥文件，示例中已经包含
	define("PUB_KEY", "PgPubk.key");
	
	//支付请求地址(测试)
	//define("REQ_URL_PAY","http://payment-test.ChinaPay.com/pay/TransGet");
	//支付请求地址(生产)
	define("REQ_URL_PAY","https://payment.ChinaPay.com/pay/TransGet");
	
	//查询请求地址(测试)
	//define("REQ_URL_QRY","http://payment-test.chinapay.com/QueryWeb/processQuery.jsp");
	//查询请求地址(生产)
	//define("REQ_URL_QRY","http://console.chinapay.com/QueryWeb/processQuery.jsp");
	
	//退款请求地址(测试)
	//define("REQ_URL_REF","http://payment-test.chinapay.com/refund/SingleRefund.jsp");
	//退款请求地址(生产)
	//define("REQ_URL_REF","https://bak.chinapay.com/refund/SingleRefund.jsp");
	
?>