<?php
/**
 * 银联在线付款过程中服务器通知
 *
 * @author zhouqingfeng
 * @copyright 2012 (c) CmsTop
 * @date 2012/04/11
 * @version $Id$
 */


require_once("chinapay_config.php");
require_once("netpayclient.php");

class chinapay_notify {

	var $checkdata;		//验证数据数组
	var $msg;			//处理结果消息

    /**构造函数
	*从配置文件中初始化变量
	*$partner 合作身份者ID
     */
    function chinapay_notify($parameter) {
		//导入公钥文件
		$flag = buildKey(PUB_KEY);
		if(!$flag) {
			$this->msg = '导入公钥文件失败！';
			//exit;
		}
		$this->checkdata = $parameter;
    }

    /********************************************************************************/

    /**对支付应答的认证
	*返回的验证结果：true/false
     */
    function notify_verify() {
        
		//验证签名值，true 表示验证通过
		$flag = verifyTransResponse($this->checkdata['merid'], $this->checkdata['orderno'],  $this->checkdata['amount'],  $this->checkdata['currencycode'],  $this->checkdata['transdate'],  $this->checkdata['transtype'],  $this->checkdata['status'],  $this->checkdata['checkvalue']);
		if(!$flag) {
			$this->msg = '验证签名失败！';
			return false;
		}
		//echo "<h2>验证签名成功！</h2>";
		//交易状态为1001表示交易成功，其他为各类错误，如卡内余额不足等
		if ($this->checkdata['status'] == '1001'){
			$this->msg = '交易成功！';
			//您的处理逻辑请写在这里，如更新数据库等。
			//注意：如果您在提交时同时填写了页面返回地址和后台返回地址，且地址相同，请在这里先做一次数据库查询判断订单状态，以防止重复处理该笔订单
			return true;
			
		} else {
			$this->msg = '交易失败！';
			return false;
		}
    }

    /********************************************************************************/


}
?>
