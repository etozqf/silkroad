<?php
//zend52   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php

class controller_admin_platform extends pay_controller_abstract
{

    private $platform = NULL;
    private $pinyin = NULL;
    private $pagesize = 15;

    public function __construct( &$app )
    {
		parent::__construct($app);
        $this->platform = loader::model( "admin/platform" );
        import( "helper.pinyin" );
        $this->pinyin = new pinyin( );
    }

    public function index( )
    {
        $this->view->assign( "head", array( "title" => "支付平台" ) );
        $this->view->assign( "pagesize", $this->pagesize );
        $this->view->display( "platform" );
    }

    public function page( )
    {
        if ( isset( $_GET['keywords'] ) && $_GET['keywords'] )
        {
            $where = where_keywords( "name", $_GET['keywords'] );
        }
        $page = max( isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1, 1 );
        $size = max( isset( $_GET['pagesize'] ) ? intval( $_GET['pagesize'] ) : $this->pagesize, 1 );
        $data = $this->platform->page( $where, $page, $size );
        $total = $this->platform->count( );
        $result = array(
            "total" => $total,
            "data" => $data
        );
        echo $this->json->encode( $result );
    }

    public function save( )
    {
        if ( $this->is_post( ) )
        {
            $apiid = $_POST['apiid'];
            if ( isset( $apiid, $apiid ) )
            {
                $this->platform->edit( $apiid, $_POST );
            }
            else
            {
                $apiid = $this->platform->add( $_POST );
                $this->platform->create_tpl( pinyin::get( $_POST['name'], "utf-8" ) );
            }
            if ( $apiid )
            {
                $json = array(
                    "state" => TRUE,
                    "data" => $this->platform->page( "`apiid` =".$apiid, "", "", TRUE )
                );
            }
            else
            {
                $json = array(
                    "state" => FALSE,
                    "error" => $this->platform->error( )
                );
            }
            exit( $this->json->encode( $json ) );
        }
        $apiid = intval( $_GET['apiid'] );
        if ( $platform = $this->platform->getbyid( $apiid ) )
        {
            $platform['tpl'] = pinyin::get( $platform['name'], "utf-8" );
            $this->view->assign( $platform );
        }
        if ( !( $tpl = $platform['tpl'] ) )
        {
            $tpl = "default";
        }
        $this->view->display( "platform/".$tpl."/save" );
    }

    public function delete( )
    {
        $apiid = $_GET['apiid'];
        $result = $this->platform->del( $apiid ) ? array(
            "state" => TRUE
        ) : array(
            "state" => FALSE,
            "error" => $this->platform->error( )
        );
        echo $this->json->encode( $result );
    }

    public function enable( )
    {
        $apiid = $_GET['apiid'];
        $result = $this->platform->enable( $apiid ) ? array(
            "state" => TRUE,
            "data" => $this->platform->page( "`apiid` =".$apiid, "", "", TRUE )
        ) : array(
            "state" => FALSE,
            "error" => $this->platform->error( )
        );
        echo $this->json->encode( $result );
    }

    public function disable( )
    {
        $apiid = $_GET['apiid'];
        $result = $this->platform->disable( $apiid ) ? array(
            "state" => TRUE,
            "data" => $this->platform->page( "`apiid` =".$apiid, "", "", TRUE )
        ) : array(
            "state" => FALSE,
            "error" => $this->platform->error( )
        );
        echo $this->json->encode( $result );
    }

}

?>
