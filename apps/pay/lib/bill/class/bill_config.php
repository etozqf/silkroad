<?php

//测试私钥证书
//define('PRI_KEY', '99bill-rsa.test.pem');
//私钥证书，由商户自己生成，生成方法查看文档
define('PRI_KEY', '99bill-rsa.pem');

//测试公钥证书
//define('PUB_KEY', '99bill[1].cert.rsa.20140803.cer');
//公钥证书，从快钱帐户上下载
define('PUB_KEY', '99bill.cert.rsa.20140728.cer');

//快钱服务器请求地址(测试)
//define('REQ_URL_PAY','https://sandbox2.99bill.com/gateway/recvMerchantInfoAction.htm');
//快钱服务器请求地址(生产)
define('REQ_URL_PAY','https://www.99bill.com/gateway/recvMerchantInfoAction.htm');