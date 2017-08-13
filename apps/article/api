<?php
/**
 * 文章
 *
 * @aca 文章
 */
class controller_api_article extends api_controller_abstract
{
	private $article;

	function __construct($app)
	{
		parent::__construct($app);
		$this->article = loader::model('admin/article');
	}

    /**
     * 读取文章内容
     *
     * @aca 读取文章内容
     */
    function get()
    {
        $contentid = intval(value($_GET, 'contentid', 0));
        if(!$contentid)
        {
            $result = array(
                'state'=>false,
                'error'=>'内容编号错误'
            );
        }
        else
        {
            $data = $this->article->get($contentid, '*', 'show');
            if($data)
            {
                foreach(array(
                            'status_old','unpublished','unpublishedby',
                            'modified','modifiedby',
                            'checked','checkedby',
                            'locked','lockedby',
                            'noted','notedby',
                            'note','workflow_step','workflow_roleid',
                            'placeid','saveremoteimage'
                        ) as $field)
                {
                    unset($data[$field]);
                }
            }
            $result = array(
                'state'=>true,
                'data'=>$data
            );
        }
        echo json_encode($result);
    }

    /**
     * 添加文章内容
     *
     * @aca 添加文章内容
     */
    function add()
    {
        $_POST['modelid'] = 1;
        $title = htmlspecialchars($_POST['title']);
        $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        $title = preg_replace($regex,"",$title);
        if (factory::db()->get("select contentid from #table_content where title = '".$title."'")) {
            $_POST['status'] = 1;
        }
        $contentid = $this->article->add($_POST);
        if ($contentid)
        {
            $result = array('state'=>true, 'contentid' => $contentid);
            $article = $this->article->get($contentid, 'url, status');
            $article['status'] == 6 && $result['url'] = $article['url'];
        }
        else
        {
            $result = array('state'=>false, 'error'=>$this->article->error());
            if($this->article->filterword)
            {
                $result['filterword'] = $this->article->filterword;
            }
        }
        echo json_encode($result);
    }
}