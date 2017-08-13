<?php
class model_account extends model
{
	public $event, $error;
	private $observers = array();

	public function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'pay_account';
		$this->_primary = 'userid';
		$this->_fields = array('userid', 'balance', 'expense', 'updated', 'updatedby', 'ip');
		$this->_readonly = array('userid');
		$this->_create_autofill = array('ip'=>IP, 'updated'=>TIME, 'updatedby'=>$this->_userid);
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array();
	}

	//开户
	public function add($userid)
	{
		return $this->insert(array('userid' => $userid));
	}

	//入款
	public function deposit($userid, $amount)
	{
		$oriBal = round(floatval(table('pay_account', $userid,'balance')), 2);
		if($oriBal < 0 || round(floatval($amount),2) < 0)
		{
			$this->error = '系统异常，请联系管理员';
			return false;
		}
		$where= array('userid' => $userid);
		$data = array('balance' => round(floatval($oriBal),2) + round(floatval($amount),2));
		return $this->update($data, $where);
	}

	//扣款
	public function debit($userid, $amount)
	{
		$oriBal = round(floatval(table('pay_account',$userid,'balance')),2);
		$oriExp = round(floatval(table('pay_account',$userid,'expense')),2);
		$amount = round(floatval($amount),2);
		if ($oriBal < $amount || $amount < 0 || $oriExp < 0 )
		{
			return false;
		}
		$data = array(
				'balance' => $oriBal -  $amount,
				'expense' => $oriExp +  $amount
		);
		$where = array('userid' => $userid);
		return $this->update($data,$where);
	}

	//获取账户信息
	public function get($userid)
	{
		return $this->get_by('userid', $userid);
	}

	//查询余额
	public function get_balance($userid)
	{
		$balance = $this->get_field('balance', $userid);
		if ($balance)
		{
			return round(floatval($balance),2);
		}
		return false;
	}

	//查询消费额
	public function get_expense($userid)
	{
		$expense = $this->get_field('expense', $userid);
		if ($expense)
		{
			return round(floatval($expense),2);
		}
		return false;
	}

	//计算差额
	public function get_diff($userid, $amount)
	{
		$balance = round(floatval($this->get_balance($userid)), 2);
		$amount = round(floatval($amount), 2);
		$diff = round(($balance - $amount),2);
		return $diff;
	}
}