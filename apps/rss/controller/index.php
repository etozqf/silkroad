<?php
class controller_index extends rss_controller_abstract
{
	private $_rss, $_category, $_catid;

	function __construct($app)
	{
		parent::__construct($app);
		$this->_rss = loader::model('content_rss');
		$this->_category = $this->_rss->category;
		$this->_catid = $this->setting['category'];
	}

	function index()
	{
		$catid = intval($_GET['catid']);
		if ($this->system['pagecached'])
		{
			$keyid = md5('pagecached_rss_index_index_' .$catid);
			cmstop::cache_start($this->system['pagecachettl'], $keyid);
		}
		
		if (isset($this->_category[$catid]))
		{
			$cat = $this->_category[$catid];
			if (!$this->_has($cat))
			{
				$this->showmessage('无此栏目订阅', '?app=rss');
			}
            $this->template->assign('alias',table('category', $catid, 'alias'));
		}

		$sons = $this->_sons($catid);
		if (empty($sons))
		{
			$sons[0] = $cat;
			$sons[0]['rss'] = $this->_rss->ls($catid);
		}
		$this->template->assign('rsslist',$sons);
		$this->template->display('rss/index.html');
		
		if ($this->system['pagecached']) cmstop::cache_end();
	}
	
	function feed()
    {
		$catid = intval($_GET['catid']);
		$title = 'RSS';
		$param = null;
		if (isset($this->_category[$catid])) {
			$cat = $this->_category[$catid];
			$title = $cat['name'];
			$param['catid'] = $catid;
		}
		$rssurl = str_replace('&','&amp;', url('rss/index/feed',$param, true));
		$this->template->assign('title',$title);
		$this->template->assign('rssurl',$rssurl);
		$this->template->assign('sitename',setting('system','sitename'));
		$this->template->assign('list',$this->_asmRss($catid));
		$feet_content_type = 'text/xml';
		$charset = config::get('config', 'charset', 'utf-8');
		header('Content-Type: ' . $feet_content_type . '; charset=' . $charset, true);
		echo '<?xml version="1.0" encoding="'.$charset.'"?>'."\n";
		echo $this->template->fetch('rss/rss.xml');
	}

	private function _asmrss($catid)
    {
    	$size = intval($this->setting['size']);
    	if ($size < 10) {
    		$size = 10;
    	}
		$list = $this->_rss->ls_rss($catid, $size, $this->setting['weight']);

		// 获得 文章所有编号
		$contentids = array();
		foreach ($list as $item) 
		{
			$contentids[] = $item['contentid'];

		}
		if (empty($contentids)) return array();

		// 组装成 contentid=>description
		$digest = ($this->setting['output'] == 'digest');
		$describes = array_combine($contentids,array_fill(0,count($contentids),''));
		foreach ($list as $item) {
			if($item['modelid']=="1"){
		    	$article = loader::model('admin/article', 'article');
				$_describes = $article->select($contentids, '`contentid`,`description`,`content`');
				foreach ($_describes as $item)
				{
					if($digest){
						$description = trim(strip_tags($item['description'],'<p><br><a><img>'));
						if (!$description) {
							$description = str_cut(trim(strip_tags($item['content'],'<p><br><a><img>')),400);
						}
						$describes[$item['contentid']] = $description;
					}
					else{
						$describes[$item['contentid']] = strip_tags($item['content'],'<p><br><a><img>');								
					}
				}
			}
			if($item['modelid']=="2"){
		    	$article = loader::model('admin/picture', 'picture');
				$_describes = $article->select($contentids, '`contentid`,`description`');
				foreach ($_describes as $item)
				{
					$description = trim(strip_tags($item['description'],'<p><br><a><img>'));
					if (!$description) {
						$description = str_cut(trim(strip_tags($item['content'],'<p><br><a><img>')),400);
					}
					$describes[$item['contentid']] = $description;
				}			
			}
			if($item['modelid']=="3"){
		    	$article = loader::model('admin/link', 'link');
				$_describes = $article->select($contentids, '`contentid`,`description`');
				foreach ($_describes as $item)
				{
					$description = trim(strip_tags($item['description'],'<p><br><a><img>'));
					if (!$description) {
						$description = str_cut(trim(strip_tags($item['content'],'<p><br><a><img>')),400);
					}
					$describes[$item['contentid']] = $description;
				}			
			}

			if($item['modelid']=="4"){
				$video = loader::model('admin/video', 'video');
				$_describes = $video->select($contentids, '`contentid`,`description`');
				foreach ($_describes as $item)
				{
					$description = trim(strip_tags($item['description'],'<p><br><a><img>'));
					if (!$description) {
						$description = str_cut(trim(strip_tags($item['content'],'<p><br><a><img>')),400);
					}
					$describes[$item['contentid']] = $description;
				}

			}
			if($item['modelid']=="5"){
		    	$article = loader::model('admin/interview', 'interview');
				$_describes = $article->select($contentids, '`contentid`,`description`');
				foreach ($_describes as $item)
				{
					$description = trim(strip_tags($item['description'],'<p><br><a><img>'));
					if (!$description) {
						$description = str_cut(trim(strip_tags($item['content'],'<p><br><a><img>')),400);
					}
					$describes[$item['contentid']] = $description;
				}			
			}
			if($item['modelid']=="7"){
		    	$article = loader::model('admin/activity', 'activity');
				$_describes = $article->select($contentids, '`contentid`,`description`');
				foreach ($_describes as $item)
				{
					$description = trim(strip_tags($item['description'],'<p><br><a><img>'));
					if (!$description) {
						$description = str_cut(trim(strip_tags($item['content'],'<p><br><a><img>')),400);
					}
					$describes[$item['contentid']] = $description;
				}			
			}
			if($item['modelid']=="8"){
		    	$article = loader::model('admin/vote', 'vote');
				$_describes = $article->select($contentids, '`contentid`,`description`');
				foreach ($_describes as $item)
				{
					$description = trim(strip_tags($item['description'],'<p><br><a><img>'));
					if (!$description) {
						$description = str_cut(trim(strip_tags($item['content'],'<p><br><a><img>')),400);
					}
					$describes[$item['contentid']] = $description;
				}			
			}
			if($item['modelid']=="9"){
		    	$article = loader::model('admin/survey', 'survey');
				$_describes = $article->select($contentids, '`contentid`,`description`');
				foreach ($_describes as $item)
				{
					$description = trim(strip_tags($item['description'],'<p><br><a><img>'));
					if (!$description) {
						$description = str_cut(trim(strip_tags($item['content'],'<p><br><a><img>')),400);
					}
					$describes[$item['contentid']] = $description;
				}			
			}												

			if($item['modelid']=="10"){
				$special = loader::model('admin/special', 'special');
				$_describes = $special->select($contentids, '`contentid`,`description`');
				foreach ($_describes as $item)
				{
					$description = trim(strip_tags($item['description'],'<p><br><a><img>'));
					if (!$description) {
						$description = str_cut(trim(strip_tags($item['content'],'<p><br><a><img>')),400);
					}
					$describes[$item['contentid']] = $description;
				}

			}
		}

		$data = array();
		foreach ($list as $item) {
			$line = array();
			$line['title'] = $item['title'];
			$line['url'] = $item['url'];
			$line['category'] = $this->_category[$item['catid']]['name'];
			$line['description'] = $describes[$item['contentid']];
			$line['published'] = date('r', $item['published']);
			$data[] = $line;
		}
		return $data;

	}

	private function _sons($catid)
    {
		$sons = array();
		foreach ($this->_category as $cid => $cat) {
			if ($cat['parentid'] == $catid && $this->_has($cat)) {
				$cat['rss'] = $this->_rss->ls($cid, 10);
				$sons[] = $cat;
			}
		}
		return $sons;
	}
	private function _has($cat)
	{
		$catids = explode(',',$cat['childids']);
		$catids[] = $cat['catid'];
		return count(array_intersect($catids, $this->_catid))>0;
	}
}