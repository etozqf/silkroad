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

class CMSTOP_API_Discuz extends CMSTOP_API
{
	/**
	 * Oracle database connection
	 *
	 * @var API_Adapter
	 */

	protected $_config;
	public function __construct(array $_config)
	{
		global $_G;
		$this->_config = $_config;
		$this->_baseUrl = $_G['siteurl'];
		DB::init('db_driver_mysql', $_config['db']);
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
		include_once DISCUZ_ROOT.'./source/function/function_block.php';
		include_once DISCUZ_ROOT.'./source/language/block/lang_threadlist.php';
		$data = array();
		$data['language'] = $lang;
		$data['data'] = block_script('forum_thread', 'thread')->setting;
		$data['forumlist'] = C::t('forum_forum')->fetch_all_fids();
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
		include_once DISCUZ_ROOT.'./source/function/function_block.php';
		include_once DISCUZ_ROOT.'./source/language/block/lang_threadlist.php';
		$data = array();
		$data['data'] = block_script('forum_thread', 'thread')->setting;
		$data['options'] = $_POST;
		$data['language'] = $lang;
		$data['forumlist'] = C::t('forum_forum')->fetch_all_fids();
		$data['threadtype'] = C::t('forum_threadtype')->fetch_all_for_order();
		$this->_parse($data['options']);
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
			$fields = $_REQUEST;
		}
		else
		{
			$page = 1;
			$fields = $_POST;
		}
		$base_url = explode('/', $this->_baseUrl);
		array_pop($base_url);
		array_pop($base_url);
		$base_url = implode('/', $base_url).'/';
		
		$fields['items'] = empty($fields['items']) ? $size : $fields['items'];
		$fields['startrow'] = ($page - 1) * $size;
		$fields = $this->_parse($fields);
		include_once DISCUZ_ROOT.'./source/class/block/forum/block_thread.php';
		$block_thread = new block_thread();
		$data = $block_thread->getdata(null, $fields);
		$list = array();
		foreach (array_values($data['data']) as $key=>$item)
		{
			$list[$key]['id']			= $item['id'];
			$list[$key]['title']		= $item['title'];
			$list[$key]['url']			= $base_url . $item['url'];
			$list[$key]['thumb']		= (empty($item['pic']) || ($item['pic'] == 'static/image/common/nophoto.gif')) ? '' : $base_url . $item['pic'];
			$list[$key]['time']			= $item['fields']['dateline'];
			$list[$key]['date']			= date('Y-m-d H:i:s', $item['fields']['dateline']);
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

	/**
	 * Required by API
	 *
	 * @return array
	 */
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
		$where = array(
			'tid'	=> $contentid,
			'first'	=> 1
		);
		$data = DB::fetch_first('SELECT %i FROM %t WHERE %i LIMIT 1', array(
			implode(',', DB::quote_field(array('subject', 'message'))),
			'forum_post',
			DB::implode($where, ' AND ')
		));
		if (empty($data))
		{
			return array('state'=>false, 'error'=>'帖子不存在或无法读取');
		}
		include_once(DISCUZ_ROOT.'/source/function/function_discuzcode.php');
		$data['message'] = discuzcode($data['message'], 2);
		$data['message'] = preg_replace('#\[attach\](\d)+\[/attach\]#', '', $data['message']);
		return array(
			'state'		=> true,
			'title'		=> $data['subject'],
			'content'	=> $data['message']
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

	protected function _parse( $data)
	{
		empty($data['fids']) || $data['fids'] = explode(',', $data['fids']);
		empty($data['sortids']) || $data['sortids'] = explode(',', $data['sortids']);
		empty($data['digest']) || $data['digest'] = explode(',', $data['digest']);
		empty($data['stick']) || $data['stick'] = explode(',', $data['stick']);
		empty($data['special']) || $data['special'] = explode(',', $data['special']);
		return $data;
	}
}