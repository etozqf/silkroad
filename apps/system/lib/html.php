<?php

class html extends object
{
	private $template, $uri, $category;

	function __construct()
	{
		import('helper.folder');
		$this->template = factory::template();
		$this->uri = loader::lib('uri', 'system');
		$this->category = table('category');
	}
	
	function picture()
	{
    	$channels = channel();
    	$this->template->assign('channels', $channels);
    	$data = $this->template->fetch('picture/index.html');
		$filename = WWW_PATH.'picture/index.shtml';
		folder::create(dirname($filename));
		write_file($filename, $data);
	}
	
	function interview()
	{
    	$channels = channel();
    	$this->template->assign('channels', $channels);
    	$data = $this->template->fetch('interview/index.html');
		$filename = WWW_PATH.'interview/index.shtml';
		folder::create(dirname($filename));
		write_file($filename, $data);
	}
	
	function special()
	{
    	$channels = channel();
    	$this->template->assign('channels', $channels);
    	$data = $this->template->fetch('special/index.html');
		$filename = WWW_PATH.'special/index.shtml';
		folder::create(dirname($filename));
		write_file($filename, $data);
	}
	
	function map()
	{
    	$channels = channel();
    	$this->template->assign('channels', $channels);
    	$data = $this->template->fetch('system/map.html');
		$filename = WWW_PATH.'map/index.shtml';
		folder::create(dirname($filename));
		write_file($filename, $data);
	}
	
	function tags()
	{
		$letters = array('a','b','c','d','e','f','g','h','i','g','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
    	$this->template->assign('letters', $letters);
    	$data = $this->template->fetch('system/tags.html');
		$filename = WWW_PATH.'tags/index.shtml';
		folder::create(dirname($filename));
		write_file($filename, $data);
	}
	
	function createIndex()
	{
    	$data = $this->template->fetch('index.html');
		$filename = WWW_PATH.'index.shtml';
		folder::create(dirname($filename));
		write_file($filename, $data);
	}
	
	function rank()
	{
    	$channels = channel();
    	$this->template->assign('channels', $channels);
		$this->template->assign('catid', 0);
    	$data = $this->template->fetch('system/rank.html');
		$filename = WWW_PATH.'rank/index.shtml';
		folder::create(dirname($filename));
		write_file($filename, $data);
		
		foreach ($channels as $catid=>$c)
		{
			$this->template->assign($c);
        	$data = $this->template->fetch('system/rank_list.html');
			$filename = WWW_PATH.'rank/'.$c['alias'].'.shtml';
			folder::create(dirname($filename));
			write_file($filename, $data);
		}
	}
	
	function roll($catid = 0, $date = null)
	{
    	$channels = channel();
    	$this->template->assign('channels', $channels);
    	$this->template->assign('date_select', $this->date($date));
    	$channelids = is_array($catid) ? $catid : ($catid ? array($catid) : array_keys($channels));
    	
    	if (is_null($date))
    	{
			$this->template->assign('catid', 0);
			$this->template->assign('catids', 0);
        	$data = $this->template->fetch('system/roll.html');
			$filename = WWW_PATH.'roll/index.shtml';
			folder::create(dirname($filename));
			write_file($filename, $data);
			
	    	foreach ($channelids as $catid)
			{
				$catids = $this->category[$catid]['childids'] ? $this->category[$catid]['childids'] : $catid;
				$this->template->assign('catid', $catid);
				$this->template->assign('catids', $catids);
	        	$data = $this->template->fetch('system/roll.html');
				$filename = WWW_PATH.'roll/'.$this->category[$catid]['alias'].'/index.shtml';
				folder::create(dirname($filename));
				write_file($filename, $data);
			}
    	}
    	else 
    	{
	    	$this->template->assign('date', $date);
	    	$channelids[] = 0;
	    	$db = factory::db();
	    	foreach ($channelids as $catid)
			{
				if ($catid)
				{
					$htmlcreated = $db->get('SELECT `htmlcreated` FROM `#table_category` WHERE `catid`=?',array($catid));
					if($htmlcreated['htmlcreated']) continue;
					$catids = $this->category[$catid]['childids'] ? $this->category[$catid]['childids'] : $catid;
					$filename = WWW_PATH.'roll/'.$this->category[$catid]['alias'].'/'.$date.'.shtml';
				}
				else 
				{
					$catids = 0;
					$filename = WWW_PATH.'roll/'.$date.'.shtml';
				}
				$this->template->assign('catid', $catid);
				$this->template->assign('catids', $catids);
	        	$data = $this->template->fetch('system/roll.html');
				folder::create(dirname($filename));
				write_file($filename, $data);
			}
    	}
    	return true;
	}
	
	private function date($date = null)
	{
		if (is_null($date)) $date = date('Y-m-d');
		$time = strtotime($date);
		$year = date('Y', $time);
		$month = date('n', $time);
		$day = date('j', $time);
		$maxyear = date('Y');
		$string = '<select name="year" id="year">';
		for ($i = 2005; $i <= $maxyear; $i++)
		{
			$string .= '<option value="'.$i.'" '.($i == $year ? 'selected' : '').'>'.$i.'</option>';
		}
        $string .= '</select> ';
		$string .= '<select name="month" id="month">';
		for ($i = 1; $i <= 12; $i++)
		{
			$string .= '<option value="'.$i.'" '.($i == $month ? 'selected' : '').'>'.$i.'</option>';
		}
        $string .= '</select> ';
		$string .= '<select name="day" id="day">';
		for ($i = 1; $i <= 31; $i++)
		{
			$string .= '<option value="'.$i.'" '.($i == $day ? 'selected' : '').'>'.$i.'</option>';
		}
        $string .= '</select>';
        return $string;
	}
}