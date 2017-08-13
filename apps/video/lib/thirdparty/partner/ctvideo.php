<?php
class thirdparty_ctvideo extends thirdparty_api
{
    function get_category()
    {
        $params = array();
        $catid = intval(value($_GET, 'catid', 0));
        if($catid) $params['catid'] = $catid;
        $url = $this->getUrl('get_category', $params);
        $result = $this->exec($url);
        if(!$result['state'])
        {
            $this->error = $result['error'];
            return false;
        }
        return $result['data'];
    }

    function get_video()
    {
        $params = array();
        $catid = intval(value($_GET, 'catid', 0));
        if($catid) $params['catid'] = $catid;
        $created = '';
        $created_min = value($_GET, 'created_min', null);
        $created_max = value($_GET, 'created_max', null);
        if($created_min) $created = $created_min .',';
        if($created_max)
        {
            if($created)
            {
                $created .= $created_max;
            }
            else{
                $created = ',' .$created_max;
            }
        }
        if($created) $params['created'] = $created;
        $keyword = value($_GET, 'keyword', null);
        if($keyword) $params['keyword'] = $keyword;
        $pagesize = value($_GET, 'pagesize', 10);
        if($pagesize) $params['pagesize'] = $pagesize;
        $page = value($_GET, 'page', 1);
        if($page) $params['page'] = $page;
        $params['modelid'] = 4;
        $url = $this->getUrl('get_video', $params);
        $result = $this->exec($url);
        if(!$result['state'])
        {
            $this->error = $result['error'];
            return false;
        }
        if(!empty($result['data']))
        {
            foreach($result['data'] as $key=>$row)
            {
                $result['data'][$key] = $this->formatRow($row, $this->fields['video']);
            }
        }
        unset($result['size']);
        unset($result['page']);
        unset($result['count']);
        return $result;
    }

    function get_playcount()
    {
        $params = array();
        $vid = intval(value($_GET, 'vid', 0));
        if(!$vid)
        {
            $this->error ='视频编号不能为空';
            return false;
        }
        $params['vid'] = $vid;
        $url = $this->getUrl('get_playcount', $params);
        $result = $this->exec($url);
        if(!$result['state'])
        {
            $this->error = $result['error'];
            return false;
        }
        return $result;
    }

    function get_live()
    {
        $params = array();
        $url = $this->getUrl('get_live', $params);
        $result = $this->exec($url);
        if(!$result['state'])
        {
            $this->error = $result['error'];
            return false;
        }
        return $result;
    }

    function output_ads()
    {
        $ads = $this->config['ads'];
        foreach($ads as $key=>&$row)
        {
            $row['type'] = 'ads';
            if(!$row['open'])
            {
                $row['file'] = $row['url'] = '';
                $row['time'] = 0;
            }
        };
        $result = array(
            'status'=>true,
            'data'=>$ads
        );
        return $result;
    }

    function output_recommend($data=array())
    {
        return $data;
    }

    private function formatRow($row, $fields)
    {
        $cFields = array(
            'vid'=>'contentid',
            'time'=>'',
            'playcount'=>'',
            'playercode'=>'',
        );
        $newRow = array();
        foreach($fields as $field)
        {
            $newField = $field;
            if(isset($cFields[$field]))
            {
                $newField = $cFields[$field];
            }
            if($newField)
            {
                $newRow[$field] = isset($row[$newField]) ? $row[$newField] : '';
            }
            else
            {
                $newRow[$field] = '';
            }
        }
        $videoInfo = $this->getVideoInfo($newRow['vid']);
        $newRow = array_merge($newRow, $videoInfo);
        return $newRow;
    }

    private function getVideoInfo($contentid)
    {
        if($contentid = intval($contentid))
        {
            $params['contentid'] = $contentid;
        }
        else
        {
            return false;
        }
        $url = $this->getUrl('video_get', $params);
        $result = $this->exec($url);
        if(!$result['state'])
        {
            return false;
        }
        $result = $result['data'];
        $result = array(
            'playercode'=>'',
            'playerparams'=>'',
            'video'=>$result['video'],
            'time'=>$result['playtime'],
            'playcount'=>$result['pv'],
        );
        if(strpos($result['video'], 'ctvideo]'))
        {
            $video = str_replace(array('[ctvideo]','[/ctvideo]'),'',$result['video']);
            $result['playerparams'] = $video;
        }
        return $result;
    }
}