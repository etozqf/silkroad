<?php
/**
 * 订阅记录
 *
 * @author zhouqingfeng
 * @copyright 2010 (c) CmsTop
 * @date 2012/06/19
 * @version $Id$
 */

class model_payview_order extends model implements SplSubject
{
	private $oid, $data = array(), $where, $payview_category;
	function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'payview_order';
		$this->_primary = 'oid';
		$this->_fields = array('orderno', 'pvcid', 'userid', 'username', 'type', 'payfee', 'starttime', 'endtime', 'status', 'createdby', 'created', 'updatedby', 'updated', 'is_invoice', 'invoice_title', 'post_name', 'post_address', 'post_phone', 'post_fees');

		$this->_readonly = array('oid', 'created', 'createdby');
		$this->_create_autofill = array('created'=>TIME, 'createdby'=>$this->_userid);
		$this->_update_autofill = array('updated'=>TIME, 'updatedby'=>$this->_userid);
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array();
		$this->payview_category = table('payview_category');
	}

	
	function get($oid, $fields = '*')
	{
		
		$this->oid = intval($oid);
		
		$this->event = 'before_get';
		$this->notify();

		$this->data = parent::get($this->oid, $fields);
		$this->data = $this->output($this->data);
        if ($this->data)
        {
			$this->event = 'after_get';
			$this->notify();
        }
        
		return $this->data;
	}
	
	function get_by($field, $value, $fields = '*', $order = null)
	{
		
		$this->oid = intval($oid);
		
		$this->event = 'before_get';
		$this->notify();

		$this->data = parent::get_by($field, $value, $fields, $order);
        if ($this->data)
        {
			$this->event = 'after_get';
			$this->notify();
        }
        
		return $this->data;
	}

	
	function get_by_pvcid($pvcid)
	{
		
		$pvcid = intval($pvcid);
		$sql = "SELECT * FROM `#table_payview_order` WHERE userid={$this->_userid} AND pvcid={$pvcid} ORDER BY oid DESC";

		$this->data = $this->db->get($sql);
        
		return $this->data;
	}

	function ls($where = null, $fields = '*', $order = '`oid` DESC', $page = null, $pagesize = null)
	{
		is_array($where) && $where = implode(' AND ', $where);
		$this->where = $where;
		$where && $where = "WHERE $where";
		$order && $order = "ORDER BY $order";
		$this->page = $page;
		$this->pagesize = $pagesize;
		$this->event = 'before_ls';
		$this->notify();
		$this->data = $this->db->page("SELECT $fields FROM `#table_payview_order` $where $order", $page, $pagesize);
		$this->output($this->data);
		$this->event = 'after_ls';
		$this->notify();
		return $this->data;
	}

	function total()
	{
		return $this->count($this->where);
	}

	/**
	 * 格式化输出数据 
	 *
	 * @param $data 是一条或多条记录
	 */
	public function output(& $data)
	{
		if(!$data) return array();
		if(!$data[0]) {
			$flag = true;
			$data = array($data);
		}
		$url = url("payview/order/repay").'&orderno=';

		foreach ($data as & $r)
		{
			$r['created'] = $r['created'] ? date('Y-m-d H:i:s', $r['created']) : 'Unknow';
			$r['createdname'] = $r['createdby'] ?  $this->get_username($r['createdby']) : 'Unknow';
			$r['updated'] = $r['updated'] ? date('Y-m-d H:i:s', $r['updated']) : 'Unknow';
			$r['updatedname'] = $r['updatedby'] ?  $this->get_username($r['updatedby']) : 'Unknow';
			$r['status_text'] = $r['status'] == 1 ? '已支付' : ($r['status'] == 2 ? '已关闭' : '<a href="'.$url.$r['orderno'].'" title="马上支付">未支付</a>');
			$r['type_text'] = $r['type'] == 1 ? '线下' : '线上';
			$r['title'] = $this->payview_category[$r['pvcid']]['title'].'('.$this->payview_category[$r['pvcid']]['timetype'].'个月)';
			$r['starttime'] = $r['starttime'] ? date('Y-m-d H:i:s', $r['starttime']) : 'Unknow';
			$r['endtime'] = $r['endtime'] ? date('Y-m-d H:i:s', $r['endtime']) : 'Unknow';
			$r['is_invoice_text'] = $r['is_invoice'] == 1 ? '是' : '否';
		}
		if($flag) $data = $data[0];
		return $data;
	}

	
	function add($data)
	{
    	$this->data = $data;
    	$this->input($this->data);
    	
    	$this->event = 'before_add';
    	$this->notify();
		$data = $this->filter_array($this->data, $this->_fields);
		if($this->oid = $this->insert($data))
		{
			$this->event = 'after_add';
			$this->notify();
		}
		return $this->oid;
	}
	
	function edit($oid, $data)
	{
    	$this->oid = id_format($oid);
    	$this->data = $data;
    	$this->input($this->data);
    	
    	$this->event = 'before_edit';
    	$this->notify();
    	
    	$data = $this->filter_array($this->data, $this->_fields);
    	
    	if($result = $this->update($data, $this->oid))
    	{
    		$this->event = "after_edit";
    		$this->notify();
    	}
    	return $result;
	
	}
	
    public function delete($oid)
    {
    	$oid = id_format($oid);
		if ($oid === false)
		{
			$this->error = "$oid 格式不正确";
			return false;
		}
		if (is_array($oid)) return array_map(array($this, 'delete'), $oid);
		
		$this->oid = $oid;
		$this->event = 'before_delete';
		$this->notify();
		
		$result = parent::delete($this->oid);
		if ($result)
		{
			$this->event = 'after_delete';
			$this->notify();
		}
		return $result;
    }
    
	// 检测用户名
	public function check_username($username)
	{
		if(empty($username))
		{
			$this->error = '用户名不能为空';
			return false;
		}
 
		if(!$this->db->get('select userid from #table_member where username=?', array($username)))
		{
			$this->error = '用户名不存在';
			return false;
		}
        
		return  true;
	}

	// 获取用户名
	public function get_username($userid)
	{
		$r = $this->db->get('select username from #table_member where userid=?', array($userid));
		return $r['username'];
	}

	// 获取用户ID
	public function get_userid($username)
	{
		$r = $this->db->get('select userid from #table_member where username=?', array($username));
		return $r['userid'];
	}
    
	
	public function input(& $r)
	{
		
	}
	
    public function attach (SplObserver $observer)
	{
		$this->observers[] = $observer;
	}

	public function detach(SplObserver $observer)
	{
		if($index = array_search($observer, $this->observers, true)) unset($this->observers[$index]);
	}

	public function notify ()
	{
		foreach ($this->observers as $observer)
		{
			$observer->update($this);
		}
	}
}
