<?php
/**
 * CmsTop OpenPort API-ENORTH(oracle)
 *
 *  depends: OCI8
 *
 * @copyright (c) 2012 CmsTop (http://www.cmstop.com)
 * @version   $Id$
 */

define('API_DIR', str_replace('\\', '/', dirname(__FILE__)));

require_once API_DIR . '/API.php';

class API_Enorth extends API
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

	protected $_cache = array();
	
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
	//                   Part of API
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
				'PUB_DATE DESC' => '发布时间 降序',
				'LIST_ORDER' => '列表顺序',
				'NEWS_ID DESC' => 'ID 降序',
				'MOD_DATE DESC' => '修改日期 降序',
				'PUB_DATE' => '发布时间 升序',
				'MOD_DATE' => '修改日期 升序',
				'NEWS_ID' => 'ID 升序',
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
				'PUB_DATE DESC' => '发布时间',
				'LIST_ORDER' => '列表顺序',
				'MOD_DATE DESC' => '修改日期',
				'NEWS_ID DESC' => 'ID'
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
		$this->_readCache('NODE');
		$adapter = $this->getAdapter();
		$maps = array(
			'id'          => 'NEWS_ID',
			'title'       => 'TITLE',
			'url'         => 'URL|CHANNEL_ID',
			'thumb'       => 'PIC_ID',
			'time'        => $adapter->dateToChar('PUB_DATE') . ' AS PUBTIMESTR',
			'date'        => $adapter->dateToChar('PUB_DATE') . ' AS PUBTIMESTR',
			'weight'      => 'LIST_ORDER',
			'description' => 'NEWS_ABS',
			'tips'        => 'TAG|KEYWORDS|KEYWORDS|KEYWORDS2|SOURCE_NAME'
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
		if (!isset($order['n.NEWS_ID'])) {
			$order['n.NEWS_ID'] = 'ASC';
		}
		$orderby = array();
		foreach ($order as $field=>$sc) {
			$orderby[] = $sc == 'DESC' ? "$field DESC" : $field;
			$fieldsarr[] = $field;
		}
		$orderby = 'ORDER BY '.implode(',', $orderby);


		$fields = implode(', ', array_unique($fieldsarr));

		$where = array();
		$bind = array();

		$table = "TN_NEWS n LEFT JOIN TN_NEWS_ATT2 a ON a.NEWS_ID=n.NEWS_ID";
		$sql = "SELECT $fields FROM $table";
		$csql = "SELECT COUNT(*) FROM";

		$where[] = "n.STATE=2000";

		// 发布时间
		if (!empty($_REQUEST['pubtime']) && ($pubtime = intval($_REQUEST['pubtime']))) {
			$where[] = 'n.PUB_DATE >= ' . $adapter->charToDate(':PUB_DATE');
			$bind[':PUB_DATE'] = date('Y-m-d H:i:s', strtotime("-$pubtime day"));
		}

		// 是否有图
		if (!empty($_REQUEST['thumb'])) {
			$where[] = "a.PIC_ID IS NOT NULL";
		}
		
		// 关键词
		if (!empty($_REQUEST['keyword'])) {
			$orwhere = array();
			$keywords = $this->_split($_REQUEST['keyword']);
			foreach ($keywords as $i=>$keyword) {
				$key = ":KEYWORD{$i}";
				$orwhere[] = "n.KEYWORDS=$key OR n.KEYWORDS2=$key OR n.KEYWORDS3=$key";
				$orwhere[] = "n.TITLE LIKE :SEARCH{$i}";
				$bind[':KEYWORD'.$i] = $keyword;
				$bind[':SEARCH'.$i] = '%'.addcslashes($keyword, '%_').'%';
			}
			if (!empty($orwhere)) {
				$where[] = '(' . implode(' OR ', $orwhere) .')';
			}
		}

		// 类型
		if (!empty($_REQUEST['typeid']) && ($typeids = $this->_ids($_REQUEST['typeid'])))
		{
			$where[] = 'n.NEWS_TYPE_ID IN (:TYPEID)';
			$bind[':TYPEID'] = $typeids;
		}

		// 所属节点: nodeid
		if (!empty($_REQUEST['nodeid']) && ($nodeids = $this->_ids($_REQUEST['nodeid'])))
		{
			$nodeids = $this->_getAllIds($nodeids);

			$count = count($nodeids);
			if ($count > 500) {
				$union = array();
				foreach (array_chunk($nodeids, 500) as $i=>$nodeids) {
					$k = ":NODEID_$i";
					$bind[$k] = $nodeids;
					$w = $where;
					$w[] = "n.CHANNEL_ID IN ($k)";
					$union[] = $sql . ' WHERE ' . implode(' AND ', $w);
				}
				$union = implode(' UNION ALL ', $union);
				$sql = "SELECT * FROM ($union) union_tbl $orderby";
				$csql = "$csql ($union) outer_tbl";
			} else {
				if ($count > 1) {
					$where[] = "n.CHANNEL_ID IN (:NODEID)";
					$bind[':NODEID'] = $nodeids;
				} elseif($count) {
					$where[] = "n.CHANNEL_ID = :NODEID";
					$bind[':CHANNELID'] = current($nodeids);
				}
				if ($where = implode(' AND ', $where)) {
					$where = "WHERE $where";
				}
				$sql = "SELECT * FROM ($sql $where) union_tbl $orderby";
				$csql = "$csql $table $where";
			}
		} else {
			if ($where = implode(' AND ', $where)) {
				$where = "WHERE $where";
			}
			$sql = "SELECT * FROM ($sql $where) union_tbl $orderby";
			$csql = "$csql $table $where";
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
			$data = $adapter->fetchAll($sql, $bind, $size, $size * ($page-1));
		} else {
			$data = $adapter->fetchAll($sql, $bind, $size);
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
	
	//-------------------------------------------------------
	//                   Other helper action
	//-------------------------------------------------------
	
	protected function nodeAction()
	{
		$nodeid = empty($_GET['id']) ? 0 : intval($_GET['id']);
		if ($nodeid < 0) {
			$nodeid = 0;
		}
		$adapter = $this->getAdapter();
		$sql = "SELECT CHANNEL_ID AS ID, CHANNEL_NAME AS NAME, 1 AS HASCHILDREN FROM TN_CHANNEL WHERE PARENT_ID=:NODEID";
		return $adapter->fetchAll($sql, array(':NODEID'=>$nodeid));
	}
	protected function nodeNameAction()
	{
		$ids = $this->_ids($_GET['id']);
		if (empty($ids)) {
			return array();
		}
		$adapter = $this->getAdapter();
		$sql = "SELECT CHANNEL_ID AS ID, CHANNEL_NAME AS NAME FROM TN_CHANNEL_NAME WHERE CHANNEL_ID IN(:NODEID)";
		return $adapter->fetchAll($sql, array('NODEID'=>$ids));
	}
	protected function typeAction()
	{

	}
	protected function typeNameAction()
	{

	}
	
	//-------------------------------------------------------
	//                Helpers for getDataAction
	//-------------------------------------------------------
	
	protected function _filterId($item)
	{
		return $item['NEWS_ID'];
	}
	protected function _filterTitle($item)
	{
		return strip_tags($item['TITLE']);
	}
	protected function _filterUrl($item)
	{
		// TODO:
		$this->_cacheNode(array($item['CHANNEL_ID']));
		return $this->_cache['NODE'][$item['CHANNEL_ID']]['DOMAIN_NAME'] . $item['URL'];
	}
	protected function _filterThumb($item)
	{
		// TODO:
		return $item['PIC_ID'];
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
		return (string)$item['LIST_ORDER'];
	}
	protected function _filterDescription($item)
	{
		// TODO:
		return $item['NEWS_ABS'];
	}
	protected function _filterTips($item)
	{
		$tips = array(
			'标签：'.$item['TAG'],
			'关键词：'.$item['KEYWORDS'].$item['KEYWORDS2'].$item['KEYWORDS3'],
			'来源：'.$item['SOURCE_NAME']
		);
		return implode('<br />', $tips);
	}
	
	//-------------------------------------------------------
	//                    Other Helpers
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
	protected function _findChildNodes(array $findIds, array &$totalIds)
	{
		$nextquery = array();
		foreach ($findIds as $id) {
			if (isset($this->_cache['NODE'][$id])) {
				$totalIds = array_merge($totalIds, $this->_nodeCache[$id]['CHILD_NODE']);
			} else {
				$nextquery = $id;
			}
		}
		if (!empty($nextquery)) {
			$adapter = $this->getAdapter();
			$sql = "SELECT CHANNEL_ID FROM TN_CHANNEL WHERE PARENT_ID IN (:NODEID)";
			$childids = $adapter->fetchCol($sql, array(':NODEID'=>$nextquery));
			$deepFindIds = array_diff($childids, $totalIds);
			if (!empty($deepFindIds)) {
				$totalIds = array_merge($totalIds, $deepFindIds);
				$this->_findChildNodes($deepFindIds, $totalIds);
			}
		}
	}
	protected function _getChildNodes($nodeid)
	{
		$ids = array();
		$this->_findChildNodes(array($nodeid), $ids);
		return array_values(array_unique($ids));
	}
	protected function _getAllIds(array $nodeids)
	{
		$this->_cacheNode($nodeids);
		$ret = array();
		foreach ($nodeids as $id) {
			$ret = array_merge($ret, $this->_cache['NODE'][$id]['CHILD_NODES']);
		}
		return array_values(array_unique(array_merge($nodeids, $ret)));
	}
	protected function _cacheNode(array $nodeids)
	{
		$nodeids = array_diff($nodeids, array_keys($this->_cache['NODE']));
		if (!empty($tocache)) {
			$adapter = $this->getAdapter();
			$sql = "SELECT CHANNEL_ID,DIR_NAME,DOMAIN_NAME,CHANNEL_NAME FROM TN_CHANNEL WHERE CHANNEL_ID IN (:NODEID)";
			$data = $adapter->fetchAll($sql, array(':NODEID'=>$nodeids));
			foreach ($data as $d) {
				$d['CHILD_NODES'] = $this->_getChildNodes($d['CHANNEL_ID']);
				$this->_cache['NODE'][$d['CHANNEL_ID']] = $d;
			}
		}
	}
	protected function _cacheType()
	{

	}

	protected function _readCache($name)
	{
		$cachefile = API_DIR . "/$name.php";
		if (is_file($cachefile) && filemtime($cachefile) + 1296000 > time()) {
			$this->_cache[$name] = @include $cachefile;
		} else {
			$this->_cache[$name] = array();
		}
	}

	protected function _writeCache($name)
	{
		$cachefile = API_DIR . "/cache-$name.php";
		file_put_contents($cachefile, '<?php return '.var_export($this->_cache[$name], true).';', LOCK_EX);
	}

	public function __destruct()
	{
		foreach ($this->_cache as $key=>$v) {
			$this->_writeCache($key);
		}
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