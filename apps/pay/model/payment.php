<?php
class model_payment extends model
{
	protected $payment;
	public function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'pay_payment';
		$this->_primary = 'paymentid';
		$this->_fields = array('paymentid', 'amount', 'title', 'url', 'created', 'createdby', 'ip', 'order_identify');
		$this->_readonly = array('paymentid');
		$this->_create_autofill = array( 'ip' => IP, 'created' => TIME, 'createdby' => $this->_userid);
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array(
			'amount'=>array('not_empty'=>array('充值金额不能为空')), 
			'title'=>array('not_empty'=>array('物品名称不能为空'))
		);

		$this->payment = loader::model('admin/payment');
	}

	/**
	 * 获取消费记录数据 调用 admin/payment.php page() 方法
	 *
	 */
	public function page($where, $order, $page, $size)
	{
		return $this->payment->page($where, $order, $page, $size);
	}

	//添加付款记录
	public function add($data)
	{
		$this->data = $data;
		return $this->insert($this->data);
	}

	//获取付款信息
	public function get($paymentid)
	{
		return $this->get_by('paymentid', $paymentid);
	}
}