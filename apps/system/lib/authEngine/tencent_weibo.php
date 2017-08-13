<?php
class authEngine_tencent_weibo extends authEngine
{
    protected $_cookie;

    protected $_stateName = 'auth_authorize_state';

    function __construct(array $options = array())
    {
        $this->_cookie = factory::cookie();

        $this->_consumer_key = value($options, 'consumer_key', setting('system', 'tencent_appkey'));
        $this->_consumer_secret = value($options, 'consumer_secret', setting('system', 'tencent_appsecret'));

        $config = app_config('system', 'auth.tencent_weibo');
        $this->_authorizeUrl = $config['authorize_url'];
        $this->_authorizeCallbackUrl = $config['authorize_callback_url'];
        $this->_revokeUrl = $config['revoke_url'];
        $this->_revokeCallbackUrl = $config['revoke_callback_uri'];
        $this->_accessTokenUrl = $config['access_token_url'];

        if (!$this->_consumer_key
            || !$this->_consumer_secret
            || !$this->_authorizeUrl
            || !$this->_authorizeCallbackUrl
            || !$this->_accessTokenUrl
        ) {
            throw new Exception('授权设置不正确');
        }
    }

    function _authorize(array $params = array())
    {
        $state = uniqid();
        $this->_cookie->set($this->_stateName, $state);

        self::redirect(self::buildUrl($this->_authorizeUrl, array(
            'client_id' => $this->_consumer_key,
            'redirect_uri' => $this->_authorizeCallbackUrl,
            'response_type' => 'code',
            'wap' => false,
            'state' => $state,
            'forcelogin' => 'true'
        )));
    }

    function _authorizeCallback(array $params = array())
    {
        $stored_state = $this->_cookie->get($this->_stateName);
        $request_state = value($params, 'state');
        if (!$stored_state || !$request_state || $request_state != $stored_state) {
            throw new Exception('您的请求来源不正确');
        }
        return $this->_accessToken($params);
    }

    function _accessToken(array $params = array())
    {
        $code = value($params, 'code');

        $response = self::request($this->_accessTokenUrl, array(
            'client_id' => $this->_consumer_key,
            'client_secret' => $this->_consumer_secret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->_authorizeCallbackUrl
        ), 'POST');

        if (!$response || $response['httpcode'] != '200') {
            throw new Exception('授权失败，请重新尝试');
        }

        parse_str($response['content'], $response);

        return array(
            'access_token' => $response['access_token'],
            'expires_in' => TIME + (int)$response['expires_in'],
            'userid' => $response['openid'],
            'username' => $response['name'],
            'nickname' => $response['nick']
        );
    }

    function _revoke(array $params = array())
    {
        $this->_api_prepare($params);
        return $this->_api_response(self::request($this->_revokeUrl, $params));
    }

    function _revokeCallback(array $params = array())
    {
        // nothing to do
        return true;
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