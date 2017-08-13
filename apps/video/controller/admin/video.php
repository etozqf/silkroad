<?php
/**
 * 视频管理
 *
 * @aca 管理
 */
class controller_admin_video extends video_controller_abstract
{
    private $pagesize = 15, $modelid, $upload_max_filesize, $weight = null;

    /**
     * @var model_admin_video
     */
    private $video;
	
	function __construct($app)
	{
		parent::__construct($app);
		$this->video = loader::model('admin/video');
		$this->weight = loader::model('admin/admin_weight', 'system');
		$this->modelid = $this->video->modelid;
		
		$this->upload_max_filesize = size_calculate(ini_get('upload_max_filesize'));
		
		if (isset($_REQUEST['catid'])) $this->priv_category($_REQUEST['catid']);
	}
	
	public function __call($method, $args)
	{
		if(in_array($method, array('delete', 'clear', 'remove', 'restore', 'restores', 'approve', 'pass', 'reject', 'islock', 'lock', 'unlock', 'publish', 'unpublish'), true)) 
		{
			$var = in_array($method, array('clear', 'restores')) ? 'catid' : 'contentid';
			$result = $this->video->$method($_REQUEST[$var]) ? array('state'=>true) : array('state'=>false, 'error'=>$this->video->error());
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
        if (!array_key_exists(modelid('video'), category_model($catid)))
        {
            $this->showmessage('该栏目下不允许发布视频');
        }

		if($this->is_post())
		{
			$_POST['video'] = trim($_POST['video']);

			if ($contentid = $this->video->add($_POST))
			{
				if($_POST['v56'])
				{
					$v56_model = loader::model('admin/video_56', 'video');
					$v56_model->insert(array(
						'contentid' => $contentid,
						'vid' => $_POST['v56'],
						'state' => 0,
						'time' => TIME
					));
				}
                $result = array('state'=>true, 'contentid' => $contentid);
                $content = $this->video->get($contentid, 'url, status');
                $content['status'] == 6 && ($result['url'] = $content['url']);
                //丝路网新增加接口调用代码 start
              	$data = table('content',$contentid);
				intface($data['publishedby'],0,$data['modelid'],table('category',$data['catid'],'name'),$contentid,'add');
				//丝路网新增加接口调用代码 end
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->video->error());
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
			              'editor'=>table('admin', $this->_userid, 'name'),
			              'allowcomment'=>1,
			              'saveremoteimage'=>1,
			             );
			$this->view->assign('openserver', $this->setting['openserver'] ? 1 : 0);
			$this->view->assign($data);
			$this->view->assign('get_tag', setting('system', 'get_tags'));
			$this->view->assign('catname', $this->video->category[$catid]['name']);
			$this->view->assign('upload_max_filesize',$this->upload_max_filesize);
			$this->view->assign('head', array('title'=>'发布视频'));
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
        	$_POST['video'] = trim($_POST['video']);
            if ($this->video->edit($contentid, $_POST))
            {
            	if($_POST['v56'])
				{
					$v56_model = loader::model('admin/video_56', 'video');
					if ($id = value($v56_model->get(array('contentid'=>$contentid)), 'id', false))
					{
						$v56_model->update(array(
							'contentid' => $contentid,
							'vid' => $_POST['v56'],
							'state' => 0,
							'time' => TIME
						), $id);
					}
					else
					{
						$v56_model->insert(array(
							'contentid' => $contentid,
							'vid' => $_POST['v56'],
							'state' => 0,
							'time' => TIME
						));
					}
				}
                $result = array('state'=>true);
                $content = $this->video->get($contentid, 'url, status');
                $content['status'] == 6 && ($result['url'] = $content['url']);
                //丝路网新增加接口调用代码 start
              	$data = table('content',$contentid);
				intface($data['publishedby'],0,$data['modelid'],table('category',$data['catid'],'name'),$contentid,'add');
				//丝路网新增加接口调用代码 end
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->video->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$myweight = $this->weight->get_weight($this->_userid);
			$contentid = intval($_GET['contentid']);
			$data = $this->video->get($contentid, '*', 'get', true);
			if (!$data) $this->showmessage($this->video->error());
			$this->priv_category($data['catid']);
			$data['playtime'] = $data['playtime']?$data['playtime']:'';
			$this->view->assign('openserver', $this->setting['openserver'] ? 1 : 0);

			$this->video->lock($contentid);
			$this->view->assign($data);
			$this->view->assign('myweight', $myweight);

			$this->view->assign('upload_max_filesize',$this->upload_max_filesize);	
			$this->view->assign('head', array('title'=>'编辑视频：'.$data['title']));
			$this->view->display('edit');
		}
	}

	/**
     * mini编辑
     *
     * @aca 编辑
     */
	public function miniedit() {
		$myweight = $this->weight->get_weight($this->_userid);
		$contentid = intval($_GET['contentid']);
		$data = $this->video->get($contentid, '*', 'get', true);
		if (!$data) $this->showmessage($this->video->error());
		
		$this->priv_category($data['catid']);
		
		$data['playtime'] = $data['playtime']?$data['playtime']:'';
		
		$this->view->assign('ccid',setting('system', 'ccid'));
		$this->view->assign('openserver',$this->setting['openserver'] ? 1 : 0);
		
		$this->video->lock($contentid);
		$this->view->assign($data);
		$this->view->assign('myweight', $myweight);
		
		$this->view->assign('upload_max_filesize',$this->upload_max_filesize);	
		$this->view->assign('head', array('title'=>'编辑视频：'.$data['title']));
		$this->view->display('miniedit');
	}

    /**
     * 查看
     *
     * @aca 查看
     */
	function view()
	{
		$this->video->is_backend = true;
		$r = $this->video->get(intval($_GET['contentid']), '*', 'view',true,true);
		if (!$r) $this->showmessage($this->video->error());	
		
		$r['autostart'] = 'false';
        $player = getVideoPlayer($r['video']);
        if($player)
        {
            $r = array_merge($r, $player);
        }
		
		$this->priv_category($r['catid']);

        $this->template->assign($r);
        $this->template->assign(array('width'=>530,'height'=>380));
        $r['playercode'] = $this->template->fetch('video/player/'.$r['player'].'.html', 'video');

		$this->view->assign($r);  
		$this->view->assign('head', array('title'=>$r['title']));
		$this->view->display('view');
	}

	/**
	 * 生成调用代码HTML
     *
     * @aca 获取调用代码
	 */
	function code()
	{
		!isset($_GET['contentid']) && $this->showmessage('数据编号错误！');;
	    $r = $this->video->get(intval($_GET['contentid']), '*', null,true,true);
		if (!$r)
		{
			$this->showmessage($this->survey->error());
		}
	  	if ($r['status'] != 6) $this->showmessage('数据状态错误，请先发布！');
	    $r['autostart'] = 'true';
        $player = getVideoPlayer($r['video']);
        if($player)
        {
            $r = array_merge($r, $player);
        }

		$this->template->assign($r);
		$code = $this->template->fetch('video/player/'.$r['player'].'.html', 'video');
		$this->view->assign('code', $code);
		$this->view->display('code');
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
        
		$data = $this->video->content->related($catid, $modelid, $keywords, $page, $pagesize);
		$result = $page == 1 ? array('state'=>true, 'data'=>$data, 'total'=>$this->video->related_total($catid, $modelid, $keywords)) : array('state'=>true, 'data'=>$data);
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
					$result = $this->video->reference($contentid, intval($cid));
					if (!$result) break;
				}
			}
			else
			{
				$result = $this->video->reference($contentid, intval($catid));
			}
			$result = $result ? array('state'=>true) : array('state'=>false, 'error'=>$this->video->error());
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
			$result = $this->video->move($contentid, $catid) ? array('state'=>true, 'contentid'=>$contentid) : array('state'=>false, 'error'=>$this->video->error());
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
     * 上传
     *
     * @aca 上传
     */
	function upload()
	{
		$attachment = loader::model('admin/attachment', 'system');
		$file = $attachment->upload('ctvideo',true, null,'swf|flv|avi|wmv|rm|rmvb|mp4',$this->upload_max_filesize,array());
		echo $file ? $attachment->aid[0].'|'.$file : '0';
	}

    /**
     * @aca public
     */
    function picker()
    {
        if ($this->is_post()) {
            $where = array("c.`status` = 6");
            if (!empty($_POST['keyword'])) $where[] = where_keywords("c.`title`", $_POST['keyword']);
            if (!empty($_POST['mobile'])) $where[] = "IS_MOBILE(v.`video`, ".(setting('cloud', 'spider_allowed') ? '1' : '0').") = 1";
            if (!empty($_POST['catid']) && ($catids = explode(',', $_POST['catid']))) {
                if ($catids = array_filter($catids, 'intval')) {
                    $catid = array();
                    foreach ($catids as $_catid) {
                        $catid[] = $_catid;
                        if ($childids = $this->video->content->category[$_catid]['childids']) {
                            $catid[] = $childids;
                        }
                    }
                    if ($catid) {
                        $catid = explode(',', (implode(',', $catid)));
                        $catid = implode(',', (array_unique($catid)));
                    }
                }
                if ($catid) $where[] = "c.`catid` IN(" . $catid . ")";
            }

            $where = implode(' AND ', $where);
            $fields = "c.`contentid`, c.`title`, c.`url`, c.`thumb`, c.`tags`, c.`sourceid`, c.`published`, c.`weight`, c.`pv`, v.*";
            $order = "c.`published` DESC";
            $from = "`#table_video` v LEFT JOIN `#table_content` c ON v.`contentid` = c.`contentid`";

            $pagesize = intval(value($_REQUEST, 'pagesize'));
            if ($pagesize) {
                $page = max(0, intval(value($_REQUEST, 'page')) - 1);
                $offset = $page * $pagesize;
                $limit = " LIMIT {$offset}, {$pagesize}";
            } else {
                $limit = '';
            }

            $db = factory::db();
            $data = $db->select("SELECT {$fields} FROM {$from} WHERE {$where} ORDER BY {$order}{$limit}");
            if ($data) {
                foreach ($data as &$d) {
                    $d['time'] = date('Y-m-d H:i', $d['published']);
                    $d['thumb'] = abs_uploadurl($d['thumb']);
                    $d['tips'] = $this->_picker_tips($d);
                    if ($d['sourceid']) {
                        $d['source'] = table('source', intval($d['sourceid']), 'name');
                    }
                }
                $total = $db->get("SELECT COUNT(*) AS `total` FROM {$from} WHERE {$where}");
                $data = array(
                    'state' => true,
                    'data' => $data,
                    'total' => $total['total']
                );
            } else {
                $data = array('state' => false);
            }
            exit($this->json->encode($data));
        }

        $mobile = intval(value($_GET, 'mobile'));
        $this->view->assign('mobile', $mobile);

        $multiple = intval(value($_GET, 'multiple'));
        $this->view->assign('multiple', $multiple);

        $this->view->assign('head', array('title' => htmlspecialchars(value($_GET, 'title', $mobile ? '选取PC端视频' : '选取视频'))));
        $this->view->display('picker');
    }

    protected function _picker_tips($item)
    {
        $tips = array(
            '标题：'.$item['title'],
            '链接：'.$item['url'],
            'Tags：'.$item['tags'],
            '点击：'.$item['pv'],
            '权重：'.$item['weight']
        );
        return implode('<br />', $tips);
    }

    /**
     * @aca public
     */
    function detail()
    {
        $contentid = intval($_REQUEST['contentid']);
        if (!$contentid
            || !($content = $this->video->get($contentid, '*', 'get'))
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
		
		$publishid = $this->video->content->cron_publish($this->modelid);
		if ($publishid) array_map(array($this->video, 'publish'), $publishid);
		
		$unpublishid = $this->video->content->cron_unpublish($this->modelid);
		if ($unpublishid) array_map(array($this->video, 'unpublish'), $unpublishid);
		
		exit ('{"state":true}');
	}

	/**
     * 56网视频转换为mp4
     *
     * state: 0-待转换 1-已转换
     * @aca cron
     */
	function cron_v56()
	{
		@set_time_limit(600);
		$v56_model = loader::model('admin/video_56', 'video');
		// 一般转码时间不超过1小时，考虑可能存在转码失败的视频，故忽略一小时前的视频
		$data = $v56_model->select('`state`=0 AND `time`>'.(TIME-3600), 'id,vid,contentid,time', '`time` desc', 10);
		$count = 0;
		foreach($data as $item)
		{
			$content = value(request(APP_URL.'?app=cloud&controller=video56&action=get_mp4&vid='.$item['vid']), 'content', '');
			if (!($content = $this->json->decode($content)) || ($content['status'] < 1) || (empty($content['info']['rfiles'])))
			{
				continue;
			}
			$video = $content['info']['rfiles'][0]['url'];
			if (strpos($video, '?')) {
				// SQL里的IS_MOBILE函数要求结尾必须是.mp4
				$video .= '&.mp4';
			}
			if ($this->video->update(array(
				'video' => $video
			), $item['contentid'])) 
			{
				$v56_model->update(array(
					'state' => 1
				), $item['id']);
				$count++;
			}
		}
		echo $this->json->encode(array(
			'state' => true,
			'info' => "转换成功{$count}个"
		));
	}

    /**
     * 广告设置
     *
     * @aca 广告设置
     */
    public function setting_ads()
    {
        if($this->is_post())
        {
            $data = array(
                'ads'=>array(
                    'begin'=>$_POST['ads']['begin'],
                    'pause'=>$_POST['ads']['pause'],
                    'end'=>$_POST['ads']['end'],
                )
            );
            $setting = new setting();
            $result = $setting->set_array($this->app->app, $data) ? array('state'=>true,'message'=>'保存成功') : array('state'=>false,'error'=>'保存失败');
            echo $this->json->encode($result);
            exit;
        }
        $ads = $this->setting['ads'];
        $head = array('title'=>'广告设置');
        $this->view->assign('head', $head);
        $this->view->assign('ads', $ads);
        $this->view->display('setting_ads');
    }
}