<?php 
class controller_itemqy extends article_controller_abstract
{
      public function __construct($app)
      {
            parent::__construct($app);
            $this->db = factory::db();
            $this->catid_list = loader::import('config.qianyi'); /*读取推送栏目对应关系*/
            $this->article = loader::model('admin/article');
            
      }
     public function name()
     {
        $a = $this->db->select("DESC cmstop_related");
        $b = '';
        foreach($a as $k=>$v){
          $b.=$v["Field"].',';
        }
        echo '<pre>';print_r($b);
     }

      public function index()
      {

          $count = $this->db->get("SELECT max(contentid) AS c FROM #table_content WHERE modelid=11 AND status=6 AND catid=135");
          //var_dump($count);die;
          $end = 1000;
          $i = 0;
          for($start=0;$start<$count['c'];$start+=1000){
            $go = $start+1; 
            // $limit = ($i-1)*1000;
            $sql1 = "SELECT * FROM cmstop_content AS c WHERE c.catid=135 AND c.modelid=11 AND c.status=6 AND c.contentid BETWEEN $go AND $end";
            $result = $this->db->select($sql1);
                  // echo '<pre>';print_r($result);die;
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
              foreach($result as $k=>$v){
                        //插入content表
                    $contentid = $this->content($v);
                      if($contentid){
                        //插入item表
                        $one = $this->db->get("SELECT * FROM #table_item AS i WHERE i.contentid=$v[contentid]");
                        $one['contentid'] = $contentid;
                        $contentidd = $this->item($one);
                        if($contentidd){
                          //插入item_type表
                          $itemtypes = $this->db->select("SELECT * FROM #table_item_type AS t WHERE t.contentid= $v[contentid]");
                          foreach($itemtypes as $key=>$val){
                            $val['contentid'] = $contentid;
                            $resultt = $this->item_type($val);
                            }
                            //插入related表(相关组图)
                            $res = $this->related($v['contentid'],$contentid);
                            if($res){
                              echo '插入成功,新id为'.$contentid.',共插入'.$i++.'条数据'.'<br>'; 
                            }else{
                              file_put_contents($contentid.'#','/data/www/cmstop/apps/article/controller/1.txt',FILE_APPEND);
                              echo '插入失败,原id为'.$contentid.'<br>';
                            }
                            
                        }else{
                            file_put_contents($contentid.'#','/data/www/cmstop/apps/article/controller/1.txt',FILE_APPEND);
                            echo '插入失败,原id为'.$v['contentid'].'<br>';
                            
                        }

                      }else{
                        file_put_contents($contentid.'#','/data/www/cmstop/apps/article/controller/1.txt',FILE_APPEND);
                            echo '插入失败,原id为'.$v['contentid'].'<br>';
                        
                      }
                  }
                $end += 1000; 
                // echo $start.'\r';
          }
      }

      public function content($data)
      {
            // var_dump($data);die;
            $data['title'] = addslashes(trim($data['title']));
            $keys = "created,published,modified,catid,modelid,title,subtitle,color,tags,source_title,sourceid,thumb,source_link,url,weight,status,status_old,createdby,publishedby,unpublished,unpublishedby,modifiedby,checked,checkedby,locked,lockedby,noted,notedby,note,workflow_step,workflow_roleid,iscontribute,spaceid,related,pv,allowcomment,comments,score,tweeted,topicid,ischarge,yuanquid";
            $values = "'$data[created]','$data[published]','$data[modified]','281','$data[modelid]','$data[title]','$data[subtitle]','$data[color]','$data[tags]','$data[source_title]','$data[sourceid]','$data[thumb]','$data[source_link]','$data[url]','$data[weight]','$data[status]','$data[status_old]','$data[createdby]','$data[publishedby]',null,'$data[unpublishedby]','$data[modifiedby]','$data[checked]','$data[checkedby]','$data[locked]','$data[lockedby]','$data[noted]','$data[notedby]','$data[note]','$data[workflow_step]','$data[workflow_roleid]','$data[iscontribute]',null,'$data[related]','$data[pv]','$data[allowcomment]','$data[comments]','$data[score]','$data[tweeted]',null,'$data[ischarge]','$data[yuanquid]'";
            $content_sql = "INSERT into #table_content($keys) VALUES($values)";
            // echo $content_sql;die;
            // var_dump($content_sql);die;
           $contentid = $this->db->insert($content_sql);
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
?>