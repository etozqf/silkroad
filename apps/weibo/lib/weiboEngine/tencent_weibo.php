<?php
class weiboEngine_tencent_weibo extends weiboEngine
{
    function __construct(array $options = array())
    {
        $this->_consumer_key = value($options, 'consumer_key', setting('system', 'tencent_appkey'));

        $config = app_config('weibo', 'platform.tencent_weibo');
        $this->_api = $config['api'];

        if (!$this->_consumer_key) {
            throw new Exception('微博设置不正确');
        }
    }

    function _get($api, array $params = array())
    {
        $this->_api_prepare($params);
        return $this->_api_response(self::request($this->_api.$api, $params));
    }

    function _post($api, array $params = array())
    {
        $this->_api_prepare($params);
        return $this->_api_response(self::request($this->_api.$api, $params, 'POST'));
    }

    function _upload($api, array $params = array(), array $files = array())
    {
        $this->_api_prepare($params);
        return $this->_api_response(self::request($this->_api.$api, $params, 'POST', $files));
    }

    function _profile(array $params = array(), $format = true)
    {
        $this->_api_prepare($params);
        $response = $this->_api_response(self::request($this->_api.'user/info', array(
            'oauth_consumer_key' => $params['oauth_consumer_key'],
            'access_token' => $params['access_token'],
            'openid' => $params['userid'],
            'clientip' => $params['clientip'],
            'oauth_version' => $params['oauth_version'],
            'scope' => $params['scope'],
            'format' => $params['format']
        )));

        if (!$response || !$format) {
            return $response;
        }

        $response = array(
            'id' => (string)$response['openid'],
            'name' => (string)$response['name'],
            'nickname' => (string)$response['nick'],
            'photo' => (string)$response['head'].'/120',
            'following' => (int)$response['idolnum'],
            'followers' => (int)$response['fansnum'],
            'isvip' => (int)$response['isvip'],
            'info' => $response['introduction']
        );
        return $response;
    }

    function _profileUrl(array $params = array())
    {
        if (!empty($params['username'])) {
            return 'http://t.qq.com/'.$params['username'];
        }
        return 'http://t.qq.com/'.$params['userid'];
    }

    function _timeline(array $params = array(), $format = true)
    {
        $this->_api_prepare($params);
        $response = $this->_api_response(self::request($this->_api.'statuses/broadcast_timeline', array(
            'oauth_consumer_key' => $params['oauth_consumer_key'],
            'access_token' => $params['access_token'],
            'openid' => $params['userid'],
            'clientip' => $params['clientip'],
            'oauth_version' => $params['oauth_version'],
            'scope' => $params['scope'],
            'format' => $params['format'],
            'pageflag' => $params['lastid'] ? 1 : 0,
            'pagetime' => $params['lasttime'],
            'reqnum' => $params['pagesize'],
            'lastid' => $params['lastid'],
            'type' => 0x1|0x2,
            'contenttype' => 0
        )));

        if (!$response || !$format) {
            return $response;
        }

        foreach ($response['info'] as $index => &$row) {
            $this->_format_weibo($row);
        }

        return array(
            'total' => (int)$response['totalnum'],
            'data' => $response['info'],
            'more' => (int)$response['hasnext'] ? 0 : 1
        );
    }

    function _show(array $params = array(), $format = true)
    {
        $this->_api_prepare($params);
        $response = $this->_api_response(self::request($this->_api.'t/show', array(
            'oauth_consumer_key' => $params['oauth_consumer_key'],
            'access_token' => $params['access_token'],
            'openid' => $params['userid'],
            'clientip' => $params['clientip'],
            'oauth_version' => $params['oauth_version'],
            'scope' => $params['scope'],
            'format' => $params['format'],
            'id' => $params['id']
        )));

        if (!$response || !$format) {
            return $response;
        }

        $response = $this->_format_weibo($response);
        return $response;
    }

    function _comments(array $params = array(), $format = true)
    {
        $this->_api_prepare($params);
        $response = $this->_api_response(self::request($this->_api.'t/re_list', array(
            'oauth_consumer_key' => $params['oauth_consumer_key'],
            'access_token' => $params['access_token'],
            'openid' => $params['userid'],
            'clientip' => $params['clientip'],
            'oauth_version' => $params['oauth_version'],
            'scope' => $params['scope'],
            'format' => $params['format'],
            'flag' => 2,
            'rootid' => $params['id'],
            'pageflag' => $params['lastid'] ? 1 : 0,
            'pagetime' => $params['lasttime'],
            'reqnum' => $params['pagesize'],
            'twitterid' => $params['lastid']
        )));

        if (!$response || !$format) {
            return $response;
        }

        foreach ($response['info'] as $index => &$row) {
            $this->_format_comment($row);
        }

        return array(
            'total' => (int)$response['totalnum'],
            'data' => $response['info'],
            'more' => (int)$response['hasnext'] ? 0 : 1
        );
    }

    function _followers(array $params = array(), $format = true)
    {
        $this->_api_prepare($params);
        if (empty($params['count'])) {
            $params['count'] = 50;
        }
        if (empty($params['cursor'])) {
            $params['cursor'] = 1;
        }
        $response = $this->_api_response(self::request($this->_api.'friends/fanslist_s', array(
            'oauth_consumer_key' => $params['oauth_consumer_key'],
            'access_token' => $params['access_token'],
            'oauth_version' => $params['oauth_version'],
            'scope' => $params['scope'],
            'openid' => $params['userid'],
            'clientip' => $params['clientip'],
            'format' => $params['format'],
            'reqnum' => $params['count'],
            'startindex' => $params['count'] * ($params['cursor'] - 1),
        )));
        if (intval($response['ret']) || !$format) {
            return $response;
        }

        foreach($response['info'] as $index => &$row) {
            $this->_format_weibo_follower($row);
        }

        $next = ($response['hasnext'] == 0) ? ++$params['cursor'] : 0;

        return array(
            'data' => (array)array_values($response['info']),
            'next' => $next
        );
    }

    function _friends(array $params = array(), $format = true)
    {
        $this->_api_prepare($params);
        if (empty($params['count'])) {
            $params['count'] = 50;
        }
        if (empty($params['cursor'])) {
            $params['cursor'] = 1;
        }
        $response = $this->_api_response(self::request($this->_api.'friends/idollist_s', array(
            'oauth_consumer_key' => $params['oauth_consumer_key'],
            'access_token' => $params['access_token'],
            'oauth_version' => $params['oauth_version'],
            'scope' => $params['scope'],
            'openid' => $params['userid'],
            'clientip' => $params['clientip'],
            'format' => $params['format'],
            'reqnum' => $params['count'],
            'startindex' => $params['count'] * ($params['cursor'] - 1),
        )));
        if (intval($response['ret']) || !$format) {
            return $response;
        }

        foreach($response['info'] as $index => &$row) {
            $this->_format_weibo_follower($row);
        }

        $next = ($response['hasnext'] == 0) ? ++$params['cursor'] : 0;

        return array(
            'data' => (array)array_values($response['info']),
            'next' => $next
        );
    }

    protected function _format_weibo(&$weibo)
    {
        $user = $this->_format_weibo_user($weibo);
        $image = $this->_format_weibo_image($weibo);
        $video = $this->_format_weibo_video($weibo);
        $source = $this->_format_weibo_source($weibo);

        $weibo = array(
            'id' => (string)$weibo['id'],
            'created' => (int)$weibo['timestamp'],
            'text' => (string)strip_tags($weibo['text']),
            'user' => $user,
            'from' => (string)strip_tags($weibo['from']),
            'reposts' => (int)$weibo['count'],
            'comments' => (int)$weibo['mcount'],
        );

        if (!empty($image)) $weibo['image'] = $image;
        if (!empty($video)) $weibo['video'] = $video;
        if (!empty($source)) $weibo['source'] = $source;

        return $weibo;
    }

    protected function _format_comment(&$weibo)
    {
        $user = $this->_format_weibo_user($weibo);
        $weibo = array(
            'id' => (string)$weibo['id'],
            'created' => (int)$weibo['timestamp'],
            'text' => (string)strip_tags($weibo['text']),
            'user' => $user,
            'from' => (string)strip_tags($weibo['from']),
        );
        return $weibo;
    }

    protected function _format_weibo_user(&$weibo)
    {
        return array(
            'nickname' => (string)$weibo['nick'],
            'photo' => (string)$weibo['head'].'/120',
            'isvip' => (int)$weibo['isvip']
        );
    }

    protected function _format_weibo_image(&$weibo)
    {
        if (empty($weibo['image'])) {
            return false;
        }
        $images = array();
        foreach ($weibo['image'] as &$image) {
            $images[] = array(
                'thumb' => (string)$image.'/80',
                'original' => (string)$image.'/2000'
            );
        }
        return $images;
    }

    protected function _format_weibo_video(&$weibo)
    {
        if (empty($weibo['video'])) {
            return false;
        }
        return array(
            'thumb' => (string)$weibo['video']['picurl'],
            'url' => (string)$weibo['video']['realurl']
        );
    }

    protected function _format_weibo_source(&$weibo)
    {
        if (empty($weibo['source'])) {
            return false;
        }
        if (!empty($weibo['source']['source'])) {
            unset($weibo['source']['source']);
        }
        return $this->_format_weibo($weibo['source']);
    }

    protected function _format_weibo_follower(&$weibo)
    {
        $weibo = array(
            'name' => $weibo['nick'],
            'photo' => $weibo['head'].'/180'
        );
        return $weibo;
    }

    protected function _api_prepare(&$params)
    {
        $params['oauth_consumer_key'] = $this->_consumer_key;
        $params['clientip'] = IP;
        $params['oauth_version'] = '2.a';
        $params['scope'] = 'all';
        $params['format'] = 'json';
    }

    protected function _api_response($response)
    {
        if (!$response || $response['httpcode'] != 200) {
            $this->_errno = $response['httpcode'];
            $this->_error = '接口请求失败，请检查系统设置或网络';
            return false;
        }

        $response = json_decode($response['content'], true);
        if ($response['ret'] == 0) {
            return $response['data'];
        }

        $this->_errno = value($response, 'errcode');
        $this->_error = value($response, 'msg');
        return false;
    }
}