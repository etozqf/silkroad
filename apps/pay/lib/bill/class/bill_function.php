<?php
/**
 * 快钱签名及验证
 *
 * @author zhouqingfeng
 * @copyright 2012 (c) CmsTop
 * @date 2012/04/18
 * @version $Id$
 */
 
require_once("bill_config.php");

/** 签名
* 
* 
*/
function sign($kq_all_para) {
	/////////////  RSA 签名计算 ///////// 开始 //
	$fp = fopen(ROOT_PATH . 'apps/pay/lib/bill/class/' . PRI_KEY , "r");
	$priv_key = fread($fp, 8192);
	fclose($fp);
	$pkeyid = openssl_get_privatekey($priv_key);

	// compute signature
	openssl_sign($kq_all_para, $signMsg, $pkeyid,OPENSSL_ALGO_SHA1);

	// free the key from memory
	openssl_free_key($pkeyid);

	$signMsg = base64_encode($signMsg);
	/////////////  RSA 签名计算 ///////// 结束 //
	return $signMsg;
 
}

function verifyResponse($kq_all_para='', $signMsg='') {
	$MAC=base64_decode($signMsg);

	$fp = fopen(ROOT_PATH . 'apps/pay/lib/bill/class/'. PUB_KEY , "r"); 
	$cert = fread($fp, 8192); 
	fclose($fp); 
	$pubkeyid = openssl_get_publickey($cert); 
	return openssl_verify($kq_all_para, $MAC, $pubkeyid); 
	
}

function kq_ck_null($para=array()) {
	$kq_all_para = '';
	foreach($para as $kq_na => $kq_va)
	{
		if($kq_va != "")
		{
			$kq_all_para .= $kq_na.'='.$kq_va.'&';
		}
	}
	$kq_all_para = substr($kq_all_para,0,strlen($kq_all_para)-1);
	return $kq_all_para;
}
 