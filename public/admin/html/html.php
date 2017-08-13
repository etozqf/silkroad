<?php
/**
 * CMSTOP命令行HTML静态页面生成器
 *
 * @version $Id:html.php,v 1.1 2012/03/31 11:15:00 Exp
 * @author xudianyang
 * @copyright Copyright (c) 2012,思拓合众
 */
class html{
	/**
	 * 配置数组
	 *
	 * @var array
	 */
	protected $config;
	
	/**
	 * 模板引擎前台模板器
	 *
	 * @var object
	 */
	protected $template;
	
	/**
	 * CMSTOP系统通用内容模型
	 *
	 * @var object
	 */
	protected $content;
	
	/**
	 * 数据库对象
	 *
	 * @var object
	 */
	protected $db;
	
	/**
	 * url类对象
	 * 
	 * @var object
	 */
	protected $uri;
	
	/**
	 * 配置文件模板
	 *
	 * @var array
	 */
	protected $default = array(
		'show'=>array(
			'article'=>array(
				'catid'=>0,
				'offset'=>0,
				'pagesize'=>100,
				'off'=>false,
			),
			'picture'=>array(
				'catid'=>0,
				'offset'=>0,
				'pagesize'=>100,
				'off'=>false,
			),
			'video'=>array(
				'catid'=>0,
				'offset'=>0,
				'pagesize'=>100,
				'off'=>false,
			),
		),
		'list'=>array(
			'catid'=>0,
			'maxpage'=>100,
		),
	);
	/**
	 * 构造方法
	 * 
	 * 读取静态页面生成器全局配置
	 * 
	 * @param void
	 * @return void
	 */
	public function __construct(){
		$this->config = include_once("./config.php");
		$this->content = loader::model('admin/content', 'system');
		$this->uri = loader::lib('uri', 'system');
		$this->db = factory::db();
	}
	
	/**
	 * 生成内容页及列表页
	 *
	 * @param string $catid 待生成内容页的栏目ID，不限制栏目请设置为0
	 * @param int $offset 开始生成内容的起始点
	 * @param int $pagesize 单次批量生成的数目
	 */
	public function show($catid = null, $pagesize = null){
		if(empty($catid)){
			$catid = 0;
		}
		if(empty($pagesize)){
			$pagesize = 50;
		}
		// 生成文章
		$this->show_article($catid, 0, $pagesize);
		
		// 生成组图
		$this->show_picture($catid, 0, $pagesize);
		
		// 生成视频
		$this->show_video($catid, 0, $pagesize);
		
		// 生成列表页
		$this->show_list($catid);
		
	}
	
	/**
	 * 根据配置文件生成各种静态页面
	 *
	 * @param voidd
	 * @return void
	 */
	public function create(){
		$types = array('article', 'picture', 'video');
		foreach($types as $type){
			foreach($this->default['show'][$type] as $k=>$v){
				$this->config['show'][$type][$k] = 
				isset($this->config['show'][$type][$k]) ?
				$this->config['show'][$type][$k]:$v;
			}
			if(!$this->config['show'][$type]['off']){
				$action = 'show_'.$type;
				$this->$action($this->config['show'][$type]['catid'],
				$this->config['show'][$type]['offset'],
				$this->config['show'][$type]['pagesize']);
			}	
		}
		if(!$this->config['list']['off'])
		$this->show_list($this->config['list']['catid'], $this->config['list']['maxpage']);
		
	}
	/**
	 * 生成文章内容页
	 *
	 * @param string $catid 待生成内容页的栏目ID，不限制栏目请设置为0
	 * @param int $offset 开始生成内容的起始点
	 * @param int $pagesize 单次批量生成的数目
	 */
	public function show_article($catid, $offset, $pagesize){
		if(empty($catid)){
			$catid = 0;
		}
		if(empty($offset)){
			$offset  = 0;
		}
		if(empty($pagesize)){
			$pagesize = 50;
		}
		$app = new cmstop('admin');
		$app->app = "article";
		$config = config::get('config');
		$setting = setting::get($app->app);
		$system = setting::get('system');
		$this->template = & factory::template($app->app);
		$this->template->assign('CONFIG',  $config);
		$this->template->assign('SETTING', $setting);
		$this->template->assign('SYSTEM', $system);
		$where = $this->makewhere($catid);
		echo "\n","Start Create Article Final Page","\n";
		$sqlt = "select count(`contentid`) as total from `#table_content` where {$where} and `modelid`=1";
		$totalarr = $this->db->get($sqlt);
		$total = $totalarr['total'];
		$sql = "select `contentid` from `#table_content` where {$where} and `modelid`=1 order by `contentid` desc limit {$offset}, {$pagesize}";
		$contents = $this->db->select($sql);
		$article = loader::model('admin/article', 'article');
		$count = 0;
		$message  = 'Article Created';
		while(!empty($contents)){
			foreach($contents as $content){
				$article->html_write($content['contentid']);
				++$count;
			}
			$schedule = ($offset+$pagesize)/$total*100;
			if($schedule > 100)$schedule = 100;
			$schedule = sprintf("%05.2f%%", $schedule);
			self::console($message, $schedule, $offset, $pagesize);
			$offset += $pagesize;
			$sql = "select `contentid` from `#table_content` where {$where} and `modelid`=1 order by `contentid` desc limit {$offset}, {$pagesize}";
			$contents = $this->db->select($sql);
		}
		echo "\n\n","Finish Create Article final page","\n";
		echo "\n","Total Created ",$count," Article Final Pages","\n";
	}
	
	/**
	 * 生成组图内容页
	 *
	 * @param string $catid 待生成内容页的栏目ID，不限制栏目请设置为0
	 * @param int $offset 开始生成内容的起始点
	 * @param int $pagesize 单次批量生成的数目
	 */
	public function show_picture($catid, $offset, $pagesize){
		if(empty($catid)){
			$catid = 0;
		}
		if(empty($offset)){
			$offset  = 0;
		}
		if(empty($pagesize)){
			$pagesize = 50;
		}
		$app = new cmstop('admin');
		$app->app = "picture";
		$config = config::get('config');
		$setting = setting::get($app->app);
		$system = setting::get('system');
		$this->template = & factory::template($app->app);
		$this->template->assign('CONFIG',  $config);
		$this->template->assign('SETTING',  $setting);
		$this->template->assign('SYSTEM',  $system);
		$where = $this->makewhere($catid);
		echo "\n","Start Create Picture Final Page","\n";
		$sqlt = "select count(`contentid`) as total from `#table_content` where {$where} and `modelid`=2";
		$totalarr = $this->db->get($sqlt);
		$total = $totalarr['total'];
		$sql = "select `contentid` from `#table_content` where {$where} and `modelid`=2 order by `contentid` desc limit {$offset}, {$pagesize}";
		$contents = $this->db->select($sql);
		$picture = loader::model('admin/picture', 'picture');
		$count = 0;
		$message = "Picture Created";
		while(!empty($contents)){
			foreach($contents as $content){
				$picture->html_write($content['contentid']);
				++$count;
			}
			$schedule = ($offset+$pagesize)/$total*100;
			if($schedule > 100)$schedule = 100;
			$schedule = sprintf("%05.2f%%", $schedule);
			self::console($message, $schedule, $offset, $pagesize);
			$offset += $pagesize;
			$sql = "select `contentid` from `#table_content` where {$where} and `modelid`=2 order by `contentid` desc limit {$offset}, {$pagesize}";
			$contents = $this->db->select($sql);
		}
		echo "\n\n","Finish Create Picture final page","\n";
		echo "\n","Total Created ",$count," Picture Final Pages","\n";
	}
	
	/**
	 * 生成视频内容页
	 *
	 * @param string $catid 待生成内容页的栏目ID，不限制栏目请设置为0
	 * @param int $offset 开始生成内容的起始点
	 * @param int $pagesize 单次批量生成的数目
	 */
	public function show_video($catid, $offset, $pagesize){
		if(empty($catid)){
			$catid = 0;
		}
		if(empty($offset)){
			$offset  = 0;
		}
		if(empty($pagesize)){
			$pagesize = 50;
		}
		$app = new cmstop('admin');
		$app->app = "video";
		$config = config::get('config');
		$setting = setting::get($app->app);
		$system = setting::get('system');
		$this->template = & factory::template($app->app);
		$this->template->assign('CONFIG',  $config);
		$this->template->assign('SETTING', $setting);
		$this->template->assign('SYSTEM',  $system);
		$where = $this->makewhere($catid);
		echo "\n","Start Create Video Final Page","\n";
		$sqlt = "select count(`contentid`) as total from `#table_content` where {$where} and `modelid`=4";
		$totalarr = $this->db->get($sqlt);
		$total = $totalarr['total'];
		$sql = "select `contentid` from `#table_content` where {$where} and `modelid`=4 order by `contentid` desc limit {$offset}, {$pagesize}";
		$contents = $this->db->select($sql);
		$video = loader::model('admin/video', 'video');
		$count = 0;
		$message = "Video Created";
		while(!empty($contents)){
			foreach($contents as $content){
				$video->html_write($content['contentid']);
				++$count;
			}
			$schedule = ($offset+$pagesize)/$total*100;
			if($schedule > 100)$schedule = 100;
			$schedule = sprintf("%05.2f%%", $schedule);
			self::console($message, $schedule, $offset, $pagesize);
			$offset += $pagesize;
			$sql = "select `contentid` from `#table_content` where {$where} and `modelid`=4 order by `contentid` desc limit {$offset}, {$pagesize}";
			$contents = $this->db->select($sql);
		}
		echo "\n\n","Finish Create Video final page","\n";
		echo "\n","Total Created ",$count," Video Final Pages","\n";
	}
	
	/**
	 * 生成列表页和栏目首页
	 *
	 * @param string $catidsstr 栏目列表，逗号分隔的字符串
	 * @param int $maxpage 生成列表页数量
	 * @return void
	 */
	public function show_list($catidsstr, $maxpage = null){
		if(empty($catidsstr)){
			$catids = $this->getcatids(0);
		}else{
			$catids = $this->getcatids($catidsstr);
		}
		$maxpage = (int)$maxpage;
		if(empty($maxpage))$maxpage = null;
		$pagesize = setting('system', 'pagesize');
		$alltotal = 0;
		$catidsarr = array();
		foreach($catids as $k=>$catid){
			$cat = $this->content->category[$catid];
			if(!empty($cat['pagesize']))
				$pagesize = $cat['pagesize'];
			$where = "catid=$catid AND status=6";
			$total = $this->content->count($where);
			$pagecount = max(ceil($total/$pagesize), 1);
			$maxlimit = is_null($maxpage) ? $pagecount : min($pagecount, $maxpage);
			if(!empty($cat['childids']) && $cat['iscreateindex']){
				$alltotal++;
				$onecat['maxpage'] = $maxlimit;
			}else if(empty($cat['childids'])){
				$alltotal += $maxlimit;
				$onecat['maxpage'] = $maxlimit;
			}
			$onecat['catid'] = $catid;
			$catidsarr[] = $onecat;
		}
		$finishpage = 0;
		echo "\n","Start Create List Page","\n";
		foreach($catidsarr as $k=>$cat){
			$catid = $cat['catid']; 
			$maxlimit = $cat['maxpage'];
			if($this->content->category[$catid]['childids'])
			{
				if($this->content->category[$catid]['iscreateindex']){
					$this->index_create($catid);
					$finishpage += 1;
					$schedule = sprintf("%7.3f%%", $finishpage/$alltotal*100);
					$message = sprintf("Category:%-5d list page", $catid);
					self::console($message, $schedule, 0, 1);
				}
			}
			else
			{
				$this->ls_create($catid, $maxlimit);
				$finishpage += $cat['maxpage'];
				$schedule = sprintf("%7.3f%%", $finishpage/$alltotal*100);
				$message = sprintf("Category:%-5d list page", $catid);
				self::console($message, $schedule, 0, $maxlimit);
			}
		}
		echo "\n\n","Finish Create List Page","\n";
		echo "\n","Total Created Category: ",count($catidsarr)," Pages: ",$alltotal,"\n";
	}
	
	/**
	 * 生成列表页
	 *
	 * @param string $catid 栏目列表逗号分隔
	 * @param int $maxlimit 生成的列表页数
	 * @return void
	 */
	protected function ls_create($catid, $maxlimit = null)
	{		
		$cat = $this->content->category[$catid];
		if (!$cat) echo "\n",'Category ID Not Found In Database',"\n";
		$category = loader::model('category', 'system');
		$app = new cmstop('admin');
		$app->app = "system";
		$config = config::get('config');
		$setting = setting::get($app->app);
		$system = setting::get('system');
		$this->template = & factory::template($app->app);
		$this->template->assign('CONFIG', $config);
		$this->template->assign('SETTING',  $setting);
		$this->template->assign('SYSTEM', $system);
		$this->template = & factory::template($app->app);
		
		$this->template->assign('pos', $category->pos($catid));
		$models = array();
		$model = unserialize($cat['model']);
		foreach ($model as $mid=>$m)
		{
			if (!isset($m['show'])) continue;
			$m = table('model', $mid);
			$models[$m['alias']] = $m['name'];
		}
		$r = $this->uri->category($catid, $modelid, '$page');
		$urlrule = $r['url'];
		$pagesize = setting('system', 'pagesize');
		$this->template->assign($cat);
		$this->template->assign('modelid', intval($modelid));
		$this->template->assign('model', $models);
		$this->template->assign('urlrule', $urlrule);
		$this->template->assign('pagesize', $pagesize);
		$this->template->assign('head', array('title'=>$cat['name']));

		$template = is_null($modelid) ? ($cat['template_list'] ? $cat['template_list'] : 'system/list.html') : table('model', $modelid, 'template_list');
		$this->template->assign('mintotal',  $maxlimit);
		for ($page = 1; $page <= $maxlimit; $page++)
		{
			$this->template->assign('page', $page);
			$data = $this->template->fetch($template);
			$r = $this->uri->category($catid, null, $page);
			$filename = $r['path'];
			if ($page == 1) folder::create(dirname($filename));
			write_file($filename, $data);
			if ($cat['iscreateindex'] && $page == 1 && is_null($modelid) && !$cat['childids'])
			{
				$r = $this->uri->category($catid);
				copy($filename, $r['path']);
			}
		}
	}
	
	/**
	 * 生成栏目首页
	 *
	 * @param string $catid 栏目ID
	 * @return  boolean
	 */
	protected function index_create($catid)
	{
		$cat = table('category', $catid);
		if (!$cat) echo "\n",'Category ID Not Found In Database',"\n";
		if ($cat['iscreateindex'])
		{
			$app = new cmstop('admin');
			$app->app = "system";
			$config = config::get('config');
			$setting = setting::get($app->app);
			$system = setting::get('system');
			$this->template = & factory::template($app->app);
			$this->template->assign('CONFIG', $config);
			$this->template->assign('SETTING',  $setting);
			$this->template->assign('SYSTEM',  $system);
			$this->template = & factory::template($app->app);
			$category = loader::model('category', 'system');
			$this->template->assign('pos', $category->pos($catid));
			$this->template->assign('head', array('title'=>$cat['name']));
			$this->template->assign($cat);

			$template = $cat['template_index'];
			$data = $this->template->fetch($template);
			$r = $this->uri->category($catid);
			$filename = $r['path'];
			folder::create(dirname($filename));
			write_file($filename, $data);
			return true;
		}
	}
	
	
	/**
	 * 输出进度信息至控制台
	 *
	 * @param string $message 提示信息
	 * @param striing $schedule 完成进度
	 * @param int $offset 正在生成的位置
	 * @param int $pagesize 单次批量生成数目
	 */
	public static function console($message, $schedule, $offset, $pagesize){
		static $fb = null;
	    static $lasttime = CMSTOP_START_TIME;
	
	    $thistime = microtime(true);
	    $usedtime = $thistime - $lasttime;
	    $label = sprintf("%09.5fs", $usedtime);
	    $lasttime = $thistime;
	    $alltime = time() - CURRENT_TIMESTAMP;
	    $hour = (int)($alltime / 3600);
	    $tmp = $alltime % 3600;
	    $min = (int)($tmp / 60);
	    $tmp = $tmp % 60;
	    $sec = $tmp;
	    $taketime = '['.sprintf("%02.0f", $hour).':'.sprintf("%02.0f", $min).':'.sprintf("%02.0f", $sec).']';
	    echo "\n{$taketime}[",$label," ",$schedule,"] ",$message," ",($offset+1),"...",($offset+$pagesize), " OK";
	}
	
	/**
	 * 生成查询条件
	 *
	 * @param string $catid 栏目ID列表
	 * @return void
	 */
	protected function makewhere($catid){
		$where[] = "`status`=6";
		if(!$catid)
		{
			/*$tmp = array();
			foreach ($this->content->category as $v)
			{
				if(!is_null($v['childids']))
					$tmp[] = $v['childids'];
				else 
					$tmp[] = $v['catid'];
			}
			$where[] = "`catid` IN(".implode(',', $tmp).")";
			*/
		}else{
			$catidarr = explode(',', $catid);
			$catidstrtoarr = array();
			foreach($catidarr as $k=>$onecatid){
				if(isset($this->content->category[$onecatid])){
					if(!is_null($this->content->category[$onecatid]['childids'])){
						$catidtoarr = explode(',', $this->content->category[$onecatid]['childids']);
					}else{
						$catidtoarr = array($onecatid);
					}
					$catidstrtoarr = array_merge($catidstrtoarr, $catidtoarr);
				}
			}
			$catidstrtoarr = array_unique($catidstrtoarr);
			$catidstr = implode(',', $catidstrtoarr);
			$where[] = "`catid` IN(".$catidstr.")";
		}
		
		$where = implode(' AND ', $where);
		return $where;
	}
	
	/**
	 * 返回栏目列表的数组形式
	 *
	 * @param string $catid
	 * @return array
	 */
	protected function getcatids($catid = null){
		if(empty($catid)){
			$sql = "select catid from `#table_category` where parentid is null";
			$parrids = $this->db->select($sql);
			if(!empty($parrids)){
				foreach($parrids as $k=>$cat){
					$catids[] = $cat['catid'];
				}
				$catid = implode_ids($catids);
			}else{
				return array();
			}
		}
		$catidarr = explode(',', $catid);
		$catidstrtoarr = array();
		foreach($catidarr as $k=>$onecatid){
			if(isset($this->content->category[$onecatid])){
				if(!is_null($this->content->category[$onecatid]['childids'])){
					$catidtoarr = explode(',', $this->content->category[$onecatid]['childids']);
					$catidtoarr[] = $onecatid;
				}else{
					$catidtoarr = array($onecatid);
				}
				$catidstrtoarr = array_merge($catidstrtoarr, $catidtoarr);
			}
		}
		$catidstrtoarr = array_unique($catidstrtoarr);
		return $catidstrtoarr;
	}
}
