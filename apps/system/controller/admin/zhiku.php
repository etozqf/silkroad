<?php
class controller_admin_zhiku extends system_controller_abstract{
	
	public $gateway,$auth_key,$auth_secret,$api_url,$catid_list,$pagesize;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->gateway="http://api.silkroad.news.cn/";
		$this->auth_key="f766b428cace0dddbd52050e3cc56a6f";
		$this->auth_secret="206cf2d4a060dc1c80278498da051d74";
		$this->api_url="?app=article&controller=2017article&action=add";
		$this->db=factory::db();
		$this->pagesize=200;
		//$this->start_time=strtotime("00:00:00");	//每天的开始时间
		//$this->end_time=strtotime("23:59:59");		//每天的结束时间
	}
	
	
	/*推送数据*/
	public function push()
	{
		/*查询出所有的专家机构
		  + 专栏表中 typeid=3 ,代表专家机构， sub_type  1,3  亚太，对应免费站 228栏目 + +亚太的专家机构：315,318,320,321,322,323,325,326,327,394,405,430,436,504,591,592,599,617,661,670,671,672,673,674,675,676,681,682,684,686,689,705,721,724,726,731
		  + sub_type  2 欧洲   对应免费站栏目  229 +欧洲专家机构435,440,441,442,445,448,449,453,457,480,485,486,489,632,655,667,668,687,688,690,694,698,703,704,711,716,717,718,719,722,723,725,727,728,729,730,732
		  
		  + sub_type  4 美洲   对应免费站栏目  230
          +美洲专家机构404,406,407,408,409,416,432,452,473,510,534,691,692,693,696,702,707,708,709,710,712,713,714,715,720
		*/
		$db=factory::db();
	//	$space_list=$db->select("select spaceid from cmstop_space where typeid=3 and sub_type in(1,3)");
		/*得到专家机构ID*/
	//	foreach($space_list as $key=>$value){
	//		$arr[]=$value['spaceid'];
	//	}
		
	//	$spaceid=implode(",",$arr);
	
		$spaceid='404';

		/*进行统计，得到总共相关专栏中总共的文章数量，进行求得分批量查询操作的次数*/
		$total_sql="select count(c.contentid) as total from cmstop_content as c left join cmstop_space as s on c.spaceid=s.spaceid where c.status=6 and s.spaceid in($spaceid)";
		
		$total=$this->db->select($total_sql);
		
		$count=ceil($total[0]['total']/$this->pagesize);

		if($count<1){
			return false;
		}
		else
		{
			/*得到文章中的详细内容*/
			$content_arr=$this->getDetail($count,$spaceid);
		}
		
	}
	
	/*得到指定文章数据的详细内容,关联文章表*/
	function getDetail($count,$spaceid)
	{
		
		/*关联内容表,得到所有的亚太地区相关的文章id*/
		for($i=1;$i<=$count;$i++)
		{
			$page=$i-1;
			$start=$page*$this->pagesize;
			$limit=" limit $start".",".$this->pagesize;
			$content_list=$this->db->select("select c.contentid as contentid from cmstop_content as c left join cmstop_space as s on c.spaceid=s.spaceid where c.status=6 and s.spaceid in($spaceid)".$limit);
			
			if(empty($content_list))
			{
			   return false;
			}
			else
			{
			
				$contentid=array();
				foreach($content_list as $key=>$value)
				{
					$contentid[]=intval($value['contentid']);
				}

				$contentid=implode(",",$contentid);
			
			

			
				/*进行content表与article表关联查询详细数据*/
				$sql="select c.*,a.* from #table_content as c inner join #table_article as a where c.contentid=a.contentid and c.contentid in($contentid)";
			
				$content_list=$this->db->select($sql);
				/*推送插入数据每次根据pagesize的数量，进行指定条数推送免费站*/
				$this->push_free($content_list);
			}
			
		}
		
	}
	
	/*推送数据至免费站*/
	private function push_free($content_list)
	{

		foreach($content_list as $key=>$value)
		{
			$value['catid']=230;	//免费站欧洲栏目
			$value['published']=date("Y-m-d H:i",$value['published']);
			$value['publishedby']=1;
			$value['saveremoteimage']=1;
			$value['status']=6;
			
			$result=$this->push_article($value);
			
			echo $value['title'].$result."<br/>";
			
		}

		

	}
	
	/*将数据通过添加文章接口，推送到免费站*/
	public function push_article($post)
	{
		ksort($post);
		$sign=md5(http_build_query($post).$this->auth_secret);
		$request_url = $this->gateway.$this->api_url.'&key='.$this->auth_key.'&sign='.$sign;
		
		$answer=request($request_url,$post);	
		if($answer['httpcode']==200)
		{
			$json=json_decode($answer['content'],true);
			if($json['state']==true){
				return "success";
			}else{
				var_dump($json);
				return "error";
			}	
		}else{
			return $answer['httpcode'];
		}



	
	}

	public function tool()
    {
        header("Content-Type: text/html; charset=UTF-8");
        echo "<pre>";
        $info = $_GET['info'];
        if ($info)
        {
            echo "测试进入成功";
        }
        if ($info=='确定')
        {
            //查到知识产权下的文章，取其属性，如果是在海外这一属性下的，取其属性name找到对应的知识产权属性id，
            //将关系对应表中这一条改为知识产权属性的id


            //先取到海外的所有子属性id
            $db = &factory::db();
            $haiwai = $db->get("select childids from cmstop_property where proid=287");
            $haiwai = explode(',',$haiwai['childids']);

            //取知识产权下的所有子属性id
            $ipr = $db->get("select childids from cmstop_property where proid=398")['childids'];


            //需要处理的catid
            $catid='320,321,322,323,324,325,326,328,329,330,331,332,333,334,335,336,337,338,339,340,341,298,299';
            $catid = explode(',',$catid);
            foreach ($catid as $key=>$val)
            {
                //分别查到每个catid下的contentid
                $sql = "select contentid from cmstop_content where catid=$val";
                $content = $db->select($sql);
//                echo "</br> 这里是查到的contentid </br>";var_dump($content);
                foreach ($content as $k=>$v)
                {
                    //查该contentid的所有proid，如果在数组内，则处理
                    $pro = $db->select("select * from cmstop_content_property where contentid=$v[contentid]");
//                    echo "</br> 这里是查到的proid </br>";var_dump($pro);
                    foreach ($pro as $kk=>$vv)
                    {
                        //如果proid在海外里面
                        if (in_array($vv['proid'],$haiwai))
                        {
                            echo "进 </br>";
                            //通过name找到对应的 知识产权下的那个属性，然后把id换上去
                            $name = $db->get("select name from cmstop_property where proid={$vv['proid']}")['name'];
                               echo "info:";var_dump($name);
                               $proid = $db->get("select proid from cmstop_property where name='{$name}' AND proid IN($ipr)")['proid'];
                               if ($proid)
                               {
                                   $up = $db->update("update cmstop_content_property set proid=$proid where contentid=$vv[contentid] AND proid=$vv[proid]");
                                   if ($up)
                                   {
                                       echo '成功了一个'."$vv[contentid]"."$name </br>";
                                   }
                               }
                               else
                               {
                                   $title = $db->get("select title from cmstop_content where contentid=$vv[contentid]")['title'];
                                   echo '失败了一个'."$vv[contentid]"."$name/ $title </br>";
                               }

                        }
                    }
                }

            }

        }
    }


	
}


