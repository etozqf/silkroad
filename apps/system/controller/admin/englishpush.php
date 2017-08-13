<?php
/*
 *付费站25栏目文章按照属性推送至148栏目
 */
class controller_admin_englishpush extends system_controller_abstract
{
	public $article,$db,$catid_list,$pagesize,$start_time,$end_time;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->article = loader::model('admin/article','article');
		$this->catid_list = config('englishpush'); /*读取推送栏目对应关系*/
		$this->db = factory::db();
		$this->pagesize = 100;
		$this->start_time = strtotime(date('Y-m-d',time()));	//每天的开始时间
		$this->end_time = $this->start_time+24*60*60;		//每天的结束时间
	}

	/*25栏目文章按照属性推送到英文免费栏目下*/
	
	public function cron_eng_article()
	{
		$proids = array_keys($this->catid_list);
		foreach($proids as $proid){
			$this->get_article($proid);
		}
		
		exit('{"state":true}');
	  
	}
	//查询含有该$proid属性的contentid
	public function get_article($proid)
	{
		$where = "WHERE p.proid=$proid AND c.status=6 AND c.modelid=1 AND c.catid=25 AND c.published>$this->start_time AND c.published<=$this->end_time";
		
		$sqlcount = "SELECT count(c.contentid) AS total FROM cmstop_content AS c LEFT JOIN cmstop_content_property AS p ON c.contentid=p.contentid $where";
		// echo $sqlcount;
		$total = $this->db->select($sqlcount);
		$count = ceil($total[0]['total']/$this->pagesize);
    $result = $this->db->select($sql);//echo 1,$count,$total[0]['total'],$this->pagesize,'<br/>';
    // echo '<pre>';print_r($total);
    /*如果该栏目下的数量小于1，则返回false*/
		if($count<1){
			return false;
		}
		for($i=1;$i<=$count;$i++)
		{   
			$page=$i-1;
			$start=$page*$this->pagesize;
			$limit=" limit $start,".$this->pagesize;
			$sql = "SELECT c.contentid FROM cmstop_content AS c LEFT JOIN cmstop_content_property AS p ON c.contentid=p.contentid $where $limit";
			$arr = $this->db->select($sql);
			
			$this->insert_article($arr,$this->catid_list[$proid]);
		}	
	}


	public function insert_article($arr,$catid)
	{
			foreach($arr as $val)
			{	
					$filed = 'c.catid,title,weight,color,tags,sourceid,thumb,unpublished,allowcomment,a.subtitle,author,editor,content,description';
					$sql = "SELECT $filed FROM cmstop_content AS c inner join cmstop_article AS a ON c.contentid=a.contentid WHERE c.contentid=$val[contentid]";
					$res=$this->db->get($sql);
					$proid = $this->db->select('SELECT proid FROM cmstop_content_property WHERE contentid='.$val['contentid']);
					foreach($proid as $v){
						$proid['proid'][] = $v['proid'];
					}
					$proids = implode(',',$proid['proid']);
					$res['proids'] = $proids;
					$res['catid'] = $catid;	
					$res['modelid'] = 1;
					$res['status'] = 6;
					$res['saveremoteimage']=1;
					$res['publishedby']=1;
					$result=$this->push_article($res);		
			}
	}
	
	public function push_article($val)
	{
		  $title = addslashes($val['title']);
			$sql = "SELECT contentid FROM cmstop_content WHERE title = '".$title."' AND modelid=1 AND status=6 AND catid=$val[catid]";
			$res = $this->db->get($sql);
			if(!$res){
       	/*执行添加*/
        $contentid = $this->article->add($val);
            if($contentid)
            {
            		//插入cmstop_content_property表
            		$this->insert_proid($contentid,$val['proids']);
               	return array('state'=>ture,'contentid'=>$contentid);
            }
            else
            {
            		$error = $this->article->error();
                exit("{'state':false,'error':$error}");
            }
                
        }
       
	}

	public function insert_proid($contentid,$proids)
	{
		$this->db->query('SET FOREIGN_KEY_CHECKS = 0');
		$proid = explode(',',$proids);
		for($i=0;$i<count($proid);$i++){
			$sql = "INSERT INTO cmstop_content_property(contentid,proid) VALUES ($contentid,$proid[$i])";
			$this->db->insert($sql);

		}
	}
}
