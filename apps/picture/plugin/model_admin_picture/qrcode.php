<?php

class plugin_qrcode extends object
{
    private $_content, $_qrcode, $_uri;

    public function __construct($content)
    {
        $this->_content = table('content', $content->contentid);
        $this->_qrcode = loader::model('admin/qrcode', 'system');
        $this->_uri = loader::lib('uri', 'system');
    }

    public function __call($method, $parameters)
    {
        $this->create_qrcode();
    }

    private function create_qrcode()
    {
        $uri = $this->_uri->content($this->_content['contentid']);
        // 检测是否已经生成
        $count = $this->_qrcode->count(
            'str = ? and contentid = ?',
            array(
                $uri['url'],
                $this->_content['contentid']
            )
        );
        if ($count) {
            return true;
        }

        // 入库
        $options = setting('system', 'qrcode');
        $data = array(
            'contentid'  => $this->_content['contentid'],
            'image'      => $options['image'],
            'image_file' => $options['image_file'],
            'modelid'    => $this->_content['modelid'],
            'note'       => $this->_content['title'],
            'str'        => $uri['url'],
            'type'       => 'content',
        );
        $qrcodeid = $this->_qrcode->add($data);
        if (!$qrcodeid) {
            return false;
        }

        // 生成
        $short = str_short($qrcodeid);
        $params = array(
            'str' => $this->_qrcode->short_url($short),
            'image_fill' => value($options, 'image_fill'),
            'image' => value($options, 'image'),
        );
        $params['size'] = 7;
        $params['filename'] = $qrcodeid . '/' . $short . '-' . 7;
        $url = $this->_qrcode->generate($params);
        if (!$url) {
            return false;
        }

        $this->_qrcode->update(array(
            'short' => $short,
            'path' => preg_replace('#^'.preg_quote(UPLOAD_URL).'#', '', $url)
        ), $qrcodeid);

        return true;
    }
}