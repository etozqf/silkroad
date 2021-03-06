<?php
class model_admin_html
{
    public $setting, $html_root, $www_root;
    private $uri;

	function __construct()
	{
		$this->db = factory::db();
		$this->tpl = factory::template('magazine');
		import('helper.folder');
        $this->setting = setting::get('magazine');
        $this->uri = loader::lib('uri','system');
        $u = $this->uri->psn($this->setting['path']);
        $this->html_root = $u['path'];
        $this->www_root  = $u['url'];
	}
	
	/********************************发布杂志列表页******************************************/
	public function index()
	{
		$html = $this->tpl->fetch('magazine/index.html');
        if(!is_dir($this->html_root))
        {
            folder::create($this->html_root);
        }
		write_file($this->html_root.'/index.shtml', $html);
		return true;
	}
	
	//发布一期
	public function edition($eid)
	{
		$e = table('magazine_edition', $eid);
		$prev_e = table('magazine_edition', $eid-1);
		$next_e = table('magazine_edition', $eid+1);
		if ($e['mid'] != $prev_e['mid'])
		{
			$prev_e = false;
		}
		if ($e['mid'] != $next_e['mid'])
		{
			$next_e = false;
		}
		$this->tpl->assign($e);
		$this->tpl->assign('prev', $prev_e);
		$this->tpl->assign('next', $next_e);
		if(!$e) return false;
		
		$m = table('magazine', $e['mid']);
		if(!$m) return false;
		$this->tpl->assign('m', $m);
		$this->elist($e['mid'], $m['default_year']);

		$html = $this->tpl->fetch($m['template_content']);
		$file = $this->html_root."/{$m['alias']}/{$e['eid']}/index.shtml";//此处按照丝路客户要求,去掉url里面的年份
		folder::create(dirname($file));
		write_file($file, $html);	
		//修改发布状态
		if($e['disabled'] != 1)
		{
			$sql = "UPDATE #table_magazine_edition SET disabled = 1 WHERE eid = $eid";
			$this->db->exec($sql);
		}
	}

	/**
	 * 更新杂志的年份/期列表
	 *
	 * @param int $mid 杂志id
	 * @param int $year 更新的年份，若为0则更新所有年
	 */
	public function elist($mid, $year=0)
	{
		$mid = intval($mid);
		$mgz = table('magazine', $mid);
		if(!$mgz) return false;
		
		$sql = "SELECT default_year FROM #table_magazine WHERE mid = $mid ";
		$years = $this->db->select($sql);
		if(!$years) return false;
		$updateYears = $year ? array(array('year' => $year)) : $years;
		
		foreach ($updateYears as $y)
		{
			$this->year($mid, $y['year'], $mgz);
		}
		$this->index();
	}
	
	//生成一份杂志一年的year
	private function year($mid, $year, $mgz)
	{
		$html = $this->tpl->fetch($mgz['template_list']);
		
		$yurl = "/{$mgz['alias']}/";//此处按照丝路客户要求,去掉url里面的年份
		$file = $this->html_root.$yurl.'index.shtml';
		folder::create(dirname($file));
		write_file($file, $html);
	}
	
	//休眠，即删除本期静态页面
	public function delEdition($eid)
	{
		$edition = loader::model('admin/edition','magazine');
		$magazine = loader::model('admin/magazine','magazine');
		$e = $edition->get($eid);
		$m = $magazine->get($e['mid']);
		$edition->set_field('disabled', 2, $eid);
		$dir = $this->html_root."/{$m['alias']}/$eid/";
		folder::delete($dir);
		return true;
	}
}