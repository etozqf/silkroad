<?php

/**
 * Luosimao 短信平台验证
 *
 * 接口地址：
 * https://sms-api.luosimao.com/v1/send.[json|xml|jsonp]	发送单条短信
 * https://sms-api.luosimao.com/v1/status.[json|xml|jsonp]	账户信息(余额)
 * 调用方式：POST
 * 参数：
 * {
 *  mobile : 'xxxxxxxxxxx',
 *  message : ''
 * }
 * mobile 手机号码
 * message 短信内容
 *
 * 注意事项：
 * 1、网站需要在获取界面增加图形验证码（防止被盗用接口进行短信轰炸等目的的滥用）
 * 2、增加60s获取的间隔限制
 *
 * */
class luosimao
{

    protected $requestURL;
    private $debug;

    public function __construct($debug = true)
    {
        $this->debug = $debug ? CACHE_PATH . 'Luosimao' . DS . date('Ymd', TIME) . 'Luosimao.md' : '';
    }

    /**
     * 发送验证码
     */
    public function sendMessage(array $params)
    {
        if (!$params['mobile'] || !$params['message'] || !$params['key']) {
            return array('state' => false, 'error' => '缺少参数');
        }
        if (!preg_match("/^1[34578]\d{9}$/", $params['mobile'])) {
            return array('state' => false, 'error' => '请输入正确的电话号码');
        }

        // 处理参数
        if (!$params['requestURL']) {
            $params['requestURL'] = 'https://sms-api.luosimao.com/v1/send.json';
        }
        $params['key'] = 'api:key-' . $params['key'];
        $params = array_map('strip_tags', $params);

        $ret = $this->luosimaoRequest($params, 'send');
        $ret = $this->formatData($ret);

        if ($this->debug) {
            $this->write_file($params, '发送消息', $ret);
        }

        return $ret['formatData'];
    }

    /**
     * 查询余额
     */
    public function getbalance(array $params)
    {
        if (!$params['key']) {
            return array('state' => false, 'error' => 'Key 参数为空');
        }

        // 余额接口暂时没有配置，写死
        $params['requestURL'] = 'http://sms-api.luosimao.com/v1/status.json';

        $params['key'] = 'api:key-' . $params['key'];
        $params = array_map('strip_tags', $params);

        $ret = $this->luosimaoRequest($params, 'balance');
        $ret = $this->formatData($ret);

        if ($this->debug) {
            $this->write_file($params, '获取余额', $ret);
        }

        return $ret['formatData'];

    }

    /**
     * 格式化数据
     */
    public function formatData($data)
    {
        if ($data['httpcode'] != 200) {
            return array('state' => false, 'error' => 'Luosimao厂商服务器错误');
        }

        $result = json_decode($data['content'], true);
        switch ($result['error']) {
            case '-10':
                $ret = array('state' => false, 'error' => "验证信息失败, 请检查通讯密钥");
                break;
            case '-20':
                $ret = array('state' => false, 'error' => "短信余额不足");
                break;
            case '-30':
                $ret = array('state' => false, 'error' => "短信内容为空");
                break;
            case '-31':
                $ret = array('state' => false, 'error' => "短信内容存在敏感词");
                break;
            case '-32':
                $ret = array('state' => false, 'error' => "短信内容缺少签名信息");
                break;
            case '-40':
                $ret = array('state' => false, 'error' => "错误的手机号");
                break;
            case '-41':
                $ret = array('state' => false, 'error' => "号码在黑名单中");
                break;
            case '-42':
                $ret = array('state' => false, 'error' => "验证码类短信发送频率过快");
                break;
            case '-50':
                $ret = array('state' => false, 'error' => "请求发送IP不在白名单内");
                break;
            case '0':
                $ret = array('state' => true, 'message' => isset($result['deposit']) ? $result['deposit'] : '验证码已发出');
                break;
            default:
                $ret = array('state' => false, 'error' => '未知错误');

        }
        return array('formatData' => $ret, 'protogenesis' => $data);
    }

    /*
     * @param array $params 获取配置信息
     * @param string $st 判断是查询余额还是发送验证码
     */
    public function luosimaoRequest(array $params, $st)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $params['requestURL']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); // 连接超时
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $params['key']);
        if ($st == 'send') {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $params['mobile'], 'message' => $params['message']));
        }

        $ret = curl_exec($ch);
        $info = curl_getinfo($ch);
        $httpcode = $info['http_code'];
        $content_length = $info['download_content_length'] ?: $info['size_download'];
        $content_type = $info['content_type'];
        curl_close($ch);
        return array(
            'httpcode' => $httpcode,
            'content_length' => $content_length,
            'content_type' => $content_type,
            'content' => $ret
        );
    }

    /**
     * 记录日志
     */
    private function write_file($params, $st, $ret)
    {
        $message = 'Current Time　： ' . date('Y-m-d H:i:s', TIME) . PHP_EOL;
        $message .= 'Params ： ' . var_export($params, true) . PHP_EOL;
        $message .= 'Operating items ： ' . $st . PHP_EOL;
        $message .= 'Result ： ' . var_export($ret, true) . PHP_EOL;
        file_put_contents($this->debug, $message, FILE_APPEND);
    }
}