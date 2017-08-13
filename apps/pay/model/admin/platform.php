<?php
//zend52   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php

class model_admin_platform extends model
{

    private $apptpl = NULL;

    public function __construct( )
    {
		parent::__construct();
        $this->_table = $this->db->options['prefix']."pay_platform";
        $this->_primary = "apiid";
        $this->_fields = array( "apiid", "name", "logo", "description", "url", "payfee", "setting", "sort", "disabled" );
        $this->_readonly = array( "apiid" );
        $this->_create_autofill = array( );
        $this->_update_autofill = array( );
        $this->_filters_input = array( );
        $this->_filters_output = array( );
        $this->_validators = array( );
        import( "helper.folder" );
        import( "helper.pinyin" );
        $this->apptpl = app_dir( "pay" )."view".DS."platform".DS;
    }

    public function page( $where, $page, $size, $single = NULL )
    {
        if ( $where )
        {
            $where = "WHERE ".$where;
        }
        $field = "apiid, name, logo, description, url, payfee, disabled, sort";
        $sql = "SELECT ".$field." FROM #table_pay_platform {$where} ORDER BY `sort` ASC";
        $data = $this->db->page( $sql, $page, $size );
        return $this->_output( $data, $single );
    }

    public function add( $data )
    {
        $data['setting'] = serialize( $data['setting'] );
        return $this->insert( $data );
    }

    public function edit( $apiid, $data )
    {
        if ( isset( $data['setting'] ) )
        {
            $data['setting'] = serialize( $data['setting'] );
        }
        return $this->update( $data, $apiid );
    }

    public function getbyid( $apiid )
    {
        $data = parent::get( $apiid );
        if ( isset( $data['setting'] ) )
        {
            $data['setting'] = unserialize( $data['setting'] );
        }
        return $data;
    }

    public function del( $ids )
    {
        foreach ( explode( ",", $ids ) as $id )
        {
            $this->del_tpl( $id );
        }
        return parent::delete( implode_ids( $ids ) );
    }

    public function enable( $apiid )
    {
        return $this->update( array( "disabled" => 0 ), "`apiid` IN (".implode_ids( $apiid ).")" );
    }

    public function disable( $apiid )
    {
        return $this->update( array( "disabled" => 1 ), "`apiid` IN (".implode_ids( $apiid ).")" );
    }

    private function _output( $data, $single = NULL )
    {
        if ( !$data )
        {
            return array( );
        }
        if ( !$data[0] )
        {
            $data = array(
                $data
            );
        }
        foreach ( $data as $r )
        {
            $r['disabled'] = $r['disabled'] == 1 ? "<font color=\"red\">已禁用</font>" : "启用";
        }
        if ( $single && count( $data ) == 1 )
        {
            $data = $data[0];
        }
        return $data;
    }

    public function del_tpl( $id )
    {
        $names = $this->get_field( "name", "apiid=".$id );
        $foldername = pinyin::get( $names, "utf-8" );
        $folder = $this->apptpl.$foldername;
        if ( is_dir( $folder ) )
        {
            folder::delete( $folder );
        }
    }

    public function create_tpl( $name )
    {
        $defaultdir = $this->apptpl."default".DS;
        $target = $this->apptpl.$name.DS;
        $defaultpl = $defaultdir."save_default.php";
        $targetpl = $target."save.php";
        if ( !is_dir( $target ) )
        {
            folder::create( $target );
        }
        if ( !file_exists( $targetpl ) )
        {
            copy( $defaultpl, $targetpl );
        }
    }

}

?>
