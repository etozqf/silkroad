<?php

class controller_indexcn extends search_controller_abstract
{
	private $search, $channel, $pagesize = 10;

	function __construct($app)
	{
		parent::__construct($app);
		$this->search = loader::model('search');
		if(!$this->search->status) $this->showmessage($this->search->error);
		$this->channel = channel();
		$this->template->assign('channel',$this->channel);
	}

	function index()
	{
		$this->template->display('cn/search/index.html');
	}
	
	function search()
	{
        $wd = isset($_GET['wd']) ? htmlspecialchars(trim($_GET['wd'])) : NULL;

        if (! $wd)
        {
            $this->redirect(url('search/indexcn/index'));
        }

        if ($wd != 'null' && json_encode($wd) == 'null')
        {
            $wd = mb_convert_encoding($wd, 'UTF-8', 'GBK');
        }

		$allowtype = array('all','article','picture','special','interview','video');
		$type = $_GET['type'];
		if(!in_array($type,$allowtype)) $type = 'all';
		
		//排序
		$order = ($order = trim(value($_GET, 'order'))) && in_array($order, array('segments', 'rel', 'time', 'pv', 'comments'))
            ? $order
            : $this->setting['order'];
		$orderby = array(
            'segments' => 'TIME_SEGMENTS',
            'rel' => 'REL',
            'time' => 'published',
            'pv' => 'pv',
            'comments' => 'comments'
        );
		
		$page = empty($_GET['page'])?1:max(1,intval($_GET['page']));
		
		$pagesize = intval($_GET['pagesize']);
		if(empty($pagesize)) $pagesize = $this->pagesize;
		$now = array();
		$now[$type] = 'class="now"';
		
		$q = array (
			'wd' => $wd,
			'before' => strtotime($_GET['before']),
			'after'  => strtotime($_GET['after']),
			'title'  => intval($_GET['title'])
		);
		
		$catid = intval($_GET['catid']);
        $q['catid'] = $catid;
		if($catid && $this->channel[$catid]['childids'] != NULL) $q['catid'] = $this->channel[$catid]['childids'];
		
		$data = $this->search->page($q, $type, 'EXT', $orderby[$order], 'DESC', $page, $pagesize);
		$total = $this->search->getTotal();
		
		$requestUrl = request::get_url();
		$requestUrl = preg_replace('/(?:&order=[^&]*)+/','',$requestUrl);
		$url = array (
			'this' => $requestUrl,
			'segments'  => $requestUrl.'&order=segments',
			'rel'  => $requestUrl.'&order=rel',
			'time' => $requestUrl.'&order=time'
		);
		
		$pageTotal = ($total > $this->search->maxLimit)?1000:$total;
		$multipage = pages($pageTotal, $page, $pagesize, 3, $url[in_array($order, array('segments', 'rel', 'time')) ? $order : 'this'], false, '', 'current', '&lt;', '&gt;');
		
		$nowlist = array();
		$nowlist['start'] = ($page-1)*$pagesize+1;
		$nowlist['end'] = min($nowlist['start']+$pagesize-1,$pageTotal);
		
		$param = array(
			'wd' => $wd,
			'title' => $q['title'],
			'order' => $order
		);
		$rssurl = url('search/index_cn/rss',$param, true);
		//获取查询结果数据
		$type1 =  $type = 'all' ? 0: 1;
		$order1 = $order = 'segments' ?  1: 0;
		$username = table('member',$this->_userid,'username');
		$data1 = $this->getdata($type1,$order1,$catid,$page,$pagesize,$wd,$username);
		
		$this->template->assign('rssurl',$rssurl);
		$this->template->assign('wd', $wd);
		$this->template->assign('now', $now);
		$this->template->assign('url', $url);
		$this->template->assign('data', array('result'=>$data, 'total'=>$total));
		$this->template->assign('nowlist', $nowlist);
		$this->template->assign('multipage', $multipage);
		$this->template->assign('order', $order);

		$this->template->display('cn/search/list.html');
	}
	
	function tag()
	{
		$result = array();
		$charset = htmlspecialchars(trim($_GET['charset']));
		if(!$charset) $charset = 'utf-8';
		$wd = urldecode($_GET['wd']);
		if (! $wd)
        {
            echo $_GET['jsoncallback']."($result);";
			exit;
        }
		if($charset != 'utf-8') $wd = str_charset($charset,'utf-8',$wd);
		$wd = htmlspecialchars(trim($wd));
		$q = array (
			'wd' => $wd,
			'title'  => 1
		);
		$page = 1;
		$pagesize = 10;
		$data = $this->search->page($q, 'all', 'EXT', 'REL', 'DESC', $page, $pagesize);
		$result = $this->json->encode(array('wd' => $wd,'data' => $data,'url' =>APP_URL.url('search/index/search',array('wd' =>urlencode($wd)))));
		echo $_GET['jsoncallback']."($result);";
	}
	
	function rss()
	{
		$wd = htmlspecialchars(trim($_GET['wd']));
		if (! $wd)
        {
           $data = array();
        }
		else
		{
			$order = in_array($_GET['order'],array('segments','rel','time'))?$_GET['order']:'segments';
			$orderby = array('segments'=>'TIME_SEGMENTS','rel' =>'REL','time'=>'published');
			$allowtype = array('all','article','picture','special','interview');
			$type = $_GET['type'];
			if(!in_array($type,$allowtype)) $type = 'all';
			
			$q = array (
				'wd' => $wd,
				'title'  => intval($_GET['title'])
			);
			$page = 1;
			$pagesize = intval($_GET['pagesize']);
			if(empty($pagesize)) $pagesize = $this->pagesize;
			$data = $this->search->page($q, $type, 'EXT', $orderby[$order], 'DESC', $page, $pagesize);
		}
		
		foreach ($data as $item) {
			$line = array();
			$line['title'] = strip_tags($item['title']);
			$line['url'] = $item['url'];
			$line['category'] = table('category',$item['catid'],'catname');
			$line['description'] = $item['content'];
			$line['published'] = gmdate('D,d M Y H:i:s', $item['published']).' GMT+8';
			$list[] = $line;
		}
		$title = $wd.'_关键词RSS订阅';
		$param['wd'] = urlencode($q['wd']);
		if($q['title']) $param['title'] = $q['title'];
		$rssurl = str_replace('&','&amp;', url('search/index/rss',$param, true));
		$this->template->assign('title',$title);
		$this->template->assign('rssurl',$rssurl);
		$this->template->assign('sitename',setting('system','sitename'));
		$this->template->assign('list',$list);
		$charset = config::get('config', 'charset', 'utf-8');
		header('Content-Type: text/xml; charset=' . $charset, true);
		echo '<?xml version="1.0" encoding="'.$charset.'"?>'."\n";
		echo $this->template->fetch('rss/rss.xml');
	}
	
	private function getdata($type,$order,$catid,$start,$rows,$keyword,$username)
	{
		$url = "http://192.168.110.110/silkroad/rest/api/$type/$order/$catid/$start/$rows/$keyword/2/$username/search";
		console($url);
		$jsonData = json_encode(array());
		$headers = array(
			'app_key: xhsl',
      'app_secret: 57ce586290087fb9a1ea856f',
      'Content-Type: application/json',
      'Content-Length: ' . strlen($jsonData)
      );
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $result = curl_exec($ch);
    curl_close($ch);
  	$data = json_decode($result,true);
    //console($data);
    return $data;
	}
}
