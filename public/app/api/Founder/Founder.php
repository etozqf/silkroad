<?php
/**
 * CmsTop OpenPort API-Founder(oracle)
 *
 *  depends: OCI8
 *
 * @copyright (c) 2012 CmsTop (http://www.cmstop.com)
 * @version   $Id$
 */

define('API_DIR', str_replace('\\', '/', dirname(__FILE__)));

require_once API_DIR . '/API.php';

class API_Founder extends API
{
	/**
	 * Oracle database connection
	 *
	 * @var API_Adapter
	 */
	protected $_adapter;

	protected $_config;

	protected $_baseUrl;

	protected $_basePath;

	protected $_rules;

	protected $_nodeRuleId = array();
	
	public function __construct(array $config)
	{
		$this->_config = $config;
		$scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

		if (empty($_SERVER['HTTP_HOST'])) {
			$host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
			$port = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : '';

			if (strlen($port) && !(($scheme == 'http' && $port == 80) || ($scheme == 'https' && $port == 443)))
			{
				$host .= ':'.$port;
			}
		} else {
			$host = $_SERVER['HTTP_HOST'];
		}

		$httphost = $scheme .'://'. $host;
		$this->_baseUrl = $httphost . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';
		$this->_basePath = $httphost . $_SERVER['SCRIPT_NAME'];
	}
	
	//-------------------------------------------------------
	//				   Part of API
	//-------------------------------------------------------
	/**
	 * Required by API
	 *
	 * @return array
	 */
	protected function getPickerAction()
	{
		if (!$this->_auth()) {
			return array('state'=>false, 'error'=>'验证失败');
		}
		$html = $this->_fetchView('where.tpl', array(
			'baseUrl' => $this->_baseUrl,
			'basePath' => $this->_basePath,
			'sortset' => array(
				'PUBTIME DESC' => '发布时间 降序',
				'ARTICLEID DESC' => 'ID 降序',
				'DISPLAYORDER DESC' => '优先级 降序',
				'ARTICLEID' => 'ID 升序',
				'DISPLAYORDER' => '优先级 升序',
				'PUBTIME' => '发布时间 升序'
			)
		));
		return array(
			'state'  => true,
			'html'   => $html,
			'assets' => array(
				'assets' => $this->_baseUrl . 'assets/picker.js',
				'depends' => 'lib.tree'
			)
		);
	}
	/**
	 * Required by API
	 *
	 * @return array
	 */
	protected function getPortAction()
	{
		if (!$this->_auth()) {
			return array('state'=>false, 'error'=>'验证失败');
		}
		$data = array(
			'baseUrl' => $this->_baseUrl,
			'basePath' => $this->_basePath,
			'options' => $_POST,
			'sortset' => array(
				'ARTICLEID' => 'ID',
				'DISPLAYORDER' => '优先级',
				'PUBTIME' => '发布时间'
			)
		);
		$html = $this->_fetchView('widget.tpl', $data);
		return array(
			'state'  => true,
			'html'   => $html,
			'assets' => array(
				'assets' => $this->_baseUrl . 'assets/port.js',
				'depends' => 'lib.tree'
			)
		);
	}
	/**
	 * Required by API
	 *
	 * @return array
	 */
	protected function getDataAction()
	{
		if (!$this->_auth()) {
			return array('state'=>false, 'error'=>'验证失败');
		}
		$adapter = $this->getAdapter();
		$maps = array(
			'id'		  => 'ARTICLEID',
			'title'	   => 'TITLE',
			'url'		 => 'URL|MASTERID|ARTICLEID|' . $adapter->dateToChar('PUBTIME') . ' AS PUBTIMESTR',
			'thumb'	   => 'PICLINKS|MASTERID',
			'time'		=> $adapter->dateToChar('PUBTIME') . ' AS PUBTIMESTR',
			'date'		=> $adapter->dateToChar('PUBTIME') . ' AS PUBTIMESTR',
			'weight'	  => 0,
			'description' => 'ABSTRACT',
			'tips'		=> 'URL|KEYWORD|IMPORTANCE|EDITOR|ATTR|WORDCOUNT'
		);
		$fields = $this->_explode($_REQUEST['fields']);
		$fieldsarr = array();
		$filters = array();
		foreach ($fields as $field) {
			$filter = '_filter' . ucfirst($field);
			if (method_exists($this, $filter)) {
				$filters[$field] = $filter;
			}
			if (empty($maps[$field])) {
				continue;
			}
			$fieldsarr = array_merge($fieldsarr, explode('|', $maps[$field]));
		}

		// 排序 orderby
		$order = array();
		if (!empty($_REQUEST['orderby'])) {
			if (is_array($_REQUEST['orderby'])) {
				foreach ($_REQUEST['orderby'][0] as $i=>$f) {
					$s = $_REQUEST['orderby'][1][$i];
					$order[$f] = strtoupper($s) == 'DESC' ? 'DESC' : 'ASC';
				}
			} else {
				$parts = explode(',', $_REQUEST['orderby']);
				foreach($parts as $part) {
					$part = trim($part);
					if (empty($part)) {
						continue;
					}
					if (preg_match('/(.*)\s*(?:\s(desc|asc))?$/Ui', $part, $m)) {
						$order[$m[1]]	= isset($m[2]) && strtoupper($m[2]) == 'DESC' ? 'DESC' : 'ASC';
					}
				}
			}
		}
		if (empty($order['ARTICLEID'])) {
			$order['ARTICLEID'] = 'ASC';
		}
		$orderby = array();
		foreach ($order as $field=>$sc) {
			$orderby[] = $sc == 'DESC' ? "$field DESC" : $field;
			if ($field !== 'DISPLAYORDER') {
				$fieldsarr[] = $field;
			}
		}
		$orderby = 'ORDER BY '.implode(',', $orderby);
		$fields = implode(', ', array_unique($fieldsarr));

		$where = array();
		$bind = array();

		// 0:预签 1:签发 2:下载 3:待签
		$where[] = "PUBLISHSTATE>1";

		// 是否有图
		if (!empty($_REQUEST['thumb'])) {
			$where[] = "PICLINKS IS NOT NULL";
		}

		// 来源
		if (!empty($_REQUEST['source'])) {
			$source = $this->_split($_REQUEST['source']);
			$csource = count($source);
			if ($csource > 1) {
				$where[] = 'SOURCENAME IN (:SOURCE)';
				$bind[':SOURCE'] = $source;
			} elseif($csource) {
				$where[] = 'SOURCENAME=:SOURCE';
				$bind[':SOURCE'] = current($source);
			}
		}

		// 创建人
		if (!empty($_REQUEST['author'])) {
			$author = $this->_split($_REQUEST['author']);
			$cauthor = count($author);
			if ($cauthor > 1) {
				$where[] = '(AUTHOR IN (:AUTHOR) OR SUBSCRIBER IN (:AUTHOR))';
				$bind[':AUTHOR'] = $author;
			} elseif ($cauthor) {
				$where[] = '(AUTHOR=:AUTHOR OR SUBSCRIBER=:AUTHOR)';
				$bind[':AUTHOR'] = current($author);
			}
		}

		// 发布时间
		if (!empty($_REQUEST['pubtime']) && ($pubtime = intval($_REQUEST['pubtime']))) {
			$where[] = 'PUBTIME >= ' . $adapter->charToDate(':PUBTIME');
			$bind[':PUBTIME'] = date('Y-m-d H:i:s', strtotime("-$pubtime day"));
		}
		
		// 关键词
		if (!empty($_REQUEST['keyword'])) {
			$orwhere = array();
			$keywords = $this->_split($_REQUEST['keyword']);
			foreach ($keywords as $i=>$keyword) {
				$orwhere[] = $adapter->instr($adapter->concat("' '", 'KEYWORD', "' '"), ":KEYWORD{$i}");
				$orwhere[] = "TITLE LIKE :SEARCH{$i}";
				$bind[':KEYWORD'.$i] = " $keyword ";
				$bind[':SEARCH'.$i] = '%'.addcslashes($keyword, '%_').'%';
			}
			if (!empty($orwhere)) {
				$where[] = '(' . implode(' OR ', $orwhere) .')';
			}
		}
		$where[] = 'PUBLISHSTATE != 3';

		$sql = "SELECT ARTICLEID FROM PAGELAYOUT";
		$csql = "SELECT COUNT(*) FROM";
		// 所属节点: nodeid
		if (!empty($_REQUEST['nodeid']) && ($nodeids = $this->_ids($_REQUEST['nodeid'])))
		{
			$this->_childNodeIds($nodeids, $nodeids);
			$nodeids = array_unique($nodeids);

			$count = count($nodeids);
			if ($count > 500) {
				$union = array();
				foreach (array_chunk($nodeids, 500) as $i=>$nodeids) {
					$k = ":NODEID_$i";
					$bind[$k] = $nodeids;
					$w = $where;
					$w[] = "NODEID IN ($k)";
					$union[] = $sql . ' WHERE ' . implode(' AND ', $w);
				}
				$union = implode(' UNION ALL ', $union);
				$sql = "SELECT * FROM ($union) union_tbl $orderby";
				$csql = "$csql ($union) union_tbl";
			} else {
				if ($count > 1) {
					$where[] = "NODEID IN (:NODEID)";
					$bind[':NODEID'] = $nodeids;
				} elseif($count) {
					$where[] = "NODEID = :NODEID";
					$bind[':NODEID'] = current($nodeids);
				}
				if ($where = implode(' AND ', $where)) {
					$where = "WHERE $where";
				}
				$sql = "$sql $where $orderby";
				$csql = "$csql PAGELAYOUT $where";
			}
		} else {
			if ($where = implode(' AND ', $where)) {
				$where = "WHERE $where";
			}
			$sql = "$sql $where $orderby";
			$csql = "$csql PAGELAYOUT $where";
		}
		
		// limit: size, page
		$size = empty($_REQUEST['size']) ? 10 : intval($_REQUEST['size']);
		if ($size < 1 || $size > 1000) {
			$size = 10;
		}
		$total = 0;
		$page = empty($_REQUEST['page']) ? 0 : intval($_REQUEST['page']);
		$data = null;
		if ($page > 0) {
			$total = $adapter->fetchColumn($csql, $bind);
			$articleids = $adapter->fetchAll($sql, $bind, $size, $size * ($page-1));
		} else {
			$articleids = $adapter->fetchAll($sql, $bind, $size);
		}
		foreach ($articleids as &$item) {
			$item = $item['ARTICLEID'];
		}
		$orderby = explode(',', $orderby);
		foreach ($orderby as &$item) {
			if (strpos($item, 'DISPLAYORDER') !== false) {
				unset($item);
			}
		}
		$orderby = implode(',', $orderby);
		$sql = "SELECT $fields FROM RELEASELIB WHERE ARTICLEID IN (".(implode(',', $articleids)).") " . $orderby;
		$article_data = $adapter->fetchAll($sql, array(), count($articleids));

		$data = array();
		foreach ($articleids as $aid) {
				foreach ($article_data as &$item) {
						if ($item['ARTICLEID'] == $aid) {
								$data[] = $item;
								unset($item);
						}
				}
		}

		if (!empty($data)) {
			$tmp = array();
			foreach ($data as &$d) {
				$row = array();
				foreach ($filters as $field=>$filter) {
					$row[$field] = $this->$filter($d);
				}
				$tmp[] = $row;
			}
			$data = $tmp;
		}
		if ($page > 0) {
			$data = array('data'=>$data, 'count'=>count($data), 'total'=>$total, 'size'=>$size, 'page'=>$page);
		}
		return $data;
	}

	/**
	 * Required by API
	 *
	 * @return array
	 */
	protected function getContentAction()
	{
		if (!$this->_auth()) {
			return array('state'=>false, 'error'=>'验证失败');
		}
		$contentid = intval($_REQUEST['contentid']);
		if (empty($contentid))
		{
			return array('state'=>false, 'error'=>'缺少contentid');
		}
		$sql = "SELECT TITLE, CONTENT FROM RELEASELIB WHERE ARTICLEID=$contentid LIMIT 1";
		$adapter = $this->getAdapter();
		$data = $adapter->fetchAll($sql);
		return array(
			'state'		=> true,
			'title'		=> $data['TITLE'],
			'content'	=> $data['CONTENT']
		);
	}
	
	//-------------------------------------------------------
	//				   Other helper action
	//-------------------------------------------------------
	
	protected function nodeAction()
	{
		$nodeid = empty($_GET['id']) ? 0 : intval($_GET['id']);
		if ($nodeid < 0) {
			$nodeid = 0;
		}
		$adapter = $this->getAdapter();
		$sql = "SELECT NODEID AS ID, NODENAME AS NAME, 1 AS HASCHILDREN FROM TYPESTRUCT WHERE PARENTID=:NODEID AND NODEID < 100000000 AND DELETETIME < ".$adapter->charToDate(':DELTIME')." ORDER BY DISPLAYORDER";
		return $adapter->fetchAll($sql, array(':NODEID'=>$nodeid, ':DELTIME'=>'2005-01-01 00:00:00'));
	}
	protected function nameAction()
	{
		$ids = $this->_ids($_GET['id']);
		if (empty($ids)) {
			return array();
		}
		$adapter = $this->getAdapter();
		$sql = "SELECT NODEID AS ID, NODENAME AS NAME FROM TYPESTRUCT WHERE NODEID IN(:NODEID)";
		return $adapter->fetchAll($sql, array('NODEID'=>$ids));
	}
	
	//-------------------------------------------------------
	//				Helpers for getDataAction
	//-------------------------------------------------------
	
	protected function _filterId($item)
	{
		return $item['ARTICLEID'];
	}
	protected function _filterTitle($item)
	{
		return strip_tags($item['TITLE']);
	}
	protected function _filterUrl($item)
	{
		if (!empty($item['URL'])) {
			return (string) $item['URL'];
		}
		$rule = $this->_getNodeRule($item['MASTERID']);
		$file = strtr($this->_config['articleUrlRule'], array(
			'{ARTICLEID}' => $item['ARTICLEID'],
			'{PUBDATE}' => date('Y-m/d', strtotime($item['PUBTIMESTR']))
		));
		return str_replace('{FILE}', $file, $rule['ART']);
	}
	protected function _filterThumb($item)
	{
		if (empty($item['PICLINKS'])) {
			return '';
		}
		$rule = $this->_getNodeRule($item['MASTERID']);
		$file = substr($item['PICLINKS'], strlen($this->_config['picturePath']));
		return str_replace('{FILE}', $file, $rule['PIC']);
	}
	protected function _filterTime($item)
	{
		return empty($item['PUBTIMESTR']) ? 0 : strtotime($item['PUBTIMESTR']);
	}
	protected function _filterDate($item)
	{
		return (string)$item['PUBTIMESTR'];
	}
	protected function _filterWeight($item)
	{
		return (string)$item['DISPLAYORDER'];
	}
	protected function _filterDescription($item)
	{
		return strip_tags($item['ABSTRACT'], '<p><a>');
	}
	protected function _filterTips($item)
	{
		$attr = array(
			31=>'一般新闻',
			32=>'PDF',
			33=>'版面图',
			34=>'纸报新闻',
			36=>'图片新闻'
		);
		$tips = array(
			'关键词：'.$item['KEYWORD'],
			'优先级：'.$item['DISPLAYORDER'],
			'编辑：'.$item['EDITOR'],
			'属性：'.(isset($attr[$item['ATTR']]) ? $attr[$item['ATTR']] : '未知'),
			'字数：'.$item['WORDCOUNT']
		);
		return implode('<br />', $tips);
	}
	
	//-------------------------------------------------------
	//					Other Helpers
	//-------------------------------------------------------

	/**
	 * @return API_Adapter
	 */
	protected function getAdapter()
	{
		if (!$this->_adapter) {
			$database = $this->_config['database'];
			$this->_adapter = API_Db::factory($database['driver'], $database);
		}

		return $this->_adapter;
	}

	protected function _auth()
	{
		return $this->_config['authkey'] === $_REQUEST['authkey'];
	}

	/**
	 * explode str with "," into array
	 *
	 * @param string $str
	 * @return array
	 */
	protected function _explode($str)
	{
		return array_unique(array_filter(array_map('trim', explode(',', $str))));
	}

	/**
	 * explode str with "," into int array
	 *
	 * @param string $str
	 * @return array
	 */
	protected function _ids($str)
	{
		return array_unique(array_filter(array_map('intval', explode(',', $str))));
	}

	/**
	 * split str with "," " " ";" into array
	 *
	 * @param string $str
	 *
	 * @return array
	 */
	protected function _split($str)
	{
		return array_unique(array_filter(preg_split('#\s*[ ,;]\s*#', $str)));
	}

	/**
	 * get all child nodeids belong $id
	 *
	 * @param array $findIds
	 * @param array $totalIds
	 */
	protected function _childNodeIds(array $findIds, array &$totalIds)
	{
		$adapter = $this->getAdapter();
		$sql = "SELECT NODEID FROM TYPESTRUCT WHERE PARENTID IN (:NODEID) AND NODEID < 100000000 AND DELETETIME < ".$adapter->charToDate(':DELTIME');
		$childids = $adapter->fetchCol($sql, array(':NODEID'=>$findIds, ':DELTIME'=>'2005-01-01 00:00:00'));
		$deepFindIds = array_diff($childids, $totalIds);
		if (!empty($deepFindIds)) {
			$totalIds = array_merge($totalIds, $deepFindIds);
			$this->_childNodeIds($deepFindIds, $totalIds);
		}
	}

	protected function _getNodeRule($nodeid)
	{
		if (!isset($this->_nodeRuleId[$nodeid])) {
			$adapter = $this->getAdapter();
			$this->_nodeRuleId[$nodeid] = $adapter->fetchColumn(
				"SELECT PUBRULEID FROM TYPESTRUCT WHERE NODEID=:NODEID",
				array(':NODEID' => $nodeid)
			);
		}
		$this->_cacheRules();
		return $this->_rules[$this->_nodeRuleId[$nodeid]];
	}

	protected function _cacheRules()
	{
		if (!empty($this->_rules)) {
			return;
		}
		$cachefile = API_DIR . '/rulecache.php';
		if (is_file($cachefile) && filemtime($cachefile) + 1296000 > time()) {
			$this->_rules = @include $cachefile;
			return;
		}
		$adapter = $this->getAdapter();
		$data = $adapter->fetchAll("SELECT s.SID,s.WEBPATH,d.DOMAINURL FROM WEBSTRUCT s
				LEFT JOIN WEBDOMAIN d ON d.DOMAINID=s.DOMAINID");
		$struct = array();
		foreach ($data as $d) {
			$struct[$d['SID']] = $d;
		}
		$data = $adapter->fetchAll("SELECT RULEID,ARTDIRECTORY,PICDIRECTORY FROM WEBRULE");
		foreach ($data as $d) {
			$this->_rules[$d['RULEID']] = array(
				'ART' => $this->_buildRule($struct[$d['ARTDIRECTORY']]),
				'PIC' => $this->_buildRule($struct[$d['PICDIRECTORY']])
			);
		}
		file_put_contents($cachefile, '<?php return '.var_export($this->_rules, true).';');
	}
	protected function _buildRule($struct)
	{
		return rtrim($struct['DOMAINURL'], '/') .'/'. trim($struct['WEBPATH'], '/') .'/{FILE}';
	}
	
	protected function _fetchView($__view_file, array $__data = array())
	{
		$__view_file = API_DIR .'/views/'. $__view_file;
		extract($__data);
		ob_start();
		try {
			@include $__view_file;
		} catch (Exception $e) {}
		return ob_get_clean();
	}
}