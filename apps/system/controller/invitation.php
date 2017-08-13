<?php
/*
 + 前台注册发送邮件获取邀请码
 +author:kanzhi
 +date:2016/03/13
*/
class controller_invitation extends system_controller_abstract
{
    public $error;

	public function __construct($app)
	{
		parent::__construct($app);
        $session=factory::session();
        $session->start();
	}

	/*
     * 向用户注册的邮箱发送邀请码获取邮件
	*/
    public function getcode()
    {
        
    	$email=!empty($_GET['email'])?$_GET['email']:false; 

    	/*随机生成6位数字邀请码*/
    	$code=$this->rand_invitation_code();
    	/*发送邮件*/
    	$result=$this->sent_email($email,$code);
        /*
          * 如果注册的会员邀请码信息，之前已存在，则先清空，然后赋新值
        */
        if(isset($_SESSION[$email]) || isset($_SESSION[$email]['invitation_code'])){
               unset($_SESSION[$email]); 
        }

        $_SESSION[$email]['code']=$code; 

                
    	if($result){
    		$arr=array('status'=>'success','info'=>'申请邀请码邮件已发出，请登陆邮箱查看');
    	}
    	else
    	{
    		$arr=array('status'=>'error','info'=>'邮件发送失败');
    	}

        $list=$this->json->encode($arr);
        echo $_GET['jsoncallback']."($list)";
    	
    }

    /*
    * 英文站向用户注册的邮箱发送邀请码获取邮件
    */
    public function getcode_cn()
    {
        
        if(empty($_GET['email']))
        {
            $arr=array('status'=>'error','info'=>'Email not empty');
            $list=$this->json->encode($arr);
            echo $_GET['jsoncallback']."($list)";
            exit;
        }
        else
        {
             $email=$_GET['email'];
        }

        /*随机生成6位数字邀请码*/
        $code=$this->rand_invitation_code();
        /*发送邮件*/
        $site_type = 2;
        $result=$this->sent_email($email,$code,$site_type);
        /*
          * 如果注册的会员邀请码信息，之前已存在，则先清空，然后赋新值
        */
        if(isset($_SESSION[$email]) || isset($_SESSION[$email]['invitationCode'])){
               unset($_SESSION[$email]); 
        }

        $_SESSION[$email]['invitationCode']=$code; 

                
        if($result){
            $arr=array('status'=>'success','info'=>'Application invitation code mail has been sent, please visit the mailbox to view');
        }
        else
        {
            $arr=array('status'=>'error','info'=>'Message sending failed');
        }

        $list=$this->json->encode($arr);
        echo $_GET['jsoncallback']."($list)";
        
    }

    /*随机生成6位数字邀请码*/
    private function rand_invitation_code(){
        $length=6;
        $arr=array(0,1,2,3,4,5,6,7,8,9);
        for($i=0;$i<6;$i++){
            $keys=mt_rand(0,9);
            $new[]=$arr[$keys];
        }

        return implode("",$new);   
    }


    /*
     * 发送邮件
     + 引入smtp类，用来发送邮件
    */
    private function sent_email($email,$code,$site_type)
    {
    	import('helper.smtp');
        $setting=setting("system","mail");
       //smtp邮件服务器，如果后台未设置，默认使用 smtp.163.com
        $smtpserver=value($setting,'smtp_host');
        //smtp端口号，默认25
        $smtpport=value($setting,"smtp_port");
        //SMTP邮箱账号
        $smtpusermail=value($setting,"smtp_username");
        //SMTP邮箱密码
        $smtppassword=value($setting,"smtp_password");
        //发件人
        $smtpusername=substr($smtpusermail,0,strpos($smtpusermail,'@'));

       

        //邮件主题
        if($site_type==2){
            //英文站邮件主题
            $title="The silkroad net registration invitation code";
            //英文站邮件内容
            $content="You are welcome to come to the silk road database registration,Please fill in this invitation code".$code." to the corresponding location on the registration page.";
        }else{
            //中文站邮件主题
            $title="丝路网注册邀请码";
            //中文站邮件内容
            $content="丝路数据库欢迎你前来注册，请将此邀请码".$code."填入到注册页相应位置。";
        }
        
        //邮件内容
        
        //邮件格式
        $mailtype="html";
        //默认进行身份验证
        $smtp_auth=true;
    
        $smtp=new smtp($smtpserver,$smtpport,true,$smtpusername,$smtppassword);
        //是否进行debug调试
        $smtp->debug=false;

        //发送邮件
        $result=$smtp->sendmail($email,$smtpusermail,$title,$content,$mailtype);

        if($result){
            return true;
        }else{
            return false;
        }

    }

   


}