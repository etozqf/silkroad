<?php

class cache_storage_file extends cache_storage
{
    private $path;

    function __construct($settings)
    {
        parent::__construct($settings);
        if (!isset($this->settings['path'])) $this->settings['path'] = CACHE_PATH;
    }

    function set($key, $value, $ttl = 0)
    {
        if ($this->debug) {
            $this->log->add('cache set: key[' . $key . '], ttl[' . $ttl . ']');
        }
        $value = $this->_set_value($value, $ttl);
        return write_file($this->_path($key), $value);
    }

    function get($key)
    {
        if ($this->debug) {
            $this->log->add('cache get: key[' . $key . ']');
        }
        $value = @file_get_contents($this->_path($key));
        if ($value !== false) {
            $value = $this->_get_value($value);
            if ($value === false) $this->rm($key);
        }
        return $value;
    }

    function rm($key)
    {
        if ($this->debug) {
            $this->log->add('cache rm: key[' . $key . ']');
        }
        return @unlink($this->_path($key));
    }

    function clear()
    {
        if ($this->debug) {
            $this->log->add('cache clear');
        }
        import('helper.folder');
        return folder::clear($this->settings['path']);
    }

    private function _path($key)
    {
        if (strlen($key) !== 32) $key = md5($key);
        $dir = $this->settings['path'] . substr($key, 0, 2) . '/';
        if (!is_dir($this->settings['path'])) @mkdir($this->settings['path']);
        if (!is_dir($dir)) @mkdir($dir, 0777);
        return $dir . $key;
    }

    private function _set_value($value, $ttl = 0)
    {
        $expires = $ttl > 0 ? (time() + $ttl) : 0;
        return serialize(array('expires' => $expires, 'value' => $value));
    }

    private function _get_value($value)
    {
        $data = unserialize($value);
        return ($data['expires'] > time() || $data['expires'] == 0) ? $data['value'] : false;
    }
}
