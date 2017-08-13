<?php
/**
 * 订阅栏目组
 *
 * @author zhouqingfeng
 * @copyright 2010 (c) CmsTop
 * @date 2012/06/19
 * @version $Id$
 */

class model_admin_payview_category extends model implements SplSubject
{
	private $pvcid, $data = array(), $category, $setting, $where;
	function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'payview_category';
		$this->_primary = 'pvcid';
		$this->_fields = array(
			'title', 'timetype', 'fee', 'logo', 'description', 'categorys', 'type',
			'createdby', 'created', 'updatedby', 'updated', 'disabled'
		);

		$this->_readonly = array('pvcid', 'created', 'createdby');
		$this->_create_autofill = array('created'=>TIME, 'createdby'=>$this->_userid);
		$this->_update_autofill = array('updated'=>TIME, 'updatedby'=>$this->_userid);
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array();
		$this->setting = setting::get('payview');
		$this->category = subcategory($this->setting['catid']);
	}
	
	function get($pvcid, $fields = '*')
	{
		
		$this->pvcid = intval($pvcid);
		
		$this->event = 'before_get';
		$this->notify();

		$this->data = parent::get($this->pvcid, $fields);
		$this->output($this->data);
        if ($this->data)
        {
			$this->event = 'after_get';
			$this->notify();
        }
        
		return $this->data;
	}
	
	function get_by($field, $value, $fields = '*', $order = null)
	{
		
		$this->pvcid = intval($pvcid);
		
		$this->event = 'before_get';
		$this->notify();

		$this->data = parent::get_by($field, $value, $fields, $order);
		$this->output($this->data);
        if ($this->data)
        {
			$this->event = 'after_get';
			$this->notify();
        }
        
		return $this->data;
	}
	
	
	function ls($where = null, $fields = '*', $order = '`pvcid` DESC', $page = null, $pagesize = null)
	{
		$where && $where = "WHERE $where";
		$this->where = $where;
		$order && $order = "ORDER BY $order";
		$this->page = $page;
		$this->pagesize = $pagesize;
		$this->event = 'before_ls';
		$this->notify();
		$this->data = $this->db->page("SELECT $fields FROM `#table_payview_category` c $where $order", $page, $pagesize);
		$this->output($this->data);
		$this->event = 'after_ls';
		$this->notify();
		return $this->data;
	}
	function total()
	{
		return $this->count($this->where);
	}
	
	function add($data)
	{
    	$this->data = $data;
    	$this->input($this->data);
    	
    	$this->event = 'before_add';
    	$this->notify();
		$data = $this->filter_array($this->data, $this->_fields);
		if($this->pvcid = $this->insert($data))
		{
			$this->event = 'after_add';
			$this->notify();
		}
		return $this->pvcid;
	}
	
	function edit($pvcid, $data)
	{
    	$this->pvcid = id_format($pvcid);
    	$this->data = $data;
    	$this->input($this->data);
    	
    	$this->event = 'before_edit';
    	$this->notify();
    	
    	$data = $this->filter_array($this->data, $this->_fields);
    	
    	if($result = $this->update($data, $this->pvcid))
    	{
    		$this->event = "after_edit";
    		$this->notify();
    	}
    	return $result;
	
	}
	
    public function disabled($pvcid, $disabled=1)
    {
    	$pvcid = id_format($pvcid);
		$disabled = $disabled ? 1 : 0;
		if ($pvcid === false)
		{
			$this->error = "$pvcid 格式不正确";
			return false;
		}
		if (is_array($pvcid)) return array_map(array($this, 'delete'), $pvcid);
		
		$this->pvcid = $pvcid;
		$this->event = 'before_disabled';
		$this->notify();
		
		$sql = "UPDATE `$this->_table` SET `disabled`=$disabled WHERE pvcid = $pvcid";
		$result = $this->db->query($sql);
		if ($result)
		{
			$this->event = 'after_disabled';
			$this->notify();
		}
		return $result;
    }
	
    public function delete($pvcid)
    {
    	$pvcid = id_format($pvcid);
		if ($pvcid === false)
		{
			$this->error = "$pvcid 格式不正确";
			return false;
		}
		if (is_array($pvcid)) return array_map(array($this, 'delete'), $pvcid);
		
		$this->pvcid = $pvcid;
		$this->event = 'before_delete';
		$this->notify();
		
		$result = parent::delete($this->pvcid);
		if ($result)
		{
			$this->event = 'after_delete';
			$this->notify();
		}
		return $result;
    }
    
	// 获取用户名
	public function get_username($userid)
	{
		$r = $this->db->get('select username from #table_member where userid=?', array($userid));
		return $r['username'];
	}
	
	public function input(& $r)
	{
		$r['categorys'] = $r['catid'];
		//把栏目与栏目组对应关系写进categorys.php文件
		$categorys = cache_read('categorys.php', ROOT_PATH.'apps'.DS.'payview'.DS.'config'.DS);
		if(!is_array($categorys)) $categorys = array();
		foreach($categorys as $id=>$category)
		{
			unset($categorys[$id][$r['pvcid']]);
		}
		$catid = explode(',',$r['catid']);
		foreach($catid as $id)
		{
			$categorys[$id][$r['pvcid']] = $r['title'];
		}
		cache_write('categorys.php', $categorys, ROOT_PATH.'apps'.DS.'payview'.DS.'config'.DS);
	}

	/**
	 * 格式化输出数据 
	 *
	 * @param $data 是一条或多条记录
	 */
	private function output(& $data)
	{
		if(!$data) return array();
		if(!$data[0]) {
			$flag = true;
			$data = array($data);
		}

		foreach ($data as & $r)
		{
			$r['created'] = $r['created'] ? date('Y-m-d H:i:s', $r['created']) : 'Unknow';
			$r['createdname'] = $r['createdby'] ?  $this->get_username($r['createdby']) : 'Unknow';
			$r['updated'] = $r['updated'] ? date('Y-m-d H:i:s', $r['updated']) : 'Unknow';
			$r['updatedname'] = $r['updatedby'] ?  $this->get_username($r['updatedby']) : 'Unknow';
			$r['catid'] = $r['categorys'];
			$categorys = explode(',',$r['categorys']);
			$categorys_name = array();
			$categorys_temp = array();
			foreach ($categorys as $cat)
			{
				$temp = array();
				$temp['catid'] = $this->category[$cat]['catid'];
				$temp['name'] = $this->category[$cat]['name'];
				$temp['url'] = $this->category[$cat]['url'];
				$temp['childids'] = $this->category[$cat]['childids'];
				$categorys_temp[] = $temp;
				$categorys_name[$cat] = $this->category[$cat]['name'];
			}
			$r['categorys'] = $categorys_temp;
			$r['categorys_name'] = implode(',',$categorys_name);
			$r['disabled_text'] = $r['disabled']==0 ? '启用' : '禁用';
			$r['type_text'] = $r['type']==0 ? '线上订阅' : '线下订阅';
		}
		if($flag) $data = $data[0];
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
