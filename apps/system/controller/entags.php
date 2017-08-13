<?php
/**
 *	Description : 英文站tags页面
 *
 *	@author      Hmy
 *	@datetime    2017/4/28 10:18
 *	@copyright   Beijing CmsTop Technology Co.,Ltd.
 */

class controller_entags extends system_controller_abstract
{
	private $content;

	function __construct($app)
	{
		parent::__construct($app);
	}

	function index()
	{
		if ($this->system['pagecached'])
		{
			$keyid = md5('pagecached_system_tags_index_');
			cmstop::cache_start($this->system['pagecachettl'], $keyid);
		}
		
		$letters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		$this->template->assign('letters', $letters);
		$this->template->display('system/entags.html');
		
		if ($this->system['pagecached']) cmstop::cache_end();
	}
	
	function tag()
	{
		if ($this->system['pagecached'])
		{
			$keyid = md5('pagecached_system_tags_tag_');
			cmstop::cache_start($this->system['pagecachettl'], $keyid);
		}

        $tag = value($_GET, 'tag');
        if (! $tag || ! loader::model('admin/tag', 'system')->get(array('tag' => $tag)))
        {
            $this->redirect(APP_URL . 'tags.php');
        }

        $size = isset($_GET['size']) ? max(1, min(100, intval(value($_GET, 'size')))) : intval(setting('system', 'pagesize'));
        if (! $size) $size = 50;
        $page = max(1, intval(value($_GET, 'page')));

        $contents = tag_content(array(
            'tags' => $tag,
            'orderby' => 'published desc',
            'size' => $size,
            'page' => $page
        ));

		$this->template->assign($contents);
        $this->template->assign('tag', $tag);
		$this->template->display('system/entag.html');
		
		if ($this->system['pagecached']) cmstop::cache_end();
	}
}
