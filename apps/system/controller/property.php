<?php
/*
  *处理前台属性动态获取与文章关联数据
  * author:kanzhi
  * date:20160305
*/
class controller_property extends system_controller_abstract
{

	public $pagesize=10;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->db=factory::db();
	}
	/*
      +根据属性ID(proid)得到相关联的文章
      + 这块的思想流程是这样的，在资讯栏目下点击相关联的属性后，执行getJSON来到这个方法中，proid代表属性ID
      + 如果proid值拥有子属性，则将期拼装成字符串，用此字符串关联cmstop_content_property(文章与属性关联表)找到所有文章记录.
      + 同时该方法只要求得到中文栏目下的关联文章，所以要记得在where 条件中去除英文栏目
      + 添加如下子句 where catid not in ($en_catid)
	*/
	public function get_proid_article()
	{
		
		$proid=!empty($_GET['proid'])?intval($_GET['proid']):0;
		$catid=table('category',intval($_GET['catid']),'childids');
		$page=!empty($_GET['page'])?intval($_GET['page']):0;
		$memberid=$this->_userid;	//当前登陆会员ID

		$db=factory::db();

		//得到该属性下所有的子属性
		$child_proid=trim($proid.",".table('property',$proid,'childids'),",");	
		
		$proid=is_null($child_proid)?$proid:$child_proid;	//如果没有子属性，则只用该属性ID

		$en_catid=table('category',24,'childids'); //得到英文站的栏目ID,下面的查询中将排除该栏目中的数据
		$en_catid="145".",".$en_catid;	//145是免费栏目，需要将其排除。

		$table = " cmstop_content as c inner join cmstop_content_property as pc on c.contentid=pc.contentid ";

		if($proid==0)
		{
			$where = " where catid not in($en_catid) and catid in($catid) and modelid=1 and status=6 order by published desc";
		}else{
			$where = " where catid not in($en_catid) and modelid=1 and pc.proid in($proid) and status=6 order by published desc";
		}

		
		
		
		$field = " distinct(c.contentid) as contentid,title,url,catid,thumb,published";
		
		$limit = " limit ".($page-1)*$this->pagesize.",".$this->pagesize;

		$list=$this->db->select("select $field from $table $where $limit");

		

		foreach($list as $key=>&$value)
		{
			$dd=$this->db->get("select description from #table_article as a where a.contentid={$value['contentid']}");
			$value['description']=empty($dd['description'])?"":$dd['description'];
			$value['published']=date("Y-m-d H:i:s",$value['published']);
			if(!empty($value['thumb']))
			{
				$value['thumb']=thumb($value['thumb'],310,220,1,null,0);
			}
		}
   		
	    if(!empty($list))
	    {
	    	$list=$this->get_collect($list,$memberid);
	    	$arr=array('status'=>'success','content'=>$list);
	    }
	    else
	    {
	    	$arr=array('status'=>'error','content'=>'暂时没有相关内容');
	    }

	   /*
	     +拼装数组加入 collect 与mark标记，区分显示收藏与取消收藏
	   */
	    console($list);
	     $list=$this->json->encode($arr);
		 echo $_GET['jsoncallback']."($list)";
	}


	public function get_more_article()
	{
		$array=array(1=>'记者观点',2=>'专家观点',3=>'机构观点');
		$page=!empty($_GET['page'])?intval($_GET['page']):0;
		$memberid=$this->_userid;	//当前登陆会员ID
		$db=factory::db();

		$table = " cmstop_content as c inner join cmstop_space as s on c.spaceid=s.spaceid ";
		$where = " where c.modelid=1 and c.status=6 order by c.published desc";
		
		$field = " distinct(c.contentid) as contentid,c.spaceid as spaceid,c.title as title,c.url as url,c.thumb as thumb,c.published as published,s.typeid as typeid";
		
		$limit = " limit ".($page-1)*$this->pagesize.",".$this->pagesize;
		$list=$this->db->select("select $field from $table $where $limit");

		foreach($list as $key=>&$value)
		{
			switch($value['typeid'])
				{
					case 1:		
						$value['typeid']=$array[1];
					break;
					case 2:
						$value['typeid']=$array[2];
						break;
					case 3:
						$value['typeid']=$array[3];
						break;
						}
			$dd=$this->db->get("select description from #table_article as a where a.contentid={$value['contentid']}");
			$value['description']=empty($dd['description'])?"":$dd['description'];
			$value['published']=date("Y-m-d H:i:s",$value['published']);
		}
		
	    if(!empty($list))
	    {
	    	$list=$this->get_collect($list,$memberid);
	    	$arr=array('status'=>'success','content'=>$list);
	    }
	    else
	    {
	    	$arr=array('status'=>'error','content'=>'暂时没有相关内容');
	    }

	   /*
	     +拼装数组加入 collect 与mark标记，区分显示收藏与取消收藏
	   */
	    
	     $list=$this->json->encode($arr);
		 echo $_GET['jsoncallback']."($list)";
	}


	/**
     * 关联查询收藏表中的关联记录
     * list:内容表与文章表关联后的查询结果，数组类型
     * memberid:当前登陆会员ID
	**/
	private function get_collect($list,$memberid)
	{
		foreach($list as $key=>$value)
	    {
	    	$collect=$this->db->get("select collectid from #table_collect where contentid={$value['contentid']} and memberid={$memberid}");
	    	$list[$key]['collectid']=$collect['collectid']; //收藏表主键ID
	    	$list[$key]['collect']=empty($collect)?'收藏':'取消收藏';
	    	$list[$key]['mark']=empty($collect)?1:2;	//mark为1时代表进行收藏操作，值为2时代表进行取消收藏操作  
	    }
	    return $list;
	}




	/**
	  *查看栏目下是否存在子栏目
	  *Return:String(以逗号分形式返回)
	**/
	private static function get_sub_category($catid)
	{
		static $instance;
		$keys=$catid;
		if(!isset($instance[$keys]))
		{
			$db=factory::db();
			$result=$db->select("select catid from #table_category where catid={$catid} or parentid={$catid}");
			foreach($result as $key=>$val)
			{
				$cat_list[]=$val['catid'];
			}
			$catid=implode(",",$cat_list);
			$instance[$keys]=$catid;	
		}

		return $instance[$keys];
	}




	/*
   	  +点击收藏时，写入表cmstop_collect(收藏表)
      +type:0(资讯) 1(记者) 2(专家) 3(机构) 4(项目)
	  +根据mark值进行判断，值为1时，代表进行收藏添加操作，值为2时代表进行取消收藏的删除记录操作。
	*/   
	
	public function collect()
	{
		$mark=!empty($_GET['mark'])?intval($_GET['mark']):1;
		$memberid=$this->_userid;
		$contentid=intval($_GET['contentid']);
		$db=factory::db();
		//得到文章标题
		$result=$db->get("select title,url,catid from #table_content where contentid={$contentid}");
		$title=$result['title'];
		$url=$result['url'];
		$catid=$result['catid'];
		$addtime=time();
		$typeid=0;	//代表类型 
		$spaceid=0; //此字段代表专栏作家的空间，资讯频道不需要这个字段，默认值为0
		$status=0; //保留字段，默认为0
		$collectid=intval($_GET['collectid']); //收藏表(cmstop_collect)主键ID
		//进行添加收藏表操作
		if($mark==1)
		{	
			
			/**写入cmstop_collect(收藏表)
	          **1、判断收藏记录是否存在，如果存在则取消写入收藏。判断条件 内容ID和当前登陆会员的ID
	          **2、不存在时，则可以写入数据表中
			**/

			$statement=$db->get("select collectid from #table_collect where contentid={$contentid} and memberid={$memberid}");
			//不存在记录时，执行写入
			if(empty($statement))
			{
		       $result=$db->
		     insert("insert into #table_collect (memberid,contentid,spaceid,title,url,catid,addtime,typeid,status) values({$memberid},{$contentid},{$spaceid},'{$title}','{$url}',
		   	'{$catid}',{$addtime},{$typeid},{$status})");

		     //写入成功时，返回取消收藏字样， 写入失败时，返回error标记
				$arr=!empty($result)?array('status'=>'success_q','collect'=>'取消收藏','mark'=>2,'collectid'=>$result):array('status'=>'error');
				echo $_GET['jsoncallback'].'('.$this->json->encode($arr).')';exit;	
			}
			else
			{
				//代表该收藏记录已存在	
				$arr=array('status'=>'cunzai','collect'=>'取消收藏','mark'=>2,'collectid'=>$statement['collectid']);
				echo $_GET['jsoncallback'].'('.$this->json->encode($arr).')';exit;	
			}
	
		}
		else
		{

			/*进行取消收藏表操作*/
			$result=$db->delete("delete from #table_collect where collectid={$collectid}");
			$arr=!empty($result)?array('status'=>'success','collect'=>'收藏','mark'=>1):array('status'=>'error');
			echo $_GET['jsoncallback'].'('.$this->json->encode($arr).')';exit;	
		}

	}

	// 视频收藏功能
	public function videoselect(){
		$db=factory::db();
		$memberid = $_GET['userid'];
		if(intval($_GET['flush'])==1){
			$contentids = '';
			$contentid = rtrim($_GET['contentid'],',');
				$statement=$db->select("select collectid,contentid from #table_collect where contentid in ({$contentid}) and memberid={$memberid}");
				if($statement){
					foreach ($statement as $key => $value) {
						$contentids .=  $value['contentid'].',';
					}
				}
			$contentids = trim($contentids,',');
			$contentids = explode(',', $contentids);
			if($contentids){

					echo $_GET['jsoncallback'].'('.$this->json->encode(array('status'=>$contentids)).')';exit;
				}else{
					echo $_GET['jsoncallback'].'('.$this->json->encode(array('status'=>false)).')';exit;

				}
		}else{

			$contentid = intval($_GET['contentid']);
			$statement=$db->get("select collectid from #table_collect where contentid={$contentid} and memberid={$memberid}");
			$collectid = $statement['collectid'];

			if($statement){
			
				$result=$db->delete("delete from #table_collect where collectid={$collectid}");
				$arr=!empty($result)?array('status'=>'bookmar','message'=>'取消收藏成功'):array('status'=>'error','message'=>'取消收藏失败');
				echo $_GET['jsoncallback'].'('.$this->json->encode($arr).')';exit;
			
			}else{
				$title = htmlspecialchars(table('content',$contentid,'title'));
				$url = table('content',$contentid,'url');
				$addtime = time();	
				$typeid = 5;
				$status = 2;
				$catid = table('content',$contentid,'catid');

				$result=$db->insert('insert into #table_collect (
					memberid,contentid,title,url,catid,addtime,typeid,status) values(
					'.$memberid.','.$contentid.',"'.$title.'","'.$url.'",
				   	"'.$catid.'","'.$addtime.'",'.$typeid.','.$status.')');
				$arr=!empty($result)?array('status'=>'unfav','message'=>'收藏成功'):array('status'=>'error','message'=>'收藏失败，重新收藏');
				echo $_GET['jsoncallback'].'('.$this->json->encode($arr).')';exit;	
			}

		}
			

	}

    

	/*
	  *	公司列表页数据
	  *author:lucong, kanzhi后期更新维护此方法.
	  * 首先根据mark值进行判断，如果mark=catid ，说明只根据栏目ID进行查询。如果mark=proid ,说明查询和该栏目下的指定属性相关联的文章。
	*/ 
	public function Companies(){
		$mark=$_GET['mark'];
		$proid = intval($_GET['proid']);
		$catid = intval($_GET['catid']);

		$db=factory::db();
		if($mark=="catid")
		{
			$result=$db->select("select contentid,title,url,weight from #table_content where catid=$catid and status=6 order by weight asc limit 100");
		}
		else
		{
			// 点击关联的属性的时候
			$where =  " and status=6 p.proid=".$proid." and c.catid=".$catid;
			$result = $db->select("select c.contentid,c.url,c.title,weight from #table_content_property as p
                            inner join #table_content as c on 
                            p.contentid=c.contentid where {$where} order by weight asc limit 100");
		}

		if($result)
		{
					foreach ($result as $key => &$value) 
					{
						$value['subtitle'] = table('article',$value['contentid'],'subtitle');
						$meta = unserialize(table('content_meta',$value['contentid'],'data'));
						$value['capital']= $meta[1]['Capital'];
						$value['mark_catid']=$catid;
					}

		}
		else
		{
			$result=array('status'=>'error');
		}


		$json = $this->json->encode($result);
        $callback = $_REQUEST['jsoncallback'];
        exit("$callback($json)");
	}

	public function index()
	{	
		//先创建内容html文件
		// $this->create();
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$db=&factory::db();
		//获取子栏目信息
		$sub_type = subcategory(233);
		$catids = array();
		foreach($sub_type as $k=>$v){
			$catids[] = $v['catid']; 
		}
		$catidss = implode(',',$catids);
		$res = $db->select("select count(contentid) as total from cmstop_content where catid in ($catidss)");
		$total = $res[0]['total'];
		$offset = ($page-1)*10;

		$list=$db->select("select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.status=6 and  c.catid in ($catidss) order by c.published desc limit $offset,10");
		foreach($list as $keys=>&$values)
		{
			
			if($values['sourceid']){
				$values['source'] = table('source',$values['sourceid'],'name');
			}
			// $result=$db->get("select description from #table_article where contentid={$values['contentid']}");
		
			//$description=empty($result)?null:$result['description'];
			$description = str_natcut(description($values['contentid']),90,'...');
			$list[$keys]['description']=$description;
		}
		
		
		// $offset = ($page-1)*9;
		// $data = array();
		// foreach($sub_type as $k=>$v){
		// 	$total=$db->select("select count(spaceid) total from cmstop_space where sub_type=".$v['sid']." and status=3");
		// 	$data["$k"]['total']=$total[0]['total'];
		// 	$result=$db->select("select * from cmstop_space where sub_type=".$v['sid']." and status=3 order by sort limit $offset,9");
		// 	$data["$k"]["data"] = $result;
		// }
		
		$this->template->assign('page', $page);
		$this->template->assign('pagesize', 10);
		$this->template->assign('data',$list);
		$this->template->assign('total',$total);
		$this->template->assign('sub_type',$sub_type);
		$this->template->display('information/information.html');

	}

	public function index_show()
	{
		$db=&factory::db();
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$sub = $_GET['sub_type'];
		// var_dump($sub);die;
		// explode(',',$sub)
		$sub_type = subcategory(233);
		
		$catids = array();
		foreach($sub_type as $k=>$v){
			$catids[] = $v['catid']; 
		}
		$catidss = implode(',',$catids);
		$offset = ($page-1)*10;
		$data = array();
		if($sub=='all'){
			$res = $db->select("select count(contentid) as total from cmstop_content where catid in ($catidss)");
		}else{
			$res = $db->select("select count(contentid) as total from cmstop_content where catid in ($sub)");

		}
		$total = $res[0]['total'];
		// $total=$db->select("select count(spaceid) total from cmstop_space where sub_type=$sub and status=3");
		// $data['total']=$total[0]['total'];
		// $result=$db->select("select * from cmstop_space where sub_type=$sub and status=3 order by sort limit $offset,9");
		// 	$data["data"] = $result;
		if($sub=='all'){
			$list=$db->select("select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.status=6 and  c.catid in ($catidss) order by c.published desc limit $offset,10");
			// var_dump($list);die;

		}else{
			$list=$db->select("select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.status=6 and  c.catid in ($sub) order by c.published desc limit $offset,10");

		}
		foreach($list as $keys=>&$values)
		{
			
			if($values['sourceid']){
				$values['source'] = table('source',$values['sourceid'],'name');
			}
			// $result=$db->get("select description from #table_article where contentid={$values['contentid']}");
		
			//$description=empty($result)?null:$result['description'];
			$description = str_natcut(description($values['contentid']),90,'...');
			$list[$keys]['description']=$description;
		}
		// foreach($sub_type as $k=>$v){
		// 	if($sub==$v['catid']){
		// 		$Subtype = $v['abbr'];
		// 	}
		// }
		// if($sub==1){
		// 	$Subtype = 'China';
		// }else if($sub==2){
		// 	$Subtype = 'Europe';
		// }else if($sub==3){
		// 	$Subtype = 'Asia';
		// }else if($sub==4){
		// 	$Subtype = 'America';
		// }
		$this->template->assign('page', $page);
		$this->template->assign('sub', $sub);
		$this->template->assign('Subtype', $Subtype);
		$this->template->assign('pagesize', 10);
		$this->template->assign('data',$list);
		$this->template->assign('total',$total);

		$this->template->assign('sub_type',$sub_type);
		$this->template->display('information/information.html');

	}

	public function create()
	{
		$cate = subcategory(249);
		foreach($cate as $k=>$v){
			$dest_file = 'F:\project\silkroad\code\templates\silkroad\information\sub_type\/'.$v['abbr'].'.html';
			$source_file = 'F:\project\silkroad\code\templates\silkroad\information\sub_type\index.html';
			if(!file_exists($dest_file)){
				copy($source_file,$dest_file);
			}
		}
	}




}
