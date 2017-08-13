<?php

class model_admin_item extends model implements SplSubject
{
	public $content, $catid, $modelid, $contentid, $data, $itemdata, $fields, $order, $action, $category, $filterword, $options, $toolbox, $old_pagecount;
	public $itemtype = array('trade','itemtype','investmenttype','itemnature','country');
	private $observers = array();

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'item';
		$this->_primary = 'contentid';
		$this->_fields = array('contentid', 'editor', 'description', 'starttime', 'stoptime', 'publishorganization', 'itemsum', 'income','paybacktime' ,'itemunit' ,'itemcontacts' ,'phone' ,'faxes' ,'email', 'address', 'postcode','itemnatureid');
		$this->_readonly = array('contentid');
		$this->_create_autofill = array();
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array('contentid'=>array('not_empty' =>array('内容ID不能为空'),
		                                              'is_numeric' =>array('内容ID必须是数字'),
		                                              'max_length' =>array(8, '内容ID不得超过8个字节'),
		                                             )
		                          );
        $this->content = loader::model('admin/content', 'system');
        $this->country = loader::model('admin/columnattr','attribute');
        $this->itype = loader::model('admin/itemtype','item');
        $this->item_fields = app_config('item','item_fields');
        $this->category = & $this->content->category;
        $this->modelid = modelid('item');
	}
	
	public function __call($method, $args)
	{
		if(!priv::aca('item', 'item', $method)) return true;
		if(in_array($method, array('clear', 'remove', 'restore', 'restores', 'approve', 'pass', 'reject', 'islock', 'lock', 'unlock', 'publish', 'unpublish'), true)) 
		{
			$id = id_format($args[0]);
			if ($id === false)
			{
				$this->error = "$id 格式不正确";
				return false;
			}
			if (is_array($id)) return array_map(array($this, $method), $id);
			if (in_array($method, array('clear', 'restores')))
			{
				$this->catid = $args[0];
			}
			else 
			{
				$this->contentid = $args[0];
			}
			
			$this->event = 'before_'.$method;
			$this->notify();
			$result = $this->content->$method($args[0]);
			if (!$result)
			{
				$this->error = $this->content->error();
				return false;
			}
			$this->event = 'after_'.$method;
			$this->notify();
			return $result;
        }
	}

	function get($contentid, $fields = '*', $action = null, $table_item = true)
	{
		if (!in_array($action, array(null, 'get', 'view', 'show'))) return false;
		
		$this->contentid = intval($contentid);
		$this->fields = $fields;
		$this->action = $action;
		$this->table_item = $table_item;

		$this->event = 'before_get';
		$this->notify();
		
		if ($this->table_item)
		{
			$this->data = $this->db->get("SELECT $this->fields FROM `#table_content`, `#table_item` WHERE `#table_content`.`contentid`=`#table_item`.`contentid` AND `#table_content`.`contentid`=$this->contentid");
			if ($this->data && ($this->action == 'get' || $this->action == 'view' || $this->action == 'show'))
			{
				if ($this->action != 'show') $this->content->output($this->data);
				$this->content->contentid = & $this->contentid;
				$this->content->action = & $this->action;
				$this->content->data = & $this->data;
				$this->content->event = 'after_get';
				$this->content->notify();
			}
		}
		else 
		{
			$this->data = $this->content->get($this->contentid, $this->fields, $this->action);
		}

		$this->event = 'after_get';
		$this->notify();
		
		return $this->data;
	}
	
	function ls($where = null, $fields = '*', $order = '`#table_content`.`contentid` DESC', $page = null, $pagesize = null, $table_item = false)
	{
		$this->where = $where;
		$this->fields = $fields;
		$this->order = $order;
		$this->page = $page;
		$this->pagesize = $pagesize;
		$this->table_item = $table_item;
		
		$this->event = 'before_ls';
		$this->notify();
		
		if ($this->table_item)
		{
			if (is_array($this->where)) $this->where = str_replace('WHERE','',$this->content->where($this->where));
			$this->sql = "SELECT $this->fields FROM `#table_content` c, `#table_item` a WHERE c.`contentid`=a.`contentid`";
			if (!is_null($this->where)) $this->sql .= ' AND '.$this->where;
			if ($this->order) $this->sql .= ' ORDER BY '.$this->order;
			$this->data = $this->db->page($this->sql, $this->page, $this->pagesize);
			if ($this->data)
			{
				array_walk($this->data, array($this->content, 'output'));
				if (!is_null($page))
				{
					$sql = "SELECT count(*) as `count` FROM `#table_content` c, `#table_item` a WHERE c.`contentid`=a.`contentid`";
					if (!is_null($this->where)) $sql .= ' AND '.$this->where;
					$r = $this->db->get($sql);
					$this->total = $r ? $r['count'] : 0;
				}
			}
		}
		else 
		{
			$this->data = $this->content->ls($this->where, $this->fields, $this->order, $this->page, $this->pagesize);
			if (!is_null($page)) $this->total = $this->content->total;
		}
		
		$this->event = 'after_ls';
		$this->notify();
		
		return $this->data;
	}

	function seachls($where = null, $fields = '*', $order = '', $page = null, $pagesize = null, $table_item = false)
	{
		foreach ($where as $k => $v)
		{
			if(in_array($k, array('trade','itemtype','investmenttype','itemnature')))
			{
				$typeid = implode(',', $v);
				$w? $w = "select contentid from cmstop_item_type where type='$k' and typeid in ($typeid) and contentid in($w)" : $w = "select contentid from cmstop_item_type where type='$k' and typeid in ($typeid)";
			}
			elseif($k=="country")
            {
				foreach ($v as $m => $n)
				{
					$id = $this->country->get($n,'columnattrid,childids');
					$ids ? $ids .= ','.$id['columnattrid'].','.$id['childids'] : $ids = $id['columnattrid'].','.$id['childids'];
				}
				$w? $w = "select contentid from cmstop_item_type where type='$k' and typeid in ($ids) and contentid in($w)" : $w = "select contentid from cmstop_item_type where type='$k' and typeid in ($ids)";
			}
			elseif($k=="starttime")
            {
				$cw ? $cw .= " and i.$k>".ceil($v/1000) : $cw = " i.$k>".ceil($v/1000);
			}
			elseif($k=="stoptime")
            {
				$cw ? $cw .= " and i.starttime<".ceil($v/1000) : $cw = " i.$k<".ceil($v/1000);
			}
			elseif($k=="catid")
            {
				$catids = table('category',$v,'childids');
				$catids ? $catids = $v.','.$catids : $catids = $v;
				$cw ? $cw .= " and c.$k in ($catids)" : $cw = " c.$k in ($catids)";
			}
			elseif ($k == "minsum" && !empty($v))
            {
                //处理金额范围
                $cw .= " and cast(i.itemsum as decimal(8,0)) > $v";
            }
            elseif ($k == "maxsum" && !empty($v))
            {
                //处理金额范围
                $cw .= " and cast(i.itemsum as decimal(8,0)) < $v";
            }
            elseif ($k == "currency" && !empty($v))
            {
                //处理币种
                if ($v == 'CNY')
                {
                    $cw .= " and i.itemsum like '%%人民币%%'";
                }
                elseif ($v == 'USD')
                {
                    $cw .= " and i.itemsum like '%%美元%%'";
                }
            }
			else
            {
                if (!empty($v))
                {
                    $cw ? $cw .= " and c.$k=$v" : $cw = "c.$k=$v";
                }
			}
		}
		if($w)
		{
			$cdata = $this->db->select($w);
			foreach ($cdata as $m => $n)
			{
				$contentids[$m] = $n['contentid'];
			}
			$contentids = implode(',', array_unique($contentids));
			$cw .= " and c.contentid in ($contentids)";
		}
		//查询数据结果
		$sql = "select $fields from #table_content c,#table_item i where $cw and c.contentid=i.contentid order by $order";
		$this->data = $this->db->page($sql,$page,$pagesize);

		//针对查询到的数据结果进行数据处理,方便模板嵌套
		$this->data = $this->selectdata($this->data);
		//查询数据总条数
		$tsql = "select count(c.contentid) total from #table_content c,#table_item i where $cw and c.contentid=i.contentid";
		if (!is_null($page)) $this->total = $this->db->select($tsql)[0]['total'];
		return array('total'=>$this->total,'data'=>$this->data);
	}



	function selectdata($data){
		foreach ($data as $k => &$v) {
			$thumb = $v['thumb'] = $this->db->select('select image from #table_related r,#table_picture_group p where r.orign_contentid = p.contentid and r.contentid = '.$v["contentid"].' order by p.sort asc limit 1')[0]['image'];
			$v['thumb'] = thumb($thumb,133,100,1,null,1);
			$v['stoptime'] = date('Y.m.d',$v['stoptime']);
			$v['starttime'] = date('Y.m.d',$v['starttime']);
			$v['description'] = strip_tags(html_entity_decode(str_natcut($v['description'],'42','...')));
			$v['gusturl'] = APP_URL."?app=guestbook&controller=index&action=index&typeid=10";
			$countryids = $this->itype->ls(array('type'=>'country','contentid'=>$v['contentid']),'typeid');
			foreach ($countryids as $m => $n) {
				$country = $this->country->get($n['typeid'],'name,alias');
				if($k==0){
					$v['countryname'] = $country['name'];
					$v['countryalias'] = $country['alias'];
				}else{
					$v['countryname'] .= ' '.$country['name'];
					$v['countryalias'] .= ' '.$country['alias'];
				}
			}
			$tradeids = $this->itype->ls(array('type'=>'trade','contentid'=>$v['contentid']),'typeid');
			foreach ($tradeids as $m => $n) {
				$typeid = $n['typeid'];
				if($m == 0){
					$v['tradename'] = $this->item_fields['trade'][$typeid]['zh'];
					$v['tradealias'] = $this->item_fields['trade'][$typeid]['cn'];
				}else{
					$v['tradename'] .= ' '.$this->item_fields['trade'][$typeid]['zh'];
					$v['tradealias'] .= ' '.$this->item_fields['trade'][$typeid]['cn'];
				}
			}
		}
		return $data;
	}

	function add($data, $options)
	{
		$data['description']	= htmlspecialchars($data['description']);
		$this->toolbox = !empty($data['toolbox']);
		$this->data = $data;
		$this->data['starttime'] = strtotime($data['starttime']);
		$this->data['stoptime'] = strtotime($data['stoptime']);
		$this->itemdata = array('trade'=>$data['tradeid'],
								'itemtype'=>$data['itemtypeid'],
								'investmenttype'=>$data['investmenttypeid'],
								'country'=>$data['countryid'],
							);
		$this->options = $options;
		$this->event = 'before_add';
		$this->notify();

		if($this->filterword && !isset($_REQUEST['ignoreword']))
		{
			$this->error = '内容中存在以下敏感词，是否继续发布？';
			return false;
		}
		
		$this->contentid = $this->content->add($this->data);
		if (!$this->contentid)
		{
			$this->error = $this->content->error();
			return false;
		}
		$this->data['contentid'] = $this->contentid;
		$this->data = $this->filter_array($this->data, array('contentid', 'editor', 'description', 'starttime', 'stoptime', 'publishorganization', 'itemsum', 'income','paybacktime' ,'itemunit' ,'itemcontacts' ,'phone' ,'faxes' ,'email', 'address', 'postcode','itemnatureid'));
		$result = $this->insert($this->data);
        if ($result)
        {
			$this->event = 'after_add';
			$this->notify();
        }
		$firstid = $this->contentid ? $this->contentid : 0;
		return $result ? $firstid : false;
	}

	function edit($contentid, $data, $options)
	{
		$this->contentid = intval($contentid);
		$data['description']	= htmlspecialchars($data['description']);
		$this->options = $options;
		$this->data = $data;
		$this->data['starttime'] = strtotime($data['starttime']);
		$this->data['stoptime'] = strtotime($data['stoptime']);
		$this->itemdata = array('trade'=>$data['tradeid'],
								'itemtype'=>$data['itemtypeid'],
								'investmenttype'=>$data['investmenttypeid'],
								'country'=>$data['countryid'],
							);
		$this->event = 'before_edit';
		$this->notify();
		
		if($this->filterword && !isset($_REQUEST['ignoreword']))
		{
			$this->error = '内容中存在敏感词，是否仍然发布？';
			return false;
		}
		
		if (!$this->content->edit($this->contentid, $this->data))
        {
			$this->error = $this->content->error();
			return false;
        }
        
        $this->old_pagecount = $this->data['old_pagecount'];
        $this->data = $this->filter_array($this->data, array('editor', 'description', 'starttime', 'stoptime', 'publishorganization', 'itemsum', 'income','paybacktime' ,'itemunit' ,'itemcontacts' ,'phone' ,'faxes' ,'email', 'address', 'postcode','itemnatureid'));
        $result = $this->update($this->data, $this->contentid);
        if ($result)
        {
			$this->event = 'after_edit';
			$this->notify();
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
		
		$this->contentid = intval($contentid);
		
		$this->event = 'before_delete';
		$this->notify();
		
		if (!$this->content->delete($this->contentid))
		{
			$this->error = $this->content->error();
			return false;
		}
		$this->event = 'after_delete';
		$this->notify();
		return true;
	}

	function copy($contentid, $catid)
	{
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_array($contentid)) return array_map(array($this, 'copy'), $contentid, array_fill(0, count($contentid), $catid));

		$this->contentid = intval($contentid);
		$this->catid = intval($catid);
		
		$this->event = 'before_copy';
		$this->notify();
		
		$id = $this->content->copy($this->contentid, $this->catid);
		if (!$id)
		{
			$this->error = $this->content->error();
			return false;
		}

		if (!$this->copy_by_id($contentid, array('contentid'=>$id)))
		{
			$this->error = "复制失败";
			return false;
		}
		
		$this->contentid = $id;
		
		$this->event = 'after_copy';
		$this->notify();
		
		return $this->contentid;
	}
	
	function move($contentid, $catid)
	{
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}

		// 判断当前栏目是否支持此模型
		if (!$cate = value(table('category'), $catid))
		{
			$this->error = "栏目不存在";
			return false;
		}

		foreach (unserialize($cate['model']) as $key=>$item)
		{
			if (isset($item['show']) && $item['show'])
			{
				$model[] = $key;
			}
		}
		if (!in_array($this->modelid, $model))
		{
			$this->error	= '栏目不支持此模型内容';
			return false;
		}
		
		if(!priv::aca('item', 'item', 'move')) return true;
		if (is_array($contentid)) return array_map(array($this, 'move'), $contentid, array_fill(0, count($contentid), $catid));
		
		$this->contentid = $contentid;
		$this->catid = intval($catid);
		
		$this->event = 'before_move';
		$this->notify();
		
		if (!$this->content->move($this->contentid, $this->catid))
		{
			$this->error = $this->content->error();
			return false;
		}
		
		$this->event = 'after_move';
		$this->notify();
		
		return true;
	}
	
	function reference($contentid, $catid)
	{
		$this->contentid = $contentid;
		$this->catid = intval($catid);
		
		$this->event = 'before_reference';
		$this->notify();
		
		if (!$this->content->reference($this->contentid, $this->catid))
		{
			$this->error = $this->content->error();
			return false;
		}
		
		$this->event = 'after_reference';
		$this->notify();
		
		return true;
	}
	
	function html_write($contentid)
	{
		$contentid = id_format($contentid);
		if ($contentid === false)
		{
			$this->error = "$contentid 格式不正确";
			return false;
		}
		if (is_array($contentid)) return array_map(array($this, 'html_write'), $contentid);

		$this->contentid = $contentid;
		
		$this->event = 'html_write';
		$this->notify();
		
		return true;
	}
	
	function statistics($spaceid)
	{
		$statistics = array();
		$statistics['published'] = $this->content->count("`spaceid`='{$spaceid}' AND `status`='6'");
		$statistics['submitted'] = $this->content->count("`spaceid`='{$spaceid}' AND `status`='3'");
		$statistics['rejected'] = $this->content->count("`spaceid`='{$spaceid}' AND `status`='2'");
		$statistics['drafted'] = $this->content->count("`spaceid`='{$spaceid}' AND `status`='1'");
		return $statistics;
	}
	
	function clear_contribution()
	{
		return $this->content->clear_contribution();
	}
	
	function get_count()
	{
		$result = array(
			'total' => $this->content->count("`iscontribute` = '1'"),
			'wait' => $this->content->count("`iscontribute` = '1' AND `status` = '3'"),
			'publish' => $this->content->count("`iscontribute` = '1' AND `status` = '6'"),
			'reject' => $this->content->count("`iscontribute` = '1' AND `status` = '2'"),
			'draft' => $this->content->count("`iscontribute` = '1' AND `status` = '1'"),
			'remove' => $this->content->count("`iscontribute` = '1' AND `status` = '0'")
		);
		return $result;
	}
	function count_comment($userid)
	{
		$sql = 'SELECT SUM(comments) AS comments 
				FROM #table_content 
				WHERE `iscontribute`=1 AND `createdby`='.$userid.' AND `status`=6';
		$r = $this->db->get($sql);
		return intval($r['comments']);
	}
	
	function get_comment($userid, $page, $pagesize)
	{
		$sql = 'SELECT f.commentid,f.content,f.created,f.createdby,f.nickname,s.title,s.url
				FROM `#table_comment` AS `f`
				LEFT JOIN `#table_content` AS `s`
				ON `f`.`contentid` =`s`.`contentid`
				WHERE `s`.`iscontribute`=1 AND `s`.`status`=6 AND `s`.`createdby`='.$userid.'
				ORDER BY `f`.`created` DESC';
		$data = $this->db->page($sql,$page,$pagesize);
		return $data;
	}
	
	function output(& $r)
	{
		$r['subtitle'] = htmlspecialchars($r['subtitle']);
		$r['editor'] = htmlspecialchars($r['editor']);
		$r['description'] = htmlspecialchars($r['description']);
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