<?php 
class controller_engqianyi extends article_controller_abstract
{
      public function __construct($app)
      {
            parent::__construct($app);
            
//            $this->catid_list = loader::import('config.qianyi3'); /*读取推送栏目对应关系*/
            
            
      }

      public function index(){
        // echo '<pre>';print_r($this->catid_list);die;
            foreach($this->catid_list as $k=>$v){
                  // var_dump($this->catid_list);die;
                  $contentids = $this->content_pro($k,$v);
             } 
             
      }
      //查询含有该属性的contentid
      public function content_pro($proid,$catid){
            $db = & factory::db();
            $sql = "SELECT * FROM cmstop_content_property WHERE proid=$proid";
            $result = $db->select($sql);
            $i = 0;
            // echo '<pre>';print_r($result);die;
            //查询每一条contentid的数据
            foreach($result as $k=>$v){
                  $db->query('SET FOREIGN_KEY_CHECKS = 0');
                  $ord_catid = table('content',$v['contentid'],'catid');
                  $content = table('content',$v['contentid']);
                  $article = table('article',$v['contentid']);

                  // echo $ord_catid;
                  if($content['modelid']==1 && $ord_catid==25){
                    //插入content表
                    $new_contentid = $this->content_insert($content,$catid);
                      if($new_contentid){
                        $article['contentid'] = $new_contentid;
                        //插入article表
                        $article_contentid = $this->article_insert($article);
                        if($article_contentid){
                          //插入content_property表
                          $proids = $db->select('SELECT proid FROM cmstop_content_property WHERE contentid='.$content['contentid']);
                          if(is_array($proids)){
                            foreach($proids as $k=>$v){
                              $this->content_pro_insert($new_contentid,$v['proid']);
                            }
                          }elseif(is_numeric($proids)){
                            $this->content_pro_insert($new_contentid,$proids);
                          }
                          echo '原ID为'.$content['contentid'].'执行成功'.$i++.'<br>';
                        }else{
                          file_put_contents($content['contentid'].'<br>','/data/www/cmstop/apps/article/controller/1.txt',FILE_APPEND);
                              echo '插入article表失败,原id为'.$content['contentid'].'<br>';
                        }
                    }else{
                        file_put_contents($content['contentid'].'<br>','/data/www/cmstop/apps/article/controller/1.txt',FILE_APPEND);
                              echo '插入content表失败,原id为'.$content['contentid'].'<br>';
                    }
                }
                  
            }
      }
      //迁移单一栏目数据
      public function content_catid($ord_catid,$new_catid){
          $db = & factory::db();
          $sql = "SELECT * FROM cmstop_content WHERE catid=ord_catid AND status=6";
          $res = $db->select($sql);
          foreach($res as $k=>$v){
            $db->query('SET FOREIGN_KEY_CHECKS = 0');
            
            $content = table('content',$v['contentid']);
            $article = table('article',$v['contentid']);
            $new_contentid = $this->content_insert($content,$new_catid);
            if($new_contentid){
              $article['contentid'] = $new_contentid;
              //插入article表
              $article_contentid = $this->article_insert($article);
              if($article_contentid){
                echo '原ID为'.$content['contentid'].'执行成功'.$i++.'<br>';
              }else{
                file_put_contents($content['contentid'].'<br>','/data/www/cmstop/apps/article/controller/1.txt',FILE_APPEND);
                echo '插入article表失败,原id为'.$content['contentid'].'<br>';
              }
            }else{
              file_put_contents($content['contentid'].'<br>','/data/www/cmstop/apps/article/controller/1.txt',FILE_APPEND);
              echo '插入content表失败,原id为'.$content['contentid'].'<br>';
            }
          }

      }

      public function content_insert($data,$catid){
            $db = & factory::db();
            $data['title'] = addslashes(trim($data['title']));

            // $db->query('SET FOREIGN_KEY_CHECKS = 0');

            $content_sql = "insert into cmstop_content(created,published,modified,catid,modelid,title,subtitle,color,tags,source_title,sourceid,thumb,source_link,url,weight,status,status_old,createdby,publishedby,unpublished,unpublishedby,modifiedby,checked,checkedby,locked,lockedby,noted,notedby,note,workflow_step,workflow_roleid,iscontribute,spaceid,related,pv,allowcomment,comments,score,tweeted,topicid,ischarge,yuanquid) values('$data[created]','$data[published]','$data[modified]','$catid','$data[modelid]','$data[title]','$data[subtitle]','$data[color]','$data[tags]','$data[source_title]','$data[sourceid]','$data[thumb]','$data[source_link]','$data[url]','$data[weight]','$data[status]','$data[status_old]','$data[createdby]','$data[publishedby]',null,'$data[unpublishedby]','$data[modifiedby]','$data[checked]','$data[checkedby]','$data[locked]','$data[lockedby]','$data[noted]','$data[notedby]','$data[note]','$data[workflow_step]','$data[workflow_roleid]','$data[iscontribute]',null,'$data[related]','$data[pv]','$data[allowcomment]','$data[comments]','$data[score]','$data[tweeted]',null,'$data[ischarge]','$data[yuanquid]')";
            // var_dump($content_sql);die;
           return $contentid = $db->insert($content_sql);
            // var_dump($contentid);die;

      }

      public function article_insert($data){
            $db = & factory::db();
            $data['content'] = addslashes(trim($data['content']));
            $data['description'] = addslashes(trim($data['description']));
            // $db->query('SET FOREIGN_KEY_CHECKS = 0');
            $article_sql = "insert into cmstop_article(contentid,subtitle,author,editor,description,content,pagecount,saveremoteimage) values($data[contentid],'$data[subtitle]','$data[author]','$data[editor]','$data[description]','$data[content]','$data[pagecount]','$data[saveremoteimage]')";
                                    // var_dump($article_sql);die;
            return $res = $db->insert($article_sql);
      }

      public function article_insert_error($data){
            $db = & factory::db();
            $db->query('SET FOREIGN_KEY_CHECKS = 0');
            $article_sql = "insert into cmstop_article(contentid,subtitle,author,editor,description,content,pagecount,saveremoteimage) values($data[contentid],'$data[subtitle]','$data[author]','$data[editor]','$data[description]','$data[content]','$data[pagecount]','$data[saveremoteimage]')";
                                    // var_dump($article_sql);die;
            $res = $db->insert($article_sql);
            $error = $db->error();
            return $error[2];
            // if(!$res){
            //       return $db->error().'=>'.$res;
            // }
            
      }

      public function content_pro_insert($contentid,$proids){
        $db = & factory::db();
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $sql = "INSERT INTO cmstop_content_property(contentid,proid) VALUES ($contentid,$proids)";
        $db->insert($sql);
      }
      public function ceshi(){
        $db = & factory::db();
        // $db->select('select * from cmstop where id=34444');
        // $error = $db->error();
        // $a = implode(',',array_keys(subcategory(148)));
        // $b = implode(',',array_keys(subcategory(263)));
        //     echo '<pre>';print_r($a);
        //     echo '<pre>';
        //     echo '<pre>';print_r($b);
        $a = 5226;
        // $b = array(23,24,25);
        // var_dump(is_numeric($a));
        // var_dump(is_numeric($b));
         $proids = $db->select('SELECT proid FROM cmstop_content_property WHERE contentid='.$a);
         echo '<pre>';print_r($proids);

      }
      public function ceshi2(){
            $db = & factory::db();
            $a = $db->get('select content from cmstop_article where contentid=3802');
            $b = addslashes(trim($a['content']));
            echo '<pre>';
            print_r($b);
      }
      public function log()
	{//var_dump(fileperms("/var/log/"));
		$string = 'yangya';
		$r = file_put_contents("./log_html.txt",$string);
		var_dump($r);var_dump(printf('%o',fileperms("./")));
	}
      public function info()
	{
		//phpinfo();
		echo ROOT_PATH;die;
		$a = UPLOAD_PATH."search/";var_dump($a);if (!file_exists($a)) mkdir($a,0777, true);
	
	}
	public function api()
	{
		$id = $_GET['contentid'];
		$db=factory::db();
		$res = $db->get("select * from cmstop_article where contentid=$id");
		echo '<pre>';print_r($res);
		$a = json_encode($res);
		echo htmlspecialchars($a);
	}
	public function getip()
	{
	global $ip;  
    	if (getenv("HTTP_CLIENT_IP"))  
        	$ip = getenv("HTTP_CLIENT_IP");  
    	else if(getenv("HTTP_X_FORWARDED_FOR"))  
        	$ip = getenv("HTTP_X_FORWARDED_FOR");  
    	else if(getenv("REMOTE_ADDR"))  
        	$ip = getenv("REMOTE_ADDR");  
    	else $ip = "Unknow";  
    		var_dump($ip);
	}

	public function html()
	{
	$m = '<p style=\"text-align: center;\"><img src=\"http://upload.db.silkroad.news.cn/2017/0112/1484190462365.png\" border=\"0\" alt=\"我傲娇不驻香港特派员公署特派员宋哲\" /></p>\n<p style=\"text-align: center; font-size: 12px; text-indent: 0;\">图为外交部驻香港特派员公署特派员宋哲</p>\n<p>新华社信息香港1月12日电　<span style=\"text-indent: 2em;\">外交部驻香港特派员公署特派员宋哲11日表示,近年来,随着中国经济逐渐稳步转型,各项改革措施取得阶段性胜利,中国经济在不断有人唱衰的环境下依然稳步前进,他对于未来中国的经济发展,充满了信心。</span></p>\n<p>11日,宋哲在香港报章上刊登题为《我对中国经济充满信心》的文章,希望各方能够正视中国经济的发展,并从中树立对于中国经济的信心。</p>\n<p><strong>信心来自于不断站稳的经济基本面</strong></p>\n<p>宋哲说,与世界绝大多数经济体一样,国际金融危机以来,中国经济增速出现了趋势性回调,尽管回落幅度较大,但中国经济没有像一些国家那样出现&ldquo;断崖式&rdquo;下跌,而是在不断探底回稳,且稳中有进。</p>\n<p>&ldquo;2016年中国经济的一个突出特征就是&lsquo;稳&rsquo;&rdquo;,宋哲强调道,这主要表现在两个方面:一是经济增速非常平稳。各季度经济增长的稳定性明显增强,前三季度增长6.7%,预计全年也在6.7%左右。</p>\n<p>二是金融市场运行基本平稳。银行体系流动性充裕,货币信贷和社会融资规模平稳较快增长,利率水准低位稳定运行,人民币汇率弹性增强。</p>\n<p>宋哲还指出,中国在经济形势总体稳定的同时,一些方面继续取得积极进展。一是经济增长品质明显提高。前11个月,规模以上工业企业利润累计增幅9.4%。</p>\n<p>二是经济结构继续优化、新旧动力转换正在加快。前三季度,服务业增加值占GDP的比重为52.8%,比上年同期提高1.6个百分点。前11个月,高技术产业和装备制造业保持10%以上的增速,新产业、新技术、新业态、新模式、新服务等继续保持较快增长。</p>\n<p>三是人民生活持续改善。前11个月,城镇新增就业1249万人,31个大城市城镇调查失业率低于5%,居民消费物价总水准控制在2%左右。前三季度,全国居民人均可支配收入按年实际增长6.3%,与经济增长基本同步。随着精准扶贫战略全面实施,全年减少贫困人口1000万以上,这个数字相当于一个中等国家的人口总数。</p>\n<p><strong>信心来自于对世界经济发展做出的贡献</strong></p>\n<p>宋哲认为,2012年以来,虽然中国经济增速有所放缓,但对全球经济增长的贡献率仍在30%以上,在世界经济中发挥了&ldquo;定海神针&rdquo;的作用。</p>\n<p>据IMF估算,2016年中国对世界经济贡献了近40%的增长率,约为1.2个百分点。相比之下,美国、欧洲和日本分别只贡献了0.3、0.2和0.1个百分点。这就是说,如果中国不再增长,全球增长率只有1.9%,世界经济就会陷入萧条。</p>\n<p>此外,中国正在成为全球重要的商品和服务市场。宋哲指出,在世界经济低迷和贸易保护主义抬头的情况下,开放的市场成为各国经济增长的重要资源。中国有近3亿中等收入人口,这将为世界经济带来巨大的需求。</p>\n<p>宋哲还强调,中国对世界经济发展的贡献,还表现在对经济全球化制度的维护上。在&ldquo;逆全球化&rdquo;思潮明显上扬的背景下,中国继续高举合作共赢的旗帜,继续推动贸易投资自由化和便利化。中国提出的&ldquo;一带一路&rdquo;倡议,为世界各国开辟了公平、开放、共用的新合作平台,也为全球经济提供了新的增长点。</p>';
$n = strip_tags(htmlspecialchars_decode(str_replace('\n','',$m)));
echo $n;echo '<br>';echo $m;
}
 

}
?>
