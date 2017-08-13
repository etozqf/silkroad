<?php
abstract class authEngine
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
     * @var string App Secret
     */
    protected $_consumerSecret;

    /**
     * @var string 授权请求地址
     */
    protected $_authorizeUrl;

    /**
     * @var string 授权回调地址
     */
    protected $_authorizeCallbackUrl;

    /**
     * @var string 取消授权地址
     */
    protected $_revokeUrl;

    /**
     * @var string 取消授权回调地址
     */
    protected $_revokeCallbackUrl;

    /**
     * @var string 获取授权码地址
     */
    protected $_accessTokenUrl;

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
        return app_config('system', 'auth');
    }

    /**
     * @param $platform
     * @param array $options
     * @return authEngine
     * @throws Exception
     */
    static function getInstance($platform, array $options = array())
    {
        $key = md5($platform . json_encode($options));
        if (isset(self::$_instances[$key])) {
            return self::$_instances[$key];
        }
        loader::import("lib.authEngine.$platform", app_dir('system'));
        $class = "authEngine_$platform";
        if (!class_exists($class, false)) {
            throw new Exception("Auth platform '$platform' not exists.");
        }
        return self::$_instances[$key] = new $class($options);
    }

    static function authorize($platform, array $params = array())
    {
        return self::getInstance($platform)->_authorize($params);
    }

    static function authorizeCallback($platform, array $params = array())
    {
        return self::getInstance($platform)->_authorizeCallback($params);
    }

    static function revoke($platform, array $params = array())
    {
        return self::getInstance($platform)->_revoke($params);
    }

    static function revokeCallback($platform, array $params = array())
    {
        return self::getInstance($platform)->_revokeCallback($params);
    }

    static function accessToken($platform, array $params = array())
    {
        return self::getInstance($platform)->_accessToken($params);
    }

    abstract function _authorize(array $params = array());
    abstract function _authorizeCallback(array $params = array());
    abstract function _revoke(array $params = array());
    abstract function _revokeCallback(array $params = array());
    abstract function _accessToken(array $params = array());

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
        curl_setopt($curl, CURLOPT_USERAGENT, 'CmsTop OAuth 2.0');
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
}