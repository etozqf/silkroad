<?php

class controller_index extends search_controller_abstract
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
		$this->template->display('search/index.html');
	}
	
	function indexsearch()
	{
		$this->template->display('search/indexsearch.html');
	}
	function search()
	{
        $wd = isset($_GET['wd']) ? htmlspecialchars(trim($_GET['wd'])) : NULL;

        if (! $wd)
        {
            $this->redirect(url('search/index/index'));
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
		if($catid!==51){
			$data = $this->search->page($q, $type, 'EXT', $orderby[$order], 'DESC', $page, $pagesize);
			$total = $this->search->getTotal();
		}else{
			$db = factory::db();
			switch($order){
				case $order=='pv':
					$order = "ORDER BY c.pv desc";
					break;
				case $order=='comments':
					$order = "ORDER BY c.comments desc";
					break;
				case $order=='time':
					$order = "ORDER BY c.published desc";
					break;
					default :
					$order = "ORDER BY c.published desc";
				}
			$limit = 'limit '.($page-1)*$pagesize.','.$pagesize;
			$children = $this->channel[$catid]['childids'];
			$wds = explode(' ',$wd);
			$likes = '';
			for($i=0;$i<count($wds);$i++){
				if($i==count($wds)-1){
					$likes .= "c.title LIKE '%$wds[$i]%'";
				}else{
					$likes .= "c.title LIKE '%$wds[$i]%' AND ";
				}
			}
			$sql = "SELECT c.contentid,title,thumb,published,url,i.description FROM cmstop_content AS c LEFT JOIN cmstop_item AS i ON  c.contentid=i.contentid WHERE c.modelid=11 AND c.status=6 AND c.catid=281 AND ($likes) $order $limit";
			$sqlcount = "SELECT count(c.contentid) AS total FROM cmstop_content AS c LEFT JOIN cmstop_item AS i ON  c.contentid=i.contentid WHERE c.modelid=11 AND c.status=6 AND c.catid=281 AND ($likes)";
			$data = $db->select($sql);
			$totalarr = $db->select($sqlcount);
			$total = $totalarr[0]['total'];
			$newd = array();
			foreach($wds as $k=>$v){
				$newd["$v"] = "<span class='keyword'>$v</span>";
			}
			foreach($data as $k=>$v){
				$data[$k]['title'] = strtr($v['title'],$newd);
			}
		}
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
		$rssurl = url('search/index/rss',$param, true);
		$this->template->assign('rssurl',$rssurl);
		$this->template->assign('wd', $wd);
		$this->template->assign('now', $now);
		$this->template->assign('catidd', $catid);
		$this->template->assign('url', $url);
		$this->template->assign('data', array('result'=>$data, 'total'=>$total));
		$this->template->assign('nowlist', $nowlist);
		$this->template->assign('multipage', $multipage);
		$this->template->assign('order', $order);

		$this->template->display('search/list.html');
	}
	
	function testsearch()
	{
		$catid = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0;
		if($catid && $catid==9999){
				$catechilds = '9998,9999';
		}
		elseif($catid && $catid!==9999){
				$catechilds = $catid.','.table("category",$catid,'childids');
		}
		else{
				$catechilds=0;
		}
		//处理keyword
		$keyword = isset($_GET['wd']) ? $_GET['wd'] : false;
		if(!$keyword) return false;
		if(preg_match('/\+/',$keyword)){
			$keyword = str_replace(' ','',str_replace('+','++',$keyword));
		}
		if(preg_match('/\s+/',$keyword)){
			$keyword = str_replace(' ','+',$keyword);
		}
		console($keyword);
		$keyword =  urlencode($keyword);console($keyword);
		$type = isset($_GET['mode']) ? $_GET['mode'] : 0;
		$order = (isset($_GET['order']) && $_GET['order']=='time') ? 1 : 0;
		$page = isset($_GET['page']) ? intval($_GET['page']-1) : 0;
		$pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize;
		$start = $page;
		$rows = $pagesize;
		//获取查询结果数据
		$username = table('member',$this->_userid,'username');
		$data = $this->getdata($type,$order,$catechilds,$start,$rows,$keyword,$username);
		$pageTotal = $data['data']['totalElements'];
    //echo '<pre>';print_r($data);die;

		$requestUrl = request::get_url();
		$requestUrl = preg_replace('/(?:&order=[^&]*)+/','',$requestUrl);
		$cateurl = array (
			'this' => $requestUrl,
			'segments'  => $requestUrl.'&order=segments',
			'rel'  => $requestUrl.'&order=rel',
			'time' => $requestUrl.'&order=time'
		);
		$multipage = pages($pageTotal, $page+1, $pagesize, 3, $cateurl['this'], false, '', 'current', '&lt;', '&gt;');
    //echo '<pre>';print_r($data);die;
    $this->template->assign('data',$data['data']['content']);
    $this->template->assign('url',$cateurl);
    $this->template->assign('total',$data['data']['totalElements']);
    $this->template->assign('totalpages',$data['data']['totalPages']);
    $this->template->assign('multipage',$multipage);
    $this->template->assign('mode',$type);
    $this->template->assign('catid',$catid);
    $this->template->display('search/searchtest.html');
	}

	private function getdata_bak($type,$order,$catid,$start,$rows,$keyword)
	{
		$url = $catid ? "http://192.168.110.110/silkroad/rest/api/$type/$order/$catid/$start/$rows/$keyword/search" : "http://192.168.110.110/silkroad/rest/api/$type/$order/$start/$rows/$keyword/search";
		console($url);
		$headers = array(
			'app_key: xhsl',
      'app_secret: 57ce586290087fb9a1ea856f',
      'Content-Type: application/json'
      );
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $result = curl_exec($ch);
    curl_close($ch);
    //console($result);
    $data = json_decode($result,true);
    console($data);
    return $data;
	}

	function CreateWord()
	{
		$contentids = $_GET['contentids'];
		$word = loader::lib("CreateWord","search");
		$html = $this->getContent($contentids);
		//echo $html;die;
    $word->start(); 
 		//$html = "aaa".$i; 
 		//$wordname = 'F:\project\myproject\word\\'.$this->_username.time().".doc";
 		$dir = 'search/'.$this->_username;
		$path = UPLOAD_PATH.$dir.'/'.date('Ymd').'/';
	  if(!file_exists($path)) mkdir($path, 0777, true);
	  $fill = md5(time()).'.doc';
 		echo $html; 
 		$a = $word->save($path.$fill); 
	}

	function CreateTXT()
	{
		$contentids = $_GET['contentids'];
		$html = $this->getContent($contentids);
		$result = $this->html2text($html);
		// var_dump($result);die;
		//file_put_contents('F:\project\myproject\txt\\'.$this->_username.time().".txt",$result);
		$dir = 'search/'.$this->_username;
		$path = UPLOAD_PATH.$dir.'/'.date('Ymd').'/';
	  if(!file_exists($path)) mkdir($path, 0777, true);
	  $fill = md5(time()).'.txt';
		file_put_contents($path.$fill,$result);
	}

	function CreatePDF()
	{
		$contentids = $_GET['contentids'];var_dump($this->_userid);var_dump($this->_username);
		$pdf = loader::lib("createPdfSearch","country"); //实例化PDF类
		$html = $this->getContent($contentids);
		$dir = 'search/'.$this->_username;
    $pdf->create($html,$dir);
    	
	}

	function getContent($contentids)
	{
		$contentid_array = explode(',',$contentids);
		$htmls = '';
		foreach($contentid_array as $val){
			$content = table('content',$val);
			$article = table('article',$val);
			$source_name = $content['sourceid'] ? table('source',$content['sourceid'],'name') : '';
			$date = date('Y-m-d H:i',$content['published']);
			$htmls .= "<h1 class='ya'>".$content['title']."</h1><div class='info'><span>时间：".$date."</span>&nbsp;&nbsp;<span>来源：".$source_name."</span>&nbsp;&nbsp;<span>作者: ".$article['author']."</span></div><div class='cont'>".$article['content']."</div>";
		}
		
		return $htmls;
	}
	
	function html2text($str)
	{ 
	  $str = preg_replace("/<style .*?<\/style>/is", "", $str);  $str = preg_replace("/<script .*?<\/script>/is", "", $str); 
	  $str = preg_replace("/<br \s*\/?\/>/i", "\n", $str); 
	  $str = preg_replace("/<\/?p>/i", "\n", $str); 
	  $str = preg_replace("/<\/?td>/i", "\n", $str); 
	  $str = preg_replace("/<\/?div>/i", "\n", $str); 
	  $str = preg_replace("/<\/?blockquote>/i", "\n", $str); 
	  $str = preg_replace("/<\/?li>/i", "\n", $str); 
	  $str = preg_replace("/\&nbsp\;/i", " ", $str); 
	  $str = preg_replace("/\&nbsp/i", " ", $str); 
	  $str = preg_replace("/\&amp\;/i", "&", $str); 
	  $str = preg_replace("/\&amp/i", "&", $str);   
	  $str = preg_replace("/\&lt\;/i", "<", $str); 
	  $str = preg_replace("/\&lt/i", "<", $str); 
	  $str = preg_replace("/\&ldquo\;/i", '"', $str); 
	  $str = preg_replace("/\&ldquo/i", '"', $str); 
	  $str = preg_replace("/\&lsquo\;/i", "'", $str); 
	  $str = preg_replace("/\&lsquo/i", "'", $str); 
	  $str = preg_replace("/\&rsquo\;/i", "'", $str); 
	  $str = preg_replace("/\&rsquo/i", "'", $str); 
	  $str = preg_replace("/\&gt\;/i", ">", $str);  
	  $str = preg_replace("/\&gt/i", ">", $str);  
	  $str = preg_replace("/\&rdquo\;/i", '"', $str);  
	  $str = preg_replace("/\&rdquo/i", '"', $str);
	  $str = strip_tags($str); 
	  $str = html_entity_decode($str, ENT_QUOTES, $encode); 
	  $str = preg_replace("/\&\#.*?\;/i", "", $str);         
  	return $str;
	}

	function getdata($type,$order,$catid,$start,$rows,$keyword,$username)
	{
		$url = "http://192.168.110.110/silkroad/rest/api/$type/$order/$catid/$start/$rows/$keyword/1/$username/search";
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
}
