<?php
//zend52   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php

class model_admin_account extends model
{

    private $iplocation = NULL;
    private $charge = NULL;

    public function __construct( )
    {
		parent::__construct();
        $this->_table = $this->db->options['prefix']."pay_account";
        $this->_primary = "userid";
        $this->_fields = array( "userid", "balance", "expense", "updated", "updatedby", "ip" );
        $this->_readonly = array( "userid" );
        $this->_create_autofill = array( );
        $this->_update_autofill = array( );
        $this->_filters_input = array( );
        $this->_filters_output = array( );
        $this->_validators = array( );
        $this->charge = loader::model( "admin/charge" );
    }

    public function page( $where, $order, $page, $size )
    {
        if ( $where )
        {
            $where = "WHERE ".$where;
        }
        if ( $order )
        {
            $order = "ORDER BY ".$order;
        }
        $field = "userid, balance, expense, updated, updatedby, ip";
        $sql = "SELECT ".$field." FROM #table_pay_account {$where} {$order}";
        $data = $this->db->page( $sql, $page, $size );
        return $this->_output( $data );
    }

    private function _output( $data )
    {
        if ( !$data )
        {
            return array( );
        }
        if ( !$data[0] )
        {
            $flag = TRUE;
            $data = array(
                $data
            );
        }
        import( "helper.iplocation" );
        $this->iplocation = new iplocation( );
        foreach ( $data as $r )
        {
            $r['updated'] = $r['updated'] ? date( "Y-m-d H:i:s", $r['updated'] ) : "Unknow";
            $r['username'] = $r['userid'] ? $this->charge->get_username( $r['userid'] ) : "Unknow";
            $r['ip'] = $r['ip']." ".$this->iplocation->get( $r['ip'] );
        }
        if ( $flag )
        {
            $data = $data[0];
        }
        return $data;
    }

}

?>
