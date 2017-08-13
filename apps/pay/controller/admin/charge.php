<?php
//zend52   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php

class controller_admin_charge extends pay_controller_abstract
{

    private $chargem = NULL;
    private $pagesize = 12;

    public function __construct( &$app )
    {
		parent::__construct($app);
        $this->charge = loader::model( "admin/charge" );
    }

    public function index( )
    {
        $this->view->assign( "head", array( "title" => "财务管理" ) );
        $this->view->assign( "pagesize", $this->pagesize );
        $this->view->display( "charge" );
    }

    public function page( )
    {
        $rwkeyword = trim( $_GET['rwkeyword'] );
        if ( isset( $rwkeyword ) && $rwkeyword )
        {
            if ( is_numeric( $rwkeyword ) && "19" == mb_strlen( $rwkeyword ) )
            {
                $where = where_keywords( "c.orderno", $rwkeyword );
            }
            else if ( is_string( $rwkeyword ) )
            {
                $userid = $this->charge->get_userid( $rwkeyword );
                $userid = $userid ? $userid : 0;
                $where = where_keywords( "c.createdby", $userid );
                unset( $userid );
                unset( $rwkeyword );
            }
        }
        $starttime = $_GET['published'];
        $endtime = $_GET['unpublished'];
        if ( $starttime )
        {
            $where = $starttime = where_mintime( "`c`.`created`", $starttime );
        }
        if ( $endtime )
        {
            $where = where_maxtime( "`c`.`created`", $endtime );
        }
        if ( $starttime )
        {
            $where = $starttime." AND ".$where;
        }
        $order = "`created` DESC";
        $page = max( isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1, 1 );
        $size = max( isset( $_GET['pagesize'] ) ? intval( $_GET['pagesize'] ) : $this->pagesize, 1 );
        $data = $this->charge->page( $where, $order, $page, $size );
        $total = $this->charge->count( );
        $result = array(
            "total" => $total,
            "data" => $data
        );
        echo $this->json->encode( $result );
    }

}

?>
