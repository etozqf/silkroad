<?php
/**
 * 充值记录
 *
 * @author liuyuan
 * @copyright 2010 (c) CmsTop
 * @date 2011/04/25
 * @version $Id$
 */

class model_admin_charge extends model
{
	private $iplocation;
	function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'pay_charge';
		$this->_primary = 'chargeid';
		$this->_fields = array(
			'chargeid', 'orderno', 'amount', 
			'apiid', 'created', 'createdby','createip',
			'inputed','inputedby','inputip', 'status','memo'
		);

		$this->_readonly = array('chargeid', 'orderno');
		$this->_create_autofill = array();
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array();
	}

	/**
	 * 首页数据查询、显示
	 *
	 * @table csmtop_pay_charge AS c 充值记录
	 * @table cmstop_pay_platform AS p 支付平台（支付接口）
	 */
	public function page($where, $order, $page, $size)
	{
		$where && $where = "WHERE $where";
		$order && $order = "ORDER BY $order";
		$field = "c.chargeid, c.orderno, c.amount, c.created, c.createdby, c.inputedby, c.status, c.inputip, p.name";
		$sql = "SELECT $field FROM #table_pay_charge c
				LEFT JOIN #table_pay_platform p ON c.apiid = p.apiid
				$where $order";
		$data = $this->db->page($sql, $page, $size);
		return $this->_output($data);
	}

	/**
	 * 格式化输出数据 
	 *
	 * @param $data 是一条或多条记录
	 */
	private function _output($data)
	{
		if(!$data) return array();
		if(!$data[0]) {
			$flag = true;
			$data = array($data);
		}

		import('helper.iplocation');
		$this->iplocation = new iplocation();

		foreach ($data as & $r)
		{
			$r['userid'] = $r['inputedby'] ? $r['inputedby'] : $r['createdby'];
			$r['created'] = $r['created'] ? date('Y-m-d H:i:s', $r['created']) : 'Unknow';
			$r['inputedby'] = $r['inputedby'] ?  $this->get_username($r['inputedby']) : $this->get_username($r['createdby']);
			$r['status_cn'] = ($r['status'] == 1) ? '成功' : (($r['status'] == 2) ? '<font color="red">失败</font>' : '未支付');
			$r['inputip'] = $r['inputip'].' '.$this->iplocation->get($r['inputip']);
			empty($r['name']) && $r['name'] = 'Unknow';
		}
		if($flag) $data = $data[0];
		return $data;
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
}
