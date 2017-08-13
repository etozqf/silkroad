<?php
class wechat_live
{
    const API_BASE = 'http://app.cmstop.cn/wechat/live';

    const PLATFORM = 'cmstop';

    const SIGN_EXPIRES = 3600;

    protected $appkey;

    protected $secret;

    protected $errno;

    protected $error;

    public function __construct($appkey, $secret)
    {
        $this->appkey = $appkey;
        $this->secret = $secret;
    }

    public function ping()
    {
        $url = self::API_BASE . '/ping';
        return $this->request($url);
    }

    public function request_userbind_qrcode(array $data)
    {
        $url = self::API_BASE . '/userbindqrcode';

        $data = array(
            'userkey' => $data['userkey']
        );

        return $this->request($url, $data);
    }

    public function post_url(array $data)
    {
        $url = self::API_BASE . '/post';
        return $this->prepare($url, $data);
    }

    public function members_url(array $data)
    {
        $url = self::API_BASE . '/members';
        return $this->prepare($url, $data);
    }

    public function edit_url(array $data)
    {
        $url = self::API_BASE . '/edit';
        return $this->prepare($url, $data);
    }

    public function errno()
    {
        return $this->errno;
    }

    public function error()
    {
        return $this->error;
    }

    protected function prepare($url, array $data = array())
    {
        $data['platform'] = self::PLATFORM;
        $data['appkey'] = $this->appkey;
        $data['time'] = time();

        $sign = $this->sign($this->appkey, $this->secret, $data);
        $data['sign'] = $sign;

        if (strpos($url, '?') === false) {
            $url .= '?';
        } else {
            $url .= '&';
        }
        $url .= http_build_query($data);

        return $url;
    }

    protected function request($url, array $data = array())
    {
        $this->errno = null;
        $this->error = null;

        $url = $this->prepare($url, $data);
        $result = request($url);
        if (!$result || $result['httpcode'] != 200 || empty($result['content'])) {
            return false;
        }

        $content = json_decode($result['content'], true);
        if (empty($content)) {
            return false;
        }

        if (!$content['state']) {
            $this->error = isset($content['error']) ? $content['error'] : '微信直播接口请求失败';
            return false;
        }

        if (isset($content['data'])) {
            return $content['data'];
        }

        return true;
    }

    public static function sign($appkey, $secret, array $data)
    {
        ksort($data);
        $query = http_build_query($data);
        $sign = md5(md5($query) . $appkey . $secret . $data['time']);
        return $sign;
    }

    public function verify_sign($sign, array $data)
    {
        $time = value($data, 'time');
        if (empty($time)) {
            return false;
        }

        if ($time < time() - self::SIGN_EXPIRES) {
            return false;
        }

        return self::sign($this->appkey, $this->secret, $data) === $sign;
    }
}