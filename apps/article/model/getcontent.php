<?php

class model_getcontent extends model
{

    function __construct()
    {
        parent::__construct();
        $db = &factory::db();
    }


    /**
     * @param $countryname 国家名
     * @param $fname 法律名
     * @return mixed
     * 获取国家，法律名，同时获取列表,
     */
    function getdata($catid)
    {
        //utf-8 用来调试

        header("Content-Type: text/html; charset=UTF-8");
        $catid=$_GET['catid'];
        $data=array();
        $sql="select * from cmstop_content where catid =".$v;
        $res = $this->db->select($sql);
        foreach ($res as $key => $value) {
            $data['title']=$value['title'];
            $data['published']=$value['published'];
            $data['contentid']=$value['contentid'];
            $get_content_sql="select content from cmstop_article where contentid=".$data['contentid'];
            $data['content']=$this->db->select($sql);
        }   
        return $data;
    }
  function getList($page,$pagesize,$catid)
    {
        $catid=$_GET['catid'];
        $offset = ($page-1)*$pagesize;
        $sql = "select a.contentid as contentid,a.description as description,a.author,c.title,c.url as url from cmstop_article as a JOIN (select contentid,title,url from cmstop_content where catid=".$catid.") as c ON a.contentid=c.contentid limit $offset,$pagesize";
        $list = $this->db->select($sql);
        $total = $this->db->get("select count(a.contentid) as total from cmstop_article as a JOIN (select contentid,title from cmstop_content where catid=".$catid.") as c ON a.contentid=c.contentid")['total'];

        $data['list'] = $list;
        $data['total'] = $total;
        return $data;
    }
}
