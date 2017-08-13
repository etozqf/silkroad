<?php
abstract class weiboEngine
{
    /**
     * @var array Engine 实例
     */
    protected static $_instances = array();

    /**
     * @var string App Key
     */
    protected $_consumerKey;

    /**
     * @var string API 起始地址
     */
    protected $_api;

    /**
     * @var string 错误状态码
     */
    protected $_errno;

    /**
     * @var string 错误信息
     */
    protected $_error;

    static final function platforms()
    {
        return app_config('weibo', 'platform');
    }

    /**
     * @param $platform
     * @param array $options
     * @return weiboEngine
     * @throws Exception
     */
    static function getInstance($platform, array $options = array())
    {
        $key = md5($platform . json_encode($options));
        if (isset(self::$_instances[$key])) {
            return self::$_instances[$key];
        }
        loader::import("lib.weiboEngine.$platform", app_dir('weibo'));
        $class = "weiboEngine_$platform";
        if (!class_exists($class, false)) {
            throw new Exception("Weibo platform '$platform' not exists.");
        }
        return self::$_instances[$key] = new $class($options);
    }

    static function get($platform, $api, array $params = array())
    {
        return self::getInstance($platform)->_get($api, $params);
    }

    static function post($platform, $api, array $params = array())
    {
        return self::getInstance($platform)->_post($api, $params);
    }

    static function upload($platform, $api, array $params = array(), array $files = array())
    {
        return self::getInstance($platform)->_upload($api, $params);
    }

    static function profile($platform, array $params = array(), $format = true)
    {
        return self::getInstance($platform)->_profile($params, $format);
    }

    static function profileUrl($platform, array $params = array())
    {
        return self::getInstance($platform)->_profileUrl($params);
    }

    static function timeline($platform, array $params = array(), $format = true)
    {
        return self::getInstance($platform)->_timeline($params, $format);
    }

    static function show($platform, array $params = array(), $format = true)
    {
        return self::getInstance($platform)->_show($params, $format);
    }

    static function comments($platform, array $params = array(), $format = true)
    {
        return self::getInstance($platform)->_comments($params, $format);
    }

    static function followers($platform, array $parmas = array(), $format = true)
    {
        return self::getInstance($platform)->_followers($params, $format);
    }

    static function friends($platform, array $parmas = array(), $format = true)
    {
        return self::getInstance($platform)->_friends($params, $format);
    }

    abstract function _get($api, array $params = array());
    abstract function _post($api, array $params = array());
    abstract function _upload($api, array $params = array(), array $files = array());

    abstract function _profile(array $params = array(), $format = true);
    abstract function _profileUrl(array $params = array());
    abstract function _timeline(array $params = array(), $format = true);
    abstract function _show(array $params = array(), $format = true);
    abstract function _comments(array $params = array(), $format = true);
    abstract function _followers(array $params = array(), $format = true);
    abstract function _friends(array $params = array(), $format = true);

    function errno()
    {
        return $this->_errno;
    }

    function error()
    {
        return $this->_error;
    }

    final static function request($url, array $params = array(), $method = 'GET', array $multipart = array(), $extra_headers = array())
    {
        $method = strtoupper($method);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERAGENT, 'CmsTop Weibo 1.0');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);

        $headers = (array) $extra_headers;
        switch ($method)
        {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                if (! empty($params))
                {
                    if ($multipart)
                    {
                        foreach ($multipart as $key => $file)
                        {
                            $params[$key] = '@'.$file;
                        }
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                        $headers[] = 'Expect: ';
                    }
                    else
                    {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
                    }
                }
                break;
            case 'DELETE':
            case 'GET':
                if ($method == 'DELETE')
                {
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                }
                if (! empty($params))
                {
                    $url = $url . (strpos($url, '?') !== false ? '&' : '?')
                        . (is_array($params) ? http_build_query($params) : $params);
                }
                break;
        }

        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        if ($headers)
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        $content = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $httpinfo = curl_getinfo($curl);
        curl_close($curl);

        return array(
            'content' => $content,
            'httpcode' => $httpcode,
            'httpinfo' => $httpinfo
        );
    }

    final static function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    final static function buildUrl($base, $query)
    {
        if (!$query) {
            return $base;
        }
        if (is_array($query)) {
            $query = http_build_query($query);
        }
        return $base . (strpos($base, '?') === false ? '?' : '&') . $query;
    }

    final static function parseText($text)
    {
        $regex = '/((?:http|ftp|https):\/\/[\w-]+(?:\.[\w-]+)+(?:[\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?)/ims';
        return preg_replace($regex, '<a href="$1" target="_blank">$1</a>', $text);
    }
}