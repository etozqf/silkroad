<?php

// 判断操作系统是否是windows
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    define('FILE_ENV_IS_WIN', true);
} else {
    define('FILE_ENV_IS_WIN', false);
}

class file_client
{
    /** 操作类型 */
    const METHOD_CREATE_FILE = 1; // 创建文件，当文件不存在时，尝试创建
    const METHOD_MODIFY_FILE = 2; // 修改文件，当文件不存在时，返回错误，当文件存在时从头部开始写入
    const METHOD_APPEND_FILE = 3; // 增量写入文件，当文件不存在时，尝试创建，当文件存在时从尾部追加写入
    const METHOD_REMOVE_FILE = 4; // 删除文件，当文件不存在时，返回错误
    const METHOD_CREATE_DIR = 5; // 创建目录
    const METHOD_REMOVE_DIR = 6; // 删除目录，含子目录中的内容和目录本身
    const METHOD_CLEAR_DIR = 7; // 清空目录，只清空目录中的内容含子目录，但目录本身不删除
    const METHOD_COPY = 8; // 复制一个路径，文件或文件夹，如果是文件夹递归复制所有子目录中的内容
    const METHOD_RENAME = 9; // 更名，重命名一个路径，文件或文件夹，如果不存在返回错误

    const PORT = 9468; // 默认端口
    const HEADER_LENGTH = 4; // 返回数据头部长度
    const WRITE_TIMEOUT = 300; // 写入数据超时，秒
    const READ_TIMEOUT = 10; // 读取数据超时，秒

    /**
     * 服务器IP地址
     *
     * @var string
     */
    protected $ip;

    /**
     * 服务器通讯密钥
     *
     * @var string
     */
    protected $key;

    /**
     * client资源句柄
     *
     * @var resource
     */
    protected $client;

    /**
     * 实例化文件客户端
     *
     * @param string $ip 服务器IP地址
     * @param string $key 服务器通讯密码
     * @throws RuntimeException
     */
    public function __construct($ip, $key = '')
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new RuntimeException('Invalid server ip param');
        }
        $this->ip = $ip;
        $this->key = $key;

        // 检测路径
        if (!defined('CMSTOP_PATH')) {
            throw new RuntimeException('Invalid call');
        }
        if (isset($_SERVER["SCRIPT_FILENAME"])) {
            $path = $_SERVER["SCRIPT_FILENAME"];
            $path = str_replace('\\', '/', $path);
            if (strpos($path, CMSTOP_PATH) === false) {
                throw new RuntimeException('Invalid call');
            }
        }
    }

    public function __destruct()
    {
        $this->close_client();
    }

    /**
     * 创建文件
     *
     * @param string $path 文件路径
     * @param string $body 文件内容
     * @return mixed 返回数组 code,message，code = 0 成功，其他失败
     */
    public function create($path = '', $body = '')
    {
        return $this->execute(self::METHOD_CREATE_FILE, $path, $body);
    }

    /**
     * 写入/修改文件
     *
     * @param string $path 文件路径
     * @param string $body 文件内容
     * @return mixed 返回数组 code,message，code = 0 成功，其他失败
     */
    public function write($path = '', $body = '')
    {
        return $this->execute(self::METHOD_MODIFY_FILE, $path, $body);
    }

    /**
     * 追加写入文件
     *
     * @param string $path 文件路径
     * @param string $body 文件内容
     * @return mixed 返回数组 code,message，code = 0 成功，其他失败
     */
    public function append($path = '', $body = '')
    {
        return $this->execute(self::METHOD_APPEND_FILE, $path, $body);
    }

    /**
     * 删除文件
     *
     * @param string $path 文件路径
     * @return mixed 返回数组 code,message，code = 0 成功，其他失败
     */
    public function remove($path = '')
    {
        return $this->execute(self::METHOD_REMOVE_FILE, $path);
    }

    /**
     * 创建目录
     *
     * @param string $path 文件路径
     * @return mixed 返回数组 code,message，code = 0 成功，其他失败
     */
    public function mkdir($path = '')
    {
        return $this->execute(self::METHOD_CREATE_DIR, $path);
    }

    /**
     * 删除目录 含子目录和文件
     *
     * @param string $path 文件路径
     * @return mixed 返回数组 code,message，code = 0 成功，其他失败
     */
    public function remove_dir($path = '')
    {
        return $this->execute(self::METHOD_REMOVE_DIR, $path);
    }

    /**
     * 清空目录 仅删除子目录和文件，不删除文件夹本身
     *
     * @param string $path 文件路径
     * @return mixed 返回数组 code,message，code = 0 成功，其他失败
     */
    public function clear_dir($path = '')
    {
        return $this->execute(self::METHOD_CLEAR_DIR, $path);
    }

    /**
     * 复制文件或文件夹
     *
     * @param string $path 原始路径
     * @param string $newpath 目标路径
     * @return mixed 返回数组 code,message，code = 0 成功，其他失败
     */
    public function copy($path = '', $newpath = '')
    {
        $params = json_encode(array(
            'newpath' => $newpath
        ));
        return $this->execute(self::METHOD_COPY, $path, $params);
    }

    /**
     * 重命名文件或文件夹
     *
     * @param string $path 原始路径
     * @param string $newpath 目标路径
     * @return mixed 返回数组 code,message，code = 0 成功，其他失败
     */
    public function rename($path = '', $newpath = '')
    {
        $params = json_encode(array(
            'newpath' => $newpath
        ));
        return $this->execute(self::METHOD_COPY, $path, $params);
    }

    protected function get_client()
    {
        if ($this->client) {
            return $this->client;
        }

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            throw new RuntimeException('Create socket failed');
        }

        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => self::WRITE_TIMEOUT, 'usec' => 0));
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => self::READ_TIMEOUT, 'usec' => 0));

        if (!socket_connect($socket, $this->ip, self::PORT)) {
            $errno = socket_last_error($socket);
            $error = socket_strerror($errno);
            throw new RuntimeException('Connect to node failed: ' . $error);
        }

        $this->client = $socket;
        return $this->client;
    }

    protected function close_client()
    {
        if ($this->client) {
            socket_close($this->client);
        }
        $this->client = null;
    }

    protected function execute($method = 0, $path = '', $body = '')
    {
        $socket = $this->get_client();
        $data = $this->encode($method, $path, $body);
        try {
            $length = strlen($data);
            $offset = 0;
            while ($offset < $length) {
                $sent = socket_write($socket, substr($data, $offset), $length - $offset);
                if ($sent === false) {
                    break;
                }
                $offset += (int)$sent;
            }
            if ($offset < $length) {
                $errno = socket_last_error($socket);
                $error = socket_strerror($errno);
                throw new RuntimeException('Error while send data to node: ' . $error);
            }

            // read length
            if (false === ($bytes = socket_recv($socket, $length, self::HEADER_LENGTH, 0))) {
                $errno = socket_last_error($socket);
                $error = socket_strerror($errno);
                throw new RuntimeException('Error while read response length: ' . $error);
            }
            $length = unpack('Vlen', $length);

            // read content
            if (false === ($bytes = socket_recv($socket, $buffers, $length['len'], 0))) {
                $errno = socket_last_error($socket);
                $error = socket_strerror($errno);
                throw new RuntimeException('Error while read response content: ' . $error);
            }
        } catch (Exception $ex) {
            $this->close_client();
            throw $ex;
        }

        // parse response
        if (empty($buffers) || !($response = json_decode($buffers, true))) {
            $this->close_client();
            throw new RuntimeException('Invalid response format');
        }
        if ($response['code']) {
            $this->close_client();
            throw new RuntimeException($response['message'], $response['code']);
        }

        return $response;
    }

    protected function encode($method = 0, $path = '', $body = '')
    {
        $header = pack('V', $method);
        $header .= pack('V', strlen($this->key));
        $header .= pack('V', strlen($path));
        $header .= pack('V', strlen($body));
        $data = $header . $this->key . $path . $body;
        return $data;
    }
}

/**
 * 原生的文件操作类 TODO
 *
 * 供不支持fileserver的系统使用，如：windows，接口方法设计与fileserver一致
 *
 * Class file_client_orig
 */
class file_client_orig{

}