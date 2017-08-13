<?php
/**
 *	Description : 获取10天内更新的国家id
 *
 *	@author      Hmy
 *	@datetime    2017/3/22 19:30
 *	@copyright   Beijing CmsTop Technology Co.,Ltd.
 */
 
class controller_getNew extends country_controller_abstract
{
	function __construct($app)
	{
		parent::__construct($app);
	}
	
	public function index()
	{
		$db=factory::db();
		$strt = time() - 864000;
		$SQL = "select p.proid as proid from cmstop_content as c,cmstop_content_property as p where c.contentid = p.contentid and c.catid between 31 and 45 and c.modelid = 1 and c.modified > $strt";
		$result=$db->select($SQL);
		$result = $this ->arrUnique($result);
		
		if(!empty($result))
		{
				$data=array('state'=>true,'data'=>$result);
		}
		else
		{
				$data=array('state'=>false,'message'=>'no data');
		}
		
		echo $this->json->encode($data);
	}
	
	//去除二维数组中重复的值
	private function arrUnique($arr) {
		foreach ($arr as $k => $v) {
			$v = join(',', $v);
			$temp[$k] = $v;
		}
		$temp = array_unique($temp);
		foreach ($temp as $k => $v) {
			$arr = explode(',', $v);
			$temp1[$k]['proid'] = $arr[0];
		}
		return $temp1;
	}
	
}