<?php
/**
 * 银联在线外部服务接口控制
 *
 * @author zhouqingfeng
 * @copyright 2012 (c) CmsTop
 * @date 2012/04/11
 * @version $Id$
 */

require_once("chinapay_config.php");
require_once("netpayclient.php");

class chinapay_service {
	var $forminfo;			//表单数组
	var $config;			//配置信息

    /**构造函数
	*从配置文件及入口文件中初始化变量
	*$parameter 需要签名的参数数组
    */
	function chinapay_service($parameter) {
		$this->config = $parameter;
		//print_r($parameter);exit;
		//B2C取商户号
		$MerId_B2C = buildKey(PRI_KEY_B2C); 
		$plain_b2c = $MerId_B2C . $parameter['OrdId'] . $parameter['TransAmt'] . $parameter['CuryId'] . $parameter['TransDate'] . $parameter['TransType'] . $parameter['Priv1'];
		$ChkValue_B2C = sign($plain_b2c);
		//B2B取商户号
		$MerId_B2B = buildKey(PRI_KEY_B2B); 
		$plain_b2b = $MerId_B2B . $parameter['OrdId'] . $parameter['TransAmt'] . $parameter['CuryId'] . $parameter['TransDate'] . $parameter['TransType'] . $parameter['Priv1'];
		$ChkValue_B2B = sign($plain_b2b);
		
		$MerId = $parameter['b2b_or_b2c']=='b2b' ? $MerId_B2B : $MerId_B2C; 
		if(!$MerId) {
			echo "导入私钥文件失败！";
			exit;
		}
		
		//生成签名值
		$ChkValue = $parameter['b2b_or_b2c']=='b2b' ? $ChkValue_B2B : $ChkValue_B2C; 
		if (!$ChkValue) {
			echo "签名失败！";
			exit;
		}
		
		//支付表单数据
		$this->forminfo['MerId']		= $MerId;
		$this->forminfo['ChkValue']		= $ChkValue;
		$this->forminfo['CuryId']		= $parameter['CuryId'];
		$this->forminfo['OrdId'] 		= $parameter['OrdId'];
		$this->forminfo['TransAmt'] 	= $parameter['TransAmt'];
		$this->forminfo['TransDate'] 	= $parameter['TransDate'];
		$this->forminfo['TransType'] 	= $parameter['TransType'];
		$this->forminfo['Priv1'] 		= $parameter['Priv1'];
		$this->forminfo['Version']		= $parameter['Version'];
		$this->forminfo['BgRetUrl']		= $parameter['BgRetUrl'];
		$this->forminfo['PageRetUrl']	= $parameter['PageRetUrl'];
		$this->forminfo['GateId']		= $parameter['GateId'];
		$this->forminfo['MerId_B2C']	= $MerId_B2C;
		$this->forminfo['MerId_B2B']	= $MerId_B2B;
		$this->forminfo['ChkValue_B2C']	= $ChkValue_B2C;
		$this->forminfo['ChkValue_B2B']	= $ChkValue_B2B;
		
    }

    /********************************************************************************/

    /**构造表单提交HTML
	*return 表单提交HTML文本
     */
    function build_form() {
		//POST方式传递
        $sHtml = '<form action="' . REQ_URL_PAY . '" method="post" target="_blank">';

        while (list ($key, $val) = each ($this->forminfo)) {
            $sHtml .= '<input type="hidden" name="' . $key . '" id="' . $key . '" value="' . $val . '"/>' . "\n";
        }
		
		if($this->config['b2b_or_b2c']=='all')
		{
			$sHtml .= '<label><input type="radio" name="b2b_or_b2c" value="b2c" checked="checked"> 个人（b2c）</label>
						<label><input type="radio" name="b2b_or_b2c" value="b2b" > 企业（b2b）</label><br /><br />';
		}
		//submit按钮控件请不要含有name属性
        $sHtml = $sHtml.'<input class="zl-ok" type="submit" value="银联在线确认付款"></form>';
		
		if($this->config['b2b_or_b2c']=='all')
		{
			$sHtml .= '<script type="text/javascript">
			$("input[name=\'b2b_or_b2c\']").click(function(){
				if($(this).val()=="b2c")
				{
					$("#MerId").val($("#MerId_B2C").val());
					$("#ChkValue").val($("#ChkValue_B2C").val());
				}
				else
				{
					$("#MerId").val($("#MerId_B2B").val());
					$("#ChkValue").val($("#ChkValue_B2B").val());
				}
			});
			</script>';
		}
		
        return $sHtml;
    }
	
    /********************************************************************************/

}
?>