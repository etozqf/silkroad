<?php
//zend52   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php

class controller_admin_account extends pay_controller_abstract
{

    private $account = NULL;
    private $charge = NULL;
    private $pagesize = 15;

    public function __construct( &$app )
    {
		parent::__construct($app);
        $this->account = loader::model( "admin/account" );
        $this->charge = loader::model( "admin/charge" );
    }

    public function index( )
    {
        $this->view->assign( "head", array( "title" => "会员账户" ) );
        $this->view->assign( "pagesize", $this->pagesize );
        $this->view->display( "account" );
    }

    public function page( )
    {
        if ( isset( $_GET['keywords'] ) && $_GET['keywords'] )
        {
            $userid = $this->charge->get_userid( $_GET['keywords'] );
            $userid = $userid ? $userid : 0;
            $where = where_keywords( "userid", $userid );
            unset( $userid );
        }
        $order = "`updated` DESC";
        $page = max( isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1, 1 );
        $size = max( isset( $_GET['pagesize'] ) ? intval( $_GET['pagesize'] ) : $this->pagesize, 1 );
        $data = $this->account->page( $where, $order, $page, $size );
        $total = $this->account->count( );
        $result = array(
            "total" => $total,
            "data" => $data
        );
        echo $this->json->encode( $result );
    }

}

?>
