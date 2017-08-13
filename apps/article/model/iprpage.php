<?php

class model_iprpage extends model
{

    function __construct()
    {
        parent::__construct();
        $db = &factory::db();
    }

    /**
     * @param $id
     * @return string
     * 根据menu的id输出对应的menu
     */
    public function menu($id)
    {
        $arr = array(
            //这里是每个菜单，获取数据的基本配置，需要注意几个显示国家列表的菜单，可能catid有几个，如果有几个，则在取数据时直接加进去
            //菜单id，page，pagesize，catid
            array('title'=>'知识产权动态信息','url'=>'javascript:getData(1,1,10,294)'),
            array('title'=>'典型案例','url'=>'javascript:getData(2,1,10,295)'),
            array('title'=>'专家观点','url'=>'javascript:getData(3,1,10,303)'),
            array('title'=>'法律法规','url'=>'javascript:getData(4,0,0,297)'),
            array('title'=>'知识产权环境','url'=>'javascript:getData(5,0,0,298)'),
            array('title'=>'知识产权实务指引','url'=>'javascript:getData(6,0,0,299)'),
            array('title'=>'区域舆情','url'=>'javascript:getData(7,1,10,317)'),
            array('title'=>'行业舆情','url'=>'javascript:getData(8,1,10,318)'),
            array('title'=>'企业舆情','url'=>'javascript:getData(9,1,10,319)'),
            array('title'=>'专利布局/预警','url'=>'javascript:getData(10,1,4,301)'),
            array('title'=>'分析成果展示','url'=>'javascript:getData(11,1,10,355)'),
        );
        $menu = '';
        foreach ($arr as $key=>$val)
        {
            //key被选中，且不是二级菜单
            if ($key == $id-1 && ($key<6 || $key>8))
            {
                $menu .= "<li class='on'><a href='$val[url]' title='$val[title]'>$val[title]<em class='icon-jl'></em></a></li>";
            }
            //key没被选中，且不是二级菜单
            elseif ($key !== $id-1 && ($key<6 || $key>8))
            {
                $menu .= "<li><a href='$val[url]' title='$val[title]'>$val[title]<em class='icon-jl'></em></a></li>";
            }
            //key没被选中，是二级菜单
            elseif (($id<7 || $id>9) && ($key>=6 && $key<=8))
            {
                switch ($key) {
                    case 6:
                        $menu .="<li class='selcets' id='select'><a href='javascript:;' target='_self' title='专利舆情'>专利舆情<em class='icon-jl'></em></a><dl class='li_dl' id='option'><dd tip='$val[title]'><a href='$val[url]' title='$val[title]'>$val[title]</a></dd>";
                        break;
                    case 7:
                        $menu .="<dd tip='$val[title]'><a href='$val[url]' title='$val[title]'>$val[title]</a></dd>";
                        break;
                    case 8:
                        $menu .="<dd tip='$val[title]'><a href='$val[url]' title='$val[title]'>$val[title]</a></dd></dl></li>";
                        break;
                    default:
                        $menu .="";
                        break;
                }
            }
            //key被选中，是二级菜单(key在7-9，id也在6-8)
            else
            {
                if ($key == 6)
                {
                    if ($id == 7)
                    {
                        $menu .="<li class='selcets on' id='select'><a title='专利舆情'>专利舆情<em class='icon-jl'></em></a><dl class='li_dl' id='option'><dd class='curr' tip='$val[title]'><a href='$val[url]' title='$val[title]'>$val[title]</a></dd>";
                    }
                    else
                    {
                        $menu .="<li class='selcets on' id='select'><a title='专利舆情'>专利舆情<em class='icon-jl'></em></a><dl class='li_dl' id='option'><dd tip='$val[title]'><a href='$val[url]' title='$val[title]'>$val[title]</a></dd>";
                    }
                }
                elseif ($key == 7)
                {
                    if ($id == 8)
                    {
                        $menu .="<dd class='curr' tip='$val[title]'><a href='$val[url]' title='$val[title]'>$val[title]</a></dd>";
                    }
                    else
                    {
                        $menu .="<dd tip='$val[title]'><a href='$val[url]' title='$val[title]'>$val[title]</a></dd>";
                    }
                }
                elseif($key == 8)
                {
                    if ($id == 9)
                    {
                        $menu .="<dd class='curr' tip='$val[title]'><a href='$val[url]' title='$val[title]'>$val[title]</a></dd></dl></li>";
                    }
                    else
                    {
                        $menu .="<dd tip='$val[title]'><a href='$val[url]' title='$val[title]'>$val[title]</a></dd></dl></li>";
                    }
                }
            }

        }

        return $menu;
    }

    /**
     * @param $id
     * @return string
     * 输出右侧show下方的html前半部分
     */
    public function gethead($id)
    {
        //普通列表
        $head = "";
        $arr = array(1,2,7,8,9,11);
        if (in_array($id,$arr))
        {
            $head = "<div id='t-data2'>";
        }
        elseif($id == 10)
        {
            $head = "<div class='yuj_list'><div class='list_box mar-t-20'><ul>";
        }
        elseif ($id == 3)
        {
            $head = "<div id='zhuanjia'><div class='observa'>";
        }
        return $head;
    }

    /**
     * @param $id
     * @return string
     * 返回某几个列表上方有的大标题，返回为html
     */
    public function gettitle($id)
    {
        $title = "";
        $arr = array(
            1=>'知识产权动态信息',
            2=>'典型案例',
            3=>'专家观点',
            4=>'法律法规',
            5=>'知识产权环境',
            6=>'知识产权实务指引',
            7=>'区域舆情',
            8=>'行业舆情',
            9=>'企业舆情',
            11=>'分析成果展示',
        );
        //如果id
        if ($arr[$id])
        {
            $title .= "<div class='hd'><h3 style='padding:0 15px 0 15px;'>$arr[$id]</h3></div>";
            return $title;
        }
        return $title;
    }


    /**
     * @param $id
     * @param $page
     * @param $pagesize
     * @param $catid
     * @return array|bool
     * 获取文章列表信息
     */
    public function getlist($id,$page,$pagesize,$catid)
    {
        //如果是几个显示国家列表的去取国家列表，不用取列表数据
        if($id == 4 || $id == 5 || $id == 6)
        {
            $data = array('list'=>$this->getcountry($id,$catid));
            return $data;
        }
        if ($id==10)
        {
            $pagesize=4;
        }
        //1,2,3,7,8,9,10,11都是有列表数据需要取的
        $arr = array(1,2,3,7,8,9,10,11);
        if (in_array($id,$arr))
        {
            console('进来开始取数据了');
            //定义一个list，全拼接在此
            $list = "";
            //专家观点需要先取专家的各个信息，再去拼接列表，id=3
            if ($id == 3)
            {
                $list .= $this->getExpert();
            }


            $res = $this->db->select("select count(contentid) as total from cmstop_content where catid=$catid and status=6");
            //总条数
            $total = $res[0]['total'];
            //总页数
            $totalpage = ceil($total/$pagesize);
            $offset = ($page-1)*$pagesize;
            $data=$this->db->select("select c.contentid as contentid,c.catid as catid,c.modelid as modelid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author,a.description from cmstop_content as c left join cmstop_article as a on c.contentid=a.contentid where c.status=6 and  c.catid={$catid} order by c.published desc limit {$offset},{$pagesize}");
            $sql = "select c.contentid as contentid,c.catid as catid,c.modelid as modelid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author,a.description from cmstop_content as c left join cmstop_article as a on c.contentid=a.contentid where c.status=6 and  c.catid={$catid} order by c.published desc limit {$offset},{$pagesize}";
            console($sql);
            console($data);

            //样式带进去，这是普通列表的
            $arrtoo = array(1,2,7,8,9,11);
            if (in_array($id,$arrtoo))
            {
                $list .="<div id='list'><ul class='summary-list'>";
            }
            //遍历拼接html
            foreach ($data as $key=>$val)
            {
                $val['url']="javascript:getarticle($id,$catid,$val[contentid])";
                if (strlen($val['title'])>88)
                {
                    $val['title']=mb_substr($val['title'],0,88,'utf-8');
                    $val['title'] .='...';
                }
                if (strlen($val['description'])>350)
                {
                    $val['description']=mb_substr($val['description'],0,350,'utf-8');
                    $val['description'] .='...';
                }
                $time = date('H:i',$val['published']);
                $date = date('Y-m-d',$val['published']);
                //10是显示样式不一样的（专利布局）
                $arr2 = array(10);
                if (in_array($id,$arr2))
                {
                    if(!empty($val[thumb]))
                    {
                        $list .="<li><a href='{$val[url]}' class='f-l'><img src='http://upload.db.silkroad.news.cn/{$val[thumb]}' width='78' height='74' alt=''/></a>
<div class='txt-box'><h3><a href='{$val[url]}' >{$val[title]}</a></h3>
<h2>$date</h2><p class='mar-t-5'>{$val['description']}</p></div></li>";
                    }
                    else
                    {
                        $list .="<li>
                        <div class='txt-box' style='width:700px;margin-left:0;'><h3><a href='{$val[url]}' >{$val[title]}</a></h3>
                        <h2>$date</h2><p class='mar-t-5'>{$val['description']}</p></div></li>";
                    }
                    
                }
                else
                {
                    $list .="<li class='bor-b'><em class='f-l'>&bull;</em><a href='{$val[url]}' class='f-l'>$val[title]</a><div class='f-r txt mar-t-3'><span>$date</span><i>$time</i></div></li>";
//                    $list .="<li class='bor-b'><em class='f-l'>&bull;</em><a href='{$val[url]}' target='_blank' class='f-l'>$val[title]</a><div class='f-r txt mar-t-3'><span>$date</span><i>$time</i></div></li>";
                }
            }

            //样式带进去，这是普通列表的
            $arrtoo = array(1,2,7,8,9,11);
            if (in_array($id,$arrtoo))
            {
                $list .="</ul></div>";
            }
            elseif($id == 10)
            {
                $list .="</ul></div>";
            }
            elseif ($id == 3)
            {
                $list .="</ul>";
            }

            //这里来处理分页按钮
            $pagebutton = $this->pagebutton($page,$totalpage,$id,$catid,$total);

            $data = array('list'=>$list,'pagebutton'=>$pagebutton);
            return $data;
        }
    }


    /**
     * @return string
     * 获取专家列表信息
     */
    public function getExpert()
    {
        //按更新时间来排，取前三位
        $sql = "select typeid,spaceid,name,author,alias,photo,description,modified from cmstop_space where status=3 AND typeid=4 ORDER by modified DESC limit 3";
        $res = $this->db->select($sql);
        if (!$res)
        {
            $list = '未查询到专家列表信息';
            return $list;
        }
        $list .= "<div class='pic-summary mar-t-10'><ul>";
        foreach ($res as $key=>$val)
        {
            //链接到专家的文章列表页，现在有争议，搁置
            $url = 'http://space.db.silkroad.news.cn/'.$val['alias'].'?typeid='.$val['typeid'].'&spaceid='.$val['spaceid'];
            $spaceid = $val['spaceid'];
            $name = $val['name'];
            //专家最近一篇文章名
            $article = $this->db->get("select contentid,title,modified,created from cmstop_content where spaceid=$spaceid ORDER by modified DESC limit 1");
            //专家最近一篇文章时间
            $date = date('Y-m-d',$article['modified']);
            if (strlen($article['title'])>9)
            {
                $article['title']=mb_substr($article['title'],0,9,'utf-8');
                $article['title'] .='...';
            }
            $title = $article['title'];
            //专家最近一篇文章链接
            $arturl = "javascript:getarticle(3,111,$article[contentid])";
            if (strlen($val['description'])>80)
            {
                $val['description']=mb_substr($val['description'],0,80,'utf-8');
                $val['description'] .='...';
            }
            $description = strip_tags($val['description']);
//            $list .= "<li><a href='$url' class='h-t'><img src='$val[photo]' width='45' height='45' alt='$name' class='f-l h-img'/><span class='a-span'>$name</span></a><p>$description</p><p class='ph-18'><a href='$arturl' target='_blank' class='h-t'>$title</a><span>|</span><span>$date</span></p></li>";
            $list .= "<li><a class='h-t'><img src='http://upload.db.silkroad.news.cn/$val[photo]' width='45' height='45' class='f-l h-img'/><span class='a-span'><a href='$url' target='_blank'>$name</a></span></a><p>$description</p><p class='ph-18'><a href='$arturl' class='h-t'>$title</a><span>|</span><span>$date</span></p></li>";
        }

        //这里连着下方list的开头div也加进来了，后方list，只用取li循环就可以
        $list .= "</ul></div></div></div><!--转载观点--><div class='view mar-t-20'><div class='view-list'><ul class='summary-list'>";
        return $list;
    }



    /**
     * @param $page
     * @param $totalpage
     * @param $id
     * @param $catid
     * @param $total
     * @return string
     * 拼接分页按钮，在list方法中调用
     */
    public function pagebutton($page,$totalpage,$id,$catid,$total)
    {
        console('这里是页码');
        console($page);
        console($totalpage);
        console($total);
        $totalpage = $totalpage?$totalpage:0;
        $pagebutton = "<div id='pagebutton' class='page'>";
        //如果小于5页，就全部显示出来
        if ($page==1)
        {
            $pagebutton .= "<a class='on'>1</a>";
        }
        else
        {
            $pagebutton .= "<a href='javascript:void(0);' onclick='getData($id,$page-1,0,$catid)'><</a>";
            $pagebutton .= "<a href='javascript:void(0);' onclick='getData($id,1,0,$catid)'>1</a>";
        }
        if ($page>2)
        {
            $pagebutton .= "<a>...</a>";
        }
        for ($i=$page;$i<=$page+2&&$i<=$totalpage-1&&$totalpage>2;$i++)
        {
            if ($i == 1)
            {
                continue;
            }
            if ($page == $i)
            {
                $pagebutton .= "<a class='on'>$i</a>";
            }
            else
            {
                $pagebutton .= "<a href='javascript:void(0);' onclick='getData($id,$i,0,$catid)'>$i</a>";
            }
        }
        if ($page<$totalpage-3)
        {
            $pagebutton .= "<a>...</a>";
        }
        if ($page != $totalpage && $totalpage !== 0)
        {
            $pagebutton .= "<a href='javascript:void(0);' onclick='getData($id,$totalpage,0,$catid)'>$totalpage</a>";
            $pagebutton .= "<a href='javascript:void(0);' onclick='getData($id,$page+1,0,$catid)'>></a>";
        }
        else
        {
            if ($totalpage != 1 && $totalpage != 0)
            {
                $pagebutton .= "<a class='on'>$totalpage</a>";
            }
        }
        $pagebutton .= "<span>共<i>$page/$totalpage</i>页 , <i>$total</i>记录</span></div>";
        return $pagebutton;
    }



    /**
     * @param $id
     * 处理结尾
     */
    public function getbootom($id)
    {
        $bootom = "";
        $arrtoo = array(1,2,7,8,9,11);
        if (in_array($id,$arrtoo))
        {
            $bootom ="</div>";
        }
        elseif($id == 10)
        {
            $bootom ="</div></div>";
        }
        elseif ($id == 3)
        {
            $bootom ="</div></div>";
        }
        return $bootom;
    }


    /**
     * @param $contentid
     * ajax获取文章详情（所有的详情都在这里获取）
     */
    public function getarticle($contentid,$catid,$id)
    {
        //传入contentid，catid,来查文章详情，以及前一篇，后一篇


        //1,2,3,7,8,9,10,11这边是普通文章样式
        if ($id<4||$id>6)
        {
            $sql        = "select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author,a.content,c.pv from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.contentid=$contentid";
            $res        = $this->db->get($sql);
            //查上一条下一条数据
            $topsql     = "select contentid,title from #table_content where contentid<{$contentid} and catid={$res['catid']} order by contentid desc limit 1";
            $nextsql    = "select contentid,title from #table_content where contentid>{$contentid} and catid={$res['catid']} order by contentid asc limit 1";
            $top        = $this->db->get($topsql);
            $next       = $this->db->get($nextsql);
            $time       = date('Y-m-d H:i',$res['published']);
            $title      = $res['title'];
            $author     = $res['author'];
            $pv         = $res['pv']?$res['pv']:0;
            $content    = $res['content'];
            $topurl     = $top?"javascript:getarticle($id,$catid,$top[contentid])":null;
            $toptitle   = $top?'【上一篇】'.$top['title']:null;
            $nexturl    = $next?"javascript:getarticle($id,$catid,$next[contentid])":null;
            $nexttitle  = $next?'【下一篇】'.$next['title']:null;
            $article    = "<div class='msg-conts'><div class='conts-box'><div class='tit-box'><h3>{$title}</h3><p class='tit-summary mar-t-5'>时间：{$time}　　作者／记者：{$author}　 阅读 {$pv}次</p></div>{$content}<div class='a_tit mar-t-30'><ul><li><a href={$topurl} >{$toptitle}</a></li><li><a href={$nexturl} >{$nexttitle}</a></li></ul></div></div></div>";
        }
        else
        {
            //4,5,6这边是国家列表点进来的，先做56的

            //设置每个菜单对应的catid,通过爹catid，获取所有子caiid（数组）
            //然后再查对应的contentid（应该是有多个，数组形式存起来），再来两个数组sql匹配，想想就头疼
            $catidlist=subcategory($catid);
            if ($catidlist)
            {
                $catid = "";
                foreach ($catidlist as $key=>$val)
                {
                    //判断其有没有子caiid，没有就取其本身catid
                    if(empty($val['childids']))
                    {
                        $catid .= $key;
                    }
                    else
                    {
                        $catid .= $val['childids'];
                    }
                    $catid .=',';
                }
                $catid = substr($catid,0,strlen($catid)-1);
            }

//            $catid = array_filter(explode(',',$catid)); //处理成数组
            //因为当时的设计问题，此处传的是国家列表处传过来的proid!
            $proid = $contentid;

            //有了catid数组，contentid数组，
            if ($id == 4)
            {
                $sql = "SELECT contentid FROM #table_content WHERE status=6 AND contentid IN(select contentid from cmstop_content_property WHERE weight=100 AND proid={$proid} ORDER BY contentid desc) AND catid IN({$catid}) ORDER by published DESC limit 1";
            }
            else
            {
                $sql = "SELECT contentid FROM #table_content WHERE status=6 AND contentid IN(select contentid from cmstop_content_property WHERE proid={$proid} ORDER BY contentid desc) AND catid IN({$catid}) ORDER by published DESC limit 1";
            }
            $contentid = $this->db->get($sql)['contentid'];

            //通过contentid查数据 4,5,6
            if ($id == 4)
            {
                console("到1这里开始查 $contentid 的文章数据");
                //查文章信息
                $sql = "select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author,a.content,c.pv from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.contentid=$contentid";
                $res = $this->db->get($sql);
                //查表格信息，循环遍历表格
                $table = $this->gettable($catid,$proid,$contentid);
                $article="<div class='common'><div class='hd'><h3 style='padding:0 15px 0 15px;'>{$res['title']}</h3></div><div class='del-cont mar-t-30'><p>{$res['content']}</p>{$table}</div></div>";
            }
            else
            {
                console("到2这里开始查 $contentid 的文章数据");
                $sql = "select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author,a.content,c.pv,a.pagecount from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.contentid=$contentid";
                $res = $this->db->get($sql);
                //查属性值
                $name = $this->db->get("select name from cmstop_property where proid=(select proid from cmstop_content_property where contentid=$contentid AND proid between 405 and 500)")['name'];
                //处理分页标签
                $content = $res['content'];
                preg_match_all("/mcePageBreak\">&nbsp\;(.*?)<\/p>/",$res['content'],$page);

                //console($res);
                $page = $page[1];//取到正则括号里面的内容，分页名

                for ($i=0;$i<count($page);$i++)
                {
                    $pagelist .= "<li><em class='ques-icon'></em><a href='#page$i'>$page[$i]</a></li>";
                    //处理content的 标签，给p加上name
                    $content = preg_replace('/mcePageBreak\">/', "mcePageBreak\" id='page$i'>", $content,1);
                }
                //console('这里是处理过的content');
                //console($content);
                //嵌入到html中
                $article = "<div class='data' id='t-data5'><div class='top_box mar-t-20'><img src='{$res[thumb]}' width='84' height='55' alt='' class='flag'/><p><!--<span>{$name}</span> RU -->{$res['title']}</p></div><div class='ques-box mar-t-5'><div class='ques-line'></div>
                            <div class='ques-list mar-t-5'><ul class='mar-t-5'>{$pagelist}</ul></div><div class='ques-cont mar-t-15'><div class='qc-box'>
                            <!--<h3 class='mar-t-30'><em class='ques-icon1'></em>{$res['title']}</h3><h4 class='mar-t-20'><em class='ques-icon2'></em>{$res['title']}</h4>--><p class='mar-t-20'>{$content}</p></div></div></div></div>";
            }

        }


        $data = array('article'=>$article);
        return $data;
    }


    //获取国家列表
    public function getcountry($id,$catid)
    {
        console('进入选择国家列表');
        if($id==5 || $id==6)
        {
            console('进入if');
//            if($id==5) $iprpath = "http://db.silkroad.news.cn/ipr/iprcanquan.shtml";
            if($id==5) $iprpath = CMSTOP_PATH."public/www/ipr/iprcanquan.shtml";
//            if($id==6) $iprpath = "http://db.silkroad.news.cn/ipr/iprshiwu.shtml";
            if($id==6) $iprpath = CMSTOP_PATH."public/www/ipr/iprshiwu.shtml";

            console($iprpath);

            $list = file_get_contents($iprpath);
            console($list);

            return $list;
        }
        $title = $this->gettitle($id);
        $list = "<div class='allcountry w740'>".$title."<div class='bd'>";

        //国家列表在cmstop_property 表，id 398为ipr顶级父id
        $iprid = 398;
        $sql = "select proid,parentid,name,sort from #table_property where parentid={$iprid} order by sort DESC";
        $ares = $this->db->select($sql);
        for ($i=0;$i<count($ares);$i++)
        {
            $list .= "<dl><dt>{$ares[$i][name]}</dt><dd>";
            $cres = $this->db->select("select proid,name,childids,sort,disabled from #table_property where parentid={$ares[$i][proid]} AND disabled=0");
            for ($j=0;$j<count($cres);$j++)
            {
                //有可能一个属性(proid)对应的有n个文章，需要取catid对应的，时间为最新的那篇
                //这里挖一个坑！如果在此循环中来执行多个略复杂的sql，必定会拖慢速度，这里将proid的值赋给所谓的contentid，ajax传到取文章详情处来处理

                $proid = $cres[$j]['proid'];
                $url = "javascript:getarticle($id,$catid,$proid)";
                $list .="<span><a href='javascript:void(0);' onclick='$url' >{$cres[$j]['name']}</a></span>";
            }
            $list .= "<div class='morebtn'></div></dd></dl>";
        }
        $list .= "</div></div>";
        console($list);
        return $list;
    }


    /**
     * @param $contentid
     * @return string
     * 处理法律法规中的表格，传入catid数组，proid国家属性id，contentid排除此id，返回表格代码
     */
    public function gettable($catid,$proid,$contentid)
    {
        $contentid = $contentid?$contentid:1;
        $table = "<div class='legal-table'><div class='th-box'><ul><li class='w300'>法名称</li><li class='w120'>语言</li>
							<li class='w120'>公布日期</li><li class='w120'>下载</li></ul></div><div class='td-box'><ul>";
        console('这里是table');
        $sql = "SELECT contentid FROM #table_content WHERE status=6 AND contentid IN(select contentid from cmstop_content_property WHERE proid={$proid} ORDER BY contentid desc) AND catid IN({$catid}) AND contentid<>{$contentid}";
//        $sql = "select aid,filename,filepath,description,fileext from #table_attachment where contentid={$contentid}";
        $res = $this->db->select($sql);
        console($res);
        foreach ($res as $key=>$val)
        {
            //需要取到：文件名，语言，发布时间，下载链接
            console($val['contentid']);
            $cid = $val['contentid'];
            //这里先去通过cmstop_article表找到文件名
            $sql = "select content from #table_article WHERE contentid={$cid}";
            $aname = $this->db->get("select contentid,title from #table_content WHERE contentid={$cid}")['title'];
            $file = $this->db->get($sql)['content'];
            preg_match("/\/>(.*?)\./",$file,$filename);
            preg_match("/href=\"(.*?)\"><img/",$file,$url);
            preg_match("/\d{4}/",$filename[1],$time);
            $filename = $filename[1];
            $time     = $time[0];
            $lauage   = $this->db->get("select proid,name from #table_property where proid IN(select proid from #table_content_property where contentid={$cid}) AND parentid=362")['name'];
            $url      = $url[1]."?auth=b978d3501ca8d3f22625682abd2e553e";
            $aname     =  preg_replace("/$lauage/","",$aname);
            console($filename);
            console($time);
            console($lauage);
            console($url);
            $table .= "<li class='w300'>$aname</li>";
            $table .= "<li class='w120'>$lauage</li>";
            $table .= "<li class='w120'>$time</li>";
            $table .= "<li class='w120'><a href='$url' class='wj'><img src='http://img.db.silkroad.news.cn/templates/silkroad/ipr/img/wenjian.jpg' width='32' height='32' alt=''/></a></li>";

        }
//        console($info);



        $table .= '</ul></div></div>';
        return $table;
    }
}
