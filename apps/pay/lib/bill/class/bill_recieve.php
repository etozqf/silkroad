<?php
/**
 * 快钱外部服务接口控制
 *
 * @author zhouqingfeng
 * @copyright 2012 (c) CmsTop
 * @date 2012/04/18
 * @version $Id$
 */

require_once("bill_config.php");
require_once("bill_function.php");

class bill_notify {
	var $checkdata;			//验证数据数组
	var $notify;			//处理结果通知
	var $signMsg;			//签名

    /**构造函数
	*从配置文件及入口文件中初始化变量
	*$parameter 需要签名的参数数组
    */
	function bill_notify($parameter) {
		
		
		//支付验证数组
		$this->checkdata = array();
		$this->checkdata['merchantAcctId']	= $parameter['merchantAcctId'];		//人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
		$this->checkdata['version']			= $parameter['version'];			//网关版本，固定值：v2.0,该值与提交时相同。
		$this->checkdata['language']		= $parameter['language'];			//语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
		$this->checkdata['signType']		= $parameter['signType'];			//签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
		$this->checkdata['payType']			= $parameter['payType'];			//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
		$this->checkdata['bankId']			= $parameter['bankId'];				//银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
		$this->checkdata['orderId']			= $parameter['orderId'];			//商户订单号，,该值与提交时相同。
		$this->checkdata['orderTime']		= $parameter['orderTime'];			//订单提交时间，格式：yyyyMMddHHmmss
		$this->checkdata['orderAmount']		= $parameter['orderAmount'];		//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
		$this->checkdata['dealId']			= $parameter['dealId'];				//快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
		$this->checkdata['bankDealId']		= $parameter['bankDealId'];			//银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
		$this->checkdata['dealTime']		= $parameter['dealTime'];			//快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
		$this->checkdata['payAmount']		= $parameter['payAmount'];			//商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
		$this->checkdata['fee']				= $parameter['fee'];				//费用，快钱收取商户的手续费，单位为分。
		$this->checkdata['ext1']			= $parameter['ext1'];				//扩展字段1，该值与提交时相同
		$this->checkdata['ext2']			= $parameter['ext2'];				//扩展字段2，该值与提交时相同。
		$this->checkdata['payResult']		= $parameter['payResult'];			//处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
		$this->checkdata['errCode']			= $parameter['errCode'];			//错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
		$this->signMsg						= $parameter['signMsg'];
		
    }

    /********************************************************************************/

    /**对支付应答的认证
	*返回的验证结果：true/false
     */
    function notify_verify() {
		//
        //$this->checkdata['payResult'] = '10';
		$kq_check_all_para = kq_ck_null($this->checkdata);
		//验证签名值，1 表示验证通过
		$flag = verifyResponse($kq_check_all_para,$this->signMsg);
		
		if ($flag == 1)
		{ 
			switch($this->checkdata['payResult']){
				case '10':
					$this->notify = 'success';
					return true;
					break;
				default:
					$this->notify = 'failed';
					return false;
					break;
			}
		}
		else
		{
			$this->notify = 'error';
			return false;
		}
    }
	
    /********************************************************************************/

}
?>