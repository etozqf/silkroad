<?php
/**
 * CmsTop OpenPort API-TRS
 *
 *  depends: PDO & PDO_MYSQL|PDO_OCI|PDO_DBLIB
 *
 * @copyright (c) 2012 CmsTop (http://www.cmstop.com)
 * @version   $Id$
 */
define('API_DIR', str_replace('\\', '/', dirname(__FILE__)));

require_once API_DIR . '/API.php';


class API_TRS extends API
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
				'DOCPUBTIME DESC' => '发布时间 降序',
				'HITSCOUNT DESC' => '点击数 降序',
				'DOCID DESC' => 'ID 降序',
				'DOCID' => 'ID 升序',
				'HITSCOUNT' => '点击数 升序',
				'DOCPUBTIME' => '发布时间 升序'
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
				'DOCID' => 'ID',
				'HITSCOUNT' => '点击数',
				'DOCPUBTIME' => '发布时间'
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
			'id'          => 'DOCID',
			'title'       => 'DOCTITLE',
			'url'         => 'DOCPUBURL',
			'thumb'       => 'DOCID|ATTACHPIC',
			'time'        => $adapter->dateToChar('DOCPUBTIME').' AS PUBTIMESTR',
			'date'        => $adapter->dateToChar('DOCPUBTIME').' AS PUBTIMESTR',
			'weight'      => '',
			'description' => 'DOCABSTRACT',
			'tips'        => 'DOCKEYWORDS|DOCPUBURL|HITSCOUNT|CRUSER|DOCAUTHOR|DOCWORDSCOUNT'
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
		if (!isset($order['DOCID'])) {
			$order['DOCID'] = 'ASC';
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

		// 10:已发
		$where[] = "DOCSTATUS = 10";

		// 是否有图
		if (!empty($_REQUEST['thumb'])) {
			$where[] = "ATTACHPIC > 0";
		}

		// 来源
		if (!empty($_REQUEST['source'])) {
			$source = $this->_split($_REQUEST['source']);
			$csource = count($source);
			if ($csource > 1) {
				$keys = implode(',', $keys);
				$where[] = '(DOCSOURCE IN (SELECT SOURCEID FROM WCMSOURCE WHERE SRCNAME IN (:SOURCE)) OR DOCSOURCENAME IN (:SOURCE))';
				$bind[':SOURCE'] = $source;
			} elseif($csource) {
				$where[] = '(DOCSOURCE IN (SELECT SOURCEID FROM WCMSOURCE WHERE SRCNAME=:SOURCE) OR DOCSOURCENAME=:SOURCE)';
				$bind[':SOURCE'] = current($source);
			}
		}

		// 创建人
		if (!empty($_REQUEST['author'])) {
			$author = $this->_split($_REQUEST['author']);
			$cauthor = count($author);
			if ($cauthor > 1) {
				$where[] = '(CRUSER IN (:AUTHOR) OR DOCAUTHOR IN (:AUTHOR))';
				$bind[':AUTHOR'] = $author;
			} elseif ($cauthor) {
				$where[] = '(CRUSER=:AUTHOR OR DOCAUTHOR=:AUTHOR)';
				$bind[':AUTHOR'] = current($author);
			}
		}

		// 发布时间
		if (!empty($_REQUEST['pubtime']) && ($pubtime = intval($_REQUEST['pubtime']))) {
			$where[] = 'DOCPUBTIME >= ' . $adapter->charToDate(':PUBTIME');
			$bind[':PUBTIME'] = date('Y-m-d H:i:s', strtotime("-$pubtime day"));
		}
		
		// 关键词
		if (!empty($_REQUEST['keyword'])) {
			$orwhere = array();
			$keywords = $this->_split($_REQUEST['keyword']);
			foreach ($keywords as $i=>$keyword) {
				$orwhere[] = $adapter->instr($adapter->concat("';'", 'DOCKEYWORDS', "';'"), ":KEYWORD{$i}");
				$orwhere[] = "DOCTITLE LIKE :SEARCH{$i}";
				$bind[':KEYWORD'.$i] = ";$keyword;";
				$bind[':SEARCH'.$i] = '%'.addcslashes($keyword, '%_').'%';
			}
			if (!empty($orwhere)) {
				$where[] = '(' . implode(' OR ', $orwhere) .')';
			}
		}

		$sql = "SELECT $fields FROM WCMDOCUMENT";
		$csql = "SELECT COUNT(*) FROM";
		// 所属频道
		if (!empty($_REQUEST['channelid']) && ($ids = $this->_explode($_REQUEST['channelid'])))
		{
			$siteids = array();
			$chnlids = array();
			foreach ($ids as $id) {
				if ($id[0] == '+') {
					$siteids[] = intval(substr($id, 1));
				} else {
					$chnlids[] = intval($id);
				}
			}
			$siteids = array_unique(array_filter($siteids));
			$totalids = empty($siteids) ? array() : $this->_siteChannelIds($siteids);
			$chnlids = array_diff(array_unique(array_filter($chnlids)), $totalids);
			if (!empty($chnlids)) {
				$totalids = array_merge($totalids, $chnlids);
				$this->_childChannelIds($chnlids, $totalids);
			}
			$chnlids = array_unique($totalids);

			$count = count($chnlids);

			if ($count > 500) {
				$union = array();
				foreach (array_chunk($chnlids, 500) as $i=>$chnlids) {
					$k = ":CHNLID_$i";
					$bind[$k] = $chnlids;
					$w = $where;
					$w[] = "DOCCHANNEL IN ($k)";
					$union[] = $sql . ' WHERE ' . implode(' AND ', $w);
				}
				$union = implode(' UNION ALL ', $union);
				$sql = "SELECT * FROM ($union) union_tbl $orderby";
				$csql = "$csql ($union) union_tbl";
			} else {
				if ($count > 1) {
					$where[] = "DOCCHANNEL IN (:CHNLID)";
					$bind[':CHNLID'] = $chnlids;
				} elseif ($count) {
					$where[] = "DOCCHANNEL = :CHNLID";
					$bind[':CHNLID'] = current($chnlids);
				}
				if ($where = implode(' AND ', $where)) {
					$where = "WHERE $where";
				}
				$sql = "$sql $where $orderby";
				$csql = "$csql WCMDOCUMENT $where";
			}
		} else {
			if ($where = implode(' AND ', $where)) {
				$where = "WHERE $where";
			}
			$sql = "$sql $where $orderby";
			$csql = "$csql WCMDOCUMENT $where";
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
	
	protected function channelAction()
	{

		$id = empty($_GET['id']) ? 0 : $_GET['id'];
		$issite = false;
		if (substr($id, 0, 1) == '+') {
			$issite = true;
			$id = intval(substr($id, 1));
		} else {
			$id = intval($id);
			if ($id <= 0) {
				$id = 0;
				$issite = true;
			}
		}
		$adapter = $this->getAdapter();
		if ($issite) {
			$sql = "SELECT SITEID AS ID, SITENAME AS NAME, SITEDESC, 1 AS HASCHILDREN FROM WCMWEBSITE WHERE PARENTID=:ID ORDER BY SITEORDER DESC";
			$site = $adapter->fetchAll($sql, array(
				'ID'=>$id,
			));
			foreach($site as &$d) {
				if (!empty($d['SITEDESC'])) {
					$d['NAME'] = $d['SITEDESC'];
				}
				$d['ID'] = '+' . $d['ID'];
				unset($d['SITEDESC']);
			}
			$sql = "SELECT CHANNELID AS ID, CHNLNAME AS NAME, CHNLDESC, 1 AS HASCHILDREN FROM WCMCHANNEL WHERE SITEID=:ID AND PARENTID=0";
			$data = $adapter->fetchAll($sql, array(
				'ID'=>$id,
			));
			foreach($data as &$d) {
				if (!empty($d['CHNLDESC'])) {
					$d['NAME'] = $d['CHNLDESC'];
				}
				unset($d['CHNLDESC']);
			}
			$data = array_merge($site, $data);
		} else {
			$sql = "SELECT CHANNELID AS ID, CHNLNAME AS NAME, CHNLDESC, 1 AS HASCHILDREN FROM WCMCHANNEL WHERE PARENTID=:ID";
			$data = $adapter->fetchAll($sql, array(
				'ID'=>$id,
			));
			foreach($data as &$d) {
				if (!empty($d['CHNLDESC'])) {
					$d['NAME'] = $d['CHNLDESC'];
				}
				unset($d['CHNLDESC']);
			}
		}
		return $data;
	}
	protected function nameAction()
	{
		$ids = $this->_explode($_GET['id']);
		if (empty($ids)) {
			return array();
		}
		$siteids = array();
		$chnlids = array();
		foreach ($ids as $id) {
			if ($id[0] == '+') {
				$siteids = intval(substr($id, 1));
			} else {
				$chnlids[] = intval($id);
			}
		}
		$siteids = array_unique(array_filter($siteids));
		$chnlids = array_unique(array_filter($chnlids));
		$data = array();
		if (!empty($siteids)) {
			$sql = "SELECT SITEID AS ID, SITENAME AS NAME, SITEDESC FROM WCMWEBSITE WHERE SITEID IN(:ID)";
			$sites = $this->getAdapter()->fetchAll($sql, array('ID'=>$siteids));
			foreach($sites as &$d) {
				if (!empty($d['SITEDESC'])) {
					$d['NAME'] = $d['SITEDESC'];
				}
				unset($d['SITEDESC']);
				$d['ID'] = '+' . $d['ID'];
			}
			$data = array_merge($data, $sites);
		}
		if (!empty($chnlids)) {
			$sql = "SELECT CHANNELID AS ID, CHNLNAME AS NAME, CHNLDESC FROM WCMCHANNEL WHERE CHANNELID IN(:ID)";
			$channels = $this->getAdapter()->fetchAll($sql, array('ID'=>$chnlids));
			foreach($channels as &$d) {
				if (!empty($d['CHNLDESC'])) {
					$d['NAME'] = $d['CHNLDESC'];
				}
				unset($d['CHNLDESC']);
			}
			$data = array_merge($data, $channels);
		}
		return $data;
	}
	
	//-------------------------------------------------------
	//                Helpers for getDataAction
	//-------------------------------------------------------
	
	protected function _filterId($item)
	{
		return $item['DOCID'];
	}
	protected function _filterTitle($item)
	{
		return strip_tags($item['DOCTITLE']);
	}
	protected function _filterUrl($item)
	{
		return (string) $item['DOCPUBURL'];
	}
	protected function _filterThumb($item)
	{
		if (empty($item['ATTACHPIC'])) {
			return '';
		}
		$adapter = $this->getAdapter();
		$file = $adapter->fetchColumn($adapter->limit("SELECT APPFILE FROM WCMAPPENDIX WHERE APPDOCID=:DOCID AND FILEEXT IN ('jpg','gif','bmp','png') ORDER BY APPENDIXID", 1), array(
			'DOCID'=>$item['DOCID']
		));
		return str_replace('%s', $file, $this->_config['imageurl']);
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
		return '';
	}
	protected function _filterDescription($item)
	{
		return strip_tags($item['DOCABSTRACT'], '<p><a>');
	}
	protected function _filterTips($item)
	{
		$tips = array(
			'链接：'.$item['DOCPUBURL'],
			'作者：'.(empty($item['DOCAUTHOR']) ? $item['CRUSER'] : $item['DOCAUTHOR']),
			'字数：'.$item['DOCWORDSCOUNT'],
			'关键词：'.$item['DOCKEYWORDS'],
			'点击数：'.$item['HITSCOUNT']
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
	 * get all channel belong to siteid
	 *
	 * @param array $siteids
	 *
	 * @return array
	 */
	protected function _siteChannelIds(array $siteids)
	{
		$sql = "SELECT CHANNELID FROM WCMCHANNEL WHERE SITEID IN (:SITEID)";
		return $this->getAdapter()->fetchCol($sql, array('SITEID'=>$siteids));
	}

	/**
	 * get all child channelids belong $id
	 *
	 * @param array $findIds
	 * @param array $totalIds
	 */
	protected function _childChannelIds(array $findIds, array &$totalIds)
	{
		$sql = "SELECT CHANNELID FROM WCMCHANNEL WHERE PARENTID IN (:CHANNELID)";
		$childids = $this->getAdapter()->fetchCol($sql, array('CHANNELID'=>$findIds));
		$deepFindIds = array_diff($childids, $totalIds);
		if (!empty($deepFindIds)) {
			$totalIds = array_merge($totalIds, $deepFindIds);
			$this->_childChannelIds($deepFindIds, $totalIds);
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