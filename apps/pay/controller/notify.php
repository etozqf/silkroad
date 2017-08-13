<?php
//zend52   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php

class controller_notify extends pay_controller_abstract
{

    protected $cookie = NULL;
    protected $charge = NULL;
    protected $account = NULL;

    public function __construct( &$app )
    {
		parent::__construct($app);
        $this->charge = loader::model( "charge" );
        $this->account = loader::model( "account" );
    }

    public function notify_url( )
    {
        $trade_status = $_POST['trade_status'];
        $out_trade_no = $_POST['out_trade_no'];
        $amount = $_POST['total_fee'];
        $cinfo = $this->charge->get( $out_trade_no );
        if ( $trade_status == "TRADE_SUCCESS" || $trade_status == "TRADE_FINISHED" )
        {
            if ( $cinfo['status'] != 1 )
            {
                $this->account->deposit( $cinfo['createdby'], $amount );
                $this->charge->deposit( $out_trade_no );
            }
            echo "success";
        }
        else
        {
            $this->charge->failed( $out_trade_no );
            echo "fail";
        }
    }

}

?>
