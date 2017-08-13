<?php
// 编辑后保存版本
class plugin_itemtype extends object 
{
	private $item;
	private $itemtype;
	private $diff=array('trade'=>array(),'itemtype'=>array(),'investmenttype'=>array(),'country'=>array());
	
	public function __construct($item)
	{
		$this->item = $item;
		$this->itemtype = loader::model('admin/itemtype');
	}

	
	public function after_add()
	{
		if($this->item->contentid){
			$data = $this->item->itemdata;
			foreach ($data as $key => $value) {
				foreach ($value as $k => $v) {
					$this->itemtype->add(array('contentid'=>$this->item->contentid,'type'=>$key,'typeid'=>$v));
				}
			}
		}

	}

	public function after_edit()
	{	
		if($this->item->contentid){
			$data = $this->item->itemdata;
			$itemdata = $this->itemtype->ls(array('contentid'=>$this->item->contentid));
			foreach ($itemdata as $k => $v) {
				//如果数据库中的值在传输过来的新值数组中存在就继续处理传输过来的新值,否则就从数据库中删除这条数据
				if(in_array($v['typeid'], $data[$v['type']])){
					$this->diff[$v['type']][] = $v['typeid'];
				}else{
					$this->itemtype->del($v['itemtypeid']);
				}
			}
			foreach ($this->diff as $k => $v) {
				$array_diff = array_diff($data[$k],$v);
				foreach ($array_diff as $m => $n) {
					$this->itemtype->add(array('contentid'=>$this->item->contentid,'type'=>$k,'typeid'=>$n));
				}
			}
			
		}
	}
}