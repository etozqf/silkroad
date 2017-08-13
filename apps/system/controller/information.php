<?php
class controller_information extends system_controller_abstract
{
	public $db;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->db=factory::db();
	}

 /*
  *处理前台属性动态获取与文章关联数据
  * author:zhudi
  * date:201603111
  * date:kanzhi 于20160325晚进行重构
  */
  public function get_property_content()
  {

  	 	$proid=intval($_GET['proid']);
  	 	$page=empty($_GET['page'])?1:intval($_GET['page']);
  	 	$pagesize=empty($_GET['pagesize'])?14:intval($_GET['pagesize']);
  	 	$url=empty($_GET['url'])?table('category',25,'url'):$_GET['url'];
                $catid=empty($_GET['catid'])?25:intval($_GET['catid']);   //英文资讯默认栏目ID是25

  	 	$field="c.contentid as contentid,title,url,catid,published";
  	 	$table="cmstop_content as c inner join cmstop_content_property as pc on c.contentid=pc.contentid";
  	 	$where="where catid=$catid and status=6 and pc.proid=$proid order by published desc";
  	 	$limit=" limit ".($page-1)*$pagesize.",".$pagesize;
		
		

  	 	$list=$this->db->select("select $field from $table $where $limit");

  	 	$total=$this->get_count($table,$where);

  	 	foreach($list as $key=>&$value){
  	 		$value['published']=date("Y-m-d H:i",$value['published']);
  	 	}

  	 	if(!empty($list))
  	 	{
  	 		/*得到分页函数*/
  	 		$page=$this->custom_page($total,$page,$pagesize,$url);
  	 		$arr=array('status'=>'success','content'=>$list,'page'=>$page,'total'=>$total);
  	 	}else{
  	 		$arr=array('status'=>'error','content'=>'No Content');
  	 	}
  	 
  	 	echo $_GET['jsoncallback']."(".$this->json->encode($arr).")";
  	 	
  }

  public function get_count($table,$where)
  {
  		$field="c.contentid as contentid";
  		$table="cmstop_content as c inner join cmstop_content_property as pc on c.contentid=pc.contentid";  	
  		$result=$this->db->select("select count(c.contentid) as contentid from $table $where");
  		return $result[0]['contentid'];
  }


  /*自定义分页函数*/
  public function custom_page($total,$page=1,$pagesize,$url)
  {
   	 return pages_infolist($total,$page,$pagesize,$offset=2,$url);                
  }


	
}
