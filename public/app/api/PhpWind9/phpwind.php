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
require_once API_DIR . '/db/discuz_database.php';

class DB extends discuz_database {}

class CMSTOP_API_Phpwind extends CMSTOP_API
{
	/**
	 * Oracle database connection
	 *
	 * @var API_Adapter
	 */

	protected $_config, $_baseUrl, $_siteUrl;
	public function __construct(array $_config)
	{
		$this->_config = $_config;
		$this->_siteUrl = $_config['serverUrl'].'/';
		$this->_baseUrl = $_config['serverUrl'].'/' . array_pop(explode('/', API_DIR)) . '/';
		$dsn = $this->_dsn_to_params($_config['dsn']);
		DB::init('db_driver_mysql', array(
			'1'=> array(
				'dbhost'	=> $dsn['host'],
				'dbuser'	=> $_config['user'],
				'dbpw'		=> $_config['pwd'],
				'dbcharset'	=> $_config['clientCharser'],
				'dbname'	=> $dsn['dbname'],
				'pconnect'	=> 0,
				'tablepre'	=> $_config['tableprefix']
			)
		));
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
		if (!$this->_auth())
		{
			return array('state'=>false, 'error'=>'验证失败');
		}
		$data = array();
		$data['forumlist'] = $this->_get_forum();
		$html = $this->_fetchView('where.tpl', $data);
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
		if (!$this->_auth())
		{
			return array('state'=>false, 'error'=>'验证失败');
		}
		$data = array();
		$data['forumlist'] = $this->_get_forum();
		$data['options'] = $_POST;
		$html = $this->_fetchView('widget.tpl', $data);
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
		if (!$this->_auth())
		{
			return array('state'=>false, 'error'=>'验证失败');
		}
		$size = (int)$_REQUEST['size'];
		(empty($size)) && $size = 10;
		if (isset($_REQUEST['page']))
		{
			$page = (int)$_REQUEST['page'];
			(empty($page)) && $page = 1;
			$where = $this->_where($_REQUEST);
		}
		else
		{
			$page = 1;
			$where = $this->_where($_POST);
		}
		$order = empty($_REQUEST['order']) ? 'created_time' : $_REQUEST['order'];
		$ret = DB::fetch_all('SELECT %i FROM %t WHERE %i ORDER BY %i %i', array(
			implode(DB::quote_field(array('tid', 'subject', 'created_time')), ','),
			'bbs_threads',
			$where,
			DB::order($order, 'desc'),
			DB::limit(($page - 1) * $size, $size)
		));
		$list = array();
		foreach ($ret as $key => $item)
		{
			$list[$key]['id']			= $item['tid'];
			$list[$key]['title']		= $item['subject'];
			$list[$key]['url']			= $this->_siteUrl . 'read.php?tid=' . $item['tid'];
			$list[$key]['thumb']		= null;
			$list[$key]['time']			= $item['created_time'];
			$list[$key]['date']			= date('Y-m-d H:i:s', $item['created_time']);
			$list[$key]['weight']		= 60;
			$list[$key]['description']	= '';
			$list[$key]['tips']			= $list[$key]['url'];
		}
		if (empty($_REQUEST['page']))
		{
			return $list;
		}
		$count = count($list);
		if ($count > 0)
		{
			$total = ($page + 1) * $size;
		}
		else {
			$total = 1;
		}
		return array (
			'data'  => $list, // 查询出的记录集
			'count' => $count,
			'total' => $total,
			'size'  => $size,
			'page'  => $page
		);
	}

	protected function getContentAction()
	{
		if (!$this->_auth())
		{
			return array('state'=>false, 'error'=>'验证失败');
		}
		$contentid = intval($_REQUEST['contentid']);
		if (empty($contentid))
		{
			return array('state'=>false, 'error'=>'缺少contentid');
		}
		$data = DB::fetch_first('SELECT %i FROM %t as t, %t as tc WHERE %i LIMIT 1', array(
			't.subject as subject, tc.content as content',
			'bbs_threads',
			'bbs_threads_content',
			't.tid='.$contentid.' AND tc.tid='.$contentid,
		));
		if (empty($data))
		{
			return array('state'=>false, 'error'=>'帖子不存在或读取失败');
		}
		Wind::import('LIB:ubb.PwUbbCode');
		$data['content'] = preg_replace('/\r\n/', '<br />', $data['content']);
		$data['content'] = PwUbbCode::convertParagraph($data['content']);
		$data['content'] = PwUbbCode::convertHr($data['content']);
		$data['content'] = PwUbbCode::convertList($data['content']);
		$data['content'] = PwUbbCode::convertFont($data['content']);
		$data['content'] = PwUbbCode::convertColor($data['content']);
		$data['content'] = PwUbbCode::convertBackColor($data['content']);
		$data['content'] = PwUbbCode::convertSize($data['content']);
		$data['content'] = PwUbbCode::convertEmail($data['content']);
		$data['content'] = PwUbbCode::convertAlign($data['content']);
		$data['content'] = PwUbbCode::convertGlow($data['content']);
		$data['content'] = PwUbbCode::convertTable($data['content']);
		$data['content'] = PwUbbCode::parseImg($data['content']);
		$data['content'] = PwUbbCode::parseEmotion($data['content']);
		$data['content'] = PwUbbCode::parseUrl($data['content']);
		$data['content'] = PwUbbCode::parseCode($data['content']);
		$data['content'] = PwUbbCode::parseQuote($data['content']);
		$data['content'] = PwUbbCode::parseFlash($data['content']);
		$data['content'] = PwUbbCode::parseMedia($data['content']);
		$data['content'] = PwUbbCode::parseIframe($data['content']);
		return array(
			'state'	=> true,
			'title'	=> $data['subject'],
			'content' => $data['content']
		);
	}
	
	//-------------------------------------------------------
	//                   Other helper action
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

	private function _get_forum()
	{
		$ret = DB::fetch_all('SELECT %i FROM %t WHERE %i ORDER BY %i', array(
			implode(DB::quote_field(array('fid', 'name')), ','),
			'bbs_forum',
			DB::field('type', 'category', '<>'),
			DB::order('vieworder', 'desc')
		));
		return $ret;
	}

	private function _dsn_to_params($dsn)
	{
		preg_match_all('#(\w+)=(\w+);?#', $dsn, $matches);
		$ret = array();
		for ($i = 0, $l = count($matches[0]); $i < $l; $i++)
		{
			$ret[$matches[1][$i]] = $matches[2][$i];
		}
		return $ret;
	}

	private function _where($field)
	{
		$where = array();
		if (!empty($field['keyword']))
		{
			$where[] = "`subject` LIKE '%{$field[keyword]}%'";
		}
		if (!empty($field['username']))
		{
			$where[] = DB::field('created_name', $field['username']);
		}
		if (!empty($field['fids']))
		{
			$where[] = DB::field('fid', explode(',', $field['fids']), 'in');
		}
		if (!empty($field['lastpost']))
		{
			$where[] = DB::field('created_time', $field['lastpost'], '>=');
		}
		if (!empty($field['filter']))
		{
			$where[] = DB::field($field['filter'], 0, '>');
		}
		$where = count($where) ? implode(' AND ', $where) : 1;
		return $where;
	}
}