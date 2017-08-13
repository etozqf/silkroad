<?php

class model_admin_content extends model implements SplSubject
{
	public $contentid, $catid, $category, $action, $data = array(), $total = 0, $section_url = null;
	       
	private $observers = array();
	
	function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'content';
		$this->_primary = 'contentid';
		$this->_fields = array('contentid', 'catid', 'modelid', 'title', 'subtitle', 'color', 'thumb', 'tags', 'sourceid', 'source_title', 'source_link', 'url', 'weight', 'status', 'status_old', 'created', 'createdby', 'published', 'publishedby', 'unpublished', 'unpublishedby', 'modified', 'modifiedby', 'checked', 'checkedby', 'locked', 'lockedby', 'noted', 'notedby', 'note', 'workflow_step', 'workflow_roleid', 'iscontribute', 'allowcomment', 'comments', 'pv', 'spaceid', 'related','score', 'topicid', 'tweeted', 'ischarge','yuanquid');
		$this->_readonly = array('contentid', 'modelid', 'created', 'createdby');
		$this->_create_autofill = array('created'=>TIME, 'createdby'=>$this->_userid);
		$this->_update_autofill = array('modified'=>TIME, 'modifiedby'=>$this->_userid);
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array(
            'title' => array(
                'not_empty' => array('标题不能为空'),
                'max_length' => array(200, '标题不得超过200字节')
            ),
            'catid' => array(
                'not_empty' => array('请选择栏目'),
                'max_length' => array(5, '栏目ID不得超过5字节'),
                'is_numeric' => array('栏目ID格式不正确')
            ),
            'modelid'=> array(
                'not_empty' => array('请选择内容模型'),
                'max_length' => array(3, '内容模型ID不得超过3字节'),
                'is_numeric' => array('内容模型ID格式不正确'),
                'min' => array(1, '请选择内容模型'),
                'max' => array(255, '内容模型ID不得大于255'),
            ),
            'status' => array(
                'is_numeric' => array('内容状态必须是数字'),
            ),
        );
		$this->category = table('category');
		$this->section_url = loader::model('admin/section_url','page');
	}
	
	function get($contentid, $fields = '*', $action = null)
	{
		if (!in_array($action, array(null, 'get', 'view', 'show'))) return false;
		
		$this->contentid = intval($contentid);
		$this->fields = $fields;
		$this->action = $action;
		
		$this->event = 'before_get';
		$this->notify();

		$this->data = parent::get($this->contentid, $this->fields);
        if ($this->data)
        {
			$this->event = 'after_get';
			$this->notify();
        }
        
       // if ($action == 'get') $this->data = htmlspecialchars_deep($this->data);
        
		return $this->data;
	}
	
	function add($data)
	{
        // 判断栏目是否存在
        if (is_null($cate = $this->category[$data['catid']]))
        {
            $this->error	= '未选择栏目或栏目不存在';
            return false;
        }

        $catid = intval($data['catid']);
        if (!array_key_exists($data['modelid'], category_model($catid)))
        {
            $this->error = '该栏目下不允许发布'.table('model', intval($data['modelid']), 'name');
            return false;
        }

        // 最大稿件限制
        global $cmstop;
        if ($cmstop->client == 'admin')
        {
            $contents = factory::db()->get("SELECT COUNT(*) AS `total` FROM `#table_content`");
            $contents && license(array('contents' => intval($contents['total'])), $cmstop);
        }

		unset($data['workflow_step'], $data['workflow_roleid']);

        // 之前采用的是数据库默认值，导致很多依赖状态值的插件工作不能
        if (! isset($data['status'])) $data['status'] = 3;
    //丝路网项目新加 start 拼接yuanquid,传过来的字符串是以;分隔的
		$db = & factory::db();
		$data['yuanqu'] = str_replace(';',';',$data['yuanqu']);
    $data['yuanqu'] = str_replace('；',';',$data['yuanqu']);
    $arr = explode(';',$data['yuanqu']);
    $i = count($arr);
    $res='';
    foreach($arr as $k=>$v){
    	$sql = "select contentid from cmstop_content where title='{$v}'";
    	$m = $db->get($sql);
			if($k<$i-1){
    		$res .= $m['contentid'].',';
    	}elseif($k==$i-1){
				$res .= $m['contentid'];
    	}
    	
    }
    $data['yuanquid'] = $res;
    //丝路网项目新加 end
		$this->input($data);

		$this->data = $data;

		$this->event = 'before_add';
		$this->notify();
		
		empty($data['thumb']) && $data['thumb'] = null;
		$data = $this->filter_array($this->data, array('catid', 'modelid', 'title', 'subtitle', 'color', 'thumb', 'tags', 'sourceid', 'source_title', 'source_link', 'url','weight', 'created', 'createdby','published', 'publishedby', 'unpublished', 'unpublishedby', 'status', 'workflow_step', 'workflow_roleid', 'iscontribute', 'allowcomment', 'related','spaceid', 'ischarge','yuanquid'));
   

		$this->contentid = $this->insert($data);
		
		if ($this->contentid)
		{
			$this->event = 'after_add';
			$this->notify();
		}
		return $this->contentid;
	}
	
	function edit($contentid, $data)
	{
        if (isset($data['catid']) && isset($data['modelid']))
        {
            $catid = intval($data['catid']);
            if (!array_key_exists($data['modelid'], category_model($catid)))
            {
                $this->error = '该栏目下不允许发布'.table('model', intval($data['modelid']), 'name');
                return false;
            }
        }

		$this->contentid = intval($contentid);
		$this->input($data);
		$oldpublished = strtotime($data['oldpublished']);
		$newpublished = strtotime(date('Y-m-d', $data['published']));
		if ($data['oldpublished'] && $data['published'] && $oldpublished < $newpublished) {
			$data['status'] = 5;
		}
		//丝路网项目新加 start 拼接yuanquid,传过来的字符串是以;分隔的
		$db = & factory::db();
		$data['yuanqu'] = str_replace(';',';',$data['yuanqu']);
    $data['yuanqu'] = str_replace('；',';',$data['yuanqu']);
    $arr = explode(';',$data['yuanqu']);
    $i = count($arr);
    $res='';
    foreach($arr as $k=>$v){
    	$sql = "select contentid from cmstop_content where title='{$v}'";
    	$m = $db->get($sql);
    	if($k<$i-1){
    		$res .= $m['contentid'].',';
    	}elseif($k==$i-1){
				$res .= $m['contentid'];
    	}
    	
    }
    $data['yuanquid'] = $res;
    //丝路网项目新加 end
		$this->data = $data;
		
		$this->event = 'before_edit';
		$this->notify();

		empty($data['thumb']) && $data['thumb'] = null; 
		$data = $this->filter_array($this->data, array('catid', 'modelid', 'title', 'subtitle', 'color', 'thumb', 'tags', 'sourceid', 'source_title', 'source_link', 'url', 'weight', 'published','publishedy','unpublished', 'unpublishedby', 'modified', 'modifiedby', 'status', 'status_old', 'allowcomment', 'related','spaceid','ischarge','yuanquid'));
        $result = $this->update($data, $this->contentid);
		if ($result)
		{
			$this->event = 'after_edit';
			$this->notify();
			if ($oldpublished < $newpublished) {
				$path = PUBLIC_PATH.'www/'.date('Y', $oldpublished).'/'.date('md', $oldpublished).'/'.$contentid.'.shtml';
				if (file_exists($path)) {
					@unlink($path);
				}
			}
		}
        return $result;
	}
	
	function delete($contentid)
	{
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_array($contentid)) return array_map(array($this, 'delete'), $contentid);
		
		$this->contentid = $contentid;
		$this->event = 'before_delete';
		$this->notify();
		
		$result = parent::delete($this->contentid);
		if ($result)
		{
			$this->event = 'after_delete';
			$this->notify();
		}
		return $result;
	}
	
	function clear($catid = null)
	{
		$where = '`status`=0';
		if ($catid) $where .= ' AND `catid`='.intval($catid);
		$contentid = $this->gets_field('contentid', $where);
        return array_map(array($this, 'delete'), $contentid);
	}
	
	function clear_contribution()
	{
		$where = '`status`=0 AND `iscontribute`=1';
		$contentid = $this->gets_field('contentid', $where);
		return array_map(array($this, 'delete'), $contentid);
	}
	
	function remove($contentid)
	{
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_array($contentid)) return array_map(array($this, 'remove'), $contentid);

		$this->contentid = $contentid;
		$this->event = 'before_remove';
		$this->notify();

        $status = $this->get_field('status', $this->contentid);
		$data = array('status'=>0, 'status_old'=>$status, 'unpublished'=>TIME, 'unpublishedby'=>$this->_userid);
		$result = $this->update($data, $this->contentid);
		if ($result)
		{
			$this->event = 'after_remove';
			$this->notify();
		}
		return $result;
	}
	
	function restore($contentid)
	{
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_array($contentid)) return array_map(array($this, 'restore'), $contentid);
		
		$this->contentid = $contentid;
		$this->event = 'before_restore';
		$this->notify();

        $status_old = $this->get_field('status_old', $this->contentid);
        $data = array(
            'status' => $status_old ? $status_old : 6,
            'status_old' => 0,
            'unpublished' => null,
            'unpublishedby' => null
        );
        $result = $this->update($data, $this->contentid);
		if ($result)
		{
			$this->event = 'after_restore';
			$this->notify();
		}
		return $result;
	}

	function restores($catid = null)
	{
		$where = '`status`=0';
		if ($catid) $where .= ' AND `catid`='.intval($catid);
		$contentid = $this->gets_field('contentid', $where);
        return array_map(array($this, 'restore'), $contentid);
	}

	function copy($contentid, $catid)
	{
		$this->contentid = $contentid;
		$this->catid = $catid;
		if (!isset($this->category[$catid]))
		{
			$this->error = "栏目ID $catid 不存在";
			return false;
		}
		
		$this->event = 'before_copy';
		$this->notify();
		
		$contentid = intval($contentid);
		$id = $this->copy_by_id($contentid, array('catid'=>$catid, 'locked'=>null, 'lockedby'=>null));
		if (!$id)
		{
			$this->error = "内容ID $contentid 不存在";
			return false;
		}
        //导入属性选择
        $this->copy_property($contentid,$id);
		$this->contentid = $id;

		//导入扩展字段		
		$pid = $this->db->get("SELECT projectid FROM #table_category_field AS a LEFT JOIN #table_content as b ON a.catid=b.catid WHERE contentid=$contentid");
		$pidcopy = $this->db->get("SELECT projectid FROM #table_category_field WHERE catid = $catid");
		if($pidcopy[projectid] == $pid[projectid]){
			$this->copy_meta($contentid, $id);
			$this->contentid = $id;
		}
		
		$this->event = 'after_copy';
		$this->notify();
		return $id;
	}

	function reference($contentid, $catid)
	{
		if (!isset($this->category[$catid]))
		{
			$this->error = "栏目ID $catid 不存在";
			return false;
		}
		$contentid = id_format($contentid);
		$this->contentid = $contentid;
		$this->catid = $catid;
		if (is_array($contentid))
		{
			$ids = array();
			foreach ($contentid as $id)
			{
				$ids[] = $this->reference($id, $catid);
			}
			return $ids;
		}
		else 
		{
			$modelid = table('content', $contentid, 'modelid');
			$modelalias = table('model', $modelid, 'alias');
			if(!priv::aca($modelalias, $modelalias, 'reference')) return true;
			
			$this->event = 'before_reference';
			$this->notify();
			
			$id = $this->copy_by_id($contentid, array('catid'=>$catid, 'modelid'=>3, 'locked'=>null, 'lockedby'=>null, 'pv'=>0));
			if (!$id)
			{
				$this->error = "内容ID $contentid 不存在";
				return false;
			}

			// 导入引用表
			$model_model = loader::model('admin/'.$modelalias,$modelalias);
			$description = $model_model->get_field('description',$contentid);
            $data = array(
                'contentid'=>$id,
                'referenceid'=>$contentid,
                'description'=>$description
            );
            loader::model('admin/link','link')->insert($data);

            //导入属性选择
            $this->copy_property($contentid,$id);
			
			$this->event = 'after_reference';
			$this->notify();
			
			return $id;
		}
	}
	
	function move($contentid, $catid)
	{
		if (!isset($this->category[$catid]))
		{
			$this->error = "栏目ID $catid 不存在";
			return false;
		}
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_array($contentid))
		{
			foreach ($contentid as $id)
			{
				$result = $this->move($id, $catid);
			}
		}
		else 
		{
			$modelid = table('content', $contentid, 'modelid');
			$modelalias = table('model', $modelid, 'alias');
			if(!priv::aca($modelalias, $modelalias, 'move')) return true;
			
			$this->contentid = intval($contentid);
			$this->catid = $catid;

			$this->event = 'before_move';
			$this->notify();

			//移动内容时处理扩展字段
			$pid = $this->db->get("SELECT projectid FROM #table_category_field AS a LEFT JOIN #table_content as b ON a.catid=b.catid WHERE contentid=$contentid");
			$pidcopy = $this->db->get("SELECT projectid FROM #table_category_field WHERE catid = $catid");
			if($pidcopy[projectid] != $pid[projectid]){
				$this->db->update("UPDATE #table_content_meta set data= '' WHERE contentid=$contentid");
			}						

			$result = $this->set_field('catid', $this->catid, $this->contentid);


			if ($result)
			{
				$this->event = 'after_move';
				$this->notify();
			}
		}
		return $result;
	}
	
	function move_by_catid($sourceid, $targetid)
	{
		if (!isset($this->category[$sourceid]))
		{
			$this->error = "栏目ID $sourceid 不存在";
			return false;
		}
		if (!isset($this->category[$targetid]))
		{
			$this->error = "栏目ID $sourceid 不存在";
			return false;
		}
		if ($this->category[$targetid]['childids'])
		{
			$this->error = "目标栏目必须是终极栏目";
			return false;
		}
		
		@set_time_limit(600);
		
		$count = 0;
		$model = table('model');
		foreach ($model as $modelid=>$m)
		{				
			$contentid = $this->gets_field('contentid', "catid=$sourceid AND modelid=$modelid");
			if ($contentid)
			{
				if ($m['alias'] == 'link')
				{
					$this->move($contentid, $targetid);
				}
				else
				{
					loader::model('admin/'.$m['alias'], $m['alias'])->move($contentid, $targetid);
				}
				$count += count($contentid);
			}
		}
		return $count;
	}
	
	function approve($contentid)
	{
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_array($contentid)) return array_map(array($this, 'approve'), $contentid);
		
		$this->contentid = $contentid;
		$this->data = $this->get($this->contentid, '`catid`, `workflow_step`, `workflow_roleid`');
		$this->approve = array('status'=>3, 'checked'=>null, 'checkedby'=>null, 'published'=>null, 'publishedby'=>null, 'workflow_step'=>null, 'workflow_roleid'=>null);

		$this->event = 'before_approve';
		$this->notify();
		
		$result = $this->update($this->approve, $this->contentid);
		if ($result)
		{
			$this->event = 'after_approve';
			$this->notify();
		}
		return $result;
	}
	
	function pass($contentid)
	{
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_array($contentid)) return array_map(array($this, 'pass'), $contentid);
		
		$this->contentid = $contentid;
		$this->data = $this->get($this->contentid, '`catid`, `published`, `workflow_step`, `workflow_roleid`');

        $published = $this->data['published'];

		$pass = array('status'=>6, 'checked'=>TIME, 'checkedby'=>$this->_userid, 'publishedby'=>$this->_userid,'published'=>TIME);

		if ($published && $published > TIME)
        {
            if ($pass['status'] == 6)
            {
                $pass['status'] = 5;
            }
            $pass['published'] = $published;
        }
        
		$this->pass = $pass;
		$this->event = 'before_pass';
		$this->notify();

		$result = $this->update($this->pass, $this->contentid);
		if ($result)
		{
			$this->event = 'after_pass';
			$this->notify();
		}
		return $result;
	}
	
	function reject($contentid)
	{
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_array($contentid)) return array_map(array($this, 'reject'), $contentid);
		
		$this->contentid = $contentid;
		$this->data = $this->get($this->contentid, '`catid`, `workflow_step`, `workflow_roleid`');
        $this->reject = array('status'=>2, 'workflow_step'=>null, 'workflow_roleid'=>null);
        
		$this->event = 'before_reject';
		$this->notify();
		
		$result = $this->update($this->reject, $this->contentid);
		if ($result)
		{
			$this->event = 'after_reject';
			$this->notify();
		}
		return $result;
	}
	
	function islock($contentid, $locked = false, $lockedby = false)
	{
		$contentid = intval($contentid);
		if ($locked === false)
		{
			$r = factory::cache()->get('content:lock:'.$contentid);
			$locked = $r['locked'];
			$lockedby = $r['lockedby'];
		}
		if ($lockedby != $this->_userid && $locked > TIME-60) return true;
		if ($locked) $this->unlock($contentid);
		return false;
	}
	
	function lock($contentid)
	{
        factory::cache()->set('content:lock:'.$contentid, array(
        	'locked' => TIME,
        	'lockedby' => $this->_userid
        ), 60);
        return true;
	}
	
	function unlock($contentid)
	{
        factory::cache()->rm('content:lock:'.$contentid);
        return true;
	}
	
	function publish($contentid, $time = null)
	{
		$contentid = id_format($contentid);
		
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_null($time)) $time = TIME;
		if (is_array($contentid)) return array_map(array($this, 'publish'), $contentid, array_fill(0, count($contentid), $time));
		
		$this->contentid = $contentid;
		$this->time = $time;
		$this->event = 'before_publish';
		$this->notify();

		// 检测工作流
		if (!$this->_check_workflow()) {
			$this->error = '内容处于工作流审核中';
			return false;
		}
		
		$data = array('status'=>6, 'published'=>$time,'publishedby'=>$this->_userid,'unpublished'=>null, 'unpublishedby'=>null);

		$result = $this->update($data, $this->contentid);
		if ($result)
		{
			$this->event = 'after_publish';
			$this->notify();
		}
		return $result;
	}
	
	function unpublish($contentid, $time = null)
	{
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_null($time)) $time = TIME;
		if (is_array($contentid)) return array_map(array($this, 'unpublish'), $contentid, array_fill(0, count($contentid), $time));
		
		$this->contentid = $contentid;
		$this->time = $time;
		$this->event = 'before_unpublish';
		$this->notify();
		
		$data = array('status'=>4, 'unpublished'=>$this->time, 'unpublishedby'=>$this->_userid);
		$result = $this->update($data, $this->contentid);
		if ($result)
		{
			$this->event = 'after_unpublish';
			$this->notify();
		}
		return $result;
	}

	function ls($where = null, $fields = '*', $order = 'c.`contentid` DESC', $page = null, $pagesize = null)
	{
		$this->where = $where;
		$this->fields = $fields;
		$this->order = $order;
		$this->page = $page;
		$this->pagesize = $pagesize;
		$this->action = 'ls';
		$start = microtime(1);
		$this->event = 'before_ls';
		$this->notify();
		if (is_array($this->where)) $this->where = $this->where($this->where);
		$r = $this->db->get("SELECT count(c.`contentid`) AS total FROM `#table_content` c $this->where");
		$this->total = $r['total'];
		if (!$this->total)
		{
		    return array();
		}
		$data = $this->db->page("SELECT c.`contentid` FROM `#table_content` c $this->where ORDER BY $order", $page, $pagesize);
		$query = array();
		foreach ($data as &$v)
		{
		    $query[] = "SELECT * FROM `#table_content` WHERE contentid=".$v['contentid'];
		}
		$this->data = $this->db->select(implode(' UNION ALL ', $query));
		$this->_after_select($this->data, true);
		$this->event = 'after_ls';
		$this->notify();
		return $this->data;
	}
	
	function cron_publish($modelid)
	{
		return $this->gets_field('contentid', '`modelid`=? AND `status`=? AND `published`<?', array(intval($modelid), 5, TIME));
	}
	
	function cron_unpublish($modelid)
	{
		return $this->gets_field('contentid', '`modelid`=? AND `status`=? AND `unpublished`<?', array(intval($modelid), 6, TIME));
	}
	
	function template($catid, $modelid)
	{
		if (!isset($this->category[$catid])) return false;
		$model = unserialize($this->category[$catid]['model']);
		return isset($model[$modelid]) ? $model[$modelid]['template'] : false;
	}
	
	function related($catid = null, $modelid = null, $thumb = false, $keywords = null, $page = 1, $pagesize = 30, $time_format = 'Y-m-d', $published = null)
	{
		$where = "`status`=6";
		if ($catid) $where .= " AND `catid` IN ($catid)";
		if ($modelid) $where .= " AND `modelid`=$modelid";
        if ($thumb) $where .= " AND `thumb` <> ''";
		if ($keywords) $where .= ' AND '.where_keywords('title', $keywords);
        if ($published) $where .= ' AND '.where_mintime('published', $published);
		$fields = '`contentid`,`catid`, `title`, `thumb`, `url`, `published` AS `time`, `tweeted`';
		$order = '`contentid` DESC';
		$data = $this->page($where, $fields, $order, $page, $pagesize);
		foreach ($data as $k=>$r)
		{
			$data[$k]['catname'] = $this->category[$r['catid']]['name'];
			$data[$k]['caturl'] = $this->category[$r['catid']]['url'];
			$data[$k]['time'] = date($time_format, $r['time']);
			$data[$k]['title'] = htmlspecialchars($data[$k]['title'], ENT_NOQUOTES);
			$data[$k]['tweeted'] = ($r['tweeted'] > 0 ) ? date('Y-m-d H:i', $r['tweeted']) : false;
		}
		return $data;
	}

	function related_total($catid = null, $modelid = null, $thumb = false, $keywords = null, $published = null)
	{
		$where = "`status`=6";
		if ($catid) $where .= " AND `catid` IN($catid)";
		if ($modelid) $where .= " AND `modelid`=$modelid";
        if ($thumb) $where .= " AND `thumb` <> ''";
		if ($keywords) $where .= ' AND '.where_keywords('title', $keywords);
        if ($published) $where .= ' AND '.where_mintime('published', $published);
		return $this->count($where);
	}

	public function where($where)
	{
		if (isset($where['contentid']) && $where['contentid']) return " WHERE c.`contentid`=".$where['contentid'];
		switch ($where['date'])
		{
			case 'today':
				$where['created_min'] = date('Y-m-d H:i:s', strtotime('today'));
				break;
			case 'yesterday':
				$where['created_min'] = date('Y-m-d H:i:s', strtotime('yesterday'));
				$where['created_max'] = date('Y-m-d H:i:s', strtotime('today'));
				break;
            case 'week':
                $where['created_min'] = date::this_week();
                break;
            case 'month':
                $where['created_min'] = date('Y-m-01 00:00:00', strtotime('this month'));
                break;
		}
		if ($catid = intval(value($where, 'catid')))
		{
			if ($childids = $this->category[$catid]['childids'])
            {
                $catid .= ',' . $childids;
            }
		}
		if ($catids = explode(',', (value($where, 'catids'))))
		{
            if ($catids = array_filter($catids, 'intval'))
            {
                $catid = array();
                foreach ($catids as $_catid)
                {
                    $catid[] = $_catid;
                    if ($childids = $this->category[$_catid]['childids'])
                    {
                        $catid[] = $childids;
                    }
                }
                if ($catid)
                {
                    $catid = explode(',', (implode(',', $catid)));
                    $catid = implode(',', (array_unique($catid)));
                }
            }
		}
		$ischarge = value($where, "ischarge");
		$ischarge == null && $ischarge = 'all';
		if($ischarge != 'all' && in_array($ischarge, array(0, 1))) {
			$condition[] = "c.ischarge=$ischarge";
		}
		if ($catid) $condition[] = "c.catid IN(" . $catid . ")";
		if (isset($where['note']) && $where['note']) $condition[] = "c.note = 1";
        if (isset($where['modelids']) && $where['modelids']) $condition[] = "c.modelid IN(".$where['modelids'].")";
		if (isset($where['status'])) $condition[] = "c.`status`=".intval($where['status']);
		if (isset($where['spaceid'])) $condition[] = "c.`spaceid`=".intval($where['spaceid']);
		if (isset($where['modelid']) && $where['modelid']) $condition[] = "c.`modelid`=".intval($where['modelid']);
		if (isset($where['weight_min']) && $where['weight_min']) $condition[] = "c.`weight`>=".intval($where['weight_min']);
		if (isset($where['weight_max']) && $where['weight_max']) $condition[] = "c.`weight`<=".intval($where['weight_max']);
		if (isset($where['published_min']) && $where['published_min']) $condition[] = where_mintime('c.published', $where['published_min']);
		if (isset($where['published_max']) && $where['published_max']) $condition[] = where_maxtime('c.published', $where['published_max']);
		if (isset($where['unpublished_min']) && $where['unpublished_min']) $condition[] = where_mintime('c.unpublished', $where['unpublished_min']);
		if (isset($where['unpublished_max']) && $where['unpublished_max']) $condition[] = where_maxtime('c.unpublished', $where['unpublished_max']);
		if (isset($where['created_min']) && $where['created_min']) $condition[] = where_mintime('c.created', $where['created_min']);
		if (isset($where['created_max']) && $where['created_max']) $condition[] = where_maxtime('c.created', $where['created_max']);
        if (isset($where['thumb']) && intval($where['thumb']) === 1) $condition[] = "c.`thumb` <> ''";
		if (isset($where['keywords']) && $where['keywords']) $condition[] = where_keywords('c.title', $where['keywords']);

		if (isset($where['createdbyname']) && $where['createdbyname'])
		{
			$createdby = userid($where['createdbyname']);
			if ($createdby) $condition[] = "c.`createdby`=$createdby";
		}
		
		if (isset($where['publishedbyname']) && $where['publishedbyname'])
		{
			$publishedby = userid($where['publishedbyname']);
			if ($publishedby) $condition[] = "c.`publishedby`=$publishedby";
		}
		if (isset($where['unpublishedbyname']) && $where['unpublishedbyname'])
		{
			$unpublishedby = userid($where['unpublishedbyname']);
			if ($unpublishedby) $condition[] = "c.`unpublishedby`=$unpublishedby";
		}
		if (isset($where['sourceid']) && $where['sourceid']) $condition[] = "c.`sourceid`=".intval($where['sourceid']);
		if (isset($where['iscontribute']) && $where['iscontribute']) $condition[] = "c.`iscontribute`=".intval($where['iscontribute']);
		if (isset($where['createdby']) && $where['createdby']) $condition[] = "c.`createdby`=".intval($where['createdby']);
		if (isset($where['workflow_roleid']) && $where['workflow_roleid']) $condition[] = "c.`workflow_roleid`=".intval($where['workflow_roleid']);
		
		$join = $on = array();
		
		if (isset($where['proid']) && $where['proid'])
		{
			$property = table('property');
			$proids = is_numeric($where['proid']) ? array($where['proid']) : explode(',', $where['proid']);
			foreach ($proids as $kid=>$proid)
			{
				$p = 'p'.$kid;
				$join[] = "`#table_content_property` $p";
				$on[] = "c.`contentid`=$p.`contentid`";

				if (empty($property[$proid]['parentids']) && empty($property[$proid]['childids']))
				{
					$condition[] = "$p.`proid`=$proid";
				}
				else
				{
					$table = $property[$proid];
					$proid .= empty($table['parentids']) ? '' : ",{$table[parentids]}";
					$proid .= empty($table['childids']) ? '' : ",{$table[childids]}";
					$condition[] = "$p.`proid` IN ($proid)";
				}
			}
		}
		/*if ($where['status'] == 3 && $this->_roleid != 1) 
		{
			$condition[] = "(c.`workflow_roleid` IS NULL OR c.`workflow_roleid` = ".$this->_roleid.")";
		}*/
		$where = '';
		if ($join) $where .= " LEFT JOIN(".implode(',', $join).") ON(".implode(' AND ', $on).") ";
		$where .= ' WHERE '.implode(' AND ', $condition);
        if($where == ' WHERE ') $where = '';
		return $where;
	}
	
	function _after_select(& $data, $multiple = false)
	{
		if (!$data || is_null($this->action) || $this->action == 'show') return ;
		if ($multiple)
		{
			array_walk($data, array($this, 'output'));
		}
		else 
		{
			$this->output($data);
		}
	}

	private function _parse_section($sections, $url)
	{
		if(!$url) return false;
		$flag = str_replace('/', '', substr($url, 23, -6));
		foreach($sections as $section)
		{
			$str .= $section['pagename'].' > '.$section['sectionname'].'<br />';
$strs = <<<EOF
<a href="javascript:;" onclick="content.section('$url');" tips="已被推送并显示至：<br />
$str "class="title_list section_$flag"><img src="images/section.gif" /></a>
EOF;
		}

		return $strs;
	}

	public function output(& $r)
	{
		if (!$r) return ;
		$r['thumb'] = htmlspecialchars($r['thumb']);
		$r['tags'] = htmlspecialchars($r['tags']);

		$section = $this->_parse_section($this->section_url->get_section_url($r['url']), $r['url']);
		$r['section'] = $section ? $section : '';

		if (isset($r['catid']) && $r['catid'])
		{
			$r['catname'] = $this->category[$r['catid']]['name'];
			$r['caturl'] = $this->category[$r['catid']]['url'];
		}
		if (isset($r['modelid']) && $r['modelid'])
		{
			$m = table('model', $r['modelid']);
			$r['modelname'] = $m['name'];
			$r['modelalias'] = $m['alias'];
		}
		if (isset($r['createdby']) && isset($r['created']) && $r['createdby'] && $r['created'])
		{
			$r['createdbyname'] = username($r['createdby']);
			$r['created'] = date('Y-m-d H:i:s', $r['created']);
		}
		else 
		{
			$r['created'] = $r['createdby'] = $r['createdbyname'] = '';
		}
		
		if (isset($r['modifiedby']) && isset($r['modified']) && $r['modifiedby'] && $r['modified'])
		{
			$r['modifiedbyname'] = username($r['modifiedby']);
			$r['modified'] = date('Y-m-d H:i:s', $r['modified']);
		}
		else 
		{
			$r['modified'] = $r['modifiedby'] = $r['modifiedbyname'] = '';
		}
		
		if (isset($r['checkedby']) && isset($r['checked']) && $r['checkedby'] && $r['checked'])
		{
			$r['checkedbyname'] = username($r['checkedby']);
			$r['checked'] = date('Y-m-d H:i:s', $r['checked']);
		}
		else 
		{
			$r['checked'] = $r['checkedby'] = $r['checkedbyname'] = '';
		}
		
		if (isset($r['publishedby']) && isset($r['published']) && $r['publishedby'] && $r['published'])
		{
			$r['publishedbyname'] = username($r['publishedby']);
			$r['published'] = date('Y-m-d H:i', $r['published']);
		}
		else 
		{
			$r['published'] = $r['publishedby'] = $r['publishedbyname'] = '';
		}
		
		if (isset($r['unpublishedby']) && isset($r['unpublished']) && $r['unpublishedby'] && $r['unpublished'])
		{
			$r['unpublishedbyname'] = username($r['unpublishedby']);
			$r['unpublished'] = date('Y-m-d H:i:s', $r['unpublished']);
		}
		else 
		{
			$r['unpublished'] = $r['unpublishedby'] = $r['unpublishedbyname'] = '';
		}
		
		if (isset($r['lockedby']) && isset($r['locked']) && $r['lockedby'] && $r['locked'] && $this->islock($r['contentid'], $r['locked'], $r['lockedby']))
		{
			$r['lockedbyname'] = username($r['lockedby']);
			$r['locked'] = date('Y-m-d H:i:s', $r['locked']);
			$r['lock'] = '<img src="images/lock.gif" title="'.$r['lockedbyname'].'('.$r['locked'].')" />';
		}
		else 
		{
			$r['lock'] = $r['locked'] = $r['lockedbyname'] = '';
		}
		
		if (isset($r['notedby']) && isset($r['noted']) && $r['notedby'] && $r['noted'])
		{
			$r['notedbyname'] = username($r['notedby']);
			$r['noted'] = date('Y-m-d H:i:s', $r['noted']);
			$r['note'] = '<a href="javascript: content.note('.$r['contentid'].')">'.($r['note'] ? '<img src="images/label_1.gif" alt="新批注 by '.$r['notedbyname'].'('.$r['noted'].')" class="note"/>' : '<img src="images/label_0.gif" alt="批注 by '.$r['notedbyname'].'('.$r['noted'].')" />').'</a>';
		}
		else 
		{
			$r['note'] = $r['noted'] = $r['notedbyname'] = '';
		}		
		if ($r['comments'])
		{
			$r['comment_flag'] = '<a class="dialog-tips" href="javascript:;" onclick="ct.assoc.open(\'?app=comment&controller=comment&action=index&rwkeyword='.$r['topicid'].'\',\'newtab\')" tips="'.$r['comments'].'条评论"><img src="images/dialog.gif" alt="评论" /></a>';
		}
		else
		{
			$r['comment_flag'] = '';
		}
		$r['thumb_icon'] = isset($r['thumb']) && $r['thumb'] ? '<img class="thumb" src="images/thumb.gif" tips="'.htmlspecialchars('<img src="'.thumb($r['thumb'], 300, 300).'" class="floatImg" />').'" /> ' : '';
		
		if($r['score'])
		{
			$data = loader::model('admin/score','system')->get_by_contentid($r['contentid']);
			$r['score'] = '<span class="score" tips="'.$data['comment'].'" style="cursor:pointer;background:url(images/star.gif) 0px '.-16*(5-$r['score']).'px no-repeat">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>'	;
		}
		else 
		{
			$r['score'] = '';
		}
		
		$r['workflow_rolename'] = '';
		if (isset($r['workflow_roleid']) && $r['workflow_roleid'])
		{
			$roles = table('role');
			$r['workflow_rolename'] = $roles[$r['workflow_roleid']]['name'];
		}
		if (isset($r['tweeted']) && $r['tweeted'] > 0)
		{
			$r['tweeted'] = date('Y-m-d H:i', $r['tweeted']);
			$r['tweeted'] = '<img class="tweeted" src="css/images/tweeted.png" tips="在'.$r['tweeted'].'被转发" />';
		}
		else
		{
			$r['tweeted'] = '';
		}
	}
	
	public function input(& $r)
	{
		if ($r['published'])
		{
			$r['published'] = $r['published'] ? strtotime($r['published']) : null;
			if ($r['published']) $r['publishedby'] = $this->_userid;
		}
		else
		{
			$r['published']	= TIME;
		}
		if (isset($r['unpublished'])) 
		{
			$r['unpublished'] = $r['unpublished'] ? strtotime($r['unpublished']) : null;
			if ($r['unpublished']) $r['unpublishedby'] = $this->_userid;
		}
		
		if (isset($r['status']))
		{
			$workflowid = table('category', $r['catid'], 'workflowid');
			$workflow_step = loader::model('admin/workflow_step', 'system');
			$last_roleid = value($workflow_step->get(array('workflowid'=>$workflowid), 'roleid', 'step desc'), 'roleid');
			$user = online();
			//如果角色id大于2，即非管理员角色和主编角色，并且确实存在工作流时，则设置默认状态为待审，状态可能为待审或者回收站
			if ($this->_roleid > 2 && table('category', $r['catid'], 'workflowid'))
			{
				$status_default = 3;
				$status_in = array(1, 3);
				if($user['roleid'] == $last_roleid)
				{

				$status_default = 6;
				$status_in = array(1, 3, 6);

				}

			}
			else 
			{
				$status_default = 6;
				$status_in = array(1, 3, 6);
			}
			if (!in_array($r['status'], $status_in)) $r['status'] = $status_default;
			if ($r['status'] == 6)
			{
				if (isset($r['published']) && $r['published'] > TIME)
				{
					$r['status'] = 5;
				}
				else
				{
					//$r['published'] = TIME;
					$r['publishedby'] = $this->_userid;
				}
			}
		}
		// 判断当前用户的权重权限
		if(isset($r['weight']))
		{
			$weight = loader::model('admin/admin_weight', 'system');
			$weight = array_shift($weight->get_by('userid', $this->_userid, 'weight'));
			$weight && $r['weight'] > $weight && $r['weight'] = $weight;
		}

		$r['allowcomment'] = (isset($r['allowcomment']) && $r['allowcomment']) ? 1 : 0;
		$r['locked'] = $r['lockedby'] = null;
	}
	
	//统计总数目，标注数，投稿数
	public function total($modelid=0, $catid=0, $original=false)
	{
		if($catid) {
			$ids = $this->db->get("SELECT catid, childids FROM #table_category WHERE catid = $catid");
			if(!$ids['childids']) {
				$where[] = "catid = $catid";
			} else {
				$where[] = "catid IN ({$catid},{$ids['childids']})";
			}
		}
        $modelid && $where[] = "modelid = $modelid";
        $where && $where = 'WHERE '.implode(' AND ', $where);
        $total_key = 'content_total_'. md5($where);

        // 缓存COUNT
        $content_rows = explain_rows('content');
        if ($original) {
            $data = $this->total_db($where);
            if ($content_rows) {
                factory::cache()->set($total_key, $data, 28800);
            }
        } else {
            if ($content_rows) {
                $total_cache = factory::cache()->get($total_key);
                if (!empty($total_cache)) {
                    $data = $total_cache;
                } else {
                    $data = $this->total_db($where);
                    factory::cache()->set($total_key, $data, 28800);
                }
            } else {
                $data = $this->total_db($where);
            }
        }

		return $data;
	}

    private function total_db($where)
    {
        $a = $this->db->get("SELECT COUNT(*) AS num FROM #table_content $where");

        $temp = $where ? $where.' AND note = 1' : 'WHERE note = 1';
        $b = $this->db->get("SELECT COUNT(*) AS num FROM #table_content $temp");
        $temp = $where ? $where.' AND iscontribute = 1' : 'WHERE iscontribute = 1';
        $c = $this->db->get("SELECT COUNT(*) AS num FROM #table_content $temp");
        $temp = $where ? $where.' AND status = 3' : 'WHERE status = 3';
        $d = $this->db->get("SELECT COUNT(*) AS num FROM #table_content $temp");
        $temp = $where ? $where.' AND status = 6' : 'WHERE status = 6';
        $p = $this->db->get("SELECT COUNT(*) AS num FROM #table_content $temp");

        $data = array(
            'total' => intval($a['num']),
            'note' => intval($b['num']),
            'contribute' => intval($c['num']),
            'wait'	=>	intval($d['num']),
            'published' => intval($p['num'])
        );
        return $data;
    }

    private function copy_property($contentid,$newid)
    {
        $proids = $this->db->select("SELECT proid FROM #table_content_property WHERE contentid=?",array($contentid));
        if($proids)
        {
            foreach($proids as $row)
            {
                $proid = $row['proid'];
                $this->db->insert("INSERT INTO #table_content_property VALUES (?,?)", array($newid,$proid));
            }
        }
        return true;
    }

    private function copy_meta($contentid,$newid)
    {
    	$fieldmeta = $this->db->get("SELECT data From #table_content_meta WHERE contentid = $contentid");
    	if($fieldmeta[data])
		{
			$this->db->update("INSERT INTO #table_content_meta VALUES (?,?)", array($newid,$fieldmeta[data]));
		}
		return true;

    }
    private function _check_workflow()
    {
    	if (in_array($this->_roleid, array(1,2)))
    	{
    		return true;
    	}
    	$catid = value($this->get($this->contentid, 'catid'), 'catid');
		$workflowid = table('category', $catid, 'workflowid');
		if (empty($workflowid) || $workflowid <= 0)
		{
			return true;
		}
		$workflow_step = loader::model('admin/workflow_step', 'system');
		$last_roleid = value($workflow_step->get(array('workflowid'=>$workflowid), 'roleid', 'step desc'), 'roleid');
		return ($this->_roleid == $last_roleid);
    }
	
	public function attach(SplObserver $observer)
	{
		$this->observers[] = $observer;
	}

	public function detach(SplObserver $observer)
	{
		if($index = array_search($observer, $this->observers, true)) unset($this->observers[$index]);
	}

	public function notify()
	{
		foreach ($this->observers as $observer)
		{
			$observer->update($this);
		}
	}
}
