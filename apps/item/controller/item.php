<?php
class controller_item extends item_controller_abstract
{
	private $item,$page,$pagesize,$where,$fields,$order;

	function __construct($app)
	{
		parent::__construct($app);
		$this->item = loader::model('admin/item');
		$this->category = loader::model('category', 'system');
	}

    function pagesize(){
        $this->page = isset($_GET['page'])?max(intval($_GET['page']),1):1;
        $this->pagesize = empty($_GET['pagesize']) ? 10 : intval($_GET['pagesize']);
        $this->where['catid'] =  empty($_GET['catid']) ? 51 : intval($_GET['catid']);
        console('分类');
        console($_GET);
        foreach ($_GET as $key => $value) {
           if($key>0){
                $v = explode("_",$value);
                if(in_array($v[0],$this->item->itemtype)){
                    $this->where[$v[0]][] = $v[1];
                }elseif(in_array($v[1], array('desc','asc'))){
                    if($v[0]=="weight"){
                        $this->order ? $this->order = $this->order.",c.$v[0] $v[1]" : $this->order = " c.$v[0] $v[1]";
                    }else{
                        $this->order ? $this->order = $this->order.",i.$v[0] $v[1]" : $this->order = " i.$v[0] $v[1]";
                    }
                }else{
                    $this->where[$v[0]] = $v[1];
                }
           }
        }
        $this->where['status'] = 6;
        $this->where['modelid'] = 11;
        $this->where['minsum'] = empty($_GET['minsum'])?null:intval($_GET['minsum']);
        $this->where['maxsum'] = empty($_GET['maxsum'])?null:intval($_GET['maxsum']);
        $this->where['currency'] = empty($_GET['currency'])?null:$_GET['currency'];//CNY或者USD

        $this->fields = 'c.thumb,c.contentid,c.catid,c.modelid,c.title,c.url,i.description,i.stoptime,i.starttime,i.itemsum';
        if(!$this->order)$this->order = "i.starttime desc";
        $data = $this->item->seachls($this->where,$this->fields,$this->order,$this->page,$this->pagesize);


        $result = $this->json->encode($data);
        echo ($_GET['jsoncallback']."(".$result.")");
    }

    function selectcountry(){
        if($this->is_post())
        {
            $data = $_POST;
            foreach ($data as $key => $value)
            {
                $result[] = explode('_',$value);
            }
            echo $this->json->encode($result);
        }
        else
        {
            $this->view->display('item/selectcountry');
        }
    }
	
	function fulltext()
	{
		if (!$contentid = intval($_GET['contentid'])) {
			$this->showmessage('ID不存在');
		}
		$r = $this->item->get($contentid, 'content');
		$r['content'] = preg_replace('/<p\s*[^>]*>(\s|&nbsp;)*<\/p>/isU', '', $r['content']);
		$r = $this->json->encode($r);
		echo $_GET['jsoncallback']."($r);";
	}
	
	function printing()
	{
		$contentid = intval($_GET['contentid']);
		if ($this->system['pagecached'])
		{
			$keyid = md5('pagecached_item_item_printing_' .$contentid);
			cmstop::cache_start($this->system['pagecachettl'], $keyid);
		}
				
		$data = $this->item->get($contentid);
		if($data['modelid']!=1 || $data['status'] != 6) return $this->showmessage('没有此打印内容！');
		$this->template->assign('pos', $this->category->pos($data['catid']));
		$this->template->assign($data);
		$this->template->display('item/print.html');
		
		if ($this->system['pagecached']) cmstop::cache_end();
	}

    function show()
    {
        loader::import('lib.addon', app_dir('mobile'));
        foreach (array('mobile_content', 'mobile_slider') as $tag) {
            $this->template->set_rule('/{\/('.$tag.')}/', '<?php endforeach; unset(\$_$1); ?>');
            $this->template->set_rule('/{('.$tag.')(\s+[^}]+?)(\/?)}/e', 'self::_tag_parse(\'$1\', \'$2\', \'$3\')');
        }

        loader::import('lib.function', app_dir('mobile'));
        $contentid = intval(value($_GET, 'contentid'));
        loader::model('pv', 'system')->get($contentid);

        $debug = intval(value($_GET, 'debug'));
        $ttl = intval(setting('mobile', 'cache_content'));
        $pageTpl = 'mobile/touch/item/show.html';

        // 缓存
        if ($ttl && $debug !== 1) {
            $pageHtml = $this->cache->get('mobilepage_'.md5($contentid.$pageTpl));
            if ($pageHtml) {
                exit($pageHtml);
            }
        }

        $content = $this->_get_content($contentid);

        $content['content'] = preg_replace("/<p class\=\"mcePageBreak\">(.*?)<\/p>/", '', $content['content']);

        $this->template->assign('content', $content);
        $pageHtml = $this->template->fetch($pageTpl);
        if ($ttl) {
            $this->cache->set('mobilepage_'.md5($contentid.$pageTpl), $pageHtml, $ttl);
        }

        echo $pageHtml;
    }

    protected function _get_content($contentid)
    {
        if (!$contentid
            || !($data = $this->item->get($contentid))
            || $data['status'] != 6
            || $data['modelid'] != 1
        ) {
            $this->_not_found();
        }
        return $data;
    }

    protected function _not_found()
    {
        header('HTTP/1.1 404 Not Found');
        $this->template->display('mobile/404.html');
        exit;
    }

}