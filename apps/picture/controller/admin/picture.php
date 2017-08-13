<?php
/**
 * 组图管理
 *
 * @aca 管理
 */
class controller_admin_picture extends picture_controller_abstract
{
	private $picture, $pagesize = 15, $upload_max_filesize = 2048, $modelid, $weight = null,$attachment;
	
	function __construct($app)
	{
		parent::__construct($app);
		$this->picture = loader::model('admin/picture');
		$this->weight = loader::model('admin/admin_weight', 'system');
		$this->modelid = $this->picture->modelid;
		$this->attachment = loader::model('admin/attachment', 'system');
		
		if (isset($_REQUEST['catid'])) $this->priv_category($_REQUEST['catid']);

        $upload_max_filesize = size_calculate(ini_get('upload_max_filesize'));
        if ($upload_max_filesize) 
		{
            $this->upload_max_filesize = $upload_max_filesize;
        }
	}
	
	public function __call($method, $args)
	{
		if(in_array($method, array('delete', 'clear', 'remove', 'restore', 'restores', 'approve', 'pass', 'reject', 'islock', 'lock', 'unlock', 'publish', 'unpublish'), true)) 
		{
			$var = in_array($method, array('clear', 'restores')) ? 'catid' : 'contentid';
			$result = $this->picture->$method($_REQUEST[$var]) ? array('state'=>true) : array('state'=>false, 'error'=>$this->picture->error());
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
        if (!array_key_exists(modelid('picture'), category_model($catid)))
        {
            $this->showmessage('该栏目下不允许发布组图');
        }

		if ($this->is_post())
		{
			if ($contentid = $this->picture->add($_POST))
			{
                $result = array('state'=>true, 'contentid' => $contentid);
                $content = $this->picture->get($contentid, 'url, status');
                $content['status'] == 6 && ($result['url'] = $content['url']);
                //丝路网新增加接口调用代码 start
              	$data = table('content',$contentid);
				intface($data['publishedby'],0,$data['modelid'],table('category',$data['catid'],'name'),$contentid,'add');
				//丝路网新增加接口调用代码 end
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->picture->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$setting = setting('system');
			$catid = $_GET['catid'];
			$myweight = $this->weight->get_weight($this->_userid);
			$weight = $this->setting['weight'];
			$data = array('status'=>6,
			              'weight' => $myweight ? (($myweight-$weight)>=0 ? $weight : $myweight) : 0,
			              'source'=>$this->setting['source'],
			              'editor'=>table('admin', $this->_userid, 'name'),
			              'allowcomment'=>1,
			             );
			$this->view->assign($data);
			$this->view->assign('get_tag', setting('system', 'get_tags'));
            $this->view->assign('catname', $this->picture->category[$catid]['name']);
		    $this->view->assign('head', array('title'=>'发布组图'));
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
            if ($this->picture->edit($contentid, $_POST))
            {
                $result = array('state'=>true, 'contentid'=>$contentid);
                $content = $this->picture->get($contentid, 'url, status');
                $content['status'] == 6 && ($result['url'] = $content['url']);
                //丝路网新增加接口调用代码 start
              	$data = table('content',$contentid);
				intface($data['publishedby'],0,$data['modelid'],table('category',$data['catid'],'name'),$contentid,'add');
				//丝路网新增加接口调用代码 end
			}
			else 
			{
				$result = array('state'=>false, 'contentid'=>$_POST['contentid'], 'error'=>$this->picture->error());
			}
			echo $this->json->encode($result);
		}
		else 
		{
			$myweight = $this->weight->get_weight($this->_userid);
			$contentid = intval($_GET['contentid']);
			$data = $this->picture->get($contentid, '*', 'get');
			if (!$data) $this->showmessage($this->picture->error());
			
			$this->priv_category($data['catid']);
			
			$this->picture->lock($contentid);
			
			$this->view->assign($data);
			$this->view->assign('get_tag', setting('system', 'get_tags'));
			$this->view->assign('myweight', $myweight);
		    $this->view->assign('head', array('title'=>'编辑组图：'.$data['title']));
			$this->view->display('edit');
		}
	}

	/**
     * 网编工具箱编辑
     *
     * @aca 编辑
     */
	function miniedit()
	{
		$myweight = $this->weight->get_weight($this->_userid);
		$contentid = intval($_GET['contentid']);
		$data = $this->picture->get($contentid, '*', 'get');
		if (!$data) $this->showmessage($this->picture->error());
		
		$this->priv_category($data['catid']);
		
		$this->picture->lock($contentid);
		$this->view->assign($data);
		$this->view->assign('get_tag', setting('system', 'get_tags'));
		$this->view->assign('myweight', $myweight);
	    $this->view->assign('head', array('title'=>'编辑组图：'.$data['title']));
		$this->view->display('miniedit');
	}

    /**
     * 查看
     *
     * @aca 查看
     */
	function view()
	{
		$contentid = intval($_GET['contentid']);
		$r = $this->picture->get($contentid, '*', 'view');
		if (!$r) $this->showmessage($this->picture->error());
		
        $this->priv_category($r['catid']);
        
		if(is_array($r['pictures']))
		foreach ($r['pictures'] as $key=>$val)
		{
			$r['pictures'][$key]['info'] = $this->_get_pic_info(UPLOAD_PATH.$val['image'], $val['image']);
		}
		
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

		$data = $this->picture->content->related($catid, $modelid, $keywords, $page, $pagesize);
		$result = $page == 1 ? array('state'=>true, 'data'=>$data, 'total'=>$this->picture->content->related_total($catid, $modelid, $keywords)) : array('state'=>true, 'data'=>$data);
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
					$result = $this->picture->reference($contentid, intval($cid));
					if (!$result) break;
				}
			}
			else
			{
				$result = $this->picture->reference($contentid, intval($catid));
			}
			$result = $result ? array('state'=>true) : array('state'=>false, 'error'=>$this->picture->error());
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
			$data = $treeview->get(null, 'category_tree', '<li><span id="{$catid}">{$checkbox}{$name}</span>{$child}</li>');
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
			$result = $this->picture->move($contentid, $catid) ? array('state'=>true, 'contentid'=>$contentid) : array('state'=>false, 'error'=>$this->picture->error());
			echo $this->json->encode($result);
		}
		else 
		{
			$category = table('category');
			foreach ($category as $k=>$c)
			{
				$category[$k]['radio'] = '';
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
					$category[$k]['radio'] = '<input type="radio" name="catid" value="'.$c['catid'].'" class="radio_style" />';
				}
			}
			import('helper.treeview');
			$treeview = new treeview($category);
			$data = $treeview->get(null, 'category_tree', '<li><span id="{$catid}">{$radio}{$name}</span>{$child}</li>');
			$this->view->assign('data', $data);
			$this->view->display('content/move', 'system');
		}
	}

    /**
     * 远程采集
     *
     * @aca 添加,编辑
     */
	function remote()
	{
		if ($this->is_post())
		{
			$remote_pictures = $_POST['remote_pictures'];
			if (empty($remote_pictures))
			{
				$result = array('state'=>false, 'error'=>'远程图片地址不能为空！');
			}
			else
			{
				$attachment = loader::model('admin/attachment', 'system');
				$k = 0;
				$pictures = array();
				$imgext = array('jpeg','jpg','gif','png');
				$remote_pictures = array_filter(array_map('trim', explode("\n", $remote_pictures)));
				foreach ($remote_pictures as $url)
				{
					 if(in_array(strtolower(pathinfo($url, PATHINFO_EXTENSION)), $imgext))
					 {	
					 	$file = $attachment->download_by_file($url);
					 	if ($file)
					 	{
						 	$pictures[] = $attachment->aid[$k].'|'.UPLOAD_URL.$file;
						 	$k++;
					 	}
					 }
				}
				if($k > 0)
				{
                    $result = array('state'=>true, 'data'=>$pictures);
				}
				else
				{
					$result = array('state'=>false, 'error'=>'远程图片获取错误！');
				}
			}
			echo $this->json->encode($result);
		}
		else 
		{
            $this->view->assign('single', intval(value($_GET, 'single', 0)));
			$this->view->display('remote');
		}
	}

    /**
     * @aca public
     */
    function detail()
    {
        $contentid = intval($_REQUEST['contentid']);
        if (!$contentid
            || !($content = $this->picture->get($contentid, '*', 'get'))
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
		
		$publishid = $this->picture->content->cron_publish($this->modelid);
		if ($publishid) array_map(array($this->picture, 'publish'), $publishid);
		
		$unpublishid = $this->picture->content->cron_unpublish($this->modelid);
		if ($unpublishid) array_map(array($this->picture, 'unpublish'), $unpublishid);
		
		exit ('{"state":true}');
	}

	/**
	 * 图组模型中插入图片的iframe
     *
     * @aca 添加,编辑
	 */
	function image()
	{
		$setting	= setting('system');
		$use_watermark = setting('picture', 'watermark') && $setting['watermark_enabled'];
		$watermark	= loader::model('admin/watermark', 'system')->select('disable=0' ,'`watermarkid` as id, `name`');
		$dmsc = setting::get('dmsc', 'status');
		if ($catid = intval(value($_GET, 'catid'))) {
			$default_watermark = table('category', $catid, 'watermark');
		}
		if (empty($default_watermark)) {
			$default_watermark = $setting['default_watermark'];
		}
		$this->view->assign('dmsc', (bool)$dmsc);
		$this->view->assign('use_watermark', $use_watermark);
		$this->view->assign('default_watermark', $default_watermark);
		$this->view->assign('watermark', $watermark);
        $this->view->assign('single', value($_GET, 'single', false));
		$this->view->display('image');
	}
}