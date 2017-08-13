<?php
abstract class thirdparty_api
{
    public $config;
    public $fields;
    public $error;

    function __construct($config)
    {
        $this->config = $config;
        $this->fields = array(
            'category'=>array('catid', 'name', 'url', 'hasChildren'),
            'video'=>array('vid', 'catid', 'title', 'tags', 'created', 'published', 'time', 'playcount', 'playercode', 'thumb'),
            'playcount'=>array('vid', 'playcount'),
        );
    }

    function __destruct()
    {
        $this->config = null;
        $this->fields = null;
    }

    function __call($do, $param)
    {
        $this->error = 'method [' .$do .'] not exists!';
        return false;
    }

    /**
     * 获取分类栏目列表
     */
    abstract function get_category();

    /**
     * 获取视频列表
     */
    abstract function get_video();

    /**
     * 获取视频播放量
     */
    abstract function get_playcount();

    /**
     * 获取直播频道
     */
    abstract function get_live();

    /**
     * 输出广告
     *
     */
    abstract function output_ads();

    /**
     * 输出推荐列表
     *
     */
    abstract function output_recommend($data=array());

    /**
     * 生成接口访问地址
     *
     * @param $method
     * @param array $params
     * @return string
     */
    protected  function getUrl($method, $params=array())
    {
        $url = $this->config['apiurl'];
        if(strpos($url, '?'))
        {
            $url = $url .'&do=' .$method;
        }else{
            $url = $url .'?do=' .$method;
        }
        foreach($params as $key=>$value)
        {
            $url .= '&'.$key.'='.urlencode($value);
        }
        return $url;
    }

    /**
     * 执行接口请求
     *
     * @param $url
     * @return array|mixed
     */
    protected function exec($url)
    {
        $ctx = stream_context_create(array(
                'http' => array(
                    'timeout' => 10 //设置一个超时时间，单位为秒
                )
            )
        );
        $authkey = $this->config['authkey'];
        $result = file_get_contents($url .'&authkey='.urlencode($authkey), 0, $ctx);
        if($result)
        {
            $result = json_decode($result, true);
        }
        return $result;
    }
}