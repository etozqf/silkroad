<?php

class model_legal extends model
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
    function getCountry($countryname,$fname)
    {
        //utf-8 用来调试

        header("Content-Type: text/html; charset=UTF-8");

        $url = APP_URL."?app=article&controller=legal&";

        $sql = "select a.contentid,a.author from cmstop_article as a JOIN (select contentid from cmstop_content where catid=362) as c ON a.contentid=c.contentid";
        $res = $this->db->select($sql);

        //国家名数组
        $country = array();

        $ci = 0;
        $li = 0;
        foreach ($res as $key=>$val)
        {

            //处理国家名
            preg_match('/(.*?)\|/',$val['author'],$infoguo);
            //国家名默认值
            $countryname = $countryname?$countryname:$infoguo[1];

            if (!$this->deep_in_array($infoguo[1],$country))
            {
                $country[$ci]['country'] = $infoguo[1];
                if ($countryname == $infoguo[1])
                {
                    $country[$ci]['class'] = 'on';
                }
                else
                {
                    $country[$ci]['class'] = '';
                }
                $country[$ci]['url'] = $url."country=$infoguo[1]";
            }

            //处理法名
            preg_match("/$countryname\|((.*?)法)/",$val['author'],$infofa);

            if (!$this->deep_in_array($infofa[1],$legal) && !empty($infofa[2]))
            {
                $legal[$li]['legal'] = $infofa[1];
                if ($fname == $infofa[1])
                {
                    $legal[$li]['class'] = 'on';
                }
                else
                {
                    $legal[$li]['class'] = '';
                }
                $legal[$li]['url'] = $url."country=$countryname"."&legal=$infofa[1]";
            }
            $ci++;
            $li++;
        }

        $data['country'] = $country;
        $data['legal']   = $legal;
        $data['countryname'] = $countryname;
        return $data;
    }


    /**
     * @param $country
     * @param string $legal
     * @return mixed
     * 获取列表数据，传入国家名，以及法律名
     */
    function getList($country,$legal='',$page,$pagesize)
    {
        $offset = ($page-1)*$pagesize;

        $sql = "select a.contentid as contentid,a.description as description,a.author,c.title,c.url as url from cmstop_article as a JOIN (select contentid,title,url from cmstop_content where catid=362) as c ON a.contentid=c.contentid where a.author like '%%{$country}%%' AND a.author like '%%{$legal}%%' limit $offset,$pagesize";
        $list = $this->db->select($sql);
        $total = $this->db->get("select count(a.contentid) as total from cmstop_article as a JOIN (select contentid,title from cmstop_content where catid=362) as c ON a.contentid=c.contentid where a.author like '%%{$country}%%' AND a.author like '%%{$legal}%%'")['total'];

        $data['list'] = $list;
        $data['total'] = $total;
        return $data;
    }



    //判断多维数组中是否有某值
    protected function deep_in_array($value, $array) {
        foreach($array as $item) {
            if(!is_array($item)) {
                if ($item == $value) {
                    return true;
                } else {
                    continue;
                }
            }

            if(in_array($value, $item)) {
                return true;
            } else if($this->deep_in_array($value, $item)) {
                return true;
            }
        }
        return false;
    }

}
