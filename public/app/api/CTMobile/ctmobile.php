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

class CMSTOP_API_CTMobile extends CMSTOP_API
{
	/**
	 * Oracle database connection
	 *
	 * @var API_Adapter
	 */

	protected $_config, $_db, $_baseUrl;
	public function __construct(array $_config)
	{
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
		$this->_config = $_config;
		$this->_baseUrl = $httphost . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';
		$this->_db = API_Db::factory('mysql', $_config['database']);
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
		$cateSql = sprintf('SELECT * FROM `%scategory` WHERE `disabled` = 0 ORDER BY `sort` asc', $this->_config['database']['prefix']);
		$html = $this->_fetchView('where.tpl', array(
			'category' => $this->_db->fetchAll($cateSql),
			'model' => $this->_config['model']
		));
		return array(
			'state'  => true,
			'html'   => $html,
			'assets' => array(
				'assets' => $this->_baseUrl . 'assets/picker.js',
				'depends' => 'lib.tree lib.list'
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
		$cateSql = sprintf('SELECT * FROM `%scategory` WHERE `disabled` = 0 ORDER BY `sort` asc', $this->_config['database']['prefix']);
		$html = $this->_fetchView('widget.tpl', array(
			'category' => $this->_db->fetchAll($cateSql),
			'model' => $this->_config['model']
		));
		return array(
			'state'  => true,
			'html'   => $html,
			'assets' => array(
				'assets' => $this->_baseUrl.'assets/port.js '.$this->_baseUrl.'assets/style.css',
				'depends' => 'lib.tree lib.list'
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
		$page = empty($_REQUEST['page']) ? 1 : intval($_REQUEST['page']);
		$size = empty($_REQUEST['size']) ? 10 : intval($_REQUEST['size']);
		$keyword = empty($_REQUEST['keyword']) ? '' : $_REQUEST['keyword'];
		$catid = empty($_REQUEST['catid']) ? null : implode(',', array_filter(array_map('intval', explode(',', $_REQUEST['catid']))));
		$modelid = empty($_REQUEST['modelid']) ? null : implode(',', array_filter(array_map('intval', explode(',', $_REQUEST['modelid']))));
		$where = '';
		if (!empty($catid)) {
			$where = 'LEFT JOIN `'.$this->_config['database']['prefix'].'content_category` cate ';
			$where .= 'ON content.contentid = cate.contentid ';
			$where .= 'WHERE cate.catid IN ('.$catid.')';
		} else {
			$where = 'WHERE 1';
		}
		if (!empty($keyword)) {
			$where .= ' AND `title` LIKE "%'.$keyword.'%"';
		}
		if (!empty($modelid)) {
			$where .= sprintf(' AND `modelid` in (%s)', $modelid);
		}
		if ($size < 1 || $size > 1000) {
			$size = 10;
		}
		$offset = ($page - 1) * $size;
		$sql = sprintf('SELECT *, 0 as weight from %scontent content %s order by `sorttime` desc limit %d, %d', $this->_config['database']['prefix'], $where, $offset, $size);
		$totalSql = sprintf('SELECT count(*) as cnt from %scontent content %s', $this->_config['database']['prefix'], $where);
		$list = $this->_db->fetchAll($sql);
		if (empty($_REQUEST['page']))
		{
			return $list;
		}
		$total = $this->_db->fetchRow($totalSql);
		$total = $total['cnt'];
		foreach ($list as & $item) {
			$item['date'] = date('Y-m-d H:i', $item['published']);
			$item['thumb'] = $this->_absUploadurl($item['thumb']);
		}
		return array (
			'data'  => $list, // 查询出的记录集
			'count' => count($list),
			'total' => $total,
			'size'  => $size,
			'page'  => $page
		);
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
		if (empty($contentid)) {
			return array('state'=>false, 'error'=>'缺少contentid');
		}

		$sql = sprintf('SELECT * FROM %sarticle as `article`, %scontent as `content` WHERE `article`.`contentid` = %d AND `content`.`contentid` = %d'
			, $this->_config['database']['prefix'], $this->_config['database']['prefix'], $contentid, $contentid);
		$data = $this->_db->fetchRow($sql);
		return array(
			'state'		=> true,
			'title'		=> $data['title'],
			'content'	=> $data['content']
		);
	}

	protected function _absUploadurl($url)
	{
		if (empty($url)) {
			return '';
		}
		if (strpos($url, '://') !== 0) {
			$url = UPLOAD_URL . $url;
		}
		return $url;
	}
	
	//-------------------------------------------------------
	//	Other helper action
	//-------------------------------------------------------
	protected function _auth()
	{
		return $this->_config['authkey'] === $_REQUEST['authkey'];
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