<?php
/**
 * 订阅记录
 *
 * @author zhouqingfeng
 * @copyright 2010 (c) CmsTop
 * @date 2012/06/19
 * @version $Id$
 */

class model_admin_payview_power extends model implements SplSubject
{
	private $userid, $data = array(), $payview_category, $setting, $category, $user_pvcid;
	function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'payview_power';
		$this->_primary = 'userid';
		$this->_fields = array('oid', 'pvcid', 'userid', 'username', 'starttime', 'endtime', 'updatedby', 'updated');

		$this->_readonly = array();
		$this->_create_autofill = array('updated'=>TIME, 'updatedby'=>$this->_userid);
		$this->_update_autofill = array('updated'=>TIME, 'updatedby'=>$this->_userid);
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array();
		$this->payview_category = table('payview_category');
		$this->setting = setting::get('payview');
		$this->category = subcategory($this->setting['catid']);
	}

	
	function get($oid, $fields = '*')
	{
		
		$this->oid = intval($oid);
		
		$this->event = 'before_get';
		$this->notify();

		$where = 'WHERE oid='.$oid;
		$this->data = $this->db->get("SELECT $fields FROM `#table_payview_power` $where");
		$this->data = $this->_output($this->data);
        if ($this->data)
        {
			$this->event = 'after_get';
			$this->notify();
        }
        
		return $this->data;
	}
	
	function get_by($field, $value, $fields = '*', $order = null)
	{
		
		$this->event = 'before_get';
		$this->notify();

		$this->data = parent::get_by($field, $value, $fields, $order);
		$this->data = $this->_output($this->data);
        if ($this->data)
        {
			$this->event = 'after_get';
			$this->notify();
        }
        
		return $this->data;
	}

	function ls($where = null, $fields = '*', $order = '`updated` DESC', $page = null, $pagesize = null)
	{
		is_array($where) && $where = implode(' AND ', $where);
		$this->where = $where;
		$where && $where = "WHERE $where";
		$order && $order = "ORDER BY $order";
		$this->page = $page;
		$this->pagesize = $pagesize;
		$this->event = 'before_ls';
		$this->notify();
		//$groupby = 'GROUP BY userid';
		$this->data = $this->db->page("SELECT $fields FROM `#table_payview_power` c $where $groupby $order", $page, $pagesize);
		$this->_output($this->data);
		$this->event = 'after_ls';
		$this->notify();
		return $this->data;
	}
	function total()
	{
		return $this->count($this->where);
	}

	/**
	 * 格式化输出数据 
	 *
	 * @param $data 是一条或多条记录
	 */
	public function _output(& $data)
	{
		if(!$data) return array();
		if(!$data[0]) {
			$flag = true;
			$data = array($data);
		}

		foreach ($data as & $r)
		{
			$r['updated'] = $r['updated'] ? date('Y-m-d H:i:s', $r['updated']) : 'Unknow';
			$r['updatedname'] = $r['updatedby'] ?  $this->get_username($r['updatedby']) : 'Unknow';
			$r['title'] = $this->payview_category[$r['pvcid']]['title'].'('.$this->payview_category[$r['pvcid']]['timetype'].'个月)';
			$r['starttime'] = $r['starttime'] ? date('Y-m-d', $r['starttime']) : 'Unknow';
			$r['endtime'] = $r['endtime'] ? date('Y-m-d', $r['endtime']) : 'Unknow';
			
			$r['timetype'] = $this->payview_category[$r['pvcid']]['timetype'];
			$r['categorys'] = $this->payview_category[$r['pvcid']]['categorys'];
			$categorys = explode(',',$r['categorys']);
			$categorys_name = array();
			$categorys_temp = array();
			foreach ($categorys as $cat)
			{
				$temp = array();
				$temp['catid'] = $this->category[$cat]['catid'];
				$temp['name'] = $this->category[$cat]['name'];
				$temp['url'] = $this->category[$cat]['url'];
				$categorys_temp[] = $temp;
				$categorys_name[$cat] = $this->category[$cat]['name'];
			}
			$r['categorys'] = $categorys_temp;
			$r['categorys_name'] = implode(',',$categorys_name);
		}
		if($flag) $data = $data[0];
		return $data;
	}

	
	function edit($data)
	{
    	$this->data = $data;
    	$this->input($this->data);
    	
    	$this->event = 'before_edit';
    	$this->notify();
		$data = $this->filter_array($this->data, $this->_fields);console($data);
		$result = $this->db->insert("REPLACE INTO `$this->_table` (`".implode('`,`', array_keys($data))."`) VALUES(".implode(',', array_fill(0, count($data), '?')).")", array_values($data));
		if($result)
		{
			$this->remove_power_cache($data['userid']);
			$this->event = 'after_edit';
			$this->notify();
		}
		return $result;
	}
	
    public function delete($oid)
    {
    	$oid = id_format($oid);
		if ($oid === false)
		{
			$this->error = "$oid 格式不正确";
			return false;
		}
		
		$this->event = 'before_delete';
		$this->notify();
		
		$data = $this->db->get("SELECT * FROM `$this->_table` WHERE `oid` = $oid");
		if($data)
		{
			$sql = "DELETE FROM `$this->_table` WHERE `oid` = $oid ";
			$result = $this->db->query($sql);
			if ($result)
			{
				$this->remove_power_cache($data['userid']);
				$this->event = 'after_delete';
				$this->notify();
			}
		}
		return $result;
    }

	// 获取用户名
	public function get_username($userid)
	{
		$r = $this->db->get('select username from #table_member where userid=?', array($userid));
		return $r['username'];
	}
	
	// 获取用户ID
	public function get_userid($username)
	{
		$r = $this->db->get('select userid from #table_member where username=?', array($username));
		return $r['userid'];
	}


	public function input(& $r)
	{
		$r['updated'] = TIME;
		$r['updatedby'] = $this->_userid;
		!is_numeric($r['starttime']) && $r['starttime'] = strtotime($r['starttime'].' 00:00:00');
		!is_numeric($r['endtime']) && $r['endtime'] = strtotime($r['endtime'].' 23:59:59');
		$r['username'] && empty($r['userid']) &&  $r['userid'] = $this->get_userid($r['username']);
	}
	
	/**
	 * 格式化权限输出数据 
	 *
	 * @param $data 是一条或多条记录
	 */
	public function power_cache_output(& $data)
	{
		if(!$data) return array();
		if(!$data[0]) {
			$flag = true;
			$data = array($data);
		}

		foreach ($data as & $r)
		{
			$r['timetype'] = $this->payview_category[$r['pvcid']]['timetype'];
			$r['title'] = $this->payview_category[$r['pvcid']]['title'].'('.$this->payview_category[$r['pvcid']]['timetype'].'个月)';
			$r['categorys'] = $this->payview_category[$r['pvcid']]['categorys'];
			$categorys = explode(',',$r['categorys']);
			$categorys_name = array();
			$categorys_temp = array();
			$power_catids = '';
			foreach ($categorys as $cat)
			{
				$temp = array();
				$temp['catid'] = $this->category[$cat]['catid'];
				$temp['childids'] = $this->category[$cat]['childids'];
				$temp['name'] = $this->category[$cat]['name'];
				$temp['url'] = $this->category[$cat]['url'];
				$categorys_temp[] = $temp;
				$categorys_name[$cat] = $this->category[$cat]['name'];
				$power_catids .= $this->category[$cat]['childids'] ? $this->category[$cat]['catid'].','.$this->category[$cat]['childids'].',' : $this->category[$cat]['catid'].',';
			}
			$this->user_pvcid[] = $r['pvcid'];
			$r['categorys'] = $categorys_temp;
			$r['categorys_name'] = implode(',',$categorys_name);
			$power_catids = substr($power_catids,0,-1);
			$r['power_catids'] = explode(',',$power_catids);
		}
		if($flag) $data = $data[0];
		return $data;
	}
	
	//获取用户权限缓存
	public function get_power_cache($userid, $cache_time)
	{
		$cache_time = $cache_time ? $cache_time : ($this->setting['cache_time'] ? $this->setting['cache_time'] : 86400);
		$cache = & factory::cache();
		$user_power_cache = $cache->get('user_power_cache'.$userid);
		if($user_power_cache == false) {
			$this->user_pvcid = array();
			$where = "userid=$userid AND endtime>".TIME;
			$user_power_cache = $this->select($where);
			$this->power_cache_output($user_power_cache);
			$user_power_cache[0]['user_pvcid'] = $this->user_pvcid;
			$cache->set('user_power_cache'.$userid, $user_power_cache, $cache_time);
		}
		return $user_power_cache;
	}
	
	//删除用户权限缓存
	public function remove_power_cache($userid)
	{
		$cache = & factory::cache();
		$cache->set('user_power_cache'.$userid, false);
	}
	
	//重载权限数据
	public function reload()
	{
		//清空表
		$this->db->query("TRUNCATE TABLE `#table_payview_power`");
		//获取未过期订单，全部载入
		$result = $this->db->select("SELECT * FROM `#table_payview_order` WHERE status=1 AND endtime>".TIME);console($result);
		foreach($result as $data)
		{
			$this->edit($data);
		}
		$this->clear_cache();
	}
	
	//删除权限缓存
	public function clear_cache()
	{
		$cache = & factory::cache();
		$cache->clear();
	}
	
	//删除过期权限
	public function clear_past()
	{
		$sql = "DELETE FROM `$this->_table` WHERE `endtime` < ".TIME;
		$result = $this->db->query($sql);
		$this->clear_cache();
		return $result;
	}
	
    public function attach (SplObserver $observer)
	{
		$this->observers[] = $observer;
	}

	public function detach(SplObserver $observer)
	{
		if($index = array_search($observer, $this->observers, true)) unset($this->observers[$index]);
	}

	public function notify ()
	{
		foreach ($this->observers as $observer)
		{
			$observer->update($this);
		}
	}
}
