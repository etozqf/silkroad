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
        echo htmlspecialchars(json_encode($result));
    }

    /*得到全站的所有文章
      +1、每次按位置读取500条
      +@page:当前位置
      +@pagesize:每次获取的最大条数,默认是400条
    */
    function getall()
    {
        
        $published = isset($_REQUEST['published']) ? $_REQUEST['published'] : '';
        $page=$_REQUEST['page'];
        if(empty($page) && $page<1)
        {
            $page=1;
        }
	   else
	   {
            $page=intval($_REQUEST['page']);
       }

        $pagesize=200;
        $db=factory::db();
        $field="SELECT c.*,a.*";
        $table=" from cmstop_content as c inner join cmstop_article as a on c.contentid=a.contentid";
        $where=$published ? " where c.modelid=1 and c.status=6 and c.published>$published" : " where c.modelid=1 and c.status=6";
        $start=($page-1)*$pagesize;
        $orderby=" order by c.published desc";
        $limit=" limit $start,$pagesize";

        $catid_arr=array(24,25,26,27,28,29,92,93,94,95,96,97,98,99,100,101,103,104,105,106,108,109,110,111,112,113,114,116,117,118,119,120,121,122,123,124,125,136,137,138,142,143,148);
        $sql=$field.$table.$where.$orderby.$limit;   //拼装sql语句
        
        $data=$db->select($sql);


        /*记录全文总数*/
        if($published){
            $total=$db->select("SELECT count(contentid) as count from cmstop_content where modelid=1 and status=6 and published>$published");
        }else{
            $total=$db->select("SELECT count(contentid) as count from cmstop_content where modelid=1 and status=6");
        }
        $totalpage=ceil($total[0]['count']/$pagesize);
       

 
        if(!empty($data))
        {   
            
            $list=array();
            foreach($data as $key=>&$value)
            {   
                
                //对于默认值为null的处理
                $value['topicid']=$value['topicid'] ? $value['topicid'] : ''; 
                $value['source_title']=$value['source_title'] ? $value['source_title'] : ''; 
                $value['source_link']=$value['source_link'] ? $value['source_link'] : ''; 
                $value['unpublished']=$value['unpublished'] ? $value['unpublished'] : ''; 
                $value['unpublishedby']=$value['unpublishedby'] ? $value['unpublishedby'] : ''; 
                $value['modified']=$value['modified'] ? $value['modified'] : ''; 
                $value['checked']=$value['checked'] ? $value['checked'] : ''; 
                $value['checkedby']=$value['checkedby'] ? $value['checkedby'] : ''; 
                $value['locked']=$value['locked'] ? $value['locked'] : ''; 
                $value['lockedby']=$value['lockedby'] ? $value['lockedby'] : ''; 
                $value['noted']=$value['noted'] ? $value['noted'] : ''; 
                $value['notedby']=$value['notedby'] ? $value['notedby'] : ''; 
                $value['workflow_step']=$value['workflow_step'] ? $value['workflow_step'] : ''; 
                $value['workflow_roleid']=$value['workflow_roleid'] ? $value['workflow_roleid'] : ''; 
                $value['spaceid']=$value['spaceid'] ? $value['spaceid'] : ''; 

                $value['catname']=table('category',$value['catid'],'name');  //栏目名
                $value['createdby']=$value['createdby'] ? table('member',$value['createdby'],'username') : '';
                $value['publishedby']=$value['publishedby'] ? table('member',$value['publishedby'],'username') : '';
                //处理description和content字段
                //$value['content'] = addslashes($value['content']);
                //$value['description'] = addslashes($value['description']);
                //添加纯文本content_text字段
                $value['content_text'] = strip_tags($value['content']);
                //$value['content_tag'] = strip_tags($value['content']);
                //查询扩展字段
                $value['meta_data']=table('content_meta',$value['contentid'],'data');
                $value['meta_data'] = $value['meta_data'] ? unserialize($value['meta_data']) : '';
                //查询自定义属性
                $proids = $db->select("SELECT c.contentid,p.name FROM cmstop_content_property AS c LEFT JOIN cmstop_property AS p ON c.proid=p.proid WHERE c.contentid=$value[contentid]");
                //$value['proids']=$proids ? $proids : '';
                //查询相关联文章
                $relateds = $db->select("SELECT * FROM cmstop_related WHERE contentid=$value[contentid]");
                $value['relateds'] = $relateds ? $relateds : '';
                if(in_array($value['catid'],$catid_arr))
                {
                    $value['mark']=1;   //代表文章是英文
                }
                else
                {
                    $value['mark']=2;   //代表文章是中文
                }
            }

            $result = array('state'=>true,'page'=>$page,'totalpage'=>$totalpage,'list' =>$data);
        }
        else
        {
            $result=array('state'=>false,'error'=>'no data');
        }

        echo htmlspecialchars(json_encode($result));

    }

    /**
     *获取全站的项目
     *每一次按照位置读取200条
     *@page:当前位置
     *@pagesize:每次获取的最大条数,默认是400条
     */
    function getitem()
    {
        $published = isset($_REQUEST['published']) ? $_REQUEST['published'] : '';
        $page = $_REQUEST['page'] ? intval($_REQUEST['page']) : 1;
        $pagesize = 200;
        $catid_arr=array(24,25,26,27,28,29,92,93,94,95,96,97,98,99,100,101,103,104,105,106,108,109,110,111,112,113,114,116,117,118,119,120,121,122,123,124,125,136,137,138,142,143,148);
        $db = factory::db();
        $start = ($page-1)*$pagesize;
        $sql = $published ? "SELECT c.*,i.* FROM cmstop_content AS c LEFT JOIN cmstop_item AS i ON c.contentid=i.contentid WHERE c.modelid=11 AND c.catid=281 AND c.status=6 ORDER BY c.published DESC LIMIT $start,$pagesize" : "SELECT c.*,i.* FROM cmstop_content AS c LEFT JOIN cmstop_item AS i ON c.contentid=i.contentid WHERE c.modelid=11 AND c.status=6 AND c.catid=281 AND c.published>$published ORDER BY c.published DESC LIMIT $start,$pagesize";
        $sqlcount = $published ? "SELECT count(c.contentid) AS count FROM cmstop_content AS c LEFT JOIN cmstop_item AS i ON c.contentid=i.contentid WHERE c.modelid=11 AND c.catid=281 AND c.status=6" : "SELECT count(c.contentid) AS count FROM cmstop_content AS c LEFT JOIN cmstop_item AS i ON c.contentid=i.contentid WHERE c.modelid=11 AND c.catid=281 AND c.status=6 AND c.status=6 AND c.published>$published";
        $total = $db->select($sqlcount);
        $totalpage=ceil($total[0]['count']/$pagesize);
        $data = $db->select($sql);
        if(!empty($data))
        {   
            $list=array();
            foreach($data as $key=>&$value)
            {   
                
                //对于默认值为null的处理
                $value['topicid']=$value['topicid'] ? $value['topicid'] : ''; 
                $value['source_title']=$value['source_title'] ? $value['source_title'] : ''; 
                $value['subtitle']=$value['subtitle'] ? $value['subtitle'] : '';
                $value['thumb']=$value['thumb'] ? $value['thumb'] : '';
                $value['sourceid']=$value['sourceid'] ? $value['sourceid'] : '';
                $value['source_link']=$value['source_link'] ? $value['source_link'] : ''; 
                $value['unpublished']=$value['unpublished'] ? $value['unpublished'] : ''; 
                $value['unpublishedby']=$value['unpublishedby'] ? $value['unpublishedby'] : ''; 
                $value['modified']=$value['modified'] ? $value['modified'] : ''; 
                $value['checked']=$value['checked'] ? $value['checked'] : ''; 
                $value['checkedby']=$value['checkedby'] ? $value['checkedby'] : ''; 
                $value['locked']=$value['locked'] ? $value['locked'] : ''; 
                $value['lockedby']=$value['lockedby'] ? $value['lockedby'] : ''; 
                $value['noted']=$value['noted'] ? $value['noted'] : ''; 
                $value['notedby']=$value['notedby'] ? $value['notedby'] : ''; 
                $value['workflow_step']=$value['workflow_step'] ? $value['workflow_step'] : ''; 
                $value['workflow_roleid']=$value['workflow_roleid'] ? $value['workflow_roleid'] : ''; 
                $value['spaceid']=$value['spaceid'] ? $value['spaceid'] : ''; 
                $value['catname']=table('category',$value['catid'],'name');  //栏目名
                $value['createdby']=$value['createdby'] ? table('member',$value['createdby'],'username') : '';
                $value['publishedby']=$value['publishedby'] ? table('member',$value['publishedby'],'username') : '';
                //查询项目类型item_type表
                $item_type = $db->select("SELECT * FROM cmstop_item WHERE contentid=$value[contentid]");
                $value['item_type']=$item_type ? $item_type : '';
                //查询相关联项目图集
                $relateds = $db->select("SELECT * FROM cmstop_related WHERE contentid=$value[contentid]");
                $value['relateds'] = $relateds ? $relateds : '';
                if(in_array($value['catid'],$catid_arr))
                {
                    $value['mark']=1;   //代表项目是英文
                }
                else
                {
                    $value['mark']=2;   //代表项目是中文
                }
            }

            $result = array('state'=>true,'page'=>$page,'totalpage'=>$totalpage,'list' =>$data);
        }
        else
        {
            $result=array('state'=>false,'error'=>'no data');
        }
        echo htmlspecialchars(json_encode($result));
    }

    /**
     *获取全站的视频
     *每一次按照位置读取200条
     *@page:当前位置
     *@pagesize:每次获取的最大条数,默认是400条
     */
    function getvideo()
    {
        $published = isset($_REQUEST['published']) ? $_REQUEST['published'] : '';
        $page = $_REQUEST['page'] ? intval($_REQUEST['page']) : 1;
        $pagesize = 200;
        $catid_arr=array(24,25,26,27,28,29,92,93,94,95,96,97,98,99,100,101,103,104,105,106,108,109,110,111,112,113,114,116,117,118,119,120,121,122,123,124,125,136,137,138,142,143,148);
        $db = factory::db();
        $start = ($page-1)*$pagesize;
        $sql = $published ? "SELECT c.*,v.* FROM cmstop_content AS c LEFT JOIN cmstop_video AS v ON c.contentid=v.contentid WHERE c.modelid=4 AND c.status=6 AND c.published>$published ORDER BY c.published DESC LIMIT $start,$pagesize" : "SELECT c.*,v.* FROM cmstop_content AS c LEFT JOIN cmstop_video AS v ON c.contentid=v.contentid WHERE c.modelid=4 AND c.status=6 ORDER BY c.published DESC LIMIT $start,$pagesize";
        $sqlcount = $published ? "SELECT count(c.contentid) AS count FROM cmstop_content AS c LEFT JOIN cmstop_video AS v ON c.contentid=v.contentid WHERE c.modelid=4 AND c.status=6 AND c.published>$published" : "SELECT count(c.contentid) AS count FROM cmstop_content AS c LEFT JOIN cmstop_video AS v ON c.contentid=v.contentid WHERE c.modelid=4 AND c.status=6";
        $total = $db->select($sqlcount);
        $totalpage=ceil($total[0]['count']/$pagesize);
        $data = $db->select($sql);
        if(!empty($data))
        {   
            $list=array();
            foreach($data as $key=>&$value)
            {   
                
                //对于默认值为null的处理
                $value['topicid']=$value['topicid'] ? $value['topicid'] : ''; 
                $value['source_title']=$value['source_title'] ? $value['source_title'] : ''; 
                $value['subtitle']=$value['subtitle'] ? $value['subtitle'] : '';
                $value['thumb']=$value['thumb'] ? $value['thumb'] : '';
                $value['sourceid']=$value['sourceid'] ? $value['sourceid'] : '';
                $value['source_link']=$value['source_link'] ? $value['source_link'] : ''; 
                $value['unpublished']=$value['unpublished'] ? $value['unpublished'] : ''; 
                $value['unpublishedby']=$value['unpublishedby'] ? $value['unpublishedby'] : ''; 
                $value['modified']=$value['modified'] ? $value['modified'] : ''; 
                $value['checked']=$value['checked'] ? $value['checked'] : ''; 
                $value['checkedby']=$value['checkedby'] ? $value['checkedby'] : ''; 
                $value['locked']=$value['locked'] ? $value['locked'] : ''; 
                $value['lockedby']=$value['lockedby'] ? $value['lockedby'] : ''; 
                $value['noted']=$value['noted'] ? $value['noted'] : ''; 
                $value['notedby']=$value['notedby'] ? $value['notedby'] : ''; 
                $value['workflow_step']=$value['workflow_step'] ? $value['workflow_step'] : ''; 
                $value['workflow_roleid']=$value['workflow_roleid'] ? $value['workflow_roleid'] : ''; 
                $value['spaceid']=$value['spaceid'] ? $value['spaceid'] : ''; 
                $value['yuanquid']=$value['yuanquid'] ? $value['yuanquid'] : ''; 
                $value['catname']=table('category',$value['catid'],'name');  //栏目名
                $value['createdby']=$value['createdby'] ? table('member',$value['createdby'],'username') : '';
                $value['publishedby']=$value['publishedby'] ? table('member',$value['publishedby'],'username') : '';
                if(in_array($value['catid'],$catid_arr))
                {
                    $value['mark']=1;   //代表项目是英文
                }
                else
                {
                    $value['mark']=2;   //代表项目是中文
                }
            }

            $result = array('state'=>true,'page'=>$page,'totalpage'=>$totalpage,'list' =>$data);
        }
        else
        {
            $result=array('state'=>false,'error'=>'no data');
        }
        echo htmlspecialchars(json_encode($result));
    }

    /**
     * 读取稿件内容通用接口
     *
     * @aca 读取稿件内容
     */
    function getgeneral()
    {
        $db=factory::db();
        $contentid = $_GET['contentid'] ? intval($_GET['contentid']) : 0;
        $modelid = table('content',$contentid,'modelid');
        $catid_arr=array(24,25,26,27,28,29,92,93,94,95,96,97,98,99,100,101,103,104,105,106,108,109,110,111,112,113,114,116,117,118,119,120,121,122,123,124,125,136,137,138,142,143,148);
        if(!$contentid)
        {
            $result = array(
                'state'=>false,
                'error'=>'内容编号错误'
            );
        }
        elseif($modelid==1)//文章
        {
            $field="select c.*,a.*";
            $table=" from cmstop_content as c inner join cmstop_article as a on c.contentid=a.contentid";
            $where=" where c.contentid=$contentid";
            $sql=$field.$table.$where;   //拼装sql语句
            $data=$db->select($sql);
            $result = $this->checknull($data);
        }
        elseif($modelid==2)//组图
        {
            $field="select c.*,p.*";
            $table=" from cmstop_content as c inner join cmstop_picture as p on c.contentid=p.contentid";
            $where=" where c.contentid=$contentid";
            $sql=$field.$table.$where;   //拼装sql语句
            $data=$db->select($sql);
            $result = $this->checknull($data);
            
        }
        elseif($modelid==3)//链接
        {
            $field="select c.*,li.*";
            $table=" from cmstop_content as c inner join cmstop_link as li on c.contentid=li.contentid";
            $where=" where c.contentid=$contentid";
            $sql=$field.$table.$where;   //拼装sql语句
            $data=$db->select($sql);
            $result = $this->checknull($data);
        }
        elseif($modelid==4)//视频
        {
            $field="select c.*,v.*";
            $table=" from cmstop_content as c inner join cmstop_video as v on c.contentid=v.contentid";
            $where=" where c.contentid=$contentid";
            $sql=$field.$table.$where;   //拼装sql语句
            $data=$db->select($sql);
            $result = $this->checknull($data);
        }
        elseif($modelid==7)//活动
        {
            $field="select c.*,act.*";
            $table=" from cmstop_content as c inner join cmstop_activity as act on c.contentid=act.contentid";
            $where=" where c.contentid=$contentid";
            $sql=$field.$table.$where;   //拼装sql语句
            $data=$db->select($sql);
            $result = $this->checknull($data);
        }
        elseif($modelid==8)//投票
        {
            $field="select c.*,vo.*";
            $table=" from cmstop_content as c inner join cmstop_vote as vo on c.contentid=vo.contentid";
            $where=" where c.contentid=$contentid";
            $sql=$field.$table.$where;   //拼装sql语句
            $data=$db->select($sql);
            $result = $this->checknull($data);
        }
        elseif($modelid==9)//调查
        {
            $field="select c.*,su.*";
            $table=" from cmstop_content as c inner join cmstop_survey as su on c.contentid=su.contentid";
            $where=" where c.contentid=$contentid";
            $sql=$field.$table.$where;   //拼装sql语句
            $data=$db->select($sql);
            $result = $this->checknull($data);
        }
        elseif($modelid==10)//专题
        {
            $field="select c.*,sp.*";
            $table=" from cmstop_content as c inner join cmstop_special as sp on c.contentid=sp.contentid";
            $where=" where c.contentid=$contentid";
            $sql=$field.$table.$where;   //拼装sql语句
            $data=$db->select($sql);
            $result = $this->checknull($data);
        }
        elseif($modelid==11)//项目
        {
            $field="select c.*,i.description,i.editor,i.contentid";
            $table=" from cmstop_content as c inner join cmstop_item as i on c.contentid=i.contentid";
            $where=" where c.contentid=$contentid";
            $sql=$field.$table.$where;   //拼装sql语句
            $data=$db->select($sql);
            $result = $this->checknull($data);
        }    
        // $result = array(
        //         'state'=>true,
        //         'data'=>$data
        // );
        
        echo htmlspecialchars(json_encode($result));
    }

    /**
     *处理null字段
     */
    private function checknull($data)
    {
        $db=factory::db();
        if(!empty($data))
        {   
            foreach($data as $key=>&$value)
            {   
                //对于默认值为null的处理
                $value['topicid']=$value['topicid'] ? $value['topicid'] : ''; 
                $value['source_title']=$value['source_title'] ? $value['source_title'] : ''; 
                $value['source_link']=$value['source_link'] ? $value['source_link'] : ''; 
                $value['unpublished']=$value['unpublished'] ? $value['unpublished'] : ''; 
                $value['unpublishedby']=$value['unpublishedby'] ? $value['unpublishedby'] : ''; 
                $value['modified']=$value['modified'] ? $value['modified'] : ''; 
                $value['checked']=$value['checked'] ? $value['checked'] : ''; 
                $value['checkedby']=$value['checkedby'] ? $value['checkedby'] : ''; 
                $value['locked']=$value['locked'] ? $value['locked'] : ''; 
                $value['lockedby']=$value['lockedby'] ? $value['lockedby'] : ''; 
                $value['noted']=$value['noted'] ? $value['noted'] : ''; 
                $value['notedby']=$value['notedby'] ? $value['notedby'] : ''; 
                $value['workflow_step']=$value['workflow_step'] ? $value['workflow_step'] : ''; 
                $value['workflow_roleid']=$value['workflow_roleid'] ? $value['workflow_roleid'] : ''; 
                $value['spaceid']=$value['spaceid'] ? $value['spaceid'] : ''; 

                $value['catname']=table('category',$value['catid'],'name');  //栏目名
                $value['createdby']=$value['createdby'] ? table('member',$value['createdby'],'username') : '';
                $value['publishedby']=$value['publishedby'] ? table('member',$value['publishedby'],'username') : '';
                //添加纯文本content_text字段
                $value['content_text'] = strip_tags($value['content']);
                //$value['content_tag'] = strip_tags($value['content']);
                //查询扩展字段
                $value['meta_data']=table('content_meta',$value['contentid'],'data');
                $value['meta_data'] = $value['meta_data'] ? unserialize($value['meta_data']) : '';
                //查询自定义属性
                $proids = $db->select("SELECT c.contentid,p.name FROM cmstop_content_property AS c LEFT JOIN cmstop_property AS p ON c.proid=p.proid WHERE c.contentid=$value[contentid]");
                //$value['proids']=$proids ? $proids : '';
                //查询相关联文章
                $relateds = $db->select("SELECT * FROM cmstop_related WHERE contentid=$value[contentid]");
                $value['relateds'] = $relateds ? $relateds : '';
                if(in_array($value['catid'],$catid_arr))
                {
                    $value['mark']=1;   //代表文章是英文
                }
                else
                {
                    $value['mark']=2;   //代表文章是中文
                }

                if($value['modelid']==2)//组图
                {
                    //查询组图详细图片
                    $group = $db->select("SELECT * FROM cmstop_picture_group WHERE contentid=$value[contentid]");
                    $value['group'] = $group;
                }
                elseif($value['modelid']==3)//链接
                {

                }
                elseif($value['modelid']==4)//视频
                {

                }
                elseif($value['modelid']==8)//投票
                {
                    //查询投票项
                    $option = $db->select("SELECT * FROM cmstop_vote_option WHERE contentid=$value[contentid]");
                    $value['option'] = $option;
                }
                elseif($value['modelid']==9)//调查
                {
                    //查询调查问题项
                    $question = $db->select("SELECT qu.*,op.* FROM cmstop_survey_question AS qu LEFT JOIN cmstop_survey_question_option AS op ON qu.questionid=op.questionid WHERE qu.contentid=$value[contentid]");
                    $value['question'] = $question;

                }
                elseif($value['modelid']==10)//专题
                {

                }
                elseif($value['modelid']==11)
                {
                    //查询项目类型item_type表
                    $item_type = $db->select("SELECT * FROM cmstop_item_type WHERE contentid=$value[contentid]");
                    //$value['item_type']=$item_type ? $item_type : '';
                   
                }

            }
            $result = array('state'=>true,'data'=>$data);
        }else{
            $result = array('state'=>false,'error'=>'nodata');
        }
        return $result;
    } 
    /**
     * 添加文章内容
     *
     * @aca 添加文章内容
     */
    function add()
    {
        $_REQUEST['modelid'] = 1;
        //$title = htmlspecialchars($_POST['title']);
        $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        //$title = preg_replace($regex,"",$title);
        $title=$_REQUEST['title'];
	    $regex1 = '/["](.*?)["]/';
        preg_match($regex1,$title,$arr);
        if($arr[0])
	    {
 	       $title = addslashes($title);
        }

	    if($list=factory::db()->get("select contentid from #table_content where title = '".$title."' and status=6 and modelid=1")) 
		{
			$contentid=$this->article->edit($list['contentid'],$_REQUEST);
        }
		else
		{
			$contentid = $this->article->add($_REQUEST);
		}
        
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
