<?php
/**
 * 项目管理
 *
 * @aca 项目
 */
class controller_admin_item extends item_controller_abstract
{
	private $item, $pagesize = 15, $modelid, $weight = null,$itemtype;

	function __construct($app)
	{
		parent::__construct($app);
		$this->item = loader::model('admin/item');
		$this->country = loader::model('admin/columnattr','attribute');
		$this->weight = loader::model('admin/admin_weight', 'system');
		$this->itemtype = loader::model('admin/itemtype');
		$this->country = loader::model('admin/columnattr','attribute');
		$this->modelid = $this->item->modelid;
		
		if (isset($_REQUEST['catid'])) $this->priv_category($_REQUEST['catid']);
	}

	public function __call($method, $args)
	{
		if(in_array($method, array('delete', 'clear', 'remove', 'restore', 'restores', 'approve', 'pass', 'reject', 'islock', 'lock', 'unlock', 'publish', 'unpublish'), true)) 
		{
			$var = in_array($method, array('clear', 'restores')) ? 'catid' : 'contentid';
			$result = $this->item->$method($_REQUEST[$var]) ? array('state'=>true) : array('state'=>false, 'error'=>$this->item->error());
			$r	= $this->json->encode($result);
			echo $_GET['jsoncallback'] ? $_GET['jsoncallback']."($r)" : $r;
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
        if (!array_key_exists(modelid('item'), category_model($catid)))
        {
            $this->showmessage('该栏目下不允许发布项目');
        }

		if ($this->is_post())
		{
			if ($contentid = $this->item->add($_POST))
			{
				$result = array('state'=>true, 'contentid' => $contentid);
                $content = $this->item->get($contentid, 'url, status');
                $content['status'] == 6 && ($result['url'] = $content['url']);
                //丝路网新增加接口调用代码 start
              	$data = table('content',$contentid);
				intface($data['publishedby'],0,$data['modelid'],table('category',$data['catid'],'name'),$contentid,'add');
				//丝路网新增加接口调用代码 end
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->item->error());
				if($this->item->filterword)
				{
					$result['filterword'] = $this->item->filterword;
				}
			}
			echo $this->json->encode($result);
		}
		else
		{
			$myweight = $this->weight->get_weight($this->_userid);
			$weight = (int)$this->setting['weight'];
			$catid = intval($_GET['catid']);
			$data = array('status'=>6,
                'weight' => $myweight ? (($myweight-$weight)>=0 ? $weight : $myweight) : 0,
                'source'=>$this->setting['source'],
                'editor'=>table('admin', $this->_userid, 'name'),
                'allowcomment'=>1,
                'saveremoteimage'=>1,
                'o2h_allowed'=>setting('cloud', 'o2h_allowed')
            );
			$this->view->assign($data);
			$this->view->assign('get_tag', setting('system', 'get_tags'));
			$this->view->assign('catname', $this->item->category[$catid]['name']);
			$this->view->assign('head', array('title'=>'发布项目'));
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
			if ($this->item->edit($contentid, $_POST))
			{
                $result = array('state'=>true, 'contentid'=>$contentid);
                $content = $this->item->get($contentid, 'url, status');
                $content['status'] == 6 && ($result['url'] = $content['url']);
                //丝路网新增加接口调用代码 start
               	$data = table('content',$contentid);
				intface($data['publishedby'],0,$data['modelid'],table('category',$data['catid'],'name'),$contentid,'add');
				//丝路网新增加接口调用代码 end
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->item->error());
				if($this->item->filterword)
				{
					$result['filterword'] = $this->item->filterword;
				}
			}
			echo $this->json->encode($result);
		}
		else
		{
			$contentid = intval($_GET['contentid']);
			if (!$contentid && !empty($_GET['url']))
			{
				$source = parse_url($_GET['url']);
				$source['path'] = preg_replace('/^(.*)(?:_\d{1,3})(\.\w{3,6})/','\1\2', $source['path']);
				$key = $source['scheme'].'://'.$source['host'].$source['path'];
				$data = $this->item->content->select("url='$key'",'contentid', null, 1);
				if (! $data)
				{
					$this->showmessage('不是本站项目，无法编辑');
				}
				$contentid = $data[0]['contentid'];
			}
			$myweight = $this->weight->get_weight($this->_userid);
			$data = $this->item->get($contentid, '*', 'get');
			if (! $data)
			{
				$this->showmessage('不存在此项目');
			}
			$data['starttime'] = date('Y-m-d H:i:s',$data['starttime']);
			$data['stoptime'] = date('Y-m-d H:i:s',$data['stoptime']);
			$itemdata = $this->itemtype->ls(array('contentid'=>$data['contentid']));
			foreach ($itemdata as $k => $v) {
				if($v['type']=='country'){
					 $country = $this->country->get($v['typeid'],'columnattrid,name');
					 $data[$v['type']][] = $country['columnattrid'];
					 if(empty($data['countryname'])){
					 	$data['countryname'] = $country['name'];
					 }else{
					 	$data['countryname'] .= ','.$country['name'];
					 }
				}else{
					$data[$v['type']][] = $v['typeid'];
				}
			}
			$this->priv_category($data['catid']);
			
			$this->item->lock($contentid);
			
			$this->view->assign($data);

			$this->view->assign('o2h_allowed', setting('cloud', 'o2h_allowed'));
			$this->view->assign('get_tag', setting('system', 'get_tags'));
			$this->view->assign('myweight', $myweight);
			$this->view->assign('head', array('title'=>'编辑项目：'.$data['title']));
			$this->view->display('edit');
		}
	}

	//国家地区查看
    function selectcountry(){
        if($this->is_post())
        {
            $data = $_POST;
            foreach ($data as $key => $value)
            {
                $result[$value] = $key;
            }
            echo $this->json->encode($result);
        }
        else
        {
        	$data = explode(',',$_GET['data']);
			$country = $this->country->selectclass();
			$this->view->assign('country',$country);
			$this->view->assign('data',$data);
            $this->view->display('selectcountry');
        }
    }


    /**
     * 查看
     *
     * @aca 查看
     */
	function view()
	{
		$r = $this->item->get(intval($_GET['contentid']), '*', 'view');
		if (!$r) $this->showmessage($this->item->error());

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

		$data = $this->item->content->related($catid, $modelid, $keywords, $page, $pagesize);
		$result = $page == 1 ? array('state'=>true, 'data'=>$data, 'total'=>$this->item->content->related_total($catid, $modelid, $keywords)) : array('state'=>true, 'data'=>$data);
		echo $this->json->encode($result);
	}

    /**
     * 复制
     *
     * @aca 复制
     */
	function copy()
	{
		if ($this->is_post())
		{
			$contentid = $_REQUEST['contentid'];
			$catid = $_REQUEST['catid'];
			if (is_array($catid))
			{
				foreach ($catid as $cid)
				{					
					$result = $this->item->copy($contentid, $cid);
					if (!$result) break;
				}
			}
			else
			{			
				$result = $this->item->copy($contentid, $catid);
			}
			$result = $result ? array('state'=>true) : array('state'=>false, 'error'=>$this->item->error());
			echo $this->json->encode($result);
		}
		else
		{
			$this->view->display('content/copy', 'system');
		}
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
			$contentid = $_REQUEST['contentid'];
			$catid = $_REQUEST['catid'];
			if (is_array($catid))
			{
				foreach ($catid as $cid)
				{					
					$result = $this->item->reference($contentid, $cid);
					if (!$result) break;
				}
			}
			else
			{
				$result = $this->item->reference($contentid, $catid);
			}
			$result = $result ? array('state'=>true) : array('state'=>false, 'error'=>$this->item->error());
			echo $this->json->encode($result);
		}
		else 
		{
			$category = table('category');
			foreach ($category as $k=>$c)
			{
				$category[$k]['checkbox'] = '';
				if ($c['childids'])
				{
					if (!priv::category($k, true)) unset($category[$k]);
				}
				elseif (!priv::category($k))
				{
					unset($category[$k]);
				}
				else 
				{
					$category[$k]['checkbox'] = '<input type="checkbox" name="catid[]" value="'.$c['catid'].'" class="radio_style" />';
				}
			}
			import('helper.treeview');
			$treeview = new treeview($category);
			$data = $treeview->get(null, 'category_tree', '<li><span id="{$catid}"><label>{$checkbox}{$name}</label></span>{$child}</li>');
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
			$contentid = $_REQUEST['contentid'];
			$catid = $_REQUEST['catid'];
			$result = $this->item->move($contentid, $catid) ? array('state'=>true, 'contentid'=>$contentid) : array('state'=>false, 'error'=>$this->item->error());
			echo $this->json->encode($result);
		}
		else 
		{
			$category = table('category');
			foreach ($category as $k=>$c)
			{
				$category[$k]['radio'] = '<input type="radio" name="catid" value="'.$c['catid'].'" class="radio_style" />';
				if (!priv::category($c['catid']))
				{
					if (priv::category($c['catid'], true))
					{
						$category[$k]['radio'] = '';
					}
					else 
					{
						unset($category[$k]);
						continue;
					}
				}
				elseif ($c['childids'])
				{
					$category[$k]['radio'] = '';
				}
			}
			import('helper.treeview');
			$treeview = new treeview($category);
			$data = $treeview->get(null, 'category_tree', '<li><span id="{$catid}"><label>{$radio}{$name}</label></span>{$child}</li>');
			$this->view->assign('data', $data);
			$this->view->display('content/move', 'system');
		}
	}

    /**
     * 缩略图
     *
     * @aca 添加
     */
	function thumb()
	{
		$uri = $_POST['url'];
		if(preg_match("#^".UPLOAD_URL."#", $uri))
		{
			$pathinfo = parse_url($uri);
			$originfile = substr($pathinfo['path'],1);
			$file = date('Y/md/').TIME.mt_rand(100, 999).'.'.pathinfo($uri, PATHINFO_EXTENSION);
			@copy(UPLOAD_PATH.$originfile, UPLOAD_PATH.$file);
		}
		else 
		{
			$attachment = loader::model('admin/attachment', 'system');
			$file = $attachment->download_by_file($uri, null, '.*', null, true);
		}
		echo $file;
	}

    /**
     * @aca public
     */
    function detail()
    {
        $contentid = intval($_REQUEST['contentid']);
        if (!$contentid
            || !($content = $this->item->get($contentid, '*', 'get'))
            || $content['status'] != 6) {
            $this->showmessage('内容不存在');
        }
        exit($this->json->encode(array('state' => true, 'data' => $content)));
    }

    /**
     * 定时上下线
     *
     * @aca cron
     */
	function cron()
	{
		@set_time_limit(600);
		
		$publishid = $this->item->content->cron_publish($this->modelid);
		if ($publishid) array_walk($publishid, array($this->item, 'publish'));
		$unpublishid = $this->item->content->cron_unpublish($this->modelid);
		if ($unpublishid) array_walk($unpublishid, array($this->item, 'unpublish'));
		
		exit ('{"state":true}');
	}
}