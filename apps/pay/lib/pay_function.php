<?php
/**
 * ֧����չ����
 *
 * @author zhouqingfeng
 * @copyright 2010 (c) CmsTop
 * @date 2012/06/06
 * @version $Id$
 */


/**
 * У�鹺�ﶩ����Ϣ
 *
 * @param $amount �������
 * @param $title ��������Ʒ������
 * @param $payment_url ������Ϣ��ַ
 * @param $orderno ������
 * @param $status ֧��״̬��֧����ɺ󷵻�
 * @param $checkvalue У��ֵ��md5($amount.'#'.$title.'#'.$payment_url.'#'.$orderno.'#'.$status)  //str_encode($amount.$title.$payment_url.$orderno, config('config', 'authkey'))
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
 * ����У�鹺�ﶩ����Ϣ
 *
 * @param $amount �������
 * @param $title ��������Ʒ������
 * @param $payment_url ������Ϣ��ַ
 * @param $orderno ������
 * @param $status ֧��״̬��֧����ɺ󷵻�
 * @return string
 */
function make_verify_info($amount, $title, $payment_url, $orderno, $status='')
{
	$payment_url = "";
	//return str_encode($amount.$title.$payment_url.$orderno.$status, config('config', 'authkey'));
	return md5($amount.'#'.$title.'#'.$payment_url.'#'.$orderno.'#'.$status);
}