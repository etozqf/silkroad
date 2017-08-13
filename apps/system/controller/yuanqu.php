<?php

	/*园区功能
  * author:yangya
  * date:20160818
	*/
class controller_yuanqu extends system_controller_abstract
{

	public $pagesize=6;

	public function __construct($app)
	{
		parent::__construct($app);
		
	}
	
	public function get_yuanqu()
	{
		$db=factory::db();
		$sql = "select * from cmstop_position_yq";
		$sql1 = "select * from cmstop_yq_category where category=1";
		$data = $db->select($sql);
		$data1 = $db->select($sql1);
		$data2 = json_encode($data1);
		$total = count($data);
		$map = "";
		$yq = "";
    for($i=0;$i<$total;$i++){
        if($i<$total-1){
            $map.=$data[$i]['name'].':'.$data[$i]['point'].';';
            $yq.=$data[$i]['name'].':'.$data[$i]['value'].';';
         }else if($i==$total-1){
        		$map.=$data[$i]['name'].':'.$data[$i]['point'];
            $yq.=$data[$i]['name'].':'.$data[$i]['value'];
				 }
		}
    $catname = array();
		foreach($data1 as $k=>$v){
			$catname[] = $v['value'];
		}
		$cate = implode(',',$catname);      
		$this->template->assign('cate', $cate);
		$this->template->assign('data2', $data2);
		$this->template->assign('map', $map);
		$this->template->assign('yq', $yq);
		$this->template->display('china.html');
		
	}

	public function get_yuanqu_guoji()
	{
		$db=factory::db();
		$sql = "select * from cmstop_position_yq";
		$sql1 = "select * from cmstop_yq_category where category=2";
		$data = $db->select($sql);
		$data1 = $db->select($sql1);
		$data2 = json_encode($data1);
		$total = count($data);
		$map = "";
		$yq = "";
    for($i=0;$i<$total;$i++){
        if($i<$total-1){
            $map.=$data[$i]['name'].':'.$data[$i]['point'].';';
            $yq.=$data[$i]['name'].':'.$data[$i]['value'].';';
         }else if($i==$total-1){
        		$map.=$data[$i]['name'].':'.$data[$i]['point'];
            $yq.=$data[$i]['name'].':'.$data[$i]['value'];
				 }
		}
    $catname = array();
		foreach($data1 as $k=>$v){
			$catname[] = $v['value'];
		}
		$cate = implode(',',$catname);      
		$this->template->assign('cate', $cate);
		$this->template->assign('data2', $data2);
		$this->template->assign('map', $map);
		$this->template->assign('yq', $yq);
		$this->template->display('world.html');

	}

	public function get_cate_yuanqu()
	{
		// var_dump($_POST);die;
		$db=factory::db();
		$catname = $_POST['catname'];
		$sql = "select * from cmstop_position_yq where value='{$catname}'";
		$arr = $db->select($sql);
		// $total = count($data);
		$yq = array();
		foreach($arr as $k=>$v){
			$yq["$v[name]"] = $v["value"];
		}
		// $yq = "";
  //   for($i=0;$i<$total;$i++){
  //       if($i<$total-1){
  //           $yq.=$data[$i]['name'].':'.$data[$i]['value'].';';
  //        }else if($i==$total-1){
  //       		$yq.=$data[$i]['name'].':'.$data[$i]['value'];
		// 		 }
		// }
		// echo '<pre>';print_r($yq);die;
		$data = $this->json->encode($yq);
		// $data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		echo $data;
	}

	public function get_yuanqu_url()
	{
		$db=factory::db();
		$name = $_GET['name'];
		$sql = "select url from cmstop_position_yq where name='{$name}'";
		$url = $db->get($sql);
		$data = $this->json->encode($url);
		$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		echo $data;
	}
	public function chaxun()
	{
		$db=factory::db();

		// var_dump($_GET);
		$yq = $_GET['yuanqu'];
		$yq = explode(';',$yq);
		foreach($yq as $k=>$v){
			$sql = "select contentid from cmstop_content where title='{$v}'";
			$m = $db->get($sql);
			if(!$m){
				// $result = $yq[$k];
				// exit($this->json->encode(array('state'=>false, 'error'=>"$yq[$k]不存在")));
				$result = array('state'=>false, 'error'=>"$yq[$k]不存在");
				$data = $this->json->encode($result);
				$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
				echo $data;
				return;
			}
		}
		$result = array('state'=>true);
		$data = $this->json->encode($result);
		$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		echo $data;
	}

	public function get_map_yuanqu()
	{
		$db=factory::db();
		$province = $_GET['province'];
		// $sql = "select p.name,c.url from cmstop_content as c,cmstop_position_yq as p where province='{$province}' and c.title=p.name";
		$sql = "select url,name from cmstop_position_yq where province='{$province}'";
		$data = $db->select($sql);
		$data = $this->json->encode($data);
		$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		echo $data;
	}

	public function yq_search()
	{
		$db = factory::db();
		//获取所有有园区的省份
			$sql1 = "select province from cmstop_position_yq";
			$res1 = $db->select($sql1);
			$arr = array('辽宁省','吉林省','黑龙江省','河北省','山西省','陕西省','山东省','安徽省','江苏省','浙江省','河南省','湖北省','湖南省','江西省','台湾省','福建省','云南省','海南省','四川省','贵州省','广东省','甘肃省','青海省','西藏自治区','新疆自治区','广西自治区','内蒙古自治区','宁夏自治区','北京市','天津市','上海市','重庆市');
			$arr_province=array();
			foreach($res1 as $key=>$val){
				$arr_province[] = $val['province'];
			}
			$provinces = array_unique($arr_province);
			//获取园区类别
			$sql2 = "select * from cmstop_yq_category";
			$res2 = $db->select($sql2);
			$cate = array();
			foreach($res2 as $key=>$val){
				$cate["{$val['cateid']}"] = $val["value"];
			}
			//获取相关产业(属性)
			$res3 = $db->get("select childids from cmstop_property where name='行业'");
			$pro = array();
      $proids = explode(',',$res3['childids']);
	     foreach($proids as $k=>$v){
	          $b = $db->get("select proid,name from cmstop_property where proid=$v");
	          $pro[$b['proid']] = $b['name'];
	     }
		if($this->is_post()){
			// echo '<pre>';print_r($_POST);die;
			$cateid = $_POST['cate'];
			$province = $_POST['province'];
			$proid = $_POST['property'];
			$where = "";
			$data = array();

			//获取地区,类别和属性信息
			
			//查询所有的带有此属性的园区
			if($proid != 'all'){
				$sql4 = "select contentid from cmstop_content_property where proid=$proid";
				$res4 = $db->select($sql4);
				// echo '<pre>';print_r($res4);die;
				if($res4){
					foreach($res4 as $k=>$v){
						if(in_array(table('content',$v['contentid'],'catid'),$this->check_cate(219))){
							$name = table('content',$v['contentid'],'title');
							if($cateid!='all'){
								$value = table('yq_category',$cateid,'value');
							}
							$sql5 = "select positionid,province,value from cmstop_position_yq where name='{$name}'";
							$res5 = $db->get($sql5);
							// echo '<pre>';print_r($res2);
							//判断是否符合条件
							if($province!='all' && $cateid!='all'){
								if($res5['province']==$province && $res5['value']==$value){
									$data[] = $v['contentid'];
								}
						  }else if($province!='all' && $cateid=='all'){
						  	if($res5['province']==$province){
									$data[] = $v['contentid'];
								}
						  }else if($province=='all' && $cateid!='all'){
						  	if($res5['value']==$value){
									$data[] = $v['contentid'];
								}
						  }else if($province=='all' && $cateid=='all'){
						  	if($res5){
						  		$data[] = $v['contentid'];
						  	}
						  }
						}
							
					}
				}
			}else{
				if($province!='all' && $cateid!='all'){
						$value = table('yq_category',$cateid,'value');
						$sql6 = "select name from cmstop_position_yq where province='{$province}' and value='{$value}'";
					}else if($province!='all' && $cateid=='all'){
						$sql6 = "select name from cmstop_position_yq where province='{$province}'";
					}else if($province=='all' && $cateid!='all'){
						$value = table('yq_category',$cateid,'value');
						$sql6 = "select name from cmstop_position_yq where value='{$value}'";
					}
					$res6 = $db->select($sql6);
					// echo '<pre>';print_r($res6);die;
					foreach($res6 as $k=>$v){
						$a = $v['name'];
						$sql7 = "select contentid from cmstop_content where catid in (select catid from cmstop_category where parentid=219 or parentid=220) and title='{$a}'";
						// echo $sql7;die;
						$res7 = $db->get("select contentid from cmstop_content where catid in (select catid from cmstop_category where parentid=219 or parentid=220) and title='{$a}'");
						// echo $k.$res7['contentid'];
						// var_dump($res7);die;
						$data[] = $res7['contentid'];
					}
			}
			//搜索框选项内容变量
			$this->template->assign('provinces', $provinces);
			$this->template->assign('cate', $cate);
			$this->template->assign('pro', $pro);
			if($data){
				$total = count($data);
			}else{
				$total = 0;
				$data = '无搜索结果';
			}
			$this->template->assign('data', $data);
			$this->template->assign('total', $total);
			$this->template->display('system/yq_search.html');
		}else{
			//获取所有园区的总数
			$total = $db->get("select count(contentid) as c from cmstop_content where catid in (select catid from cmstop_category where parentid=219 or parentid=220)");
			$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
	    // var_dump($res4);die;
			$this->template->assign('provinces', $provinces);
			$this->template->assign('cate', $cate);
			$this->template->assign('pro', $pro);
			$this->template->assign('total', $total['c']);
			$this->template->assign('pagesize', $this->pagesize);
			$this->template->assign('page', $page);
			$this->template->display('system/yq_search.html');
		}
	}

	function check_cate($id)
	{
		$arr1 = subcategory($id);
		$arr2 = array();
		foreach($arr1 as $k=>$v){
			$arr2[] = $k;
		}
		return $arr2;
		}
	
	public function yq_db()
	{
		$db=factory::db();
		// var_dump($_GET);die;
		//查询园区类别
		$name1 = table('content',$_GET['contentid_first'],'title');
		$name2 = table('content',$_GET['contentid_third'],'title');
		$sql1 = "select value from cmstop_position_yq where name='{$name1}'";
		$sql2 = "select value from cmstop_position_yq where name='{$name2}'";
		$cat1 = $db->get($sql1);
		$cat2 = $db->get($sql2);
		$this->template->assign('first', $_GET['contentid_first']);
		$this->template->assign('third', $_GET['contentid_third']);
		$this->template->assign('cat1', $cat1['value']);
		$this->template->assign('cat2', $cat2['value']);
		$this->template->display('system/yq_compare.html');

	}

	public function yq_contents_news()
	{
		$db=factory::db();
		$contentid = $_GET['contentid'];
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$offset = ($page-1)*10;

		//获取新闻稿件数据
		$sql2 = "select contentid,yuanquid from cmstop_content where status=6 and catid=216 order by published desc";
    $res2 = $db->select($sql2);
    $news_total = array();
		foreach($res2 as $k=>$v){
        $arr2 = explode(',',$v['yuanquid']);
     			if(in_array($contentid,$arr2)){
      			$news_total[] = $v['contentid'];
    			}
    }
    // echo '<pre>';print_r($news_total);die;
    //分页取数据
    $news_data = array_slice($news_total,$offset,10);
		$total = count($news_total);
    $this->template->assign('page', $page);
		$this->template->assign('total', $total);
		$this->template->assign('pagesize', 10);
		$this->template->assign('news_data', $news_data);
		$this->template->assign('contentid', $contentid);
		$this->template->display('system/yq_contents_news.html');
	}

	public function yq_contents_views()
	{
		$db=factory::db();
		$contentid = $_GET['contentid'];
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$offset = ($page-1)*10;

		//获取新闻稿件数据
		$sql2 = "select contentid,yuanquid from cmstop_content where status=6 and catid=217 order by published desc";
    $res2 = $db->select($sql2);
    $views_total = array();
		foreach($res2 as $k=>$v){
        $arr2 = explode(',',$v['yuanquid']);
     			if(in_array($contentid,$arr2)){
      			$views_total[] = $v['contentid'];
    			}
    }
    // echo '<pre>';print_r($news_total);die;
    //分页取数据
    $views_data = array_slice($views_total,$offset,10);
		$total = count($views_total);
    $this->template->assign('page', $page);
		$this->template->assign('total', $total);
		$this->template->assign('pagesize', 10);
		$this->template->assign('views_data', $views_data);
		$this->template->assign('contentid', $contentid);
		$this->template->display('system/yq_contents_views.html');
	}

	public function yq_contents_touzi()
	{
		$contentid = $_GET['contentid'];
		$this->template->assign('contentid', $contentid);
		$this->template->display('system/yq_contents_touzi.html');
		
	}
	public function yq_contents_company()
	{
		$contentid = $_GET['contentid'];
		$this->template->assign('contentid', $contentid);
		$this->template->display('system/yq_contents_company.html');
	}
}
