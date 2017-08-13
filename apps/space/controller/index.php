<?php

class controller_index extends space_controller_abstract
{
	private $space, $article,$content,$pagesize = 5;
	
	function __construct($app)
	{
		parent::__construct($app);
		$this->space = loader::model('space'); 
		$this->article = loader::model('admin/article','article');
		$this->content = loader::model('admin/content','system');
		$db=factory::db();
	}

	function param()
	{
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$typeid=intval($_GET['typeid'])?intval($_GET['typeid']):0;
		$db=&factory::db();
		$total=$db->select("select count(spaceid) total from cmstop_space where typeid=$typeid");
		$offset = ($page-1)*$this->pagesize;
		$data=$db->select("select * from cmstop_space where typeid=$typeid order by sort desc limit $offset,$this->pagesize");
		foreach($data as $k=>$v)
		{
			$spaceid=$v['spaceid'];
			$article=$db->select("select * from cmstop_content where spaceid=$spaceid and status=6 order by weight desc,published desc limit 0,3");
			if(!empty($article))
			{
				$data[$k]['article']=$article;
			}
		}
		//查询collect表
		$prefix=config('cookie')['prefix'];  //获取cookie前缀.
		$p=$prefix.'userid';
		$memberid=$_COOKIE["$p"];
		$collect=$db->select("select * from cmstop_collect where memberid=$memberid and typeid=$typeid");
		foreach($collect as $k=>$v){
			$collected[] = $v['spaceid'];
		}
		$this->template->assign('collected', $collected);
		$typesname=$this->space->types[($typeid-1)];
		$this->template->assign('page', $page);
		$this->template->assign('pagesize', $this->pagesize);
		$this->template->assign($total[0]);
		$this->template->assign('data',$data);
		$this->template->assign('typesname',$typesname);
		$this->template->assign('typeid',$typeid);
		$this->template->display('space/index.html');
	}


	/*点击更多按钮,追加新内容*/
	function param_more()
	{
		$page = empty($_GET['page']) ? 2 : intval($_GET['page']);	/*页数初始值为2*/
		$typeid=intval($_GET['typeid'])?intval($_GET['typeid']):0;
		$db=&factory::db();
		$page++;
		$this->template->assign('page_more', $page);
		if($this->is_get())
		{


			
				$total=$db->select("select count(spaceid) total from cmstop_space where typeid=$typeid");
				$offset = ($page-1)*$this->pagesize;
				$data=$db->select("select * from cmstop_space where typeid=$typeid order by sort desc limit $offset,$this->pagesize");

				foreach($data as $k=>$v)
				{
					$spaceid=$v['spaceid'];
					$article=$db->select("select * from cmstop_content where spaceid=$spaceid and status=6 order by weight desc,published desc limit 0,3");
					if(!empty($article))
					{
						$data[$k]['article']=$article;
					}
				}
				//查询collect表
				$prefix=config('cookie')['prefix'];  //获取cookie前缀.
				$p=$prefix.'userid';
				$memberid=$_COOKIE["$p"];
				$collect=$db->select("select * from cmstop_collect where memberid=$memberid and typeid=$typeid");
				foreach($collect as $k=>$v){
					$collected[] = $v['spaceid'];
				}


				if(!empty($data))
				{
					$arr=array('state'=>true,'info'=>$data);
				}
				else
				{
					$arr=array('state'=>false,'error'=>'暂时没有数据');
				}

			echo $this->json->encode($arr);
		}

	}

	public function viewlist()
	{
		$db=factory::db();
		$data=$db->select("select distinct(c.contentid),c.title,c.url,c.thumb,c.published,c.spaceid,s.typeid from cmstop_content c inner join cmstop_space s on c.spaceid=s.spaceid where c.status=6 and catid not in(145) and modelid=1 order by published desc limit 0,10");
		$this->template->assign('data',$data);
		$this->template->display('space/view.html');
	}

	function homepage()
	{
		$db=&factory::db();
		$space = $this->space->get(array('alias' => trim($_GET['space'])));
		if(!$space)
		{
			$this->showmessage('指定的专栏不存在');
		}
		else if (intval($space['status']) < 3)
		{
			$this->showmessage('专栏状态未开通');
		}
		
		$this->space->set_inc('pv', $space['spaceid']);
		if(empty($space['name'])) $space['name'] = $space['name'].'的个人专栏';
		
		$where = array (
			'spaceid' => $space['spaceid'],
			'status' => 6
		);

		$articles = $this->article->ls($where, '*', '`published` DESC', 1, $this->pagesize, true);
		foreach($articles as $k => $v)
		{
			if(empty($v['description']))
				$articles[$k]['description'] = str_cut(strip_tags($v['content']),160);
			$articles[$k]['comment_url'] = APP_URL.url('comment/comment/index',"topicid=".$v['topicid']);
			$articles[$k]['published'] = time_format(strtotime($v['published']),'Y年n月j日 G:i');
		}
		
		$articles_count = $this->article->total;
		$comment = $this->space->get_comment($space['spaceid'], 1, 10);

		$this->template->assign('space', $space);
		$this->template->assign('comment', $comment);
		$this->template->assign('articles', $articles);
		$this->template->assign('articles_count',intval($articles_count));
		if($_GET['typeid']==3){
			$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
			$spaceid = $_GET['spaceid'];
			$offset = ($page-1)*8;
			$arr1 = subcategory(166);
      $arr2 = array();
      foreach($arr1 as $k=>$v){
            $arr2[] = $k;
      }
      $arr = implode(',',$arr2);
			$sql1 = "select * from cmstop_content c,cmstop_space s where s.spaceid is not null and c.modelid=1 and s.spaceid=$spaceid and c.status=6 and c.spaceid=s.spaceid and catid in ($arr) order by published desc limit $offset,8";
			$sql2 = "select count(contentid) as total from cmstop_content where modelid=1 and spaceid=$spaceid and status=6 and catid in ($arr)";
			$result = $db->select($sql1);
			$total = $db->get($sql2);
			// echo '<pre>';
			// print_r($total);die;
			$this->template->assign('result', $result);
			$this->template->assign('page', $page);
			$this->template->assign('total', $total['total']);
			$this->template->assign('pagesize', 8);
			$this->template->assign('spaceid', $spaceid);
			$this->template->display('space/zk-content.html');
		}else{
			$this->template->display('space/space.html');
		}
	}
	
	function listing()
	{
		if ($this->system['pagecached'])
		{
			$keyid = md5('pagecached_space_index_listing_');
			cmstop::cache_start($this->system['pagecachettl'], $keyid);
		}
		$this->template->display('space/list.html');
		if ($this->system['pagecached']) cmstop::cache_end();
	}
	
	function page()
	{
		$where = array (
			'spaceid' => intval($_GET['spaceid']),
			'status' => 6
		);
		$fields = '*';
		$order = (empty($_GET['order']) || !in_array($_GET['order'],array('latest','hits','hot')))?'latest':$_GET['order'];
		switch($order)
		{
			case 'hits':$order = '`pv` DESC';break;
			case 'hot':$order = '`comments` DESC';break;
			default:
				$order = '`published` DESC';
		}
		$page = isset($_GET['page'])?max(intval($_GET['page']),1):1;
		$pagesize = $_GET['pagesize'] ? $_GET['pagesize'] : $this->pagesize;
		$data = $this->article->ls($where, $fields, $order, $page, $pagesize, true);
		
		foreach($data as $k =>$v)
		{
			if(empty($v['description'])) $data[$k]['description'] = str_cut(strip_tags($v['content']),255);
			$data[$k]['comment_url'] = APP_URL.url('comment/comment/index',"topicid=".$v['topicid']);
			$data[$k]['published'] = time_format(strtotime($v['published']),'Y年n月j日 G:i');
		}
		$total = $this->article->total;
		echo $this->json->encode(array('data' => $data, 'total' => intval($total)));
	}
	
	function rss()
	{
		$space = $this->space->get(array('alias' => trim($_GET['space'])));
		if(!$space) $this->showmessage('指定的专栏不存在');
		
		$rssurl = SPACE_URL.$space['alias'];

		$where = array (
			'spaceid' => $space['spaceid'],
			'status' => 6
		);
		$fields = '*';
		$order = '`published` DESC';
		$list = $this->article->ls($where, $fields, $order, 1, 20, true);
		
		foreach($list as $k => $v)
		{
			$list[$k]['published'] = gmdate('D,d M Y H:i:s', strtotime($v['published'])).' GMT+8';
			$list[$k]['comments'] = str_replace('&','&amp;',url('comment/comment/index','topicid='.$v['topicid'], true));
			if(empty($v['description']))
				$list[$k]['description'] = str_cut(strip_tags($v['content']),160);
		}
		$pubDate = gmdate('D,d M Y H:i:s', TIME.' GMT+8');
		
		$this->template->assign('title',$space['name']);
		$this->template->assign('rssurl',$rssurl);
		$this->template->assign('pubDate',$pubDate);
		$this->template->assign('author',$space['name']);
		$this->template->assign('sitename',setting('system','sitename'));
		$this->template->assign('list',$list);
		$feet_content_type = 'text/html';
		$charset = config::get('config', 'charset', 'utf-8');
		header('Content-Type: ' . $feet_content_type . '; charset=' . $charset, true);
		echo '<?xml version="1.0" encoding="'.$charset.'"?>'."\n";
		$this->template->display('space/rss.xml');
	}
	function zkbq()
	{
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$db=&factory::db();
		$sub_type = $db->select("select * from cmstop_space_sub_type where status=1 order by sort");
		$offset = ($page-1)*9;
		$data = array();
		foreach($sub_type as $k=>$v){
			$total=$db->select("select count(spaceid) total from cmstop_space where sub_type=".$v['sid']." and status=3");
			$data["$k"]['total']=$total[0]['total'];
			$result=$db->select("select * from cmstop_space where sub_type=".$v['sid']." and status=3 order by sort limit $offset,9");
			$data["$k"]["data"] = $result;
		}
		
		$this->template->assign('page', $page);
		$this->template->assign('pagesize', 9);
		$this->template->assign('data',$data);
		$this->template->assign('sub_type',$sub_type);
		$this->template->display('space/zk-bq.html');

	}

	function zk()
	{
		$db=&factory::db();
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$sub = $_GET['sub_type'];
		$sub_type = $db->select("select * from cmstop_space_sub_type where status=1 order by sort");
		$offset = ($page-1)*9;
		$data = array();
		$total=$db->select("select count(spaceid) total from cmstop_space where sub_type=$sub and status=3");
		$data['total']=$total[0]['total'];
		$result=$db->select("select * from cmstop_space where sub_type=$sub and status=3 order by sort limit $offset,9");
			$data["data"] = $result;
		if($sub==1){
			$Subtype = 'China';
		}else if($sub==2){
			$Subtype = 'Europe';
		}else if($sub==3){
			$Subtype = 'Asia';
		}else if($sub==4){
			$Subtype = 'America';
		}
		$this->template->assign('page', $page);
		$this->template->assign('sub', $sub);
		$this->template->assign('Subtype', $Subtype);
		$this->template->assign('pagesize', 9);
		$this->template->assign('data',$data);
		$this->template->assign('sub_type',$sub_type);
		$this->template->display('space/zk-bq.html');

	}

	
}