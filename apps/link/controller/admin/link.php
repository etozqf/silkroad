<?php
/**
 * 链接
 *
 * @aca 管理
 */
class controller_admin_link extends link_controller_abstract
{
	private $link, $content, $pagesize = 15, $modelid, $weight = null;

	function __construct($app)
	{
		parent::__construct($app);
		$this->link		= loader::model('admin/link');
		$this->content = loader::model('admin/content', 'system');
		$this->weight = loader::model('admin/admin_weight', 'system');
		$this->modelid = modelid('link');
		
		if (isset($_REQUEST['catid'])) $this->priv_category($_REQUEST['catid']);
	}
	
	public function __call($method, $args)
	{
		if(!priv::aca('link', 'link', $method)) return true;
		if(in_array($method, array('clear', 'remove', 'restore', 'restores', 'approve', 'pass', 'reject', 'islock', 'lock', 'unlock', 'publish', 'unpublish'), true))
		{
			$var = in_array($method, array('clear', 'restores')) ? 'catid' : 'contentid';
			$result = $this->content->$method($_REQUEST[$var]) ? array('state'=>true) : array('state'=>false, 'error'=>$this->content->error());
			echo $this->json->encode($result);
		}
	}

    /**
     * 添加
     *
     * @aca 添加
     */
	function add()
	{
        $catid = intval($_REQUEST['catid']);
        if (!array_key_exists(modelid('link'), category_model($catid)))
        {
            $this->showmessage('该栏目下不允许发布链接');
        }

		if ($this->is_post())
		{
			if ($contentid = $this->link->add($_POST))
			{
				if($_POST['options']['catid']) //同时发到其他栏目
				{
					$catids = explode(',', $_POST['options']['catid']);
					foreach ($catids as $catid)
					{
						$this->content->reference($contentid, $catid);
					}
				}
                $result = array('state'=>true, 'contentid' => $contentid);
                $content = $this->link->get($contentid, 'url, status');
                $content['status'] == 6 && ($result['url'] = $content['url']);
                //丝路网新增加接口调用代码 start
              	$data = table('content',$contentid);
				intface($data['publishedby'],0,$data['modelid'],table('category',$data['catid'],'name'),$contentid,'add');
				//丝路网新增加接口调用代码 end
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->content->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$myweight = $this->weight->get_weight($this->_userid);
			$weight = (int)$this->system['defaultwt'];
			$catid = $_GET['catid'];
			$data = array('status'=>6,
			              'weight' => $myweight ? (($myweight-$weight)>=0 ? $weight : $myweight) : 0,
			              'editor'=>$this->_username,
			              'allowcomment'=>1,
			              'saveremoteimage'=>1,
			             );

			$this->view->assign($data);
			$this->view->assign('get_tag', setting('system', 'get_tags'));
			$this->view->assign('catname', $this->content->category[$catid]['name']);
			$this->view->assign('related_apis', table('related_api'));
			$this->view->assign('head', array('title'=>'发布链接'));
			$this->view->assign('repeatcheck', value(setting::get('system'), 'repeatcheck', 0));
			$this->view->display('add');
		}
	}

    /**
     * 编辑
     *
     * @aca 编辑
     */
	function edit()
	{
		if ($this->is_post())
		{
            $contentid = intval($_POST['contentid']);
            if ($this->link->edit($contentid, $_POST))
            {
                $result = array('state'=>true,'info'=>'success');
                $content = $this->link->get($contentid, 'url, status');
                $content['status'] == 6 && ($result['url'] = $content['url']);
                //丝路网新增加接口调用代码 start
              	$data = table('content',$contentid);
				intface($data['publishedby'],0,$data['modelid'],table('category',$data['catid'],'name'),$contentid,'add');
				//丝路网新增加接口调用代码 end
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->content->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$myweight = $this->weight->get_weight($this->_userid);
			$contentid = intval($_GET['contentid']);
			$data = $this->link->get($contentid);
			if (!$data) $this->showmessage($this->content->error());
			
			$this->priv_category($data['catid']);
			
			$this->content->lock($contentid);
			
			$this->view->assign($data);
			$this->view->assign('get_tag', setting('system', 'get_tags'));
			$this->view->assign('myweight', $myweight);
			$this->view->assign('head', array('title'=>'编辑链接：'.$data['title']));
			$this->view->display('edit');
		}
	}

    /**
     * 查看
     *
     * @aca 查看
     */
	function view()
	{
		$r = $this->content->get(intval($_GET['contentid']), '*', 'view');
		if (!$r) $this->showmessage($this->content->error());
		
		$this->priv_category($r['catid']);
		
		$this->view->assign($r);        
		$this->view->assign('head', array('title'=>$r['title']));
		$this->view->display('view');
	}

    /**
     * 相关
     *
     * @aca 添加,编辑
     */
	function related()
	{
		$keywords = $_GET['keywords'];
		$catid = intval($_GET['catid']);
		$modelid = intval($_GET['modelid']);
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : 20;

		$data = $this->content->content->related($catid, $modelid, $keywords, $page, $pagesize);
		$result = $page == 1 ? array('state'=>true, 'data'=>$data, 'total'=>$this->content->content->related_total($catid, $modelid, $keywords)) : array('state'=>true, 'data'=>$data);
		echo $this->json->encode($result);
	}

    /**
     * 引用
     *
     * @aca 引用
     */
	function reference()
	{
		if ($this->is_post())
		{
			$contentid = intval($_REQUEST['contentid']);
			$catid = $_REQUEST['catid'];
			if (is_array($catid))
			{
				foreach ($catid as $cid)
				{
					$result = $this->content->reference($contentid, intval($cid));
					if (!$result) break;
				}
			}
			else
			{
				$result = $this->content->reference($contentid, intval($catid));
			}
			$result = $result ? array('state'=>true) : array('state'=>false, 'error'=>$this->content->error());
			echo $this->json->encode($result);
		}
		else 
		{
			$category = table('category');
			import('helper.treeview');
			$treeview = new treeview($category);
			$data = $treeview->get(null, 'category_tree', '<li><span id="{$catid}"><input type="checkbox" name="catid[]" value="{$catid}" class="checkbox_style" />{$name}</span>{$child}</li>');
			$this->view->assign('data', $data);
			$this->view->display('content/reference', 'system');
		}
	}

    /**
     * 移动
     *
     * @aca 移动
     */
	function move()
	{
		if ($this->is_post())
		{
			$contentid = intval($_REQUEST['contentid']);
			$catid = intval($_REQUEST['catid']);
			$result = $this->link->move($contentid, $catid) ? array('state'=>true, 'contentid'=>$contentid) : array('state'=>false, 'error'=>$this->link->error());
			echo $this->json->encode($result);
		}
		else 
		{
			$category = table('category');
			import('helper.treeview');
			$treeview = new treeview($category);
			$data = $treeview->get(null, 'category_tree', '<li><span id="{$catid}"><input type="radio" name="catid" value="{$catid}" class="radio_style" />{$name}</span>{$child}</li>');
			$this->view->assign('data', $data);
			$this->view->display('content/move', 'system');
		}
	}

    /**
     * 删除
     *
     * @aca 从回收站彻底删除
     */
	public function delete()
	{
		$contentid = intval($_REQUEST['contentid']);
		echo $this->json->encode(array('state'=>$this->link->delete($contentid)));
	}

    /**
     * 定时上下线
     *
     * @aca cron
     */
	function cron()
	{
		@set_time_limit(600);
		
		$publishid = $this->content->cron_publish($this->modelid);
		if ($publishid) array_walk($publishid, array($this->link, 'publish'));

		$unpublishid = $this->content->cron_unpublish($this->modelid);
		if ($unpublishid) array_walk($unpublishid, array($this->link, 'unpublish'));
		exit ('{"state":true}');
	}
}