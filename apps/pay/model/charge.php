<?php
class model_charge extends model
{
	public $data;
	private $charge;

	public function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'pay_charge';
		$this->_primary = 'chargeid';
		$this->_fields = array(
			'chargeid', 'orderno', 'amount', 'apiid', 
			'created', 'createdby','createip','inputed',
			'inputedby','inputip','status','memo'
		);

		$this->_readonly = array('chargeid');
		$this->_create_autofill = array('createip'=>IP, 'created'=>TIME, 'createdby'=>$this->_userid );
		$this->_update_autofill = array('inputip'=>IP, 'inputed'=>TIME, 'inputedby'=>$this->_userid);
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array(
				'amount'=>array('not_empty'=>array('充值金额不能为空')), 
				'apiid'=>array('not_empty'=>array('请选择一种充值方式'))			
		);

		$this->charge = loader::model('admin/charge');
	}

	/**
	 * 获取交易记录数据 调用 admin/charge.php page() 方法
	 *
	 */
	public function page($where, $order, $page, $size)
	{
		return $this->charge->page($where, $order, $page, $size);
	}

	// 添加充值记录，返回订单号
	public function add($amount, $apiid)
	{
		$this->data = array(
			'orderno' => $this->mk_tradeno(),
			'amount' => $amount,
			'apiid' => $apiid
		);
		return $this->insert($this->data) ? $this->data['orderno'] : false;
	}

	// 入款充值
	public function deposit($orderno)
	{
		return $this->update(array('status' => 1), array('orderno' => $orderno)) ? $this->get($orderno) : false;
	}

	// 入款失败
	public function failed($orderno)
	{
		$order = $this->get($orderno);
		//入款成功的除外
		if($order['status'] != 1)
		{
			$this->update(array('status' => 2), array('orderno' => $orderno));
		}
	}

	// 获取订单信息
	public function get($orderno)
	{
		return $this->get_by('orderno', $orderno);
	}
	
	// 获取订单号
	public function get_orderno($amount, $apiid, $status=1)
	{
		$data = parent::get("amount='".$amount."' AND apiid='".$apiid."' AND status!=1 AND createdby=".$this->_userid);
		return $data['orderno'];
	}

	// 生成订单号
	public function mk_tradeno()
	{
		//mt_srand(microtime(TRUE) * 1000000);
		//return date('YmdHis').str_pad(mt_rand( 1, 99999), 5, '0', STR_PAD_LEFT);
		//银联在线为16位订单号
		return date('YmdHis').str_pad(mt_rand( 1, 99), 2, '0', STR_PAD_LEFT);
	}

	/**
	 * 返回订单手续费
	 *
	 * @param float $amount     订单价格
	 * @param int $payfee       手续费比率
	 * @param int $method       手续费方式(充值金额的百分比还是每笔交易固定金额)
	 */
	public function payfee($amount, $payfee)
	{
	    return round($amount*$payfee, 2);
	}
}