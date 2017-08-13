<?php
//zend52   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php

class controller_admin_payment extends pay_controller_abstract
{

    private $charge = NULL;
    private $payment = NULL;
    private $pagesize = 15;

    public function __construct( &$app )
    {
		parent::__construct($app);
        $this->payment = loader::model( "admin/payment" );
        $this->charge = loader::model( "admin/charge" );
    }

    public function index( )
    {
        $this->view->assign( "head", array( "title" => "消费记录" ) );
        $this->view->assign( "pagesize", $this->pagesize );
        $this->view->display( "payment" );
    }

    public function page( )
    {
        if ( isset( $_GET['keywords'] ) && $_GET['keywords'] )
        {
            $userid = $this->charge->get_userid( $_GET['keywords'] );
            $userid = $userid ? $userid : 0;
            $where = where_keywords( "createdby", $userid );
            unset( $userid );
        }
        $order = "`created` DESC";
        $page = max( isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1, 1 );
        $size = max( isset( $_GET['pagesize'] ) ? intval( $_GET['pagesize'] ) : $this->pagesize, 1 );
        $data = $this->payment->page( $where, $order, $page, $size );
        $total = $this->payment->count( );
        $result = array(
            "total" => $total,
            "data" => $data
        );
        echo $this->json->encode( $result );
    }

}

?>
