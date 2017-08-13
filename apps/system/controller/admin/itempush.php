<?php
/*
 *付费站281栏目项目推送至135栏目
 */
class controller_admin_itempush extends system_controller_abstract
{
	public $db,$pagesize,$start_time,$end_time;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->db = factory::db();
		$this->pagesize = 100;
		$this->start_time = strtotime(date('Y-m-d',time()));	//每天的开始时间
		$this->end_time = $this->start_time+24*60*60;		//每天的结束时间
	}

	/*付费站281栏目项目推送至135栏目*/
	 		public function index()
      {
          $total = $this->db->get("SELECT count(contentid) AS total FROM #table_content 
          WHERE modelid = 11 
          AND status = 6 
          AND catid = 281 
          AND published > $this->start_time 
          AND published <= $this->end_time");
          // var_dump($total);die;
          $count = ceil($total['total']/$this->pagesize);
          /*如果该栏目下的数量小于1，则返回false*/
					if($count<1){
						 exit('{"state":true}');
					}
          for($i=1;$i<=$count;$i++){
            $page=$i-1;
						$start=$page*$this->pagesize;
						$limit=" limit $start,".$this->pagesize;
            $sql1 = "SELECT * FROM #table_content 
					          WHERE modelid = 11 
					          AND status = 6 
					          AND catid = 281 
					          AND published > $this->start_time 
					          AND published <= $this->end_time $limit";
					          // echo $sql1;die;
            $result = $this->db->select($sql1);
                  // echo '<pre>';print_r($result);die;
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
              foreach($result as $k=>$v){
              	//首先判断是否已经推送过
              	$title = addslashes($v['title']);
								$sql = "SELECT contentid FROM cmstop_content WHERE title = '".$title."' AND modelid=11 AND status=6 AND catid=135";
								$res = $this->db->get($sql);
								if(!$res){
                    //插入content表
                    $contentid = $this->content($v);
                      if($contentid){
                        //插入item表
                        $one = $this->db->get("SELECT * FROM cmstop_item AS i WHERE i.contentid=$v[contentid]");
                        $one['contentid'] = $contentid;
                        $contentidd = $this->item($one);
                        if($contentidd){
                          //插入item_type表
                          $itemtypes = $this->db->select("SELECT * FROM cmstop_item_type AS t WHERE t.contentid= $v[contentid]");
                          foreach($itemtypes as $key=>$val){
	                            $val['contentid'] = $contentid;
	                            $resultt = $this->item_type($val);
                            }
                            //插入related表(相关组图)
                            $res = $this->related($v['contentid'],$contentid);
                            if(!$res){
                             	$error = '原ID'.$contentid.'插入related表失败';
                              exit("{'state':false,'error':$error}");
                            }
                            
                        }else{
                      			$error = '原ID'.$v['contentid'].'插入item表失败';
                            exit("{'state':false,'error':$error}");
                        }

                      }else{
                				$error = '原ID'.$v['contentid'].'插入content表失败';
                        exit("{'state':false,'error':$error}");
                      }
                    }
                  }
          }
          exit('{"state":true}');
      }

      public function content($data)
      {
            // var_dump($data);die;
            $data['title'] = addslashes(trim($data['title']));
            $keys = "created,published,modified,catid,modelid,title,subtitle,color,tags,source_title,sourceid,thumb,source_link,url,weight,status,status_old,createdby,publishedby,unpublished,unpublishedby,modifiedby,checked,checkedby,locked,lockedby,noted,notedby,note,workflow_step,workflow_roleid,iscontribute,spaceid,related,pv,allowcomment,comments,score,tweeted,topicid,ischarge,yuanquid";
            $values = "'$data[created]','$data[published]','$data[modified]','135','$data[modelid]','$data[title]','$data[subtitle]','$data[color]','$data[tags]','$data[source_title]','$data[sourceid]','$data[thumb]','$data[source_link]','$data[url]','$data[weight]','$data[status]','$data[status_old]','$data[createdby]','$data[publishedby]',null,'$data[unpublishedby]','$data[modifiedby]','$data[checked]','$data[checkedby]','$data[locked]','$data[lockedby]','$data[noted]','$data[notedby]','$data[note]','$data[workflow_step]','$data[workflow_roleid]','$data[iscontribute]',null,'$data[related]','$data[pv]','$data[allowcomment]','$data[comments]','$data[score]','$data[tweeted]',null,'$data[ischarge]','$data[yuanquid]'";
            $content_sql = "INSERT into #table_content($keys) VALUES($values)";
            // echo $content_sql;die;
            // var_dump($content_sql);die;
           $contentid = $this->db->insert($content_sql);
           //修改url
           $newurl = preg_replace('/\d+\.shtml/',$contentid.'.shtml',$data['url']);
           if($contentid){
           	$this->db->update("UPDATE cmstop_content SET url='$newurl' WHERE contentid=$contentid");
						}
           return $contentid;
            // var_dump($contentid);die;

      }
      public function item($data)
      {
            $data['description'] = addslashes(trim($data['description']));
            $keys = "contentid,editor,description,starttime,stoptime,publishorganization,itemsum,income,paybacktime,itemunit,itemcontacts,phone,faxes,email,address,postcode,itemnatureid";
            $values = "'$data[contentid]','$data[editor]','$data[description]','$data[starttime]','$data[stoptime]','$data[publishorganization]','$data[itemsum]','$data[income]','$data[paybacktime]','$data[itemunit]','$data[itemcontacts]','$data[phone]','$data[faxes]','$data[email]','$data[address]','$data[postcode]','$data[itemnatureid]'";
            $content_sql = "INSERT into #table_item($keys) VALUES($values)";
            // var_dump($content_sql);die;
           $contentid = $this->db->insert($content_sql);
           return $contentid;
            // var_dump($contentid);die;
      }

      public function item_type($data,$catid)
      {
            //$data['title'] = addslashes(trim($data['title']));
            $keys = "contentid,type,typeid";
            $values = "'$data[contentid]','$data[type]','$data[typeid]'";
            $content_sql = "INSERT into #table_item_type($keys) VALUES($values)";
            // var_dump($content_sql);die;
           $itemtypeid = $this->db->insert($content_sql);
           return $itemtypeid;
            // var_dump($contentid);die;

      }

      public function related($old_contentid,$contentid)
      {
            $data = $this->db->get("SELECT * FROM #table_related AS r WHERE r.contentid=$old_contentid");
            $keys = "contentid,orign_contentid,title,thumb,url,time,sort";
            $values = "'$contentid','$data[orign_contentid]','$data[title]','$data[thumb]','$data[url]','$data[time]','$data[sort]'";
            $content_sql = "INSERT into #table_related($keys) VALUES($values)";
            // var_dump($content_sql);die;
           $relatedid = $this->db->insert($content_sql);
           return $relatedid;
            // var_dump($contentid);die;
      }
	
	
}
