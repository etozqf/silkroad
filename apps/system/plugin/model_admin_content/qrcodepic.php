<?php

class plugin_qrcodepic extends object
{
    private $_content, $_qrcode;

    public function __construct($content)
    {
        $this->_content = $content;
        $this->_qrcode = loader::model('admin/qrcode', 'system');
    }

    public function after_get()
    {
        $path = $this->_qrcode->get_field( 'path',
            'str = ? and contentid = ?',
            array(
                $this->_content->data['url'],
                $this->_content->data['contentid']
            )
        );
        $this->_content->data['qrcode'] = UPLOAD_URL.$path;
    }
}