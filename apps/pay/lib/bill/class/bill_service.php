<?php
/**
 * 银联在线外部服务接口控制
 *
 * @author zhouqingfeng
 * @copyright 2012 (c) CmsTop
 * @date 2012/04/11
 * @version $Id$
 */

require_once("bill_config.php");
require_once("bill_function.php");

class bill_service {
	var $forminfo;			//表单数组

    /**构造函数
	*从配置文件及入口文件中初始化变量
	*$parameter 需要签名的参数数组
    */
	function bill_service($parameter) {
		
		
		//支付表单数据
		$this->forminfo = array();
		$this->forminfo['inputCharset']		= $parameter['inputCharset'];
		$this->forminfo['pageUrl']			= $parameter['pageUrl'];
		$this->forminfo['bgUrl']			= $parameter['bgUrl'];
		$this->forminfo['version']			= $parameter['version'];
		$this->forminfo['language']			= $parameter['language'];
		$this->forminfo['signType']			= $parameter['signType'];
		$this->forminfo['merchantAcctId']	= $parameter['merchantAcctId'];
		$this->forminfo['payerName']		= $parameter['payerName'];
		$this->forminfo['payerContactType']	= $parameter['payerContactType'];
		$this->forminfo['payerContact']		= $parameter['payerContact'];
		$this->forminfo['orderId']			= $parameter['orderId'];
		$this->forminfo['orderAmount']		= $parameter['orderAmount'];
		$this->forminfo['orderTime']		= $parameter['orderTime'];
		$this->forminfo['productName']		= $parameter['productName'];
		$this->forminfo['productNum']		= $parameter['productNum'];
		$this->forminfo['productId']		= $parameter['productId'];
		$this->forminfo['productDesc']		= $parameter['productDesc'];
		$this->forminfo['ext1']				= $parameter['ext1'];
		$this->forminfo['ext2']				= $parameter['ext2'];
		$this->forminfo['payType']			= $parameter['payType'];
		$this->forminfo['bankId']			= $parameter['bankId'];
		$this->forminfo['redoFlag']			= $parameter['redoFlag'];
		$this->forminfo['pid']				= $parameter['pid'];
		$this->forminfo['signMsg']			= sign(kq_ck_null($this->forminfo));  	// signMsg 签名字符串 不可空，生成加密签名串
    }

    /********************************************************************************/

    /**构造表单提交HTML
	*return 表单提交HTML文本
     */
    function build_form() {
		//POST方式传递
        $sHtml = '<form action="' . REQ_URL_PAY . '" method="post" target="_blank">';

        while (list ($key, $val) = each ($this->forminfo)) {
            $sHtml .= '<input type="hidden" name="' . $key . '" value="' . $val . '"/>' . "\n";
        }

		//submit按钮控件请不要含有name属性
        $sHtml = $sHtml.'<input class="zl-ok" type="submit" value="快钱支付确认付款"></form>';		
        return $sHtml;
    }
	
    /********************************************************************************/

}
?>