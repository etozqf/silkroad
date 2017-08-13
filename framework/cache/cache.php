<?php

loader::register('cache_storage', dirname(__FILE__) . DS . 'storage.php');

class cache extends object
{
    protected $_options, $_storage;

    function __construct($options)
    {
        $this->_options = $options;
        $this->_options['caching'] = isset($options['caching']) ? $options['caching'] : true;
        $this->_options['storage'] = isset($options['storage']) ? $options['storage'] : 'file';
        $this->_storage = $this->get_storage();
    }

    public static function get_instance($type = 'output', $options = array())
    {
        $type = strtolower(preg_replace('/[^A-Z0-9_\.-]/i', '', $type));
        $class = 'cache_' . $type;
        if (!class_exists($class)) {
            $path = dirname(__FILE__) . DS . 'handler' . DS . $type . '.php';
            if (file_exists($path)) {
                require_once($path);
            } else {
                throw new Exception('Unable to load Cache Handler: ' . $type);
            }
        }
        $instance = new $class($options);
        return $instance;
    }

    public function set_caching($enabled)
    {
        $this->_options['caching'] = $enabled;
    }

    public function get($id)
    {
        return $this->_options['caching'] ? $this->_storage->get($id) : false;
    }

    public function set($id, $data, $ttl = 0)
    {
        return $this->_options['caching'] ? $this->_storage->set($id, $data, $ttl) : false;
    }

    public function rm($id)
    {
        return $this->_storage->rm($id);
    }

    public function clear()
    {
        return $this->_storage->clear();
    }

    public function gc()
    {
        return $this->_storage->gc();
    }

    public function get_storage()
    {
        if (is_a($this->_storage, 'cache_storage')) {
            return $this->_storage;
        }
        $this->_storage = cache_storage::get_instance($this->_options['storage'], $this->_options);
        return $this->_storage;
    }

    /**
     * 根据访问频率自动衰减或增加的缓存周期控制 (仅支持Redis场景）
     *
     * 如果10分钟内点击超过3次（由调用者控制），即开始缓存，首次缓存1小时，再次访问累计，最长30天
     * 下次访问时，如果缓存已存在，且ttl不小于ttl设定，则暂不更新，如果ttl设定大于1周，则以1周为判断
     *
     * @param string $id 要检测的KEY
     * @param int $ttl 增量的缓存周期
     * @return array|bool 缓存中的数据
     */
    public function increase_get($id = '', $ttl = 3600)
    {
        $data = $this->get($id);
        if ($this->_options['storage'] != 'redis') {
            return $data;
        }

        // 先更新访问频率
        $_incrId = 'frequency:' . $id;
        $_frequency = (int)$this->get($_incrId);
        $this->set($_incrId, ++$_frequency, 600);

        if ($data) {
            // 判断是否需要更新ttl，如果小于1周更新ttl
            /** @var Redis $client */
            $client = $this->_storage->get_client();
            $_ttl = $client->ttl($id);
            $ttl = min(2592000, $_ttl + $_frequency * $ttl); // 最大86400*30，即30天
            if ($_ttl < min(604800, $ttl)) {
                $client->expire($id, $ttl);
            }
        }
        return $data;
    }
}
