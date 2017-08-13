<?php
/**
 * 支付扩展函数
 *
 * @author zhouqingfeng
 * @copyright 2010 (c) CmsTop
 * @date 2012/06/06
 * @version $Id$
 */


/**
 * 校验购物订单信息
 *
 * @param $amount 订单金额
 * @param $title 订单（商品）名称
 * @param $payment_url 订单信息地址
 * @param $orderno 订单号
 * @param $status 支付状态，支付完成后返回
 * @param $checkvalue 校验值，md5($amount.'#'.$title.'#'.$payment_url.'#'.$orderno.'#'.$status)  //str_encode($amount.$title.$payment_url.$orderno, config('config', 'authkey'))
 * @return bool
 */
function verify_info($amount, $title, $payment_url, $orderno, $checkvalue, $status='')
{
	if(!$amount || !$title) return false;
	$payment_url = "";
	//$new_checkvalue = str_encode($amount.$title.$payment_url.$orderno.$status, config('config', 'authkey'));
	$new_checkvalue = md5($amount.'#'.$title.'#'.$payment_url.'#'.$orderno.'#'.$status);
	if($new_checkvalue != $checkvalue) return false;
	return true;
}

/**
 * 生成校验购物订单信息
 *
 * @param $amount 订单金额
 * @param $title 订单（商品）名称
 * @param $payment_url 订单信息地址
 * @param $orderno 订单号
 * @param $status 支付状态，支付完成后返回
 * @return string
 */
function make_verify_info($amount, $title, $payment_url, $orderno, $status='')
{
	$payment_url = "";
	//return str_encode($amount.$title.$payment_url.$orderno.$status, config('config', 'authkey'));
	return md5($amount.'#'.$title.'#'.$payment_url.'#'.$orderno.'#'.$status);
}