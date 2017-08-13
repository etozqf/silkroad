<?php
/**
 * 国别报告
 * 
 * @author  houxiaowen@cmstop.com
 */
class controller_index extends country_controller_abstract
{
	private $country, $cache, $property;
	public $config;

	function __construct($app)
	{
		parent::__construct($app);
		$this->config = loader::import('config.country');
        $this->country = loader::model('country');
        $this->property = loader::model('admin/property', 'system');
		$this->content = loader::model('admin/content', 'system');
		$this->category = loader::model('admin/category', 'system');
        $this->cache = factory::cache();

	}

	//中文下该国家的内容
	public function index()
	{
		$proid = intval($_GET['proid']); //国家ID
		//没有国家跳转至国别列表
		if (!$proid) $this->showmessage('请选择国家', WWW_URL, 2000, false);
        $res = $this->getdata($proid, $this->config['gbbg']);
        $pro = $this->property->get($proid);
		$jianjie = $this->getjianjie($proid, $this->config['jianjie']);
		$this->template->assign($pro);
		$this->template->assign($proid);
		$this->template->assign('res', $res);
		$this->template->assign('jianjie', $jianjie);
    	$this->template->display('country/index.html');
	}

	//ajax请求生成中文pdf
	public function makepdf()
	{
		// 判断登陆
		// if ($this->_userid) {
			$contentids = trim($_POST['contentids'], ',');
			$proid = intval($_POST['proid']);
			$msg = $this->config['pdfmsg'];
			$dir = 'pdf/';
			if ($proid && $contentids) {
				$this->check_readprivileges($contentids);
				$pdf = $this->getpdf($contentids, $proid, $msg, $dir);
				$pdf = $pdf."?auth=".md5('Hmy5861e55aa30882f84ba20d60ad3874c2');
				if ($pdf) {
					//记录请求者信息
					$this->country->add($pdf, $this->_userid, $contentids, 1, table('property', $proid, 'name'));
					$result = array('state' => ture, 'data' => $pdf);
				} else {
					$result = array('state' => false, 'message' => 'PDF生成失败');
				}
			} else {
				$result = array('state' => false, 'message' => '请选择报告和国家');
			}
			
		// } else {
			// $result = array('state' => false, 'message' => 'nologin');
		// }
		// $result = array('state' => false, 'error' => '验证码不正确');
        echo $this->json->encode($result);
	}

	//英文下该国家的内容
	public function en_index()
	{
		$proid = intval($_GET['proid']); //国家ID
		$id = array('url' => table('page','39','url'));   //标记中文跳转
		//没有国家跳转至国别列表
		if (!$proid) $this->en_message(table('page','39','url'), 'Please select a country', 2000, false);
        $res = $this->getdata($proid, $this->config['en_gbbg']);
        $pro = $this->property->get($proid);
		$jianjie = $this->getjianjie($proid, $this->config['en_jianjie']);
		$this->template->assign($pro);
		$this->template->assign($proid);
		$this->template->assign('res', $res);
		$this->template->assign('jianjie', $jianjie);
    	$this->template->display('cn/country/report-generate.html');
	}

	//ajax请求生成英文pdf
	public function en_makepdf()
	{
		// 判断登陆
		// if ($this->_userid) {
			$contentids = trim($_POST['contentids'], ',');
			$proid = intval($_POST['proid']);
			$msg = $this->config['en_pdfmsg'];
			$dir = 'en_pdf/';
			if ($proid && $contentids) {
                $this->check_readprivileges($contentids);
				$pdf = $this->getpdf($contentids, $proid, $msg, $dir, 2, table('property', $proid, 'alias'));
				if ($pdf) {
					//记录请求者信息
					$this->country->add($pdf, $this->_userid, $contentids);
					$result = array('state' => ture, 'data' => $pdf);
				} else {
					$result = array('state' => false, 'message' => 'PDF Generation failure');
				}
			} else {
				$result = array('state' => false, 'message' => 'Please select a country or content');
			}
			
		// } else {
			// $result = array('state' => false, 'message' => 'nologin');
		// }
        echo $this->json->encode($result);
	}

	//获取左侧简介 有缓存直接返回
	private function getjianjie($proid, $jianjie)
	{
		//缓存
		if ($this->config['iscache']) {
			$res = $this->cache->get('table_jianjie' . md5('jianjie' . $proid . $jianjie));
	        if ($res) {
	        	return $res;	
	        }
		}
		$res = $this->country->get_jianjie($proid, $jianjie);
		//缓存
        if ($this->config['iscache'] && $res) {
        	$this->cache->set('table_jianjie' . md5('jianjie' . $proid . $jianjie), $res, $this->config['cachetime'] * 60);
        }
		return $res;
	}

	//获取PDF地址 有缓存直接返回
	private function getpdf($contentids, $proid, $msg, $dir)
	{
		//缓存
		if ($this->config['iscache']) {
			$pdf = $this->cache->get('table_pdf' . md5('pdf' . $contentids));
	        if ($pdf) {
	        	return $pdf;	
	        }
		}
		$create = $this->config['create'];
		$fill = $this->$create($contentids, $proid, $msg, $dir);
		//缓存
        if ($this->config['iscache'] && $fill) {
        	$this->cache->set('table_pdf' . md5('pdf' . $contentids), $fill, $this->config['cachetime'] * 60);
        }
		return $fill;
	}

	//生成PDF
	private function tcpdf($contentids, $proid, $msg, $dir)
	{
		$data = $this->country->get_pdfdata($contentids);
		$pro = table('property', $proid, $msg['SelfName']);
		$pdf = loader::lib('createPDF'); //实例化PDF类
    	$pdf->create($data, $pro, $msg); //生成报告PDF
    	//记录错误日志
		if ($pdf->error_msg && $this->config['iserrorlog']) {
    		$text = UPLOAD_PATH . $dir . '/error/';
    		if(!file_exists($text)) mkdir($text , 0777, true);
    		$text .= date('Y_m_d') . '_error.log';
    		file_put_contents($text, $pdf->error_msg, FILE_APPEND);
    		// return false;
    	}
    	$path = $dir . date('Ymd') . '/';
    	if(!file_exists(UPLOAD_PATH . $path)) mkdir(UPLOAD_PATH . $path, 0777, true);
    	$fill = $path . md5(time() . $_userid) .'.pdf';
    	$pdf->Output(UPLOAD_PATH . $fill, 'F');//输出PDF
    	$fill = UPLOAD_URL . $fill; 
		// 设置文档信息 
    	
    	return $fill;
	}

	//wkhtmltopdf生成PDF
	function wkhtmltopdf($contentids, $proid, $msg, $dir)
	{
    	$data = $this->country->get_pdfdata($contentids);
		$pro = table('property', $proid, $msg['SelfName']);
		$wk = $this->config['wkhtmltopdf'];
		// $headps = '--header-left ' . $pro . $msg['msg'] . '--(' . $msg['ps'] . ')';
		// $parameter = "$headps --encoding utf-8 --header-line --footer-line --footer-right [page]/[toPage] --footer-line toc --toc-header-text '55555555'";
		$parameter = "$headps --encoding utf-8 --header-line --footer-line --footer-right [page]/[toPage] --footer-line";
		$path = $dir . date('Ymd') . '/';
    	if(!file_exists(UPLOAD_PATH . $path)) mkdir(UPLOAD_PATH . $path, 0777, true);
    	$fill = $path . md5(time() . $this->_userid);
		$path_fill = UPLOAD_PATH . $fill . '.pdf';

		$data = $this->temhtml($data, $pro, $msg, $fill);
		PassThru("$wk $parameter $data $path_fill");

		unlink($data);
		if (file_exists($path_fill)) {
			return UPLOAD_URL . $fill . '.pdf';
		}
		return false;
	}
	
	//为wkhtmltopdf生成pdf格式化数据
	private function temhtml($data, $pro, $msg, $fill)
	{
		$name = $msg['SelfName'];
		$table = $msg['table'];
		$ps = $msg['ps'];
		$msgs = $msg['msg'];
		$str = $msg['str'];
    	$html = "<!DOCTYPE html><html lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><title>$pro</title>";
    	//pdf样式
    	$html .= "<style>body{font-size: 20px;} #ps{font-size: 14px;border:1px solid black;} #fengmian{font-size: 40px;line-height:45px;text-align:center;margin:300px auto;} li{list-style: none;} .titlez{text-align:center;margin:20px auto;} .namez{font-size: 25px;font-weight:bold;position:relative;} .namez a{background:#FFF; position:relative; z-index: 10;}";
    	// $html .= ".lines{border-top: 2px dotted #c9c9c9;position: absolute;top:15px;width: 100%;}";
    	$html .= ".content{width:95%;margin:10px auto;line-height:25px;} a{text-decoration: none;color:black;} .addpage{margin:20px auto;page-break-inside:avoid;} .content p{text-indent:2em;}</style>";
    	//pdf内容
    	$html .= "</head><body><div height='400'><div style='color:#FFF;'> ps:</div><span id='ps'>$ps</span></div><div id='fengmian' height='800'><p>$pro</p><p>$msgs</p><p>$str</p></div><div class='addpage'><div class='namez titlez'>$table</div><ul>";
    	$j = 0;
		foreach($data as $k => $v) {
			$j++;
		  	$html .= "<li class='namez'><div class='lines'></div><a href='#$v[catid]bot'>$j. $v[$name]</a></li>";
		}
		$html .= "</ul></div><div>";
		$j = 0;
		foreach($data as $k => $v) {
			$j++;
		  	$html .= "<div name='$v[catid]bot' class='namez'>$j. $v[$name]</div>";
		  	$html .= "<div  class='content'>$v[content]</div>";
		}
		$html .= "</div></body></html>";
		$fill = UPLOAD_PATH . $fill . '.html';
		file_put_contents($fill, $html);
    	return $fill;
	}

	private function getdata($proid, $catpid)
	{
		//缓存
		if ($this->config['iscache']) {
			$catdata = $this->cache->get('table_country' . md5('country' . $proid));
	        if ($catdata) {
	        	return $catdata;	
	        }
		}
        $catdata = $this->country->cat($catpid);
        $res = array();
		foreach ($catdata as $k => &$v) {
			$v['content'] = $this->country->get_content($proid, $v['catid']);
			if($v['content']) {
				$v['content']['content'] = readprivileges($v['content']['contentid'], $this->_userid) ? $v['content']['content'] : str_cut(strip_tags($v['content']['content']), 400);
			}
		}
		//缓存
        if ($this->config['iscache'] && $catdata) {
        	$this->cache->set('table_country' . md5('country' . $proid), $catdata, $this->config['cachetime'] * 60);
        }
		return $catdata;
	}

	public function en_message($url, $message, $ms, $success)
	{
		$id = array(
				'url'  	  => $url,
				'message' => $message,
				'ms' 	  => $ms,
				'success' => $success,
			);
		header('location:' . url('country/index/message/', $id));
	}
	public function message()
	{
		$this->template->assign('url', $_GET['url']);
		$this->template->assign('ms', $_GET['ms']);
		$this->template->assign('success', $_GET['success']);
		$this->template->assign('message', $_GET['message']);
    	$this->template->display('cn/country/message.html');
	}

    private function check_readprivileges($contentids) {
        $contentidarr = !is_array($contentids) ? array_map('intval', explode(',', $contentids)) : array_map('intval', $contentids);
        $needbuyids = array();
        foreach($contentidarr as $contentid) {
            if(!readprivileges($contentid, $this->_userid)) {
                $needbuyids[] = $contentid;
            }
        }
        if($needbuyids) {
            $contents = $this->content->select("contentid IN (".implode(',', $needbuyids).")", 'catid');
            $catids = array();
            foreach($contents as $content) {
                $catids[] = $content['catid'];
            }
            if($catids) {
                $categorys = $this->category->select("catid IN (".implode(',', $catids).")", 'name');
                $catnames = '';
                foreach($categorys as $cat) {
                    $catnames .= $cat['name'].'-';
                }
                if($catnames) {
                    $catnames = substr($catnames, 0, -1);
                }
                exit($this->json->encode(array('state' => false, 'message' => $catnames.' 需要购买后才能下载报告')));
            }
        }

    }

}
