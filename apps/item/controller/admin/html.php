<?php
/**
 * 文章生成
 *
 * @aca whole 生成
 */
class controller_admin_html extends item_controller_abstract 
{
	private $item;

	function __construct($app)
	{
		parent::__construct($app);
		$this->item = loader::model('admin/item');
	}
	
	function show()
	{
		$contentid = $_REQUEST['contentid'];
		if ($this->item->html_write($contentid))
		{
			$result = array('state'=>true);
		}
		else 
		{
			$result = array('state'=>false, 'error'=>$this->item->error());
		}
		echo $this->json->encode($result);
	}
	
	function show_batch()
	{
		$where = $_REQUEST['where'];
		$where .= " AND `modelid`=11 ";
		
		$limit = $_REQUEST['limit'];
		$offset = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0;
		$count = isset($_REQUEST['count']) && strlen($_REQUEST['count']) > 0 ? $_REQUEST['count'] : $this->item->content->count($where);
		
		if ($offset < $count)
		{
			$data = $this->item->content->select($where, 'contentid', '`contentid` DESC', $limit, $offset);
			foreach ($data as $r)
			{
                $this->template->savepoint();
				$this->item->html_write($r['contentid']);
                $this->template->rollback();
			}
		}
		$offset += count($data);
		$finished = $offset >= $count ? true : false;
		
		$result = array('state'=>true, 'count'=>$count, 'offset'=>$offset, 'finished'=>$finished);
		echo $this->json->encode($result);
	}
}