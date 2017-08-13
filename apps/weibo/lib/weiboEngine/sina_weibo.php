<?php
class weiboEngine_sina_weibo extends weiboEngine
{
    function __construct(array $options = array())
    {
        $this->_consumer_key = value($options, 'consumer_key', setting('system', 'sina_appkey'));

        $config = app_config('weibo', 'platform.sina_weibo');
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
        $response = $this->_api_response(self::request($this->_api.'users/show.json', array(
            'source' => $params['source'],
            'access_token' => $params['access_token'],
            'uid' => $params['userid']
        )));

        if (!$response || !$format) {
            return $response;
        }

        $response = array(
            'id' => (string)$response['idstr'],
            'name' => (string)$response['name'],
            'nickname' => (string)$response['screen_name'],
            'photo' => (string)$response['profile_image_url'],
            'following' => (int)$response['friends_count'],
            'followers' => (int)$response['followers_count'],
            'isvip' => (int)$response['verified'],
            'info' => $response['description']
        );
        return $response;
    }

    function _profileUrl(array $params = array())
    {
        return 'http://weibo.com/u/'.$params['userid'];
    }

    function _timeline(array $params = array(), $format = true)
    {
        $this->_api_prepare($params);
        $response = $this->_api_response(self::request($this->_api.'statuses/user_timeline.json', array(
            'source' => $params['source'],
            'access_token' => $params['access_token'],
            'max_id' => $params['lastid'],
            'count' => $params['lastid'] ? $params['pagesize'] + 1 : $params['pagesize'],
            'base_app' => 0,
            'feature' => 0,
            'trim_user' => 0
        )));

        if (!$response || !$format) {
            return $response;
        }

        foreach ($response['statuses'] as $index => &$row) {
            if ($params['lastid'] && $index == 0) {
                unset($response['statuses'][$index]);
                continue;
            }
            $this->_format_weibo($row);
        }
        return array(
            'total' => (int)$response['total_number'],
            'data' => array_values($response['statuses']),
            'more' => (int)$response['next_cursor'] ? 1 : 0
        );
    }

    function _show(array $params = array(), $format = true)
    {
        $this->_api_prepare($params);
        $response = $this->_api_response(self::request($this->_api.'statuses/show.json', array(
            'source' => $params['source'],
            'access_token' => $params['access_token'],
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
        $response = $this->_api_response(self::request($this->_api.'comments/show.json', array(
            'source' => $params['source'],
            'access_token' => $params['access_token'],
            'id' => $params['id'],
            'max_id' => $params['lastid'],
            'count' => $params['lastid'] ? $params['pagesize'] + 1 : $params['pagesize']
        )));

        if (!$response || !$format) {
            return $response;
        }

        foreach ($response['comments'] as $index => &$row) {
            if ($params['lastid'] && $index == 0) {
                unset($response['comments'][$index]);
                continue;
            }
            $this->_format_comment($row);
        }

        return array(
            'total' => (int)$response['total_number'],
            'data' => array_values($response['comments']),
            'more' => (int)$response['next_cursor'] ? 1 : 0
        );
    }

    function _followers(array $params = array(), $format = true)
    {
        $this->_api_prepare($params);
        if (empty($params['count'])) {
            unset($params['count']);
        }
        if (empty($params['cursor'])) {
            unset($params['cursor']);
        }
        $response = $this->_api_response(self::request($this->_api.'friendships/followers.json', array(
            'source' => $params['source'],
            'access_token' => $params['access_token'],
            'uid' => $params['userid'],
            'count' => $params['count'],
            'cursor' => $params['cursor']
        )));
        if (!$response || !$format) {
            return $response;
        }

        foreach($response['users'] as $index => &$row) {
            $this->_format_weibo_follower($row);
        }

        return array(
            'data' => (array)array_values($response['users']),
            'next' => (int)$response['next_cursor']
        );
    }

    function _friends(array $params = array(), $format = true)
    {
        $this->_api_prepare($params);
        if (empty($params['count'])) {
            unset($params['count']);
        }
        if (empty($params['cursor'])) {
            unset($params['cursor']);
        }
        $response = $this->_api_response(self::request($this->_api.'friendships/friends.json', array(
            'source' => $params['source'],
            'access_token' => $params['access_token'],
            'uid' => $params['userid'],
            'count' => $params['count'],
            'cursor' => $params['cursor']
        )));
        if (!$response || !$format) {
            return $response;
        }

        foreach($response['users'] as $index => &$row) {
            $this->_format_weibo_follower($row);
        }

        return array(
            'data' => (array)array_values($response['users']),
            'next' => (int)$response['next_cursor']
        );
    }

    protected function _format_weibo(&$weibo)
    {
        $user = $this->_format_weibo_user($weibo);
        $image = $this->_format_weibo_image($weibo);
        $source = $this->_format_weibo_source($weibo);

        $weibo = array(
            'id' => (string)$weibo['idstr'],
            'created' => (int)strtotime($weibo['created_at']),
            'text' => (string)strip_tags($weibo['text']),
            'user' => $user,
            'from' => (string)strip_tags($weibo['source']),
            'reposts' => (int)$weibo['reposts_count'],
            'comments' => (int)$weibo['comments_count'],
        );

        if (!empty($image)) $weibo['image'] = $image;
        if (!empty($source)) $weibo['source'] = $source;

        return $weibo;
    }

    protected function _format_comment(&$weibo)
    {
        $user = $this->_format_weibo_user($weibo);
        $weibo = array(
            'id' => (string)$weibo['idstr'],
            'created' => (int)strtotime($weibo['created_at']),
            'text' => (string)strip_tags($weibo['text']),
            'user' => $user,
            'from' => (string)strip_tags($weibo['source'])
        );
        return $weibo;
    }

    protected function _format_weibo_user(&$weibo)
    {
        return array(
            'nickname' => (string)$weibo['user']['screen_name'],
            'photo' => (string)$weibo['user']['profile_image_url'],
            'isvip' => (int)$weibo['user']['verified']
        );
    }

    protected function _format_weibo_image(&$weibo)
    {
        if (empty($weibo['original_pic'])) {
            return false;
        }
        return array(
            array(
                'thumb' => (string)$weibo['thumbnail_pic'],
                'original' => (string)$weibo['original_pic']
            )
        );
    }

    protected function _format_weibo_source(&$weibo)
    {
        if (empty($weibo['retweeted_status'])) {
            return false;
        }
        if (!empty($weibo['retweeted_status']['retweeted_status'])) {
            unset($weibo['retweeted_status']['retweeted_status']);
        }
        return $this->_format_weibo($weibo['retweeted_status']);
    }

    protected function _format_weibo_follower(&$weibo)
    {
        $weibo = array(
            'name' => $weibo['screen_name'],
            'photo' => $weibo['avatar_large']
        );
        return $weibo;
    }

    protected function _api_prepare(&$params)
    {
        $params['source'] = $this->_consumer_key;
    }

    protected function _api_response($response)
    {
        if ($response && !empty($response['content'])) {
            $content = json_decode($response['content'], true);
            if ($content && empty($content['error_code']) && empty($content['error'])) {
                return $content;
            }

            $this->_errno = value($content, 'error_code');
            $this->_error = value($content, 'error');
        } else {
            $this->_errno = $response['httpcode'];
            $this->_error = '接口请求失败，请检查系统设置或网络';
        }
        return false;
    }
}