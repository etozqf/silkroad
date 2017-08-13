<?php
define('MODULE_PATH', CACHE_PATH.'module/');
/**
 * 内容管理
 *
 * @aca 内容管理
 */
class controller_admin_content extends system_controller_abstract
{
	private $content;
	
	function __construct($app)
	{
		parent::__construct($app);
		$this->content = loader::model('admin/content', 'system');

        if ($this->_roleid > 2)
        {
            if (isset($_GET['catid']) && $_GET['catid'])
            {
                $catid = intval($_GET['catid']);
                if (!priv::category($catid))
                {
                    $this->showmessage("您没有<span style='color:red'>".table('category', $catid, 'name')."($catid)</span>栏目权限！");
                }
            }
            else if (isset($_GET['catids']) && $_GET['catids'])
            {
                $catids = array_filter(explode(',', $_GET['catids']), 'priv::category');
            }
            else
            {
                $catids = array_filter(array_keys($this->content->category), 'priv::category');
            }
            $_REQUEST['catids'] = $_GET['catids'] = isset($catids) && is_array($catids) ? implode_ids($catids) : null;
        }
	}

    /**
     * 浏览
     *
     * @aca 浏览
     */
	function index()
	{
		$status = isset($_GET['status']) ? intval($_GET['status']) : 6;
		$this->view->assign('status', $status);
		
		$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
		$this->view->assign('catid', $catid);
		$category	= $this->content->category[$catid];
		$this->view->assign('name', $category['name']);
		$this->view->assign('url', $category['url']);
		$childids = table('category',$catid,'childids');
		$childids = $catid?$childids:true;
		$this->view->assign('childids',$childids);
		
		$modelid = isset($_GET['modelid']) ? intval($_GET['modelid']) : 0;
		$this->view->assign('modelid', $modelid);
		$model = $modelid ? table('model', $modelid, 'alias') : null;
		$this->view->assign('model', $model);
		
		$statuss = table('status');
		arsort($statuss);
		$this->view->assign('statuss', $statuss);
		
		$title = '内容';
		if ($catid) $title .= '_'.$this->content->category[$catid]['name'];
		$title .= '_'.table('status', $status, 'name');
		if ($modelid) $title .= table('model', $modelid, 'name');

		$cookie = factory::cookie();
		$size = $cookie->get('contentPageSize');
		$size = empty($size) ? 15 : $size;
		$this->view->assign('size', $size);
	
		  // 大数据查询
        $content_rows = explain_rows('content');
        $published_min = $content_rows ? date('Y-m-d', strtotime("-".EXPLAIN_PUBLISHED." days", time())) : 0;
        $this->view->assign('published_min', $published_min);

	
		$this->view->assign('head', array('title'=>$title));
		$this->view->assign('total', $this->content->total($modelid, $catid));
		$this->view->display('content/index');
	}

    /**
     * 编辑内容
     *
     * @aca 编辑
     */
	function edit()
	{
		$contentid = intval($_GET['contentid']);
		$modelid = table('content', $contentid, 'modelid');
		if(!$modelid) $this->showmessage('内容不存在！');
		$model = table('model', $modelid, 'alias');
		header('location:?app='.$model.'&controller='.$model.'&action=edit&contentid='.$contentid);	
	}

	/**
     * 网编工具箱编辑
     *
     * @aca 编辑
     */
	function miniedit()
	{
		$contentid = intval($_GET['contentid']);
		$modelid = table('content', $contentid, 'modelid');
		if(!$modelid) $this->showmessage('内容不存在！');
		$model = table('model', $modelid, 'alias');
		header('location:?app='.$model.'&controller='.$model.'&action=miniedit&contentid='.$contentid);	
	}

    /**
     * 删除至回收站
     *
     * @aca 删除至回收站
     */
	function delete()
	{
		$contentid = intval($_GET['contentid']);
		$modelid = table('content', $contentid, 'modelid');
		if(!$modelid) $this->showmessage('内容不存在！');
		$model = table('model', $modelid, 'alias');
		header('location:?app='.$model.'&controller='.$model.'&action=remove&contentid='.$contentid);
	}

    /**
     * 内容列表
     *
     * @aca 浏览
     */
	function page()
	{
		$allstatus = array(
			0 => '`unpublished`',	// 撤稿
			1 => '`created`',	// 草稿
			4 => '`unpublished`',	// 撤稿时间
			6 => '`published`',	// 发布时间
		);

		$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
		$pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize;
        $status		= (int) $_GET['status'];
        if((string)$_GET['status'] == "")
        {
            unset($_GET['status']);
        }
		$order	= explode('|', $_GET['orderby']);
		if (count($order) == 2)
		{
			$order	= $order[0] .' '. $order[1];
		}
		else
		{
			$order	= in_array($status, array_keys($allstatus)) ? $allstatus[$status] : '`published`';
			$order	.= 'desc';
		}

		$data = $this->content->ls($_GET, '*', $order, $page, $pagesize);
		$result = array('total'=>$this->content->total, 'data'=>$data);
		echo $this->json->encode($result);
	}

    /**
     * 搜索内容
     *
     * @aca 浏览
     */
	function search()
	{
		$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
		$modelid = isset($_GET['modelid']) ? intval($_GET['modelid']) : 0;
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;

		$this->view->assign('catname', $this->content->category[$catid]['name']);
		$this->view->assign('modelname', table('model', $modelid, 'name'));
		$this->view->assign('statusname', table('status', $status, 'name'));
		$this->view->display('content/search');
	}

    /**
     * 移动内容
     *
     * @aca 移动
     */
	function move()
	{
		if ($this->is_post())
		{
			$contentid = $_REQUEST['contentid'];
			$sourceid	= intval($_REQUEST['sourceid']);
			$catid = $_REQUEST['catid'];
			$count = $sourceid > 0 ? $this->content->move_by_catid($sourceid, $catid) : $this->content->move($contentid, $catid);
			$result = $count !== false ? array('state'=>true, 'count'=>$count) : array('state'=>false, 'error'=>$this->content->error());
			echo $this->json->encode($result);
		}
		else
		{
			$this->view->display('content/move', 'system');
		}
	}

    /**
     * 引用内容
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
					$result = $this->content->reference($contentid, $cid);
					if (!$result) break;
				}
			}
			else
			{
				$result = $this->content->reference($contentid, $catid);
			}
			$result = $result ? array('state'=>true) : array('state'=>false, 'error'=>$this->content->error());
			echo $this->json->encode($result);
		}
		else
		{
			$this->view->display('content/reference', 'system');
		}
	}

    /**
     * 彻底删除
     *
     * @aca 彻底删除
     */
	function clear()
	{
		@set_time_limit(600);
		
		$catid		= intval($_REQUEST['catid']);
		$modelid	= intval($_REQUEST['modelid']);
		$where = "status=0 AND ";
		if($catid) 
		{
			$childids = table('category', $catid, 'childids');
			$childids && $catid = $childids;
			$where .= "catid IN($catid) AND ";
		}
		$count = 0;
		$model = $modelid ? array($modelid => table('model', $modelid)) : table('model');
		foreach ($model as $modelid=>$m)
		{
			$contentid = $this->content->gets_field('contentid', $where."modelid=$modelid");
			if ($contentid)
			{
				if($modelid == 3)
				{
					$this->content->delete($contentid);
				}
				else
				{
					loader::model('admin/'.$m['alias'], $m['alias'])->delete($contentid);
				}
				$count += count($contentid);
			}
		}
		echo $this->json->encode(array('state'=>true, 'count'=>$count));
	}

    /**
     * 内容是否被锁定
     *
     * @aca 浏览
     */
	function islock()
	{
		$result = $this->content->islock($_REQUEST['contentid']) ? array('state'=>true) : array('state'=>false, 'error'=>$this->content->error());
		echo $this->json->encode($result);
	}


		/**
	 *	预览功能
	 *
	 */
	function preview ()
	{
		if ($this->is_post())
		{
			$cache = factory::cache();
			$catid = intval($_POST['catid']);
			$modelid = intval($_POST['modelid']);
			if (!$catid)
			{
				echo $this->json->encode(array('state' => false, 'error' => "请选择栏目！"));
				exit;
			}
			$data = $_POST;
			$modelname = table('model', $modelid, 'alias');

			$model = loader::model('admin/'.$modelname, $modelname);
			$pathdata = table('category', $catid, 'model');
			$pathdata = unserialize($pathdata);
			
			$cache_key = $this->_userid.TIME;
			if ($modelname != 'link' && $modelname != 'special') 
			{
				$path = $pathdata[$modelid]['template'];
				$this->template->assign($data);
				if ($templatedata = $this->template->fetch($path))
				{
					$cache->set($cache_key,"'".$templatedata."'", 1000);
				}

			}
			else if ($modelname == 'link')
			{
				header("location: ".$data['url']);
			}
			echo $this->json->encode(array('state' => true, 'key' => $cache_key));
		}
	}


           /**
     * 更新统计数据
     */
    function updateinfo()
    {
        $catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
        $modelid = isset($_GET['modelid']) ? intval($_GET['modelid']) : 0;

        $total = $this->content->total($modelid, $catid, true);
        echo $this->json->encode(array('state' => true, 'data' => $total));
    }


     /**
     * nlp提取关键词与摘要
     */
    function nlppretreat()
    {
        $title = value($_POST, 'title');
        $content = value($_POST, 'content');
        $modelid = value($_POST, 'modelid');

        $result = factory::nlp()->pretreat(array(
            'modelid'   => $modelid,
            'title'     => $title,
            'content'   => $content
        ));
        if ($result) {
            if (is_array($result['k'])) {
                $result['k'] = implode(' ', $result['k']);
            }
            echo $this->json->encode(array(
                'state' => true,
                'data' => array(
                    'tags' => $result['k'],
                    'description' => $result['m'],
                    'similar' => $result['s']
                )
            ));
            exit;
        }
        echo $this->json->encode(array(
            'state' => false
        ));
    }


	/**
	 * 对比标题
	 *
     * @aca 对比标题
	 * @param string title 文章标题
	 * @return array 
	 */
	function compare()
	{
		if (!$title = $_GET['title'])
		{
			$rst = array('state'=>false, 'total'=>0);
			exit($this->json->encode($rst));
		}
		$content = loader::model('admin/search_content');
		if ($data = $content->page(array('title'=>1, 'wd'=>$title),1,5))
		{
			$sim = 0;
			foreach ($data as $key => $item)
			{
				similar_text($title,strip_tags($item['title']), $sim);
				if ($sim < 80)
				{
					unset($data[$key]);
				}
			}
			$rst = array('state'=>(bool)count($data), 'data'=>$data, 'total'=>count($data));
		}
		else
		{
			$rst = array('state'=>false, 'total'=>0);
		}
		exit($this->json->encode($rst));
	}

      function nlpcompare()
      {
        $title = value($_POST, 'title');
        $content = value($_POST, 'content');
        $modelid = 1;

        $data = factory::nlp()->pretreat(array(
            'modelid'   => $modelid,
            'title'     => $title,
            'content'   => $content
        ));


        if ($data && is_array($data['s'])) {
            $articleModel = loader::model('admin/article', 'article');
            $result = array();
            foreach ($data['s'] as $id) {
                $item = $articleModel->get($id);
                if ($item) {
                    $result[] = array(
                        'url' => $item['url'],
                        'title' => $item['title']
                    );
                }
            }
            echo $this->json->encode(array(
                'state' => true,
                'data'  => $result
            ));
            exit;
        }

        echo $this->json->encode(array(
            'state' => false
        ));
    }
}
