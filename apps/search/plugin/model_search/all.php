<?php

class plugin_all extends object 
{
	private $search;
	
	public function __construct($search)
	{
		$this->search = $search;
	}
	
	public function before_search_all()
	{
		//$this->search->cl->SetFilter('modelid',array(1,2,5,10)); //������ģ��IDΪ 1,2,5,10 ͬʱ����������ǰ�Ĺ���
	}
	
	public function after_search_all()
	{
		//�����Զ���һЩ���صĲ�ͬ��� ���������λ$this->search->data (�����ʽ)
		
	}
}